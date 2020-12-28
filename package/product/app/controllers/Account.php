<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\User;

class Account extends Controller {

    public function index() {

        Authentication::guard();

        /* Prepare the TwoFA codes just in case we need them */
        $twofa = new \RobThree\Auth\TwoFactorAuth($this->settings->title, 6, 30);
        $twofa_secret = $twofa->createSecret();
        $twofa_image = $twofa->getQRCodeImageAsDataUri($this->user->name, $twofa_secret);

        if(!empty($_POST)) {

            /* Clean some posted variables */
            $_POST['email']     = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $_POST['name']      = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $_POST['timezone']  = in_array($_POST['timezone'], \DateTimeZone::listIdentifiers()) ? Database::clean_string($_POST['timezone']) : $this->settings->default_timezone;
            $_POST['twofa_is_enabled']  = (bool) $_POST['twofa_is_enabled'];
            $_POST['twofa_token']       = trim(filter_var($_POST['twofa_token'], FILTER_SANITIZE_STRING));
            $twofa_secret               = $_POST['twofa_is_enabled'] ? $this->user->twofa_secret : null;

            /* Check for any errors */
            if(!Csrf::check()) {
                $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            }
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
                $_SESSION['error'][] = $this->language->register->error_message->invalid_email;
            }
            if(Database::exists('user_id', 'users', ['email' => $_POST['email']]) && $_POST['email'] !== $this->user->email) {
                $_SESSION['error'][] = $this->language->register->error_message->email_exists;
            }

            if(strlen($_POST['name']) < 3 || strlen($_POST['name'] > 32)) {
                $_SESSION['error'][] = $this->language->register->error_message->name_length;
            }

            if(!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
                if(!password_verify($_POST['old_password'], $this->user->password)) {
                    $_SESSION['error'][] = $this->language->account->error_message->invalid_current_password;
                }
                if(strlen(trim($_POST['new_password'])) < 6) {
                    $_SESSION['error'][] = $this->language->account->error_message->short_password;
                }
                if($_POST['new_password'] !== $_POST['repeat_password']) {
                    $_SESSION['error'][] = $this->language->account->error_message->passwords_not_matching;
                }
            }

            if($_POST['twofa_is_enabled'] && $_POST['twofa_token']) {
                $twofa_check = $twofa->verifyCode($_SESSION['twofa_potential_secret'], $_POST['twofa_token']);

                if(!$twofa_check) {
                    $_SESSION['error'][] = $this->language->account->error_message->twofa_check;

                    /* Regenerate */
                    $twofa_secret = $twofa->createSecret();
                    $twofa_image = $twofa->getQRCodeImageAsDataUri($this->user->name, $twofa_secret);

                } else {
                    $twofa_secret = $_SESSION['twofa_potential_secret'];
                }

            }

            if(empty($_SESSION['error'])) {

                /* Prepare the statement and execute query */
                $stmt = Database::$database->prepare("UPDATE `users` SET `email` = ?, `name` = ?, `timezone` = ?, `twofa_secret` = ? WHERE `user_id` = {$this->user->user_id}");
                $stmt->bind_param('ssss', $_POST['email'], $_POST['name'], $_POST['timezone'], $twofa_secret);
                $stmt->execute();
                $stmt->close();

                $_SESSION['success'][] = $this->language->account->success_message->account_updated;

                if(!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
                    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                    Database::update('users', ['password' => $new_password], ['user_id' => $this->user->user_id]);

                    /* Set a success message and log out the user */
                    Authentication::logout();
                }

                redirect('account');
            }

        }

        /* Store the potential secret */
        $_SESSION['twofa_potential_secret'] = $twofa_secret;

        /* Establish the account sidebar menu view */
        $menu = new \Altum\Views\View('partials/app_account_sidebar', (array) $this);
        $this->add_view_content('app_account_sidebar', $menu->run());

        /* Prepare the View */
        $data = [
            'twofa_secret'  => $twofa_secret,
            'twofa_image'   => $twofa_image
        ];

        $view = new \Altum\Views\View('account/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        Authentication::guard();

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('account');
        }

        if(empty($_SESSION['error'])) {

            /* Delete the user */
            (new User(['settings' => $this->settings]))->delete($this->user->user_id);

            Authentication::logout();

        }

    }

    public function cancelsubscription() {

        Authentication::guard();

        if(!Csrf::check()) {
            $_SESSION['error'][] = $this->language->global->error_message->invalid_csrf_token;
            redirect('account');
        }

        if(empty($_SESSION['error'])) {

            try {
                (new User(['settings' => $this->settings, 'user' => $this->user]))->cancel_subscription();
            } catch (\Exception $exception) {

                /* Output errors properly */
                if(DEBUG) {
                    echo $exception->getCode() . '-' . $exception->getMessage();

                    die();
                } else {

                    $_SESSION['error'][] = $exception->getMessage();
                    redirect('account');

                }
            }

            /* Set a message */
            $_SESSION['success'][] = $this->language->account->success_message->subscription_canceled;

            redirect('account');

        }

    }

}
