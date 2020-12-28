<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class GoalsAjax extends Controller {

    public function index() {
        die();
    }

    private function verify() {
        Authentication::guard();

        if(!Csrf::check('token') && !Csrf::check('global_token')) {
            die();
        }
    }

    public function create() {
        $this->verify();

        if($this->team) {
            die();
        }

        if(empty($_POST)) {
            die();
        }


        $_POST['type'] = in_array($_POST['type'], ['pageview', 'custom']) ? Database::clean_string($_POST['type']) : 'pageview';
        $_POST['name'] = trim(Database::clean_string($_POST['name']));

        switch($_POST['type']) {
            case 'pageview':
                $_POST['path'] = '/' . trim(Database::clean_string($_POST['path']));
                $_POST['key'] = string_generate(16);

                break;

            case 'custom':
                $_POST['key'] = empty(trim(get_slug(Database::clean_string($_POST['key'])))) ? string_generate(16) : trim(get_slug(Database::clean_string($_POST['key'])));
                $_POST['path'] = null;

                break;
        }


        /* Check for possible errors */
        if(empty($_POST['name'])) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        /* Get the count of already created goals */
        $total_websites_goals = Database::$database->query("SELECT COUNT(*) AS `total` FROM `websites_goals` WHERE `website_id` = {$this->website->website_id}")->fetch_object()->total ?? 0;
        if($this->user->plan_settings->websites_goals_limit != -1 && $total_websites_goals >= $this->user->plan_settings->websites_goals_limit) {
            Response::json($this->language->goal_create_modal->error_message->websites_goals_limit, 'error');
        }

        /* Insert to database */
        $stmt = Database::$database->prepare("INSERT INTO `websites_goals` (`website_id`, `key`, `type`, `path`, `name`, `date`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $this->website->website_id, $_POST['key'], $_POST['type'], $_POST['path'], $_POST['name'], Date::$date);
        $stmt->execute();
        $stmt->close();

        Response::json($this->language->goal_create_modal->success_message, 'success');
    }

    public function update() {
        $this->verify();

        if($this->team) {
            die();
        }

        if(empty($_POST)) {
            die();
        }

        $_POST['goal_id'] = (int) $_POST['goal_id'];
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['type'] = in_array($_POST['type'], ['pageview', 'custom']) ? Database::clean_string($_POST['type']) : 'pageview';

        switch($_POST['type']) {
            case 'pageview':
                $_POST['path'] = '/' . trim(Database::clean_string($_POST['path']));
                $_POST['key'] = string_generate(16);

                break;

            case 'custom':
                $_POST['key'] = empty(trim(get_slug(Database::clean_string($_POST['key'])))) ? string_generate(16) : trim(get_slug(Database::clean_string($_POST['key'])));
                $_POST['path'] = null;

                break;
        }


        /* Check for possible errors */
        if(empty($_POST['name'])) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        /* Update database */
        $stmt = Database::$database->prepare("UPDATE `websites_goals` SET `key` = ?, `path` = ?, `name` = ? WHERE `goal_id` = ? AND `website_id` = ?");
        $stmt->bind_param('sssss', $_POST['key'], $_POST['path'], $_POST['name'], $_POST['goal_id'], $this->website->website_id);
        $stmt->execute();
        $stmt->close();

        Response::json($this->language->goal_update_modal->success_message, 'success');
    }

    public function delete() {
        $this->verify();

        if($this->team) {
            die();
        }

        if(empty($_POST)) {
            die();
        }

        $_POST['goal_id'] = (int) $_POST['goal_id'];

        /* Delete from database */
        $stmt = Database::$database->prepare("DELETE FROM `websites_goals` WHERE `goal_id` = ? AND `website_id` = ?");
        $stmt->bind_param('ss', $_POST['goal_id'], $this->website->website_id);
        $stmt->execute();
        $stmt->close();

        Response::json($this->language->goal_delete_modal->success_message, 'success');
    }
}
