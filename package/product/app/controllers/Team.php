<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Models\Plan;
use Altum\Routing\Router;
use Altum\Title;

class Team extends Controller {

    public function index() {

        Authentication::guard();

        if($this->team) {
            redirect('teams');
        }

        $team_id = (isset($this->params[0])) ? (int) $this->params[0] : false;

        /* Get the Visitor basic data and make sure it exists */
        if(!$team = Database::get('*', 'teams', ['team_id' => $team_id, 'user_id' => $this->user->user_id])) {
            redirect('teams');
        }
        $team->websites_ids = json_decode($team->websites_ids);

        /* Create Modal */
        $view = new \Altum\Views\View('team/team_association_create_modal', (array) $this);
        \Altum\Event::add_content($view->run(['team' => $team]), 'modals');

        $view = new \Altum\Views\View('team/team_association_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Get the team members */
        $teams_associations_result = Database::$database->query("SELECT `teams_associations`.*, `users`.`email`, `users`.`name` FROM `teams_associations` LEFT JOIN `users` ON `users`.`user_id` = `teams_associations`.`user_id` WHERE `teams_associations`.`team_id` = {$team->team_id}");

        /* Prepare the View */
        $data = [
            'team'                      => $team,
            'teams_associations_result' => $teams_associations_result,
        ];

        $view = new \Altum\Views\View('team/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

        /* Set a custom title */
        Title::set(sprintf($this->language->team->title, $team->name));
    }

}
