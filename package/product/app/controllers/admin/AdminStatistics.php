<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class AdminStatistics extends Controller {
    public $type;
    public $date;

    public function index() {

        Authentication::guard('admin');

        $this->type = (isset($this->params[0])) && in_array($this->params[0], ['payments', 'growth', 'analytics', 'email_reports']) ? Database::clean_string($this->params[0]) : 'growth';

        $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : (new \DateTime())->modify('-30 day')->format('Y-m-d');
        $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : (new \DateTime())->format('Y-m-d');

        $this->date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Process only data that is needed for that specific page */
        $type_data = $this->{$this->type}();

        /* Main View */
        $data = [
            'type' => $this->type,
            'date' => $this->date
        ];
        $data = array_merge($data, $type_data);

        $view = new \Altum\Views\View('admin/statistics/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    protected function payments() {

        $payments_chart = [];
        $result = $this->database->query("SELECT COUNT(*) AS `total_payments`, DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`, TRUNCATE(SUM(`total_amount`), 2) AS `total_amount` FROM `payments` WHERE `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}' GROUP BY `formatted_date`");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $payments_chart[$row->formatted_date] = [
                'total_amount' => $row->total_amount,
                'total_payments' => $row->total_payments
            ];

        }

        $payments_chart = get_chart_data($payments_chart);

        return [
            'payments_chart' => $payments_chart
        ];

    }

    protected function growth() {

        /* Users */
        $users_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `users`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $users_chart[$row->formatted_date] = [
                'users' => $row->total
            ];
        }

        $users_chart = get_chart_data($users_chart);

        /* Websites */
        $websites_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `websites`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $websites_chart[$row->formatted_date] = [
                'websites' => $row->total
            ];
        }

        $websites_chart = get_chart_data($websites_chart);

        /* Teams */
        $teams_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `teams`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $teams_chart[$row->formatted_date] = [
                'teams' => $row->total
            ];
        }

        $teams_chart = get_chart_data($teams_chart);

        /* Users logs */
        $users_logs_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `users_logs`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $users_logs_chart[$row->formatted_date] = [
                'users_logs' => $row->total
            ];
        }

        $users_logs_chart = get_chart_data($users_logs_chart);

        /* Redeemed codes */
        if(in_array($this->settings->license->type, ['SPECIAL', 'Extended License'])) {
            $redeemed_codes_chart = [];
            $result = $this->database->query("
                SELECT
                     COUNT(*) AS `total`,
                     DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
                FROM
                     `redeemed_codes`
                WHERE
                    `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
                GROUP BY
                    `formatted_date`
                ORDER BY
                    `formatted_date`
            ");
            while ($row = $result->fetch_object()) {

                $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

                $redeemed_codes_chart[$row->formatted_date] = [
                    'redeemed_codes' => $row->total
                ];
            }

            $redeemed_codes_chart = get_chart_data($redeemed_codes_chart);
        }

        return [
            'users_chart' => $users_chart,
            'websites_chart' => $websites_chart,
            'teams_chart' => $teams_chart,
            'users_logs_chart' => $users_logs_chart,
            'redeemed_codes_chart' => $redeemed_codes_chart ?? null
        ];
    }

    protected function analytics() {

        /* Sessions events */
        $sessions_events_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `sessions_events`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $sessions_events_chart[$row->formatted_date] = [
                'sessions_events' => $row->total
            ];
        }

        $sessions_events_chart = get_chart_data($sessions_events_chart);

        /* Events children */
        $events_children_chart = [];
        $result = $this->database->query("
            SELECT
                 `type`,
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `events_children`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`,
                `type`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            /* Handle if the date key is not already set */
            if(!array_key_exists($row->formatted_date, $events_children_chart)) {
                $events_children_chart[$row->formatted_date] = [
                    'click'      => 0,
                    'form'       => 0,
                    'scroll'     => 0,
                    'resize'     => 0,
                ];
            }

            $events_children_chart[$row->formatted_date][$row->type] = $row->total;

        }

        $events_children_chart = get_chart_data($events_children_chart);

        /* Sessions replays */
        $sessions_replays_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `sessions_replays`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $sessions_replays_chart[$row->formatted_date] = [
                'sessions_replays' => $row->total
            ];
        }

        $sessions_replays_chart = get_chart_data($sessions_replays_chart);


        /* Goals conversions */
        $goals_conversions_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `goals_conversions`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $goals_conversions_chart[$row->formatted_date] = [
                'goals_conversions' => $row->total
            ];
        }

        $goals_conversions_chart = get_chart_data($goals_conversions_chart);


        return [
            'sessions_events_chart'     => $sessions_events_chart,
            'events_children_chart'     => $events_children_chart,
            'sessions_replays_chart'    => $sessions_replays_chart,
            'goals_conversions_chart'   => $goals_conversions_chart
        ];
    }

    protected function email_reports() {

        $email_reports_chart = [];
        $result = $this->database->query("
            SELECT
                 COUNT(*) AS `total`,
                 DATE_FORMAT(`date`, '%Y-%m-%d') AS `formatted_date`
            FROM
                 `email_reports`
            WHERE
                `date` BETWEEN '{$this->date->start_date_query}' AND '{$this->date->end_date_query}'
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $result->fetch_object()) {

            $row->formatted_date = \Altum\Date::get($row->formatted_date, 2);

            $email_reports_chart[$row->formatted_date] = [
                'email_reports' => $row->total
            ];

        }

        $email_reports_chart = get_chart_data($email_reports_chart);

        return [
            'email_reports_chart' => $email_reports_chart
        ];
    }

}
