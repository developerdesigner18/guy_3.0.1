<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Response;

class SessionAjax extends Controller {

    public function index() {

        Authentication::guard();

        $session_id = (isset($this->params[0])) ? (int) $this->params[0] : false;

        /* Get the Visitor basic data and make sure it exists */
        if(!$session = Database::get('*', 'visitors_sessions', ['session_id' => $session_id, 'website_id' => $this->website->website_id])) {
            die();
        }

        /* Get session events */
        $session_events_result = $this->database->query("SELECT * FROM `sessions_events` WHERE `session_id` = {$session->session_id} ORDER BY `event_id` ASC");

        $events = [];

        while($row = $session_events_result->fetch_object()) {
            $events[] = $row;
        }

        /* Get the child events */
        $session_events_children_result = $this->database->query("SELECT * FROM `events_children` WHERE `session_id` = {$session->session_id} ORDER BY `id` ASC");

        $events_children = [];

        while($row = $session_events_children_result->fetch_object()) {

            if(!isset($events_children[$row->event_id])) {
                $events_children[$row->event_id] = [];
            }

            $row->data = json_decode($row->data);

            $events_children[$row->event_id][] = $row;
        }

        /* Prepare the View */
        $data = [
            'session'           => $session,
            'events'            => $events,
            'events_children'   => $events_children
        ];

        $view = new \Altum\Views\View('session/ajaxed_partials/events', (array) $this);

        Response::json('', 'success', ['html' => $view->run($data)]);

    }

}
