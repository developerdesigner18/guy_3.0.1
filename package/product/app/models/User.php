<?php

namespace Altum\Models;

use Altum\Database\Database;
use Altum\Logger;

class User extends Model {

    public function get_user_by_user_id($user_id) {

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('user?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $data = Database::get('*', 'users', ['user_id' => $user_id]);

            if($data) {

                /* Parse the users plan settings */
                $data->plan_settings = json_decode($data->plan_settings);

                /* Parse billing details if existing */
                $data->billing = json_decode($data->billing);

                /* Save to cache */
                \Altum\Cache::$adapter->save(
                    $cache_instance->set($data)->expiresAfter(86400)->addTag('users')->addTag('user_id=' . $data->user_id)
                );
            }

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

    public function get_user_by_email_and_token_code($email, $token_code) {

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('user?email=' . md5($email) . '&token_code=' . $token_code);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $data = Database::get('*', 'users', ['email' => $email, 'token_code' => $token_code]);

            if($data) {

                /* Parse the users plan settings */
                $data->plan_settings = json_decode($data->plan_settings);

                /* Parse billing details if existing */
                $data->billing = json_decode($data->billing);

                /* Save to cache */
                \Altum\Cache::$adapter->save(
                    $cache_instance->set($data)->expiresAfter(86400)->addTag('users')->addTag('user_id=' . $data->user_id)
                );
            }

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

    public function delete($user_id) {

        /* Cancel his active subscriptions if active */
        $this->cancel_subscription($user_id);

        /* Delete the record from the database */
        Database::$database->query("DELETE FROM `users` WHERE `user_id` = {$user_id}");

        /* Clear cache */
        \Altum\Cache::$store_adapter->deleteItemsByTag('session_replay_user_' . $user_id);
        \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $user_id);

    }

    public function update_last_activity($user_id) {

        Database::update('users', ['last_activity' => \Altum\Date::$date], ['user_id' => $user_id]);

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $user_id);

    }

    /*
    * Function to update a user with more details on a login action
    */
    public function login_aftermath_update($user_id, $ip, $country, $user_agent) {

        $stmt = Database::$database->prepare("UPDATE `users` SET `ip` = ?, `country` = ?, `last_user_agent` = ?, `total_logins` = `total_logins` + 1 WHERE `user_id` = {$user_id}");
        $stmt->bind_param('sss', $ip, $country, $user_agent);
        $stmt->execute();
        $stmt->close();

        Logger::users($user_id, 'login.success');

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $user_id);

    }

    /*
     * Needs to have access to the Settings and the User variable, or pass in the user_id variable
     */
    public function cancel_subscription($user_id = false) {

        if(!isset($this->settings)) {
            throw new \Exception('Model needs to have access to the "settings" variable.');
        }

        if(!isset($this->user) && !$user_id) {
            throw new \Exception('Model needs to have access to the "user" variable or pass in the $user_in.');
        }

        if($user_id) {
            $this->user = Database::get(['user_id', 'payment_subscription_id'], 'users', ['user_id' => $user_id]);
        }

        if(empty($this->user->payment_subscription_id)) {
            return true;
        }

        $data = explode('###', $this->user->payment_subscription_id);
        $type = $data[0];
        $subscription_id = $data[1];

        switch($type) {
            case 'stripe':

                /* Initiate Stripe */
                \Stripe\Stripe::setApiKey($this->settings->stripe->secret_key);

                /* Cancel the Stripe Subscription */
                $subscription = \Stripe\Subscription::retrieve($subscription_id);
                $subscription->cancel();

                break;

            case 'paypal':

                /* Initiate paypal */
                $paypal = new \PayPal\Rest\ApiContext(new \PayPal\Auth\OAuthTokenCredential($this->settings->paypal->client_id, $this->settings->paypal->secret));
                $paypal->setConfig(['mode' => $this->settings->paypal->mode]);

                /* Create an Agreement State Descriptor, explaining the reason to suspend. */
                $agreement_state_descriptior = new \PayPal\Api\AgreementStateDescriptor();
                $agreement_state_descriptior->setNote('Suspending the agreement');

                /* Get details about the executed agreement */
                $agreement = \PayPal\Api\Agreement::get($subscription_id, $paypal);

                /* Suspend */
                $agreement->suspend($agreement_state_descriptior, $paypal);


                break;
        }

        Database::$database->query("UPDATE `users` SET `payment_subscription_id` = '' WHERE `user_id` = {$this->user->user_id}");

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $user_id);

    }

}
