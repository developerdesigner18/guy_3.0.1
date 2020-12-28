<?php

namespace Altum\Controllers;

use Altum\AnalyticsFilters;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class ReplaysAjax extends Controller {

    public function index() {
        die();
    }

    public function delete() {

        if(empty($_POST) || (!Csrf::check('token') && !Csrf::check('global_token'))) {
            die();
        }

        /* Delete one replay session */
        if(isset($_POST['session_id'])) {
            $_POST['session_id'] = (int) $_POST['session_id'];

            /* Delete from database */
            $stmt = Database::$database->prepare("DELETE FROM `sessions_replays` WHERE `session_id` = ? AND `website_id` = ?");
            $stmt->bind_param('ss', $_POST['session_id'], $this->website->website_id);
            $stmt->execute();
            $stmt->close();

            /* Clear cache */
            \Altum\Cache::$store_adapter->deleteItem('session_replay_' . $_POST['session_id']);

            Response::json($this->language->replay_delete_modal->success_message, 'success');
        }

        /* Delete all replay sessions within date range */
        else {

            /* Make sure the user has access to the website */
            if(!array_key_exists($_POST['website_id'], $this->websites)) {
                die();
            }

            /* Date parsing  */
            $start_date = isset($_POST['start_date']) ? Database::clean_string($_POST['start_date']) : (new \DateTime())->modify('-30 day')->format('Y-m-d');
            $end_date = isset($_POST['end_date']) ? Database::clean_string($_POST['end_date']) : (new \DateTime())->format('Y-m-d');

            $date = \Altum\Date::get_start_end_dates($start_date, $end_date);

            /* Filters */
            $active_filters = AnalyticsFilters::get_filters('websites_visitors');
            $filters = AnalyticsFilters::get_filters_sql($active_filters);

            /* Select all the session id's to delete from the file system */
            $stmt = Database::$database->query("
                SELECT 
                    `session_id`
                FROM 
                    `sessions_replays`
                LEFT JOIN
                    `websites_visitors` ON `sessions_replays`.`visitor_id` = `websites_visitors`.`visitor_id`
                WHERE 
                    (`sessions_replays`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                    AND {$filters}
                    AND `sessions_replays`.`website_id` = '{$_POST['website_id']}'
            ");

            while($row = $stmt->fetch_object()) {

                /* Clear cache */
                \Altum\Cache::$store_adapter->deleteItem('session_replay_' . $row->session_id);

            }

            /* Delete from database */
            $stmt = Database::$database->prepare("
                DELETE 
                    `sessions_replays`
                FROM 
                    `sessions_replays`
                LEFT JOIN
                    `websites_visitors` ON `sessions_replays`.`visitor_id` = `websites_visitors`.`visitor_id`
                WHERE 
                    (`sessions_replays`.`date` BETWEEN '{$date->start_date_query}' AND '{$date->end_date_query}')
                    AND {$filters}
                    AND `sessions_replays`.`website_id` = ?
            ");
            $stmt->bind_param('s', $_POST['website_id']);
            $stmt->execute();
            $stmt->close();

            Response::json($this->language->replays_delete_modal->success_message, 'success');
        }

    }
}
