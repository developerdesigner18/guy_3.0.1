<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Response;

class Replay extends Controller {

    public function index() {

        Authentication::guard();

        $session_id = (isset($this->params[0])) ? (int) Database::clean_string($this->params[0]) : 0;

        /* Get the Visitor basic data and make sure it exists */
        $visitor = $this->database->query("
            SELECT
                `visitors_sessions`.`session_id`,
                `websites_visitors`.`visitor_uuid`,
                `websites_visitors`.`custom_parameters`,
                `websites_visitors`.`country_code`,
                `websites_visitors`.`visitor_id`,
                `websites_visitors`.`date`
            FROM
                `visitors_sessions`
            LEFT JOIN   
                `websites_visitors` ON `visitors_sessions`.`visitor_id` = `websites_visitors`.`visitor_id`
            WHERE
                `visitors_sessions`.`session_id` = {$session_id}
                AND `visitors_sessions`.`website_id` = {$this->website->website_id}
        ")->fetch_object() ?? null;

        if(!$visitor) redirect('replays');

        /* Get the replay */
        $replay = Database::get('*', 'sessions_replays', ['session_id' => $visitor->session_id]);

        if(!$replay) redirect('replays');

        /* Events Modal */
        $view = new \Altum\Views\View('replay/replay_events_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('replay/replay_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Prepare the View */
        $data = [
            'visitor'   => $visitor,
            'replay'    => $replay,
        ];

        $view = new \Altum\Views\View('replay/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function read() {
        Authentication::guard();

        $session_id = (isset($this->params[0])) ? (int) $this->params[0] : false;

        /* Get the replay */
        $replay = Database::get('*', 'sessions_replays', ['session_id' => $session_id, 'website_id' => $this->website->website_id]);

        if(!$replay) redirect('replays');

        /* Get from file store */
        $cache_instance = \Altum\Cache::$store_adapter->getItem('session_replay_' . $session_id)->get();

        $rows = [];

        foreach($cache_instance as $row) {
            $row = [
                'type' => (int) $row->type,
                'data' => json_decode(gzdecode($row->data)),
                'timestamp' => (int) $row->timestamp,
            ];

            $rows[] = $row;
        }

        /* Prepare the events modal html */
        $events = array_filter($rows, function($item) {
            return $item['type'] == 4;
        });

        // || $item['type'] == 2
        // $test[1]["data"]->node->childNodes[1]->childNodes[0]->childNodes[1]->childNodes[0]->textContent

        $replay_events_html = (new \Altum\Views\View('replay/replay_events', (array) $this))->run(['events' => $events]);

        /* Output the proper replay data */
        Response::simple_json([
            'rows' => $rows,
            'replay_events_html' => $replay_events_html
        ]);
    }

}
