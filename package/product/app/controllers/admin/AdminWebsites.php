<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Csrf;
use Altum\Models\User;
use Altum\Middlewares\Authentication;
use Altum\Response;

class AdminWebsites extends Controller {

    public function index() {

        Authentication::guard('admin');

        /* Some statistics for the widgets */
        $total_sessions_events = $this->database->query("SELECT MAX(`event_id`) AS `total` FROM `sessions_events`")->fetch_object()->total;
        $total_events_children = $this->database->query("SELECT MAX(`id`) AS `total` FROM `events_children`")->fetch_object()->total;
        $total_sessions_replays = $this->database->query("SELECT MAX(`replay_id`) AS `total` FROM `sessions_replays`")->fetch_object()->total;

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/websites/website_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'total_sessions_events' => $total_sessions_events,
            'total_events_children' => $total_events_children,
            'total_sessions_replays' => $total_sessions_replays
        ];

        $view = new \Altum\Views\View('admin/websites/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }


    public function read() {

        Authentication::guard('admin');

        $datatable = new \Altum\DataTable();
        $datatable->set_accepted_columns(['user_id', 'name', 'usage', 'is_enabled', 'date', 'email']);
        $datatable->process($_POST);

        $result = Database::$database->query("
            SELECT 
                `websites` . *, `users` . `user_id`, `users` . `type` AS `user_type`, `users` . `email` AS `email`,
                (SELECT COUNT(*) FROM `websites`) AS `total_before_filter`,
                (SELECT COUNT(*) FROM `websites` LEFT JOIN `users` ON `websites` . `user_id` = `users` . `user_id` WHERE `users` . `email` LIKE '%{$datatable->get_search()}%' OR `users` . `name` LIKE '%{$datatable->get_search()}%' OR `websites` . `name` LIKE '%{$datatable->get_search()}%' OR `websites` . `host` LIKE '%{$datatable->get_search()}%'  OR `websites` . `path` LIKE '%{$datatable->get_search()}%') AS `total_after_filter`
            FROM 
                `websites`
            LEFT JOIN
                `users` ON `websites` . `user_id` = `users` . `user_id`
            WHERE 
                `users` . `email` LIKE '%{$datatable->get_search()}%' 
                OR `users` . `name` LIKE '%{$datatable->get_search()}%'
                OR `websites` . `name` LIKE '%{$datatable->get_search()}%'
                OR `websites` . `host` LIKE '%{$datatable->get_search()}%'
                OR `websites` . `path` LIKE '%{$datatable->get_search()}%'
            ORDER BY 
                " . $datatable->get_order() . "
            LIMIT
                {$datatable->get_start()}, {$datatable->get_length()}
        ");

        $total_before_filter = 0;
        $total_after_filter = 0;

        $data = [];

        while($row = $result->fetch_object()):

            $row->email = '<a href="' . url('admin/user-view/' . $row->user_id) . '"> ' . $row->email . '</a>';

            /* Host */
            $name = '<div>' . $row->name . '</div>';

            $host_favicon = '<img src="https://external-content.duckduckgo.com/ip3/' . $row->host . '.ico" class="img-fluid icon-favicon mr-1" />';
            $row->host = $name . '<div class="text-muted">' . $host_favicon . $row->host . $row->path . '</div>';

            /* Usage */
            $row->usage = '
            <small>
                <div class="text-muted">
                    ' . $this->language->websites->websites->sessions_events . '
                    <strong>' . nr($row->current_month_sessions_events, 1, true) . '</strong>
                </div>
    
                <div class="text-muted">
                    ' . $this->language->websites->websites->events_children . '
                    <strong>' . nr($row->current_month_events_children, 1, true) . '</strong>
                </div>
                
                <div class="text-muted">
                    ' . $this->language->websites->websites->sessions_replays . '
                    <strong>' . nr($row->current_month_sessions_replays, 1, true) . '</strong>
                </div>
            </small>
            ';

            /* Active Status badge */
            $row->is_enabled = $row->is_enabled ? '<span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> ' . $this->language->global->active . '</span>' : '<span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> ' . $this->language->global->disabled . '</span>';

            $row->date = '<span class="text-muted" data-toggle="tooltip" title="' . \Altum\Date::get($row->date, 1) . '">' . \Altum\Date::get($row->date, 2) . '</span>';
            $row->actions = include_view(THEME_PATH . 'views/admin/partials/admin_website_dropdown_button.php', ['id' => $row->website_id]);

            $data[] = $row;
            $total_before_filter = $row->total_before_filter;
            $total_after_filter = $row->total_after_filter;

        endwhile;

        Response::simple_json([
            'data' => $data,
            'draw' => $datatable->get_draw(),
            'recordsTotal' => $total_before_filter,
            'recordsFiltered' =>  $total_after_filter
        ]);

    }

    public function delete() {

        Authentication::guard();

        $website_id = (isset($this->params[0])) ? $this->params[0] : false;
        $user_id = Database::simple_get('user_id', 'websites', ['website_id' => $website_id]);

        if(!Csrf::check('global_token')) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('admin/websites');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the website */
            $this->database->query("DELETE FROM `websites` WHERE `website_id` = {$website_id}");

            /* Clear cache */
            \Altum\Cache::$adapter->deleteItem('websites_' . $user_id);
            \Altum\Cache::$store_adapter->deleteItemsByTag('session_replay_website_' . $_POST['website_id']);

            /* Success message */
            $_SESSION['success'][] = $this->language->admin_website_delete_modal->success_message;

        }

        redirect('admin/websites');
    }

}
