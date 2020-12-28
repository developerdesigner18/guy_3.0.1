<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class TeamsAjax extends Controller {

    public function index() {

        Authentication::guard();

        /* Make sure its not a request from a team member */
        if($this->team || !$this->user->plan_settings->teams_is_enabled) {
            die();
        }

        if(!empty($_POST) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Create */
                case 'create': $this->create(); break;

                /* Update */
                case 'update': $this->update(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

            }

        }

        die();
    }

    private function create() {
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $websites_ids = [];

        /* Check for possible errors */
        if(empty($_POST['name']) || !isset($_POST['websites_ids'])) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        foreach($_POST['websites_ids'] as $website_id) {
            if(array_key_exists($website_id, $this->websites)) {
                $websites_ids[] = (int) $website_id;
            }
        }

        if(!count($websites_ids)) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        if(empty($errors)) {
            $websites_ids = json_encode($websites_ids);

            /* Insert to database */
            $stmt = Database::$database->prepare("INSERT INTO `teams` (`user_id`, `name`, `websites_ids`, `date`) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $this->user->user_id, $_POST['name'], $websites_ids, Date::$date);
            $stmt->execute();
            $team_id = $stmt->insert_id;
            $stmt->close();

            Response::json($this->language->team_create_modal->success_message, 'success', ['team_id' => $team_id]);
        }
    }

    private function update() {
        $_POST['team_id'] = (int) $_POST['team_id'];
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $websites_ids = [];

        /* Check for possible errors */
        if(empty($_POST['name']) || !isset($_POST['websites_ids'])) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        foreach($_POST['websites_ids'] as $website_id) {
            if(array_key_exists($website_id, $this->websites)) {
                $websites_ids[] = (int) $website_id;
            }
        }

        if(!count($websites_ids)) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        if(empty($errors)) {
            $websites_ids = json_encode($websites_ids);

            /* Insert to database */
            $stmt = Database::$database->prepare("UPDATE`teams` SET `name` = ?, `websites_ids` = ? WHERE `user_id` = ? AND `team_id` = ?");
            $stmt->bind_param('ssss', $_POST['name'], $websites_ids, $this->user->user_id, $_POST['team_id']);
            $stmt->execute();
            $team_id = $stmt->insert_id;
            $stmt->close();

            Response::json($this->language->team_update_modal->success_message, 'success', ['team_id' => $team_id]);
        }

    }

    private function delete() {
        $_POST['team_id'] = (int) $_POST['team_id'];

        /* Delete from database */
        $stmt = Database::$database->prepare("DELETE FROM `teams` WHERE `team_id` = ? AND `user_id` = ?");
        $stmt->bind_param('ss', $_POST['team_id'], $this->user->user_id);
        $stmt->execute();
        $stmt->close();

        Response::json($this->language->team_delete_modal->success_message, 'success');

    }

}
