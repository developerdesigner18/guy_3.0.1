<?php

namespace Altum\Controllers;

use Altum\AnalyticsFilters;
use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class HeatmapsAjax extends Controller {

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

        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['path'] = '/' . trim(Database::clean_string($_POST['path']));
        $is_enabled = 1;

        /* Check for possible errors */
        if(empty($_POST['name'])) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        /* Get the count of already created goals */
        $total_websites_heatmaps = Database::$database->query("SELECT COUNT(*) AS `total` FROM `websites_heatmaps` WHERE `website_id` = {$this->website->website_id}")->fetch_object()->total ?? 0;
        if($this->user->plan_settings->websites_heatmaps_limit != -1 && $total_websites_heatmaps >= $this->user->plan_settings->websites_heatmaps_limit) {
            Response::json($this->language->heatmaps->error_message->websites_heatmaps_limit, 'error');
        }

        /* Insert to database */
        $stmt = Database::$database->prepare("INSERT INTO `websites_heatmaps` (`website_id`, `name`, `path`, `is_enabled`, `date`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $this->website->website_id, $_POST['name'], $_POST['path'], $is_enabled, Date::$date);
        $stmt->execute();
        $stmt->close();

        Response::json($this->language->heatmap_create_modal->success_message, 'success');
    }

    public function update() {
        $this->verify();

        if($this->team) {
            die();
        }

        if(empty($_POST)) {
            die();
        }

        $_POST['name'] = trim(Database::clean_string($_POST['name']));
        $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);
        $_POST['heatmap_id'] = (int) $_POST['heatmap_id'];

        /* Check for possible errors */
        if(empty($_POST['name'])) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        /* Update database */
        $stmt = Database::$database->prepare("UPDATE `websites_heatmaps` SET `name` = ?, `is_enabled` = ? WHERE `heatmap_id` = ? AND `website_id` = ?");
        $stmt->bind_param('ssss', $_POST['name'], $_POST['is_enabled'], $_POST['heatmap_id'], $this->website->website_id);
        $stmt->execute();
        $stmt->close();

        Response::json($this->language->heatmap_update_modal->success_message, 'success');
    }

    public function retake_snapshots() {
        $this->verify();

        if($this->team) {
            die();
        }

        if(empty($_POST)) {
            die();
        }

        $_POST['snapshot_id_desktop'] = (int) isset($_POST['snapshot_id_desktop']);
        $_POST['snapshot_id_tablet'] = (int) isset($_POST['snapshot_id_tablet']);
        $_POST['snapshot_id_mobile'] = (int) isset($_POST['snapshot_id_mobile']);
        $_POST['heatmap_id'] = (int) $_POST['heatmap_id'];

        foreach(['desktop', 'tablet', 'mobile'] as $key) {

            if($_POST['snapshot_id_' . $key]) {
                $stmt = Database::$database->prepare("DELETE FROM `heatmaps_snapshots` WHERE `website_id` = ? AND `heatmap_id` = ? AND `type` = ?");
                $stmt->bind_param('sss', $this->website->website_id, $_POST['heatmap_id'], $key);
                $stmt->execute();
                $stmt->close();
            }

        }

        Response::json($this->language->heatmap_retake_snapshots_modal->success_message, 'success');
    }

    public function delete() {
        $this->verify();

        if($this->team) {
            die();
        }

        if(empty($_POST)) {
            die();
        }

        $_POST['heatmap_id'] = (int) $_POST['heatmap_id'];

        /* Delete from database */
        $stmt = Database::$database->prepare("DELETE FROM `websites_heatmaps` WHERE `heatmap_id` = ? AND `website_id` = ?");
        $stmt->bind_param('ss', $_POST['heatmap_id'], $this->website->website_id);
        $stmt->execute();
        $stmt->close();

        Response::json($this->language->heatmap_delete_modal->success_message, 'success');
    }
}
