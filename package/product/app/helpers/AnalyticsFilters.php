<?php

namespace Altum;

use Altum\Database\Database;

class AnalyticsFilters {
    public static $websites_visitors_filters = [
        'country_code',
        'screen_resolution',
        'browser_language',
        'os_name',
        'device_type',
        'browser_name'
    ];

    public static $sessions_events_filters = [
        'path',
        'title',
        'referrer_host',
        'utm_source',
        'utm_medium',
        'utm_campaign'
    ];

    public static function get_date() {

        /* Establish the start and end date for the statistics */
        if(isset($_GET['start_date'], $_GET['end_date'])) {
            $start_date = Database::clean_string($_GET['start_date']);
            $end_date = Database::clean_string($_GET['end_date']);

            /* Set it to the session */
            $_SESSION['analytics_start_date'] = $start_date;
            $_SESSION['analytics_end_date'] = $end_date;
        }

        /* Try to get start / end date from sessions if any */
        else if(isset($_SESSION['analytics_start_date'], $_SESSION['analytics_end_date'])) {
            $start_date = Database::clean_string($_SESSION['analytics_start_date']);
            $end_date = Database::clean_string($_SESSION['analytics_end_date']);
        }

        /* Default start / end dates */
        else {
            $start_date = (new \DateTime())->modify('-30 day')->format('Y-m-d');
            $end_date = (new \DateTime())->format('Y-m-d');
        }

        return [
            $start_date,
            $end_date
        ];
    }

    public static function get_filters($available_filters = null) {

        /* Determine which type of filters to retrieve */
        switch($available_filters) {
            case 'websites_visitors':
                $available_filters = self::$websites_visitors_filters;

                break;

            case 'sessions_events':
                $available_filters = self::$sessions_events_filters;

                break;

            default:
                $available_filters = array_merge(self::$websites_visitors_filters, self::$sessions_events_filters);
                break;
        }

        $filters = isset($_COOKIE['filters']) ? json_decode($_COOKIE['filters']) : null;
        $processed_filters = [];

        if($filters) {

            foreach($filters as $filter) {

                if(!in_array($filter->by, $available_filters)) {
                    continue;
                }

                if(!in_array($filter->rule, [
                    'is',
                    'is_not',
                    'contains',
                    'starts_with',
                    'ends_with'
                ])) {
                    continue;
                }

                $filter->value = Database::clean_string($filter->value);

                $processed_filters[] = $filter;
            }

        }

        return $processed_filters;
    }

    public static function get_filters_sql($filters = null) {

        if(is_null($filters)) {
            $filters = self::get_filters();
        }

        $wheres = [];

        foreach($filters as $filter) {
            if(in_array($filter->by, self::$websites_visitors_filters)) {
                $table = 'websites_visitors';
            } else

            if(in_array($filter->by, self::$sessions_events_filters)) {
                $table = 'sessions_events';
            }

            switch($filter->rule) {
                case 'is':
                    $condition = "= '{$filter->value}'";
                    break;

                case 'is_not':
                    $condition = "<> '{$filter->value}'";
                    break;

                case 'contains':
                    $condition = "LIKE '%{$filter->value}%'";
                    break;

                case 'starts_with':
                    $condition = "LIKE '%{$filter->value}'";
                    break;

                case 'ends_with':
                    $condition = "LIKE '{$filter->value}%'";
                    break;
            }

            $wheres[] = "`{$table}`.`{$filter->by}` $condition";
        }

        return count($wheres) ? implode(' AND ', $wheres) : '1 = 1';
    }

}
