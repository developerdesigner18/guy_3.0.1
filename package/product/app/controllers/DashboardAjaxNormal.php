<?php

namespace Altum\Controllers;

use Altum\AnalyticsFilters;
use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class DashboardAjaxNormal extends Controller {
    public $date;
    public $request_type;
    public $by = null;
    public $filters = null;
    public $filters_array = null;

    public function index() {
        Authentication::guard();

        if(
            Csrf::check('global_token') &&
            isset($_GET['request_type']) &&
            method_exists($this, $_GET['request_type'])
        ) {
            $this->request_type = $_GET['request_type'];
            $this->limit = $_GET['limit'] == -1 ? 5000 : (int) $_GET['limit'];

            /* Date  */
            $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : (new \DateTime())->modify('-30 day')->format('Y-m-d');
            $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : (new \DateTime())->format('Y-m-d');

            $this->date = \Altum\Date::get_start_end_dates($start_date, $end_date);

            /* Check if realtime request */
            if(isset($_GET['request_subtype']) && $_GET['request_subtype'] == 'realtime' && $start_date == 'now' && $end_date == 'now') {
                $start_date = (new \DateTime())->modify('-5 minute')->format('Y-m-d H:i:s');
                $end_date = (new \DateTime())->format('Y-m-d H:i:s');

                $this->date = \Altum\Date::get_start_end_dates($start_date, $end_date , \Altum\Date::$default_timezone, \Altum\Date::$default_timezone);
            }

            /* Filters */
            $this->filters_array = AnalyticsFilters::get_filters();
            $this->filters = AnalyticsFilters::get_filters_sql($this->filters_array);

            /* Run the proper method */
            $this->{$this->request_type}();

        }

        die();
    }

    private function countries() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `websites_visitors`.`country_code`,
                COUNT(IFNULL(`websites_visitors`.`country_code`, 1)) AS `total`
            FROM
                (
                    SELECT DISTINCT `visitors_sessions`.`visitor_id` 
                    FROM `visitors_sessions`
                    JOIN `sessions_events` ON `visitors_sessions`.`visitor_id` = `sessions_events`.`visitor_id`
                    JOIN `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
                    WHERE `visitors_sessions`.`website_id` = {$this->website->website_id}  AND (`visitors_sessions`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}') AND {$this->filters}
                ) AS `visitors_sessions`
            RIGHT JOIN 
                `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE
                `visitors_sessions`.`visitor_id` IS NOT NULL
            GROUP BY
                `websites_visitors`.`country_code`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'visitors';

        $this->process_and_run($result);
    }

    private function cities() {
        /* Search by country_code */
        $_GET['country_code'] = Database::clean_string($_GET['country_code']);

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `websites_visitors`.`city_name`,
                COUNT(IFNULL(`websites_visitors`.`city_name`, 1)) AS `total`
            FROM
            	`websites_visitors`
			WHERE
			    `websites_visitors`.`website_id` = {$this->website->website_id} 
			    AND `websites_visitors`.`country_code` = '{$_GET['country_code']}'
			    AND (`websites_visitors`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
           GROUP BY
                `websites_visitors`.`city_name`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'visitors';

        $this->process_and_run($result);
    }

    private function realtime_countries() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `websites_visitors`.`country_code`,
                COUNT(IFNULL(`websites_visitors`.`country_code`, 1)) AS `total`
            FROM
                (
                    SELECT DISTINCT `visitor_id` 
                    FROM `websites_visitors` 
                    WHERE `websites_visitors`.`website_id` = {$this->website->website_id}  AND (`last_date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                ) AS `altum`
            RIGHT JOIN 
                `websites_visitors` ON `altum`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE
                `altum`.`visitor_id` IS NOT NULL
            GROUP BY
                `websites_visitors`.`country_code`
            ORDER BY 
                `total` DESC
        ");

        $this->request_type = 'countries';
        $this->by = 'visitors';

        $this->process_and_run($result);
    }

    private function operating_systems() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `websites_visitors`.`os_name`,
                COUNT(`websites_visitors`.`os_name`) AS `total`
            FROM
                (
                    SELECT DISTINCT `visitors_sessions`.`visitor_id` 
                    FROM `visitors_sessions`
                    JOIN `sessions_events` ON `visitors_sessions`.`visitor_id` = `sessions_events`.`visitor_id`
                    JOIN `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
                    WHERE `visitors_sessions`.`website_id` = {$this->website->website_id}  AND (`visitors_sessions`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}') AND {$this->filters}
                ) AS `visitors_sessions`
            RIGHT JOIN 
                `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE
                `websites_visitors`.`os_name` IS NOT NULL AND `visitors_sessions`.`visitor_id` IS NOT NULL
            GROUP BY
                `websites_visitors`.`os_name`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'visitors';

        $this->process_and_run($result);
    }

    private function screen_resolutions() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `websites_visitors`.`screen_resolution`,
                COUNT(`websites_visitors`.`screen_resolution`) AS `total`
            FROM
                (
                    SELECT DISTINCT `visitors_sessions`.`visitor_id` 
                    FROM `visitors_sessions`
                    JOIN `sessions_events` ON `visitors_sessions`.`visitor_id` = `sessions_events`.`visitor_id`
                    JOIN `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
                    WHERE `visitors_sessions`.`website_id` = {$this->website->website_id}  AND (`visitors_sessions`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}') AND {$this->filters}
                ) AS `visitors_sessions`
            RIGHT JOIN 
                `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE
                `websites_visitors`.`screen_resolution` IS NOT NULL AND `visitors_sessions`.`visitor_id` IS NOT NULL
            GROUP BY
                `websites_visitors`.`screen_resolution`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'visitors';

        $this->process_and_run($result);
    }

    private function browser_languages() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `websites_visitors`.`browser_language`,
                COUNT(`websites_visitors`.`browser_language`) AS `total`
            FROM
                (
                    SELECT DISTINCT `visitors_sessions`.`visitor_id` 
                    FROM `visitors_sessions`
                    JOIN `sessions_events` ON `visitors_sessions`.`visitor_id` = `sessions_events`.`visitor_id`
                    JOIN `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
                    WHERE `visitors_sessions`.`website_id` = {$this->website->website_id}  AND (`visitors_sessions`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}') AND {$this->filters}
                ) AS `visitors_sessions`
            RIGHT JOIN 
                `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE
                `websites_visitors`.`browser_language` IS NOT NULL AND `visitors_sessions`.`visitor_id` IS NOT NULL
            GROUP BY
                `websites_visitors`.`browser_language`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'visitors';

        $this->process_and_run($result);
    }

    private function device_types() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `websites_visitors`.`device_type`,
                COUNT(`websites_visitors`.`device_type`) AS `total`
            FROM
                (
                    SELECT DISTINCT `visitors_sessions`.`visitor_id` 
                    FROM `visitors_sessions`
                    JOIN `sessions_events` ON `visitors_sessions`.`visitor_id` = `sessions_events`.`visitor_id`
                    JOIN `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
                    WHERE `visitors_sessions`.`website_id` = {$this->website->website_id}  AND (`visitors_sessions`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}') AND {$this->filters}
                ) AS `visitors_sessions`
            RIGHT JOIN 
                `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE
                `websites_visitors`.`device_type` IS NOT NULL AND `visitors_sessions`.`visitor_id` IS NOT NULL
            GROUP BY
                `websites_visitors`.`device_type`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'visitors';

        $this->process_and_run($result);
    }

    private function realtime_device_types() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `websites_visitors`.`device_type`,
                COUNT(`websites_visitors`.`device_type`) AS `total`
            FROM
                (
                    SELECT DISTINCT `visitor_id` 
                    FROM `websites_visitors` 
                    WHERE `websites_visitors`.`website_id` = {$this->website->website_id}  AND (`last_date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                ) AS `altum`
            RIGHT JOIN 
                `websites_visitors` ON `altum`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE
                `websites_visitors`.`device_type` IS NOT NULL AND `altum`.`visitor_id` IS NOT NULL
            GROUP BY
                `websites_visitors`.`device_type`
            ORDER BY 
                `total` DESC
        ");

        $this->request_type = 'device_types';
        $this->by = 'visitors';

        $this->process_and_run($result);
    }

    private function browser_names() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `websites_visitors`.`browser_name`,
                COUNT(`websites_visitors`.`browser_name`) AS `total`
            FROM
                (
                    SELECT DISTINCT `visitors_sessions`.`visitor_id` 
                    FROM `visitors_sessions`
                    JOIN `sessions_events` ON `visitors_sessions`.`visitor_id` = `sessions_events`.`visitor_id`
                    JOIN `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
                    WHERE `visitors_sessions`.`website_id` = {$this->website->website_id}  AND (`visitors_sessions`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}') AND {$this->filters}
                ) AS `visitors_sessions`
            RIGHT JOIN 
                `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE
                `websites_visitors`.`browser_name` IS NOT NULL AND `visitors_sessions`.`visitor_id` IS NOT NULL
            GROUP BY
                `websites_visitors`.`browser_name`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'visitors';

        $this->process_and_run($result);
    }

    private function paths() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `sessions_events`.`path`,
                COUNT(`sessions_events`.`path`) AS `total`,
                SUM(`sessions_events`.`has_bounced`) AS `bounces`
            FROM
            	`sessions_events`
            LEFT JOIN 
                `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
			WHERE
			    `sessions_events`.`website_id` = {$this->website->website_id} 
			    AND (`sessions_events`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
			    AND {$this->filters}
           GROUP BY
                `sessions_events`.`path`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function landing_paths() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `sessions_events`.`path`,
                COUNT(`sessions_events`.`path`) AS `total`,
                SUM(`sessions_events`.`has_bounced`) AS `bounces`
            FROM
            	`sessions_events`
            LEFT JOIN 
                `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
			WHERE
			    `sessions_events`.`website_id` = {$this->website->website_id} 
			    AND (`sessions_events`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
			    AND `sessions_events`.`type` = 'landing_page'
			    AND {$this->filters}
           GROUP BY
                `sessions_events`.`path`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function exit_paths() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `sessions_events`.`path`,
                COUNT(`sessions_events`.`path`) AS `total`,
                SUM(`sessions_events`.`has_bounced`) AS `bounces`
            FROM
                (
                    SELECT
                        `sessions_events`.`session_id`,
                        MAX(`sessions_events`.`event_id`) AS `event_id`
                    FROM `sessions_events`
                    LEFT JOIN  `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
                    WHERE 
                        `sessions_events`.`website_id` = {$this->website->website_id} 
                        AND (`sessions_events`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
                        AND {$this->filters}
                    GROUP BY `sessions_events`.`session_id`
                ) AS `sessions_events_x`
            LEFT JOIN
            	`sessions_events` ON `sessions_events_x`.`event_id` = `sessions_events`.`event_id`
            GROUP BY
                `sessions_events`.`path`
            ORDER BY 
                `total` DESC;
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function realtime_paths() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `sessions_events`.`path`,
                COUNT(`sessions_events`.`path`) AS `total`,
                SUM(`sessions_events`.`has_bounced`) AS `bounces`
            FROM
                `sessions_events`
            LEFT JOIN
                `websites_visitors` ON `sessions_events`.`event_id` = `websites_visitors`.`last_event_id`
            WHERE
                `websites_visitors`.`website_id` = {$this->website->website_id} 
                AND (`websites_visitors`.`last_date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
            GROUP BY
                `path`
            ORDER BY 
                `total` DESC
        ");

        $this->request_type = 'paths';
        $this->by = 'visitors';

        $this->process_and_run($result);
    }

    private function referrers() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `sessions_events`.`referrer_host`,
                COUNT(IFNULL(`sessions_events`.`referrer_host`, 1)) AS `total`,
                SUM(`sessions_events`.`has_bounced`) AS `bounces`
            FROM
            	`sessions_events`
            LEFT JOIN 
                `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
			WHERE
			    `sessions_events`.`website_id` = {$this->website->website_id} 
			    AND (`sessions_events`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
			    AND {$this->filters}
           GROUP BY
                `sessions_events`.`referrer_host`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function referrer_paths() {
        /* Search by host */
        $_GET['referrer_host'] = Database::clean_string($_GET['referrer_host']);

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `sessions_events`.`referrer_path`,
                `sessions_events`.`referrer_host`,
                COUNT(IFNULL(`sessions_events`.`referrer_path`, 1)) AS `total`,
                SUM(`sessions_events`.`has_bounced`) AS `bounces`
            FROM
            	`sessions_events`
            LEFT JOIN 
                `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
			WHERE
			    `sessions_events`.`website_id` = {$this->website->website_id} 
			    AND `sessions_events`.`referrer_host` = '{$_GET['referrer_host']}'
			    AND (`sessions_events`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
           GROUP BY
                `sessions_events`.`referrer_path`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function social_media_referrers() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                CASE
                    WHEN `sessions_events`.`referrer_host` = 'l.facebook.com' then 'facebook.com'
                    WHEN `sessions_events`.`referrer_host` = 'l.instagram.com' then 'instagram.com'
                    WHEN `sessions_events`.`referrer_host` LIKE '%.pinterest.com' then 'pinterest.com'
                    WHEN `sessions_events`.`referrer_host` = 't.co' then 'twitter.com'
                    WHEN `sessions_events`.`referrer_host` = 'www.youtube.com' then 'youtube.com'
                END AS `referrer`,
                COUNT(IFNULL(`sessions_events`.`referrer_host`, 1)) AS `total`,
                SUM(`sessions_events`.`has_bounced`) AS `bounces`
            FROM
            	`sessions_events`
            LEFT JOIN 
                `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
			WHERE
			    `sessions_events`.`website_id` = {$this->website->website_id} 
			    AND (`sessions_events`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
			    AND (
			        `sessions_events`.`referrer_host` IN ('l.facebook.com', 't.co', 'www.pinterest.com', 'l.instagram.com', 'www.youtube.com') 
			        OR `sessions_events`.`referrer_host` LIKE '%.pinterest.com'
			    )
			    AND {$this->filters}
            GROUP BY
                `referrer`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function search_engines_referrers() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                CASE
                    WHEN `sessions_events`.`referrer_host` = 'www.bing.com' then 'bing.com'
                    WHEN `sessions_events`.`referrer_host` = 'ecosia.org' then 'ecosia.org'
                    WHEN `sessions_events`.`referrer_host` LIKE 'www.google.%' then 'google.com'
                    WHEN `sessions_events`.`referrer_host` LIKE '%.yahoo.com' then 'yahoo.com'
                    WHEN `sessions_events`.`referrer_host` = 'yandex.com' then 'yandex.com'
                END AS `referrer`,
                COUNT(IFNULL(`sessions_events`.`referrer_host`, 1)) AS `total`,
                SUM(`sessions_events`.`has_bounced`) AS `bounces`
            FROM
            	`sessions_events`
            LEFT JOIN 
                `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
			WHERE
			    `sessions_events`.`website_id` = {$this->website->website_id} 
			    AND (`sessions_events`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
			    AND (
			        `sessions_events`.`referrer_host` IN ('www.bing.com', 'ecosia.org', 'yandex.com') 
			        OR `sessions_events`.`referrer_host` LIKE 'www.google.%' 
			        OR `sessions_events`.`referrer_host` LIKE '%.yahoo.com'
			    )
			    AND {$this->filters}
            GROUP BY
                `referrer`
            ORDER BY 
                `total` DESC
        ");

        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function utms_source() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `sessions_events`.`utm_source` AS `utm`,
                COUNT(`sessions_events`.`utm_source`) AS `total`
            FROM
            	`sessions_events`
            LEFT JOIN 
                `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
			WHERE
			    `sessions_events`.`website_id` = {$this->website->website_id} 
			    AND (`sessions_events`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
			    AND `sessions_events`.`utm_source` IS NOT NULL
                AND {$this->filters}
           GROUP BY
                `sessions_events`.`utm_source`
            ORDER BY 
                `total` DESC
        ");

        $this->request_type = 'utms';
        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function utms_medium() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `sessions_events`.`utm_medium` AS `utm`,
                COUNT(`sessions_events`.`utm_medium`) AS `total`
            FROM
            	`sessions_events`
            LEFT JOIN 
                `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
			WHERE
			    `sessions_events`.`website_id` = {$this->website->website_id} 
			    AND (`sessions_events`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
			    AND `sessions_events`.`utm_medium` IS NOT NULL
                AND {$this->filters}
           GROUP BY
                `sessions_events`.`utm_medium`
            ORDER BY 
                `total` DESC
        ");

        $this->request_type = 'utms';
        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    private function utms_campaign() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `sessions_events`.`utm_campaign` AS `utm`,
                COUNT(`sessions_events`.`utm_campaign`) AS `total`
            FROM
            	`sessions_events`
            LEFT JOIN 
                `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
			WHERE
			    `sessions_events`.`website_id` = {$this->website->website_id} 
			    AND (`sessions_events`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
			    AND `sessions_events`.`utm_campaign` IS NOT NULL
			    AND {$this->filters}
           GROUP BY
                `sessions_events`.`utm_campaign`
            ORDER BY 
                `total` DESC
        ");

        $this->request_type = 'utms';
        $this->by = 'pageviews';

        $this->process_and_run($result);
    }

    /* Goals */
    private function goals() {

        /* Get the data */
        $result = $this->database->query("
            SELECT
                `websites_goals`.`goal_id`,
                `websites_goals`.`key`,
                `websites_goals`.`type`,
                `websites_goals`.`path`,
                `websites_goals`.`name`,
                (
                	SELECT 
                		COUNT(`goals_conversions`.`conversion_id`) 
                	FROM 
                		`goals_conversions`
                	LEFT JOIN
                		`sessions_events` ON `sessions_events`.`event_id` = `goals_conversions`.`event_id`
            		LEFT JOIN
                		`websites_visitors` ON `websites_visitors`.`visitor_id` = `goals_conversions`.`visitor_id`
            		WHERE
            			`goals_conversions`.`goal_id` = `websites_goals`.`goal_id`
                		AND `goals_conversions`.`website_id` = {$this->website->website_id} 
                		AND (`goals_conversions`.`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
			            AND {$this->filters}
                ) AS `total`
            FROM
                `websites_goals`
                WHERE
                `websites_goals`.`website_id` = {$this->website->website_id}  
            ORDER BY 
                `total` DESC;
        ");

        $this->by = 'conversions';

        $this->process_and_run($result);
    }

    /* Realtime specific requests */
    private function realtime_visitors() {
        $visitors = $this->database->query("
            SELECT 
                COUNT(*) AS `total`
            FROM 
                `websites_visitors` 
            WHERE
                `website_id` = {$this->website->website_id} 
			    AND (`last_date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
            ORDER BY
                `last_date` DESC
        ")->fetch_object()->total ?? 0;

        Response::json('', 'success', ['html' => $visitors]);
    }

    private function realtime_chart_data() {
        $logs_chart = [];

        $result = $this->database->query("
            SELECT 
                COUNT(*) AS `pageviews`, 
                COUNT(DISTINCT `session_id`) AS `sessions`, 
                COUNT(DISTINCT `visitor_id`) AS `visitors`,
                `date`
            FROM 
                `sessions_events` 
            WHERE 
                `website_id` = {$this->website->website_id} 
			    AND (`date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}')
            GROUP BY
                `date`
        ");

        /* Generate the raw chart data and save logs for later usage */
        while($row = $result->fetch_object()) {

            /* Insert data for the chart */
            $formatted_date = Date::get($row->date, 'H:i');

            if(isset($logs_chart[$formatted_date])) {
                $logs_chart[$formatted_date] = [
                    'pageviews' => $logs_chart[$formatted_date]['pageviews'] + $row->pageviews
                ];
            } else {
                $logs_chart[$formatted_date] = [
                    'pageviews' => $row->pageviews
                ];
            }

        }

        $logs_chart = get_chart_data($logs_chart);

        Response::json('', 'success', ['logs_chart_labels' => $logs_chart['labels'], 'logs_chart_pageviews' => $logs_chart['pageviews']]);
    }

    private function process($result) {
        /* Go over the result */
        $rows = [];
        $total_sum = 0;
        $total_rows = 0;
        $options = [];

        while($row = $result->fetch_object()) {
            $total_rows++;

            if(!$this->limit || ($this->limit && $total_rows <= $this->limit)) {
                $rows[] = $row;

                $total_sum += $row->total;
            }

        }

        /* Check for options in displayment */
        $options['bounce_rate'] = isset($_GET['bounce_rate']) && $_GET['bounce_rate'] == 'true';

        return [
            'by'        => $this->by,
            'rows'      => $rows,
            'options'   => $options,
            'total_sum' => $total_sum,
            'total_rows'=> $total_rows
        ];
    }

    private function process_and_run($result) {

        /* Prepare the View */
        $data = $this->process($result);

        $view = new \Altum\Views\View('dashboard/ajaxed_partials/' . $this->request_type, (array) $this);

        Response::json('', 'success', ['html' => $view->run($data), 'data' => json_encode($data)]);

    }

}
