<?php

namespace Altum\Controllers;

use Altum\Database\Database;

class Pixel extends Controller {

    public function index() {
        $seconds_to_cache = $this->settings->analytics->pixel_cache;
        header('Content-Type: application/javascript');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $seconds_to_cache) . ' GMT');
        header('Pragma: cache');
        header('Cache-Control: max-age=' . $seconds_to_cache);

        if(!isset($_SERVER['HTTP_REFERER'])) {
            die(json_encode($this->language->pixel_track->error_message->no_referrer));
        }

        /* Check against bots */
        $CrawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect();

        if($CrawlerDetect->isCrawler()) {
            die(json_encode($this->language->pixel_track->error_message->excluded_bot));
        }

        /* Clean the pixel key */
        $pixel_key = isset($this->params[0]) ? Database::clean_string($this->params[0]) : false;

        /* Find the website for the host */
        $host = Database::clean_string(parse_url($_SERVER['HTTP_REFERER'])['host']);

        /* Remove www. from the host */
        $prefix = 'www.';

        if(substr($host, 0, strlen($prefix)) == $prefix) {
            $host = substr($host, strlen($prefix));
        }

        /* Get the details of the campaign from the database */
        $website = (new \Altum\Models\Website())->get_website_by_pixel_key($pixel_key);

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

        /* Make sure to get the user data and confirm the user is ok */
        $user = (new \Altum\Models\User())->get_user_by_user_id($website->user_id);

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

        /* Make sure that the user didnt exceed the current plan */
        if($user->plan_settings->sessions_events_limit != -1 && $user->current_month_sessions_events >= $user->plan_settings->sessions_events_limit) {
            die(json_encode($this->language->pixel_track->error_message->plan_limit));
        }

        $pixel_track_events_children = (bool) $website->events_children_is_enabled && ($user->plan_settings->events_children_limit == -1 || $website->current_month_events_children < $user->plan_settings->events_children_limit);
        $pixel_track_sessions_replays = (bool) $this->settings->analytics->sessions_replays_is_enabled && $website->sessions_replays_is_enabled && ($user->plan_settings->sessions_replays_limit == -1 || $website->current_month_sessions_replays < $user->plan_settings->sessions_replays_limit);

        /* Get heatmaps if any and if the user has rights */
        $pixel_heatmaps = [];

        if($website->tracking_type == 'normal' && $user->plan_settings->websites_heatmaps_limit != 0) {
            $website_heatmaps_result = $this->database->query("SELECT `heatmap_id`, `path`, `snapshot_id_desktop`, `snapshot_id_tablet`, `snapshot_id_mobile` FROM `websites_heatmaps` WHERE `website_id` = {$website->website_id} AND `is_enabled` = 1");

            while($row = $website_heatmaps_result->fetch_object()) {

                /* Generate the full url needed to match for the heatmap */
                $row->url = $website->host . $website->path . $row->path;

                $pixel_heatmaps[] = $row;
            }
        }

        /* Get available goals for the website */
        $pixel_goals = [];

        if($user->plan_settings->websites_goals_limit != 0) {
            $website_goals_result = $this->database->query("SELECT `key`, `type`, `path` FROM `websites_goals` WHERE `website_id` = {$website->website_id}");

            while($row = $website_goals_result->fetch_object()) {

                /* Generate the full url needed to match for the heatmap */
                $row->url = $website->host . $website->path . $row->path;

                $pixel_goals[] = $row;
            }
        }

        /* Main View */
        switch($website->tracking_type) {
            case 'lightweight':
                $data = [
                    'pixel_key'                     => $pixel_key,
                    'pixel_goals'                   => $pixel_goals,
                ];

            break;

            case 'normal':
                $data = [
                    'pixel_key'                     => $pixel_key,
                    'pixel_heatmaps'                => $pixel_heatmaps,
                    'pixel_goals'                   => $pixel_goals,
                    'pixel_track_events_children'   => $pixel_track_events_children,
                    'pixel_track_sessions_replays'  => $pixel_track_sessions_replays
                ];

            break;
        }

        $view = new \Altum\Views\View('pixel/' . $website->tracking_type . '/pixel', (array) $this);

        echo $view->run($data);

    }
}
