<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class Teams extends Controller {

    public function index() {

        Authentication::guard();

        /* Create Modal */
        $view = new \Altum\Views\View('teams/team_create_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Update Modal */
        $view = new \Altum\Views\View('teams/team_update_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('teams/team_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Delete Modal */
        $view = new \Altum\Views\View('teams/team_association_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Get the teams list for of the owner user */
        $teams_result = Database::$database->query("SELECT `teams`.*, COUNT(`teams_associations`.`team_association_id`) AS `users` FROM `teams` LEFT JOIN `teams_associations` ON `teams_associations`.`team_id` = `teams`.`team_id` WHERE `teams`.`user_id` = {$this->user->user_id} GROUP BY `teams`.`team_id`");

        /* Get the teams that the current user is enrolled into */
        $teams_associations_result = Database::$database->query("SELECT `teams`.`team_id`, `teams`.`name`, `teams`.`websites_ids`, `teams_associations`.* FROM `teams_associations` LEFT JOIN `teams` ON `teams_associations`.`team_id` = `teams`.`team_id` WHERE `teams_associations`.`user_id` = {$this->user->user_id} OR `teams_associations`.`user_email` = '{$this->user->email}'");

        /* Prepare the View */
        $data = [
            'teams_result'              => $teams_result,
            'teams_associations_result' => $teams_associations_result,
        ];

        $view = new \Altum\Views\View('teams/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
