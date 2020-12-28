<?php

namespace Altum\Controllers;

use Altum\AnalyticsFilters;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class VisitorsAjax extends Controller {

    public function index() {
        die();
    }

    public function delete() {

        if(empty($_POST) || (!Csrf::check('token') && !Csrf::check('global_token'))) {
            die();
        }

        $_POST['visitor_id'] = (int) $_POST['visitor_id'];

        /* Delete from database */
        $stmt = Database::$database->prepare("DELETE FROM `websites_visitors` WHERE `visitor_id` = ? AND `website_id` = ?");
        $stmt->bind_param('ss', $_POST['visitor_id'], $this->website->website_id);
        $stmt->execute();
        $stmt->close();

        Response::json($this->language->visitor_delete_modal->success_message, 'success');

    }
}
