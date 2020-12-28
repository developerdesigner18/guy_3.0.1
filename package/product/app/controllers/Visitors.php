<?php

namespace Altum\Controllers;

use Altum\AnalyticsFilters;
use Altum\Middlewares\Authentication;
use Altum\Database\Database;

class Visitors extends Controller {

    public function index() {

        Authentication::guard();

        if(!$this->website || ($this->website && $this->website->tracking_type == 'lightweight')) {
            redirect('websites');
        }

        /* Establish the start and end date for the statistics */
        list($start_date, $end_date) = AnalyticsFilters::get_date();

        $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Filters */
        $active_filters = AnalyticsFilters::get_filters('websites_visitors');
        $filters = AnalyticsFilters::get_filters_sql($active_filters);

        /* Average time per session */
        $average_time_per_session = $this->database->query("
            SELECT 
                AVG(`seconds`) AS `average` 
            FROM 
                (
                    SELECT 
                        TIMESTAMPDIFF(SECOND, MIN(`sessions_events`.`date`), MAX(`sessions_events`.`date`)) AS `seconds` 
                    FROM 
                        `sessions_events`
                    LEFT JOIN
                        `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
                    WHERE 
                        `sessions_events`.`website_id` = {$this->website->website_id} 
                        AND (`sessions_events`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                        AND {$filters}
                    GROUP BY `sessions_events`.`session_id`
                ) AS `seconds`
        ")->fetch_object()->average ?? 0;

        /* Delete Modal */
        $view = new \Altum\Views\View('visitor/visitor_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the paginator */
        $total_rows = $this->database->query("
            SELECT 
                COUNT(*) AS `total`
            FROM 
                `websites_visitors` 
            WHERE
                `website_id` = {$this->website->website_id} 
                AND (`last_date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                AND {$filters}
            ORDER BY
                `last_date` DESC
        ")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, 25, $_GET['page'] ?? 1, url('visitors?page=%d')));

        /* Get the websites list for the user */
        $visitors = [];
        $visitors_result = Database::$database->query("
            SELECT
                `websites_visitors`.*
            FROM
            	`visitors_sessions`
            LEFT JOIN
            	`websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
			WHERE
			     `visitors_sessions`.`website_id` = {$this->website->website_id}
                AND (`visitors_sessions`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                AND {$filters}
			GROUP BY
				`visitor_id`
            ORDER BY
                `websites_visitors`.`last_date` DESC
            LIMIT
                {$paginator->getSqlOffset()}, {$paginator->getItemsPerPage()}
        ");
        while($row = $visitors_result->fetch_object()) $visitors[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the View */
        $data = [
            'date' => $date,
            'total_rows' => $total_rows,
            'average_time_per_session' => $average_time_per_session,
            'pagination' => $pagination,
            'visitors' => $visitors
        ];

        $view = new \Altum\Views\View('visitors/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
