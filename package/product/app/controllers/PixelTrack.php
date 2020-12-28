<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Response;
use MaxMind\Db\Reader;

class PixelTrack extends Controller {
    public $website;
    public $website_user;

    public function index() {

        if(!isset($_SERVER['HTTP_REFERER'])) {
            die(json_encode($this->language->pixel_track->error_message->no_referrer));
        }

        /* Check against bots */
        $CrawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect();

        if($CrawlerDetect->isCrawler()) {
            die(json_encode($this->language->pixel_track->error_message->excluded_bot));
        }

        /* Get the Payload of the Post */
        $payload = @file_get_contents('php://input');
        $post = json_decode($payload);

        if(!$post) {
            die(json_encode($this->language->pixel_track->error_message->no_post));
        }

        /* Clean the pixel key */
        $pixel_key = isset($this->params[0]) ? Database::clean_string($this->params[0]) : false;
        $date = \Altum\Date::$date;

        /* Allowed types of requests to this endpoint */
        $allowed_types = [
            /* Sessions events */
            'initiate_visitor',
            'landing_page',
            'pageview',

            /* Events children */
            'click',
            'scroll',
            'form',
            'resize',

            /* Sessions replays */
            'replays',

            /* Heatmaps */
            'heatmap_snapshot',

            /* Goal conversions */
            'goal_conversion'
        ];

        if(!isset($post->type) || isset($post->type) && !in_array($post->type, $allowed_types)) {
            die(json_encode($this->language->pixel_track->error_message->type_not_allowed));
        }

        /* Find the website for the domain */
        $host = Database::clean_string(parse_url($_SERVER['HTTP_REFERER'])['host']);

        /* Remove www. from the host */
        $prefix = 'www.';

        if(substr($host, 0, strlen($prefix)) == $prefix) {
            $host = substr($host, strlen($prefix));
        }

        /* Get the details of the campaign from the database */
        $website = $this->website = (new \Altum\Models\Website())->get_website_by_pixel_key($pixel_key);

        /* Make sure the campaign has access */
        if(!$website) {
            die(json_encode($this->language->pixel_track->error_message->no_website));
        }

        if(
            !$website->is_enabled
            || ($website->host != $host && $website->host != 'www.' . $host)
        ) {
            die();
        }

        /* Check excluded IPs */
        $excluded_ips = explode(',', $this->website->excluded_ips);

        /* Do not track if its an excluded ip */
        if(in_array(get_ip(), $excluded_ips)) {
            die(json_encode($this->language->pixel_track->error_message->excluded_ip));
        }

        /* Make sure to get the user data and confirm the user is ok */
        $user = $this->website_user = (new \Altum\Models\User())->get_user_by_user_id($website->user_id);

        if(!$user) {
            die();
        }

        if(!$user->active) {
            die();
        }

        /* Make sure the user's plan is not already expired */
        if((new \DateTime()) > (new \DateTime($user->plan_expiration_date)) && $user->plan_id != 'free') {
            die(json_encode($this->language->pixel_track->error_message->expired_plan));
        }

        /* Check against available limits */
        if(
            ($this->website_user->plan_settings->sessions_events_limit != -1 && $this->website->current_month_sessions_events >= $this->website_user->plan_settings->sessions_events_limit) ||

            (
                $this->website_user->plan_settings->events_children_limit != -1 &&
                $this->website->current_month_events_children >= $this->website_user->plan_settings->events_children_limit &&
                in_array($post->type, ['click', 'scroll', 'form','resize']) &&
                !isset($post->heatmap_id)
            ) ||

            (
                $this->website_user->plan_settings->sessions_replays_limit != -1 &&
                $this->website->current_month_sessions_replays >= $this->website_user->plan_settings->sessions_replays_limit &&
                in_array($post->type, ['replays'])
            ) ||

            (
                $this->website_user->plan_settings->websites_heatmaps_limit == 0 &&
                in_array($post->type, ['click', 'scroll']) &&
                isset($post->heatmap_id)
            ) ||

            (
                $this->website_user->plan_settings->websites_goals_limit == 0 &&
                in_array($post->type, ['goal_conversion'])
            )
        ) {
            die(json_encode($this->language->pixel_track->error_message->plan_limit));
        }

        /* Lightweight */
        if($website->tracking_type == 'lightweight') {
            /* Processing depending on the type of request */
            switch($post->type) {
                case 'landing_page':
                case 'pageview':

                    /* Process referrer */
                    $referrer = parse_url($post->data->referrer);

                    /* Check if the referrer comes from the same location */
                    if(
                        isset($referrer['host'])
                        && $referrer['host'] == $this->website->host
                        && (
                            isset($referrer['path']) && substr($referrer['path'], 0, strlen($this->website->path)) == $this->website->path
                        )
                    ) {
                        $referrer = [
                            'host' => null,
                            'path' => null
                        ];
                    }

                    /* Detect the location */
                    $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get(get_ip());

                    $location = [
                        'city_name' => $maxmind && $maxmind['city']['names']['en'] ? $maxmind['city']['names']['en'] : null,
                        'country_code' => $maxmind && $maxmind['country']['iso_code'] ? $maxmind['country']['iso_code'] : null,
                    ];

                    /* Detect extra details about the user */
                    $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);

                    /* Detect extra details about the user */
                    $os = [
                        'name' => $whichbrowser->os->name ?? null
                    ];

                    $browser = [
                        'name' => $whichbrowser->browser->name ?? null,
                        'language' => isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null
                    ];

                    $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);
                    $screen_resolution = (int) $post->data->resolution->width . 'x' . (int) $post->data->resolution->height;

                    $event = [
                        'path'              => $this->website->path ? preg_replace('/^' . preg_quote($this->website->path, '/') . '/', '', $post->data->path) : $post->data->path ?? '',
                        'referrer_host'     => $referrer['host'] ?? null,
                        'referrer_path'     => $referrer['path'] ?? null,
                        'utm_source'        => $post->data->utm->source ?? null,
                        'utm_medium'        => $post->data->utm->medium ?? null,
                        'utm_campaign'      => $post->data->utm->campaign ?? null,
                    ];

                    /* Insert the event */
                    $stmt = Database::$database->prepare("
                    INSERT INTO
                        `lightweight_events` 
                        (
                            `website_id`,
                            `type`,
                            `path`,
                            `referrer_host`,
                            `referrer_path`,
                            `utm_source`,
                            `utm_medium`,
                            `utm_campaign`,
                            `country_code`,
                            `city_name`,
                            `os_name`,
                            `browser_name`,
                            `browser_language`,
                            `screen_resolution`,
                            `device_type`,
                            `date`
                        ) 
                    VALUES
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                    $stmt->bind_param(
                        'ssssssssssssssss',
                        $this->website->website_id,
                        $post->type,
                        $event['path'],
                        $event['referrer_host'],
                        $event['referrer_path'],
                        $event['utm_source'],
                        $event['utm_medium'],
                        $event['utm_campaign'],
                        $location['country_code'],
                        $location['city_name'],
                        $os['name'],
                        $browser['name'],
                        $browser['language'],
                        $screen_resolution,
                        $device_type,
                        $date
                    );
                    $stmt->execute();
                    $stmt->close();

                    break;

                /* Handling goal conversions */
                case 'goal_conversion':

                    /* Some data to use */
                    $goal_key = Database::clean_string($post->goal_key);

                    /* Get the goal if any */
                    $website_goal = $this->database->query("SELECT `goal_id`, `type`, `path` FROM `websites_goals` WHERE `website_id` = {$this->website->website_id} AND `key` = '{$goal_key}'")->fetch_object() ?? null;

                    if (!$website_goal) {
                        die();
                    }

                    /* Check if the goal is valid */
                    if ($website_goal->type == 'pageview') {
                        if ($_SERVER['HTTP_REFERER'] != $this->website->scheme . $this->website->host . $this->website->path . $website_goal->path) {
                            die();
                        }
                    }

                    /* Prepare to insert the goal conversion */
                    $stmt = Database::$database->prepare("
                    INSERT INTO
                        `goals_conversions` (`goal_id`, `website_id`, `date`) 
                    VALUES
                        (?, ?, ?)
                ");
                    $stmt->bind_param(
                        'sss',
                        $website_goal->goal_id,
                        $this->website->website_id,
                        $date
                    );
                    $stmt->execute();
                    $stmt->close();

                    break;
            }
        }

        if($website->tracking_type == 'normal') {
            /* Processing depending on the type of request */
            switch($post->type) {

                /* Initiate the visitor event */
                case 'initiate_visitor':

                    /* Check for custom parameters */
                    $dirty_custom_parameters = $post->data->custom_parameters ?? null;
                    $custom_parameters = [];

                    if ($dirty_custom_parameters) {

                        $i = 1;
                        foreach ((array)$dirty_custom_parameters as $key => $value) {
                            $key = Database::clean_string($key);
                            $value = Database::clean_string($value);

                            if ($i++ >= 5) {
                                break;
                            } else {
                                $custom_parameters[$key] = $value;
                            }
                        }
                    }

                    $custom_parameters = json_encode($custom_parameters);

                    /* Detect the location */
                    $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get(get_ip());

                    $location = [
                        'city_name' => $maxmind && $maxmind['city']['names']['en'] ? $maxmind['city']['names']['en'] : null,
                        'country_code' => $maxmind && $maxmind['country']['iso_code'] ? $maxmind['country']['iso_code'] : null,
                        'country_name' => $maxmind && $maxmind['country']['names']['en'] ? $maxmind['country']['names']['en'] : null
                    ];

                    /* Detect extra details about the user */
                    $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);

                    /* Detect extra details about the user */
                    $os = [
                        'name' => $whichbrowser->os->name ?? null,
                        'version' => $whichbrowser->os->version->value ?? null
                    ];

                    $browser = [
                        'name' => $whichbrowser->browser->name ?? null,
                        'version' => $whichbrowser->browser->version->value ?? null,
                        'language' => isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null
                    ];

                    $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);
                    $screen_resolution = (int)$post->data->resolution->width . 'x' . (int)$post->data->resolution->height;

                    /* Insert or update the visitor */
                    $stmt = Database::$database->prepare("
                    INSERT INTO 
                        `websites_visitors` (`website_id`, `visitor_uuid`, `custom_parameters`, `country_code`, `city_name`, `os_name`, `os_version`, `browser_name`, `browser_version`, `browser_language`, `screen_resolution`, `device_type`, `date`, `last_date`) 
                    VALUES 
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                        `custom_parameters` = VALUES (custom_parameters),
                        `country_code` = VALUES (country_code),
                        `city_name` = VALUES (city_name),
                        `os_name` = VALUES (os_name),
                        `os_version` = VALUES (os_version),
                        `browser_name` = VALUES (browser_name),
                        `browser_version` = VALUES (browser_version),
                        `browser_language` = VALUES (browser_language),
                        `screen_resolution` = VALUES (screen_resolution),
                        `device_type` = VALUES (device_type),
                        `last_date` = VALUES (last_date)
                ");
                    $stmt->bind_param(
                        'ssssssssssssss',
                        $this->website->website_id,
                        $post->visitor_uuid,
                        $custom_parameters,
                        $location['country_code'],
                        $location['city_name'],
                        $os['name'],
                        $os['version'],
                        $browser['name'],
                        $browser['version'],
                        $browser['language'],
                        $screen_resolution,
                        $device_type,
                        $date,
                        $date
                    );
                    $stmt->execute();
                    $stmt->close();

                    break;

                /* Landing page event */
                case 'landing_page':

                    $post->data = json_encode($post->data);

                    /* Make sure to check if the visitor exists */
                    $visitor = Database::get(['visitor_id'], 'websites_visitors', ['visitor_uuid' => $post->visitor_uuid, 'website_id' => $this->website->website_id]);

                    if (!$visitor) {
                        Response::json('', 'error', ['refresh' => 'visitor']);
                    }

                    /* Insert the session */
                    $stmt = Database::$database->prepare("
                    INSERT IGNORE INTO
                        `visitors_sessions` (`session_uuid`, `visitor_id`, `website_id`, `date`) 
                    VALUES
                        (?, ?, ?, ?)
                ");
                    $stmt->bind_param(
                        'ssss',
                        $post->visitor_session_uuid,
                        $visitor->visitor_id,
                        $this->website->website_id,
                        $date
                    );
                    $stmt->execute();
                    $session_id = $stmt->insert_id;
                    $stmt->close();

                    /* If session is false then it was a double request, end it */
                    if (!$session_id) {
                        die();
                    }

                    /* Insert the event */
                    $event_id = $this->insert_session_event(
                        $post->visitor_session_event_uuid,
                        $session_id,
                        $visitor->visitor_id,
                        $this->website->website_id,
                        $post->type,
                        $post->data,
                        $date
                    );

                    /* Update the last action of the visitor */
                    Database::$database->query("
                    UPDATE `websites_visitors` 
                    SET 
                        `last_date` = '{$date}', 
                        `total_sessions` = `total_sessions` + 1,
                        `last_event_id` = '{$event_id}'
                    WHERE `visitor_id` = {$visitor->visitor_id}
                ");

                    break;

                /* Pageview event */
                case 'pageview':

                    $post->data = json_encode($post->data);

                    /* Make sure to check if the visitor exists */
                    $visitor = Database::get(['visitor_id'], 'websites_visitors', ['visitor_uuid' => $post->visitor_uuid, 'website_id' => $this->website->website_id]);

                    if (!$visitor) {
                        Response::json('', 'error', ['refresh' => 'visitor']);
                    }

                    /* Make sure to check if the session exists */
                    $session = Database::get(['session_id', 'total_events'], 'visitors_sessions', ['session_uuid' => $post->visitor_session_uuid, 'visitor_id' => $visitor->visitor_id, 'website_id' => $this->website->website_id]);

                    if (!$session) {
                        Response::json('', 'error', ['refresh' => 'session']);
                    }

                    /* Insert the event */
                    $event_id = $this->insert_session_event(
                        $post->visitor_session_event_uuid,
                        $session->session_id,
                        $visitor->visitor_id,
                        $this->website->website_id,
                        $post->type,
                        $post->data,
                        $date
                    );

                    /* Check if we should update the landing page event to set it as not bounced */
                    if ($session->total_events == 1) {
                        Database::$database->query("
                            UPDATE `sessions_events` 
                            SET `has_bounced` = 0 
                            WHERE `session_id` = {$session->session_id} AND `type` = 'landing_page'
                        ");
                    }

                    /* Update session */
                    Database::$database->query("
                        UPDATE `visitors_sessions` 
                        SET 
                            `total_events` = `total_events` + 1, 
                        WHERE `session_id` = {$session->session_id}
                    ");

                    /* Update visitor */
                    Database::$database->query("
                        UPDATE `websites_visitors` 
                        SET 
                            `last_date` = '{$date}', 
                            `last_event_id` = '{$event_id}'
                        WHERE `visitor_id` = {$visitor->visitor_id}
                    ");

                    break;

                /* Events Children */
                case 'click':
                case 'scroll':
                case 'form':
                case 'resize':

                    $post->data = json_encode($post->data);

                    /* Make sure to check if the visitor exists */
                    $visitor = Database::get(['visitor_id'], 'websites_visitors', ['visitor_uuid' => $post->visitor_uuid, 'website_id' => $this->website->website_id]);

                    if (!$visitor) {
                        Response::json('', 'error', ['refresh' => 'visitor']);
                    }

                    /* Make sure to check if the session exists */
                    $session = Database::get(['session_id'], 'visitors_sessions', ['session_uuid' => $post->visitor_session_uuid, 'visitor_id' => $visitor->visitor_id, 'website_id' => $this->website->website_id]);

                    if (!$session) {
                        Response::json('', 'error', ['refresh' => 'session']);
                    }

                    /* Make sure to check if the main event exists */
                    $event = Database::get(['event_id'], 'sessions_events', ['event_uuid' => $post->visitor_session_event_uuid, 'session_id' => $session->session_id, 'visitor_id' => $visitor->visitor_id, 'website_id' => $this->website->website_id]);

                    if (!$event) {
                        die();
                    }

                    $expiration_date = (new \DateTime($date))->modify('+' . $this->website_user->plan_settings->events_children_retention . ' days')->format('Y-m-d');
                    $snapshot_id = null;

                    /* Check if the event is sent for a heatmap */
                    if (isset($post->heatmap_id) && $post->heatmap_id && $this->website_user->plan_settings->websites_heatmaps_limit != 0) {

                        /* Make sure the heatmap exists and matches the data */
                        $heatmap_id = (int)Database::clean_string($post->heatmap_id);
                        $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);
                        $snapshot_id_type = 'snapshot_id_' . $device_type;

                        /* Get heatmaps if any */
                        $website_heatmap = $this->database->query("SELECT `heatmap_id`, `path`, `{$snapshot_id_type}` FROM `websites_heatmaps` WHERE `website_id` = {$this->website->website_id} AND `heatmap_id` = {$heatmap_id} AND `{$snapshot_id_type}` IS NOT NULL AND `is_enabled` = 1")->fetch_object() ?? null;

                        if (!$website_heatmap) {
                            die();
                        }

                        if ($_SERVER['HTTP_REFERER'] != $this->website->scheme . $this->website->host . $this->website->path . $website_heatmap->path) {
                            die();
                        }

                        $snapshot_id = $website_heatmap->{$snapshot_id_type};

                        $expiration_date = null;
                    }

                    /* Insert the event */
                    $this->insert_session_event_child(
                        $event->event_id,
                        $session->session_id,
                        $visitor->visitor_id,
                        $snapshot_id,
                        $this->website->website_id,
                        $post->type,
                        $post->data,
                        (int)$post->count,
                        $date,
                        $expiration_date
                    );

                    break;

                /* Replay events */
                case 'replays':

                    /* Make sure to check if the visitor exists */
                    $visitor = Database::get(['visitor_id'], 'websites_visitors', ['visitor_uuid' => $post->visitor_uuid, 'website_id' => $this->website->website_id]);

                    if (!$visitor) {
                        die();
                    }

                    /* Make sure to check if the session exists */
                    $session = Database::get(['session_id'], 'visitors_sessions', ['session_uuid' => $post->visitor_session_uuid, 'visitor_id' => $visitor->visitor_id, 'website_id' => $this->website->website_id]);

                    if (!$session) {
                        Response::json('', 'error', ['refresh' => 'session']);
                    }

                    /* Check if the replay exists and get the data */
                    $replay = Database::get('*', 'sessions_replays', ['session_id' => $session->session_id]);

                    /* Check if the time limit was crossed */
                    if ($replay && (new \DateTime($replay->last_date))->diff((new \DateTime($replay->date)))->i >= $this->website_user->plan_settings->sessions_replays_time_limit) {
                        die();
                    }

                    /* Expiration date for the replay */
                    $expiration_date = (new \DateTime($date))->modify('+' . $this->website_user->plan_settings->sessions_replays_retention . ' days')->format('Y-m-d');

                    /* New events to save */
                    $events = count($post->data);

                    /* Insert or update */
                    $stmt = Database::$database->prepare("
                    INSERT INTO
                        `sessions_replays` (`session_id`, `visitor_id`, `website_id`, `events`, `date`, `last_date`, `expiration_date`) 
                    VALUES
                        (?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                        `events` = `events` + VALUES (events),
                        `last_date` = VALUES (last_date),
                        `expiration_date` = VALUES (expiration_date)
                ");
                    $stmt->bind_param(
                        'sssssss',
                        $session->session_id,
                        $visitor->visitor_id,
                        $this->website->website_id,
                        $events,
                        $date,
                        $date,
                        $expiration_date
                    );
                    $stmt->execute();
                    $affected_rows = $stmt->affected_rows;
                    $stmt->close();

                    /* If its a new session replay, insert the usage */
                    if ($affected_rows == 1) {
                        Database::$database->query("UPDATE `websites` SET `current_month_sessions_replays` = `current_month_sessions_replays` + 1 WHERE `website_id` = {$this->website->website_id}");
                    }

                    /* Gzencode the big data */
                    foreach ($post->data as $key => $value) {
                        $post->data[$key]->data = gzencode(json_encode($post->data[$key]->data), 4);
                    }

                    /* Prepare the data */
                    $expiration_seconds = (new \DateTime($date))->modify('+' . $this->website_user->plan_settings->sessions_replays_retention . ' days')->getTimestamp() - (new \DateTime())->getTimestamp();
                    $session_replay_data = $post->data;

                    /* Store the data */
                    $cache_instance = \Altum\Cache::$store_adapter->getItem('session_replay_' . $session->session_id);

                    if ($existing_data = $cache_instance->get()) {
                        $session_replay_data = array_merge($existing_data, $post->data);
                    }

                    $cache_instance->set($session_replay_data)->expiresAfter($expiration_seconds)->addTag('session_replay_user_' . $this->website->user_id)->addTag('session_replay_website_' . $this->website->website_id);

                    \Altum\Cache::$store_adapter->save($cache_instance);

                    break;

                /* The initial snapshot of the heatmap */
                case 'heatmap_snapshot':

                    /* Some data to use */
                    $heatmap_id = (int)Database::clean_string($post->heatmap_id);
                    $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);
                    $snapshot_id_type = 'snapshot_id_' . $device_type;

                    /* Get heatmaps if any */
                    $website_heatmap = $this->database->query("SELECT `heatmap_id`, `path` FROM `websites_heatmaps` WHERE `website_id` = {$this->website->website_id} AND `heatmap_id` = {$heatmap_id} AND `{$snapshot_id_type}` IS NULL AND `is_enabled` = 1")->fetch_object() ?? null;

                    if (!$website_heatmap) {
                        die();
                    }

                    if ($_SERVER['HTTP_REFERER'] != $this->website->scheme . $this->website->host . $this->website->path . $website_heatmap->path) {
                        die();
                    }

                    /* Gzencode the data for storage in the database */
                    $data = gzencode(json_encode($post->data), 4);

                    /* Prepare to insert the snapshot */
                    $stmt = Database::$database->prepare("
                    INSERT INTO
                        `heatmaps_snapshots` (`heatmap_id`, `website_id`, `type`, `data`, `date`) 
                    VALUES
                        (?, ?, ?, ?, ?)
                ");
                    $stmt->bind_param(
                        'sssss',
                        $heatmap_id,
                        $this->website->website_id,
                        $device_type,
                        $data,
                        $date
                    );
                    $stmt->execute();
                    $snapshot_id = $stmt->insert_id;
                    $stmt->close();

                    Database::$database->query("UPDATE `websites_heatmaps` SET `{$snapshot_id_type}` = {$snapshot_id} WHERE `heatmap_id` = {$website_heatmap->heatmap_id}");

                    break;

                /* Handling goal conversions */
                case 'goal_conversion':

                    /* Make sure to check if the visitor exists */
                    $visitor = Database::get(['visitor_id'], 'websites_visitors', ['visitor_uuid' => $post->visitor_uuid, 'website_id' => $this->website->website_id]);

                    if (!$visitor) {
                        Response::json('', 'error', ['refresh' => 'visitor']);
                    }

                    /* Make sure to check if the session exists */
                    $session = Database::get(['session_id'], 'visitors_sessions', ['session_uuid' => $post->visitor_session_uuid, 'visitor_id' => $visitor->visitor_id, 'website_id' => $this->website->website_id]);

                    if (!$session) {
                        Response::json('', 'error', ['refresh' => 'session']);
                    }

                    /* Make sure to check if the main event exists */
                    $event = Database::get(['event_id'], 'sessions_events', ['event_uuid' => $post->visitor_session_event_uuid, 'session_id' => $session->session_id, 'visitor_id' => $visitor->visitor_id, 'website_id' => $this->website->website_id]);

                    if (!$event) {
                        die();
                    }

                    /* Some data to use */
                    $goal_key = Database::clean_string($post->goal_key);

                    /* Get the goal if any */
                    $website_goal = $this->database->query("SELECT `goal_id`, `type`, `path` FROM `websites_goals` WHERE `website_id` = {$this->website->website_id} AND `key` = '{$goal_key}'")->fetch_object() ?? null;

                    if (!$website_goal) {
                        die();
                    }

                    /* Check if the goal is valid */
                    if ($website_goal->type == 'pageview') {
                        if ($_SERVER['HTTP_REFERER'] != $this->website->scheme . $this->website->host . $this->website->path . $website_goal->path) {
                            die();
                        }
                    }

                    /* Make sure the goal for this user didnt already convert */
                    $conversion = Database::get(['conversion_id'], 'goals_conversions', ['visitor_id' => $visitor->visitor_id, 'website_id' => $this->website->website_id, 'goal_id' => $website_goal->goal_id]);

                    if ($conversion) {
                        die();
                    }

                    /* Prepare to insert the goal conversion */
                    $stmt = Database::$database->prepare("
                        INSERT INTO
                            `goals_conversions` (`event_id`, `session_id`, `visitor_id`, `goal_id`, `website_id`, `date`) 
                        VALUES
                            (?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->bind_param(
                        'ssssss',
                        $event->event_id,
                        $session->session_id,
                        $visitor->visitor_id,
                        $website_goal->goal_id,
                        $this->website->website_id,
                        $date
                    );
                    $stmt->execute();
                    $stmt->close();

                    break;
            }
        }
    }

    private function insert_session_event($event_uuid, $session_id, $visitor_id, $website_id, $type, $data, $date) {

        /* Parse data */
        $data = json_decode($data);

        /* Process the page path */
        $data->path = $this->website->path ? preg_replace('/^' . preg_quote($this->website->path, '/') . '/', '', $data->path) : $data->path;

        /* Process referrer */
        $referrer = parse_url($data->referrer);

        /* Check if the referrer comes from the same location */
        if(
            isset($referrer['host'])
            && $referrer['host'] == $this->website->host
            && (
                isset($referrer['path']) && substr($referrer['path'], 0, strlen($this->website->path)) == $this->website->path
            )
        ) {
            $referrer = [
                'host' => null,
                'path' => null
            ];
        }

        $session_data = [
            'path'              => $data->path ?? '',
            'title'             => $data->title ?? '',
            'referrer_host'     => $referrer['host'] ?? null,
            'referrer_path'     => $referrer['path'] ?? null,
            'utm_source'        => $data->utm->source ?? null,
            'utm_medium'        => $data->utm->medium ?? null,
            'utm_campaign'      => $data->utm->campaign ?? null,
            'utm_term'          => $data->utm->term ?? null,
            'utm_content'       => $data->utm->content ?? null,
            'viewport_width'    => $data->viewport->width ?? 0,
            'viewport_height'   => $data->viewport->height ?? 0,
            'has_bounced'       => $type == 'landing_page' ? 1 : null
        ];

        /* Insert the event */
        $stmt = Database::$database->prepare("
            INSERT INTO
                `sessions_events` (`event_uuid`, `session_id`, `visitor_id`, `website_id`, `type`, `path`, `title`, `referrer_host`, `referrer_path`, `utm_source`, `utm_medium`, `utm_campaign`, `utm_term`, `utm_content`, `viewport_width`, `viewport_height`, `has_bounced`, `date`) 
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            'ssssssssssssssssss',
            $event_uuid,
            $session_id,
            $visitor_id,
            $website_id,
            $type,
            $session_data['path'],
            $session_data['title'],
            $session_data['referrer_host'],
            $session_data['referrer_path'],
            $session_data['utm_source'],
            $session_data['utm_medium'],
            $session_data['utm_campaign'],
            $session_data['utm_term'],
            $session_data['utm_content'],
            $session_data['viewport_width'],
            $session_data['viewport_height'],
            $session_data['has_bounced'],
            $date
        );
        $stmt->execute();
        $event_id = $stmt->insert_id;
        $stmt->close();

        /* Update the website usage */
        Database::$database->query("UPDATE `websites` SET `current_month_sessions_events` = `current_month_sessions_events` + 1 WHERE `website_id` = {$website_id}");

        return $event_id;
    }


    private function insert_session_event_child($event_id, $session_id, $visitor_id, $snapshot_id, $website_id, $type, $data, $count, $date, $expiration_date) {

        /* Insert the event */
        $stmt = Database::$database->prepare("
            INSERT INTO
                `events_children` (`event_id`, `session_id`, `visitor_id`, `snapshot_id`, `website_id`, `type`, `data`, `count`, `date`, `expiration_date`) 
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            'ssssssssss',
            $event_id,
            $session_id,
            $visitor_id,
            $snapshot_id,
            $website_id,
            $type,
            $data,
            $count,
            $date,
            $expiration_date
        );
        $stmt->execute();
        $stmt->close();

        /* Update the website usage */
        Database::$database->query("UPDATE `websites` SET `current_month_events_children` = `current_month_events_children` + 1 WHERE `website_id` = {$website_id}");

    }

}
