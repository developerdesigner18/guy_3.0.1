<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;

class Visitor extends Controller {

    public function index() {

        Authentication::guard();

        $visitor_id = (isset($this->params[0])) ? $this->params[0] : false;

        /* Get the Visitor basic data and make sure it exists */
        if(!$visitor = Database::get('*', 'websites_visitors', ['visitor_id' => $visitor_id, 'website_id' => $this->website->website_id])) {
            redirect('visitors');
        }

        $start_date = isset($_GET['start_date']) ? Database::clean_string($_GET['start_date']) : (new \DateTime())->modify('-30 day')->format('Y-m-d');
        $end_date = isset($_GET['end_date']) ? Database::clean_string($_GET['end_date']) : (new \DateTime())->format('Y-m-d');

        $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

        /* Get session data */
        $sessions_result = $this->database->query("
            SELECT
                `visitors_sessions`.*,
                `sessions_replays`.`session_id` AS `sessions_replays_session_id`,
                COUNT(DISTINCT  `sessions_events`.`event_id`) AS `pageviews`,
	       		MAX(`sessions_events`.`date`) AS `last_date`
            FROM
                `visitors_sessions`
            LEFT JOIN
            	`sessions_events` ON `sessions_events`.`session_id` = `visitors_sessions`.`session_id`
            LEFT JOIN
                `sessions_replays` ON `sessions_replays`.`session_id` = `visitors_sessions`.`session_id`
            WHERE
			     `visitors_sessions`.`website_id` = {$this->website->website_id}
			     AND `visitors_sessions`.`visitor_id` = {$visitor->visitor_id}
			     AND (`visitors_sessions`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
			GROUP BY
				`visitors_sessions`.`session_id`
			ORDER BY
				`visitors_sessions`.`session_id` DESC
        ");

        /* Average time per session */
        $average_time_per_session = $this->database->query("
            SELECT 
                   AVG(`seconds`) AS `average` 
            FROM 
                 (
                     SELECT 
                            TIMESTAMPDIFF(SECOND, MIN(date), MAX(date)) AS `seconds` 
                     FROM 
                          `sessions_events`
                     WHERE 
                           `website_id` = {$this->website->website_id}
                            AND `visitor_id` = {$visitor->visitor_id}
                     GROUP BY `session_id`
                 ) AS `seconds`
        ")->fetch_object()->average ?? 0;

        /* Session Events Modal */
        $view = new \Altum\Views\View('session/session_events_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('visitor/visitor_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'date' => $date,
            'visitor' => $visitor,
            'average_time_per_session' => $average_time_per_session,
            'sessions_result' => $sessions_result
        ];

        $view = new \Altum\Views\View('visitor/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
