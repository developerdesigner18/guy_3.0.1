<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class WebsitesAjax extends Controller {

    public function index() {

        Authentication::guard();

        /* Make sure its not a request from a team member */
        if($this->team) {
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
        $_POST['scheme'] = in_array($_POST['scheme'], ['https://', 'http://']) ? Database::clean_string($_POST['scheme']) : 'https://';
        $_POST['host'] = trim($_POST['host']);
        $_POST['tracking_type'] = in_array($_POST['tracking_type'], ['lightweight', 'normal']) ? Database::clean_string($_POST['tracking_type']) : 'lightweight';
        $_POST['events_children_is_enabled'] = (int) isset($_POST['events_children_is_enabled']);
        $_POST['sessions_replays_is_enabled'] = (int) isset($_POST['sessions_replays_is_enabled']);
        $_POST['email_reports_is_enabled'] = (int) isset($_POST['email_reports_is_enabled']);
        $is_enabled = 1;

        /* Check for possible errors */
        if(empty($_POST['name']) || empty($_POST['host'])) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        /* Domain checking */
        $pslManager = new \Pdp\PublicSuffixListManager();
        $parser = new \Pdp\Parser($pslManager->getList());
        $url = $parser->parseUrl($_POST['host']);
        $punnnycode = new \TrueBV\Punycode();
        $host = Database::clean_string($punnnycode->encode($url->getHost()));
        $path = Database::clean_string($url->getPath()) ? preg_replace('/\/+$/', '', Database::clean_string($url->getPath())) : null;

        /* Generate an unique pixel key for the website */
        $pixel_key = string_generate(16);
        while(Database::exists('pixel_key', 'websites', ['pixel_key' => $pixel_key])) {
            $pixel_key = string_generate(16);
        }

        /* Make sure that the user didn't exceed the limit */
        $account_total_websites = Database::$database->query("SELECT COUNT(*) AS `total` FROM `websites` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total;
        if($this->user->plan_settings->websites_limit != -1 && $account_total_websites >= $this->user->plan_settings->websites_limit) {
            Response::json($this->language->website_create_modal->error_message->websites_limit, 'error');
        }


        /* Insert to database */
        $stmt = Database::$database->prepare("INSERT INTO `websites` (`user_id`, `pixel_key`, `name`, `scheme`, `host`, `path`, `tracking_type`, `events_children_is_enabled`, `sessions_replays_is_enabled`, `email_reports_is_enabled`, `email_reports_last_date`, `is_enabled`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssssssss', $this->user->user_id, $pixel_key, $_POST['name'], $_POST['scheme'], $host, $path, $_POST['tracking_type'], $_POST['events_children_is_enabled'], $_POST['sessions_replays_is_enabled'], $_POST['email_reports_is_enabled'], Date::$date, $is_enabled, Date::$date);
        $stmt->execute();
        $website_id = $stmt->insert_id;
        $stmt->close();

        /* Clear cache */
        \Altum\Cache::$adapter->deleteItem('websites_' . $this->user->user_id);
        \Altum\Cache::$adapter->deleteItemsByTag('website_id=' . $website_id);

        Response::json($this->language->website_create_modal->success_message, 'success');
    }

    private function update() {
        $_POST['website_id'] = (int) $_POST['website_id'];
        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['scheme'] = in_array($_POST['scheme'], ['https://', 'http://']) ? Database::clean_string($_POST['scheme']) : 'https://';
        $_POST['host'] = trim($_POST['host']);
        $_POST['events_children_is_enabled'] = (int) isset($_POST['events_children_is_enabled']);
        $_POST['sessions_replays_is_enabled'] = (int) isset($_POST['sessions_replays_is_enabled']);
        $_POST['email_reports_is_enabled'] = (int) isset($_POST['email_reports_is_enabled']);
        $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);
        $_POST['excluded_ips'] = implode(',', array_map(function($value) {
            return Database::clean_string(trim($value));
        }, explode(',', $_POST['excluded_ips'])));

        /* Check for possible errors */
        if(empty($_POST['name']) || empty($_POST['host'])) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        /* Domain checking */
        $pslManager = new \Pdp\PublicSuffixListManager();
        $parser = new \Pdp\Parser($pslManager->getList());
        $url = $parser->parseUrl($_POST['host']);
        $punnnycode = new \TrueBV\Punycode();
        $host = Database::clean_string($punnnycode->encode($url->getHost()));
        $path = Database::clean_string($url->getPath()) ? preg_replace('/\/+$/', '', Database::clean_string($url->getPath())) : null;

        if(empty($errors)) {

            /* Insert to database */
            $stmt = Database::$database->prepare("
                UPDATE 
                    `websites` 
                SET 
                    `name` = ?,
                    `scheme` = ?,
                    `host` = ?,
                    `path` = ?,
                    `excluded_ips` = ?,
                    `events_children_is_enabled` = ?,
                    `sessions_replays_is_enabled` = ?,
                    `email_reports_is_enabled` = ?,
                    `is_enabled` = ? 
                WHERE 
                    `website_id` = ? 
                    AND `user_id` = ?
            ");
            $stmt->bind_param(
                'sssssssssss',
                $_POST['name'],
                $_POST['scheme'],
                $host,
                $path,
                $_POST['excluded_ips'],
                $_POST['events_children_is_enabled'],
                $_POST['sessions_replays_is_enabled'],
                $_POST['email_reports_is_enabled'],
                $_POST['is_enabled'],
                $_POST['website_id'],
                $this->user->user_id
            );
            $stmt->execute();
            $stmt->close();

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItem('websites_' . $this->user->user_id);
            \Altum\Cache::$adapter->deleteItemsByTag('website_id=' . $_POST['website_id']);

            Response::json($this->language->website_update_modal->success_message, 'success');

        }
    }

    private function delete() {
        $_POST['website_id'] = (int) $_POST['website_id'];

        /* Delete from database */
        $stmt = Database::$database->prepare("DELETE FROM `websites` WHERE `website_id` = ? AND `user_id` = ?");
        $stmt->bind_param('ss', $_POST['website_id'], $this->user->user_id);
        $stmt->execute();
        $stmt->close();

        /* Clear cache */
        \Altum\Cache::$adapter->deleteItem('websites_' . $this->user->user_id);
        \Altum\Cache::$store_adapter->deleteItemsByTag('session_replay_website_' . $_POST['website_id']);
        \Altum\Cache::$adapter->deleteItemsByTag('website_id=' . $_POST['website_id']);

        Response::json($this->language->website_delete_modal->success_message, 'success');

    }

}
