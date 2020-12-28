<?php

namespace Altum\Controllers;

use Altum\AnalyticsFilters;
use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Models\Plan;
use Altum\Routing\Router;

class Dashboard extends Controller {

    public function index() {

        Authentication::guard();

        if(!$this->website) {
            redirect('websites');
        }

        $type = isset($this->params[0]) && in_array($this->params[0], ['paths', 'referrers', 'screen_resolutions', 'utms', 'operating_systems', 'device_types', 'countries', 'browser_names', 'browser_languages', 'goals']) ? Database::clean_string($this->params[0]) : 'default';

        /* Check to see if we need to switch the selected website */
        if(isset($_GET['website_id']) && array_key_exists($_GET['website_id'], $this->websites)) {
            $redirect = $_GET['redirect'] ?? 'dashboard';

            $_COOKIE['selected_website_id'] = (int) $_GET['website_id'];

            setcookie('selected_website_id', (int) $_GET['website_id'], time() + (86400 * 30));

            redirect($redirect);
        }


        $dashboard = $this->{$this->website->tracking_type}();

        /* Referrer Paths Modal */
        $view = new \Altum\Views\View('dashboard/referrer_paths_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Cities Modal */
        $view = new \Altum\Views\View('dashboard/cities_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Create Goal Modal */
        $view = new \Altum\Views\View('dashboard/goal_create_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Update Goal Modal */
        $view = new \Altum\Views\View('dashboard/goal_update_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the inside content View */
        $data = [
            'logs'          => $dashboard['logs'],
            'basic_totals'  => $dashboard['basic_totals'],
            'logs_chart'    => $dashboard['logs_chart']
        ];

        $view = new \Altum\Views\View('dashboard/partials/' . $type, (array) $this);

        $this->add_view_content('dashboard_content', $view->run($data));


        /* Prepare the View */
        $data = [
            'date' => $dashboard['date'],

            'logs' => $dashboard['logs'],
            'basic_totals' => $dashboard['basic_totals'],
            'logs_chart' => $dashboard['logs_chart'],

            'type' => $type
        ];

        $view = new \Altum\Views\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    private function normal() {
        /* Establish the start and end date for the statistics */
        list($start_date, $end_date) = AnalyticsFilters::get_date();

        $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Get basic overall data */
        $logs = [];
        $logs_chart = [];
        $basic_totals = [
            'pageviews' => 0,
            'sessions'  => 0,
            'visitors'  => 0
        ];

        $filters_array = AnalyticsFilters::get_filters();
        $filters = AnalyticsFilters::get_filters_sql($filters_array);

        /* If the date start and end are the same, show only evolution for the hours */
        if($date->start_date == $date->end_date) {
            $result = $this->database->query("
                SELECT 
                    COUNT(*) AS `pageviews`, 
                    COUNT(DISTINCT `sessions_events`.`session_id`) AS `sessions`, 
                    COUNT(DISTINCT `sessions_events`.`visitor_id`) AS `visitors`,
                    DATE_FORMAT(`sessions_events`.`date`, '%Y-%m-%d %H') AS `formatted_date`
                FROM 
                    `sessions_events`
                LEFT JOIN
                    `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
                WHERE 
                    `sessions_events`.`website_id` = {$this->website->website_id} 
                    AND (`sessions_events`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                    AND {$filters}
                GROUP BY
                    `formatted_date`
            ");

        } else {

            /* Apply different query when filters are applied */
            if(count($filters_array)) {
                $result = $this->database->query("
                    SELECT 
                        COUNT(*) AS `pageviews`, 
                        COUNT(DISTINCT `sessions_events`.`session_id`) AS `sessions`, 
                        COUNT(DISTINCT `sessions_events`.`visitor_id`) AS `visitors`,
                        DATE_FORMAT(`sessions_events`.`date`, '%Y-%m-%d') AS `formatted_date`
                    FROM 
                        `sessions_events`
                    LEFT JOIN
                        `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
                    WHERE 
                        `sessions_events`.`website_id` = {$this->website->website_id} 
                        AND (`sessions_events`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                        AND {$filters}
                    GROUP BY
                        `sessions_events`.`formatted_date`
                ");
            } else {
                $result = $this->database->query("
                    SELECT 
                        COUNT(*) AS `pageviews`, 
                        COUNT(DISTINCT `sessions_events`.`session_id`) AS `sessions`, 
                        COUNT(DISTINCT `sessions_events`.`visitor_id`) AS `visitors`,
                        DATE_FORMAT(`sessions_events`.`date`, '%Y-%m-%d') AS `formatted_date`
                    FROM 
                        `sessions_events`
                    WHERE 
                        `sessions_events`.`website_id` = {$this->website->website_id} 
                        AND (`sessions_events`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                    GROUP BY
                        `formatted_date`
                ");
            }
        }

        /* Formatting for the dates on the chart */
        if($date->start_date == $date->end_date) {

            /* Labels for when the date is a single day */
            $label_alt = $label = function($input) {

                $exploded_date = explode(' ', $input);

                return ((new \DateTime())->setTime($exploded_date[1], 0)->setTimezone(new \DateTimeZone(Date::$timezone))->format('H A'));
            };

        } else {
            /* Labels for when the date is a range */
            $label_alt = function($input) {
                return Date::get($input, 2, Date::$default_timezone);
            };
            $label = function($input) {
                return Date::get($input, 'd', Date::$default_timezone);
            };
        }

        /* Generate the raw chart data and save logs for later usage */
        while($row = $result->fetch_object()) {
            $logs[] = $row;

            /* Insert data for the chart */
            $logs_chart[$label($row->formatted_date)] = [
                'pageviews' => $row->pageviews,
                'sessions'  => $row->sessions,
                'visitors'  => $row->visitors,
                'labels_alt' => $label_alt($row->formatted_date)
            ];

            /* Sum for basic totals */
            $basic_totals['pageviews'] += $row->pageviews;
            $basic_totals['sessions'] += $row->sessions;
        }

        $logs_chart = get_chart_data($logs_chart);

        /* Apply different query when filters are applied */
        if(count($filters_array)) {
            $basic_totals['visitors'] = $this->database->query("
                SELECT 
                    COUNT(DISTINCT `visitors_sessions`.`visitor_id`) AS `total`
                FROM 
                    `visitors_sessions`
                LEFT JOIN
                    `sessions_events` ON `visitors_sessions`.`visitor_id` = `sessions_events`.`visitor_id`
                LEFT JOIN
                    `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
                WHERE 
                    `visitors_sessions`.`website_id` = {$this->website->website_id} 
                    AND (`visitors_sessions`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                    AND {$filters}
            ")->fetch_object()->total ?? 0;
        } else {
            $basic_totals['visitors'] = $this->database->query("
                SELECT 
                    COUNT(DISTINCT `visitors_sessions`.`visitor_id`) AS `total`
                FROM 
                    `visitors_sessions`
                WHERE 
                    `visitors_sessions`.`website_id` = {$this->website->website_id} 
                    AND (`visitors_sessions`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
            ")->fetch_object()->total ?? 0;
        }

        return [
            'date'          => $date,
            'logs'          => $logs,
            'basic_totals'  => $basic_totals,
            'logs_chart'    => $logs_chart
        ];
    }

    private function lightweight() {
        /* Establish the start and end date for the statistics */
        list($start_date, $end_date) = AnalyticsFilters::get_date();

        $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Get basic overall data */
        $logs = [];
        $logs_chart = [];
        $basic_totals = [
            'pageviews' => 0,
            'visitors'  => 0
        ];

        /* If the date start and end are the same, show only evolution for the hours */
        if($date->start_date == $date->end_date) {
            $result = $this->database->query("
                SELECT 
                    COUNT(*) AS `pageviews`, 
                    SUM(CASE WHEN `type` = 'landing_page' THEN 1 ELSE 0 END) AS `visitors`,
                    DATE_FORMAT(`date`, '%H') AS `formatted_date`
                FROM 
                    `lightweight_events`
                WHERE 
                    `website_id` = {$this->website->website_id} 
                    AND (`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                GROUP BY
                    `formatted_date`
            ");
        } else {

            $result = $this->database->query("
                SELECT 
                    COUNT(*) AS `pageviews`, 
                    SUM(CASE WHEN `type` = 'landing_page' THEN 1 ELSE 0 END) AS `visitors`,
                    DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
                FROM 
                    `lightweight_events`
                WHERE 
                    `website_id` = {$this->website->website_id} 
                    AND (`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                GROUP BY
                    `formatted_date`
            ");

        }

        /* Formatting for the dates on the chart */
        if($date->start_date == $date->end_date) {
            /* Labels for when the date is a single day */
            $label_alt = function($input) {
                return ((new \DateTime())->setTime($input, 0)->setTimezone(new \DateTimeZone(Date::$timezone))->format('H A'));
            };
            $label = function($input) {
                return ((new \DateTime())->setTime($input, 0)->setTimezone(new \DateTimeZone(Date::$timezone))->format('H A'));
            };
        } else {
            /* Labels for when the date is a range */
            $label_alt = function($input) {
                return Date::get($input, 2, Date::$default_timezone);
            };
            $label = function($input) {
                return Date::get($input, 'd', Date::$default_timezone);
            };
        }

        /* Generate the raw chart data and save logs for later usage */
        while($row = $result->fetch_object()) {
            $logs[] = $row;

            /* Insert data for the chart */
            $logs_chart[$label($row->formatted_date)] = [
                'pageviews' => $row->pageviews,
                'visitors'  => $row->visitors,
                'labels_alt' => $label_alt($row->formatted_date)
            ];

            /* Sum for basic totals */
            $basic_totals['pageviews'] += $row->pageviews;
            $basic_totals['visitors'] += $row->visitors;
        }

        $logs_chart = get_chart_data($logs_chart);

        return [
            'date'          => $date,
            'logs'          => $logs,
            'basic_totals'  => $basic_totals,
            'logs_chart'    => $logs_chart
        ];
    }

    public function csv_normal() {

        header('Content-Disposition: attachment; filename="data.csv";');
        header('Content-Type: application/csv; charset=UTF-8');

        Authentication::guard();

        if(!$this->website) {
            redirect('websites');
        }

        /* Establish the start and end date for the statistics */
        list($start_date, $end_date) = AnalyticsFilters::get_date();

        $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Filters */
        $filters = AnalyticsFilters::get_filters_sql();

        /* Get the data from the database */
        $rows = [];

        $result = $this->database->query("
            SELECT 
                `websites_visitors`.`country_code`,
                `websites_visitors`.`os_name`,
                `websites_visitors`.`os_version`,
                `websites_visitors`.`browser_name`,
                `websites_visitors`.`browser_version`,
                `websites_visitors`.`browser_language`,
                `websites_visitors`.`screen_resolution`,
                `websites_visitors`.`device_type`,
                `sessions_events`.`type`,
                `sessions_events`.`path`,
                `sessions_events`.`title`,
                `sessions_events`.`referrer_host`,
                `sessions_events`.`referrer_path`,
                `sessions_events`.`utm_source`,
                `sessions_events`.`utm_medium`,
                `sessions_events`.`utm_campaign`,
                `sessions_events`.`utm_term`,
                `sessions_events`.`utm_content`,
                `sessions_events`.`viewport_width`,
                `sessions_events`.`viewport_height`,
                DATE_FORMAT(`sessions_events`.`date`, '%Y-%m-%d') AS `formatted_date`
            FROM 
                `sessions_events`
            LEFT JOIN
                `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE 
                `sessions_events`.`website_id` = {$this->website->website_id} 
                AND (`sessions_events`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                AND {$filters}
        ");

        while($row = $result->fetch_object()) {
            $rows[] = $row;
        }

        $csv = csv_exporter($rows);

        die($csv);

    }

    public function csv_lightweight() {

        header('Content-Disposition: attachment; filename="data.csv";');
        header('Content-Type: application/csv; charset=UTF-8');

        Authentication::guard();

        if(!$this->website) {
            redirect('websites');
        }

        /* Establish the start and end date for the statistics */
        list($start_date, $end_date) = AnalyticsFilters::get_date();

        $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Get the data from the database */
        $rows = [];

        $result = $this->database->query("
            SELECT 
                *
            FROM 
                `lightweight_events`
            WHERE 
                `website_id` = {$this->website->website_id} 
                AND (`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
            ");

        while($row = $result->fetch_object()) {

            unset($row->event_id);
            unset($row->website_id);

            $rows[] = $row;
        }

        $csv = csv_exporter($rows);

        die($csv);

    }

}
