<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Date;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Response;

class TeamsAssociationsAjax extends Controller {

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
        $_POST['team_id'] = (int) $_POST['team_id'];
        $_POST['email'] = trim(Database::clean_string($_POST['email']));

        /* Check for possible errors */
        if(empty($_POST['email'])) {
            Response::json($this->language->global->error_message->empty_fields, 'error');
        }

        if(!$team = Database::get('*', 'teams', ['team_id' => $_POST['team_id']])) {
            die();
        }

        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            Response::json($this->language->team_association_create_modal->error_message->invalid_email, 'error');
        }

        if(Database::exists('team_association_id', 'teams_associations', ['team_id' => $_POST['team_id'], 'user_email' => $_POST['email']])) {
            Response::json($this->language->team_association_create_modal->error_message->email_exists, 'error');
        }

        if(empty($errors)) {

            /* Check if the email exists as an user */
            $user_exists = Database::exists(['user_id'], 'users', ['email' => $_POST['email']]);

            /* Insert to database */
            $stmt = Database::$database->prepare("INSERT INTO `teams_associations` (`team_id`, `user_email`, `date`) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $_POST['team_id'], $_POST['email'], Date::$date);
            $stmt->execute();
            $stmt->close();

            /* Send out an email notification */
            /* Prepare the email */
            $email_template = get_email_template(
                [
                    '{{TEAM_NAME}}' => $team->name,
                ],
                $this->language->global->emails->teams_association_create->subject,
                [
                    '{{TEAM_NAME}}' => $team->name,
                    '{{USER_NAME}}' => $this->user->name,
                    '{{USER_EMAIL}}' => $this->user->email,
                    '{{LOGIN_LINK}}' => url('login?redirect=teams&email=' . $_POST['email']),
                    '{{REGISTER_LINK}}' => url('register?redirect=teams&email=' . $_POST['email']) . '&unique_registration_identifier=' . md5($_POST['email'] . $_POST['email']),
                ],
                $user_exists ? $this->language->global->emails->teams_association_create->body_login : $this->language->global->emails->teams_association_create->body_register
            );

            send_mail($this->settings, $_POST['email'], $email_template->subject, $email_template->body);

            Response::json($this->language->team_association_create_modal->success_message, 'success', ['team_id' => $team->team_id]);
        }
    }

    /* Accepting the invitation of the team association */
    private function update() {
        $_POST['team_association_id'] = (int) $_POST['team_association_id'];

        if(!$team_association = Database::get(['team_association_id', 'team_id', 'user_email', 'is_accepted'], 'teams_associations', ['team_association_id' => $_POST['team_association_id']])) {
            die();
        }

        /* Make sure the invitation is not yet accepted and that it belongs to the actual logged in user */
        if($team_association->is_accepted || $team_association->user_email != $this->user->email) {
            die();
        }

        if(empty($errors)) {

            /* Update the database */
            $stmt = Database::$database->prepare("UPDATE `teams_associations` SET `user_id` = ?, `is_accepted` = 1, `accepted_date` = ? WHERE `team_association_id` = ?");
            $stmt->bind_param('sss', $this->user->user_id, Date::$date, $team_association->team_association_id);
            $stmt->execute();
            $stmt->close();

            Response::json('', 'success');

        }
    }

    /* Team Association Delete */
    private function delete() {
        $_POST['team_association_id'] = (int) $_POST['team_association_id'];

        if(!$team_association = Database::get(['team_id', 'user_id', 'is_accepted', 'user_email'], 'teams_associations', ['team_association_id' => $_POST['team_association_id']])) {
            die();
        }


        /* Check if the user is the owner, so that he can remove anyone from the team */
        if(!Database::exists('team_id', 'teams', ['team_id' => $team_association->team_id, 'user_id' => $this->user->user_id])) {

            if(
                (
                    $team_association->is_accepted &&
                    $team_association->user_id != $this->user->user_id
                )

                ||

                (
                    !$team_association->is_accepted &&
                    $team_association->user_email != $this->user->email
                )
            ) {
                die();
            }

        }

        /* Delete from database */
        $stmt = Database::$database->prepare("DELETE FROM `teams_associations` WHERE `team_association_id` = ?");
        $stmt->bind_param('s', $_POST['team_association_id']);
        $stmt->execute();
        $stmt->close();

        Response::json($this->language->team_association_delete_modal->success_message, 'success');

    }

}
