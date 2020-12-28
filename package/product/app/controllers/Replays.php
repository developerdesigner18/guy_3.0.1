<?php

namespace Altum\Controllers;

use Altum\AnalyticsFilters;
use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;

class Replays extends Controller {

    public function index() {

        Authentication::guard();

        if(!$this->website || !$this->settings->analytics->sessions_replays_is_enabled || ($this->website && $this->website->tracking_type == 'lightweight')) {
            redirect('websites');
        }

        /* Establish the start and end date for the statistics */
        list($start_date, $end_date) = AnalyticsFilters::get_date();

        $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Filters */
        $active_filters = AnalyticsFilters::get_filters('websites_visitors');
        $filters = AnalyticsFilters::get_filters_sql($active_filters);

        /* Delete Modal */
        $view = new \Altum\Views\View('replay/replay_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('replays/replays_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the paginator */
        $total_replays = $this->database->query("
            SELECT 
                COUNT(DISTINCT `sessions_replays`.`session_id`) AS `total`
            FROM 
                `visitors_sessions` 
            LEFT JOIN
                `sessions_replays` ON `sessions_replays`.`session_id` = `visitors_sessions`.`session_id`
            LEFT JOIN
            	`websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE 
                `visitors_sessions`.`website_id` = {$this->website->website_id} 
                AND `sessions_replays`.`session_id` IS NOT NULL 
                AND (`visitors_sessions`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}') 
                AND {$filters}
        ")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_replays, 25, $_GET['page'] ?? 1, url('replays?page=%d')));

        /* Get the websites list for the user */
        $replays = [];
        $replays_result = Database::$database->query("
            SELECT
                `visitors_sessions`.`session_id`,
                `websites_visitors`.`visitor_uuid`,
                `websites_visitors`.`custom_parameters`,
                `websites_visitors`.`country_code`,
                `websites_visitors`.`visitor_id`,
                `websites_visitors`.`date`,
                
                `sessions_replays`.`events`,
                `sessions_replays`.`date`,
                `sessions_replays`.`last_date`            
            FROM
            	`visitors_sessions`
            LEFT JOIN
                `sessions_replays` ON `sessions_replays`.`session_id` = `visitors_sessions`.`session_id`
            LEFT JOIN
            	`websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE
			     `visitors_sessions`.`website_id` = {$this->website->website_id}
			     AND `sessions_replays`.`session_id` IS NOT NULL
			     AND (`visitors_sessions`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
			     AND {$filters}
			GROUP BY
				`visitors_sessions`.`session_id`
			ORDER BY
				`visitors_sessions`.`session_id` DESC
            LIMIT
                {$paginator->getSqlOffset()}, {$paginator->getItemsPerPage()}
        ");
        while($row = $replays_result->fetch_object()) $replays[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the View */
        $data = [
            'date' => $date,
            'total_replays' => $total_replays,
            'replays' => $replays,
            'pagination' => $pagination
        ];

        $view = new \Altum\Views\View('replays/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
