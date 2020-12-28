<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Language;

class Cron extends Controller {

    public function index() {

        /* Initiation */
        set_time_limit(0);

        /* Make sure the key is correct */
        if(!isset($_GET['key']) || (isset($_GET['key']) && $_GET['key'] != $this->settings->cron->key)) {
            die();
        }

        $date = \Altum\Date::$date;

        /* Make sure the reset date month is different than the current one to avoid double resetting */
        $reset_date = (new \DateTime($this->settings->cron->reset_date))->format('m');
        $current_date = (new \DateTime())->format('m');

        if($reset_date != $current_date) {

            /* Reset the websites limits */
            Database::$database->query("UPDATE `websites` SET `current_month_sessions_events` = 0, `current_month_events_children` = 0, `current_month_sessions_replays` = 0");

            /* Update the settings with the updated time */
            $cron_settings = json_encode([
                'key' => $this->settings->cron->key,
                'reset_date' => $date
            ]);

            Database::$database->query("UPDATE `settings` SET `value` = '{$cron_settings}' WHERE `key` = 'cron'");

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItem('settings');
        }

        /* Delete all the sessions replays which do not meet the minimum amount of seconds */
        $result = Database::$database->query("SELECT `session_id`, TIMESTAMPDIFF(SECOND, `date`, `last_date`) AS `seconds` FROM `sessions_replays` WHERE TIMESTAMPDIFF(SECOND, `date`, `last_date`) < {$this->settings->analytics->sessions_replays_minimum_duration} OR `expiration_date` < '{$date}' LIMIT 25;");

        while($row = $result->fetch_object()) {
            Database::$database->query("DELETE FROM `sessions_replays` WHERE `session_id` = {$row->session_id}");

            /* Clear cache */
            \Altum\Cache::$store_adapter->deleteItem('session_replay_' . $row->session_id);
        }

        /* Delete all the events that need to be deleted based on the plan settings of the user */
        Database::$database->query("DELETE FROM `events_children` WHERE `expiration_date` < '{$date}'");

        /* TODO: Maybe alert people of the usage on their plans? */

        $this->email_reports();

    }

    private function email_reports() {

        /* Only run this part if the email reports are enabled */
        if(!$this->settings->analytics->email_reports_is_enabled) {
            return;
        }

        $date = \Altum\Date::$date;

        /* Determine the frequency of email reports */
        $days_interval = 7;

        switch($this->settings->analytics->email_reports_is_enabled) {
            case 'weekly':
                $days_interval = 7;

                break;

            case 'monthly':
                $days_interval = 30;

                break;
        }

        /* Get potential websites from users that have almost all the conditions to get an email report right now */
        $result = Database::$database->query("
            SELECT
                `websites`.`website_id`,
                `websites`.`name`,
                `websites`.`host`,
                `websites`.`path`,
                `websites`.`email_reports_last_date`,
                `websites`.`tracking_type`,
                `users`.`user_id`,
                `users`.`email`,
                `users`.`plan_settings`,
                `users`.`language`
            FROM 
                `websites`
            LEFT JOIN 
                `users` ON `websites`.`user_id` = `users`.`user_id` 
            WHERE 
                `users`.`active` = 1
                AND `websites`.`is_enabled` = 1 
                AND `websites`.`email_reports_is_enabled` = 1
				AND DATE_ADD(`websites`.`email_reports_last_date`, INTERVAL {$days_interval} DAY) <= '{$date}'
            LIMIT 25
        ");

        /* Go through each result */
        while($row = $result->fetch_object()) {
            $row->plan_settings = json_decode($row->plan_settings);

            /* Make sure the plan still lets the user get email reports */
            if(!$row->plan_settings->email_reports_is_enabled) {
                Database::$database->query("UPDATE `websites` SET `email_reports_is_enabled` = 0 WHERE `website_id` = {$row->website_id}");

                continue;
            }

            /* Prepare */
            $previous_start_date = (new \DateTime())->modify('-' . $days_interval * 2 . ' days')->format('Y-m-d H:i:s');
            $start_date = (new \DateTime())->modify('-' . $days_interval . ' days')->format('Y-m-d H:i:s');

            /* Start getting information about the website to generate the statistics */
            switch($row->tracking_type) {
                case 'lightweight':
                    $basic_analytics = $this->database->query("
                        SELECT 
                            COUNT(*) AS `pageviews`, 
                            SUM(CASE WHEN `type` = 'landing_page' THEN 1 ELSE 0 END) AS `visitors`
                        FROM 
                            `lightweight_events`
                        WHERE 
                            `website_id` = {$row->website_id} 
                            AND (`date` BETWEEN '{$start_date}' AND '{$date}')
                    ")->fetch_object() ?? null;

                    $previous_basic_analytics = $this->database->query("
                        SELECT 
                            COUNT(*) AS `pageviews`, 
                            SUM(CASE WHEN `type` = 'landing_page' THEN 1 ELSE 0 END) AS `visitors`
                        FROM 
                            `lightweight_events`
                        WHERE 
                            `website_id` = {$row->website_id} 
                            AND (`date` BETWEEN '{$previous_start_date}' AND '{$start_date}')
                    ")->fetch_object() ?? null;
                break;

                case 'normal':
                    $basic_analytics = $this->database->query("
                        SELECT 
                            COUNT(*) AS `pageviews`, 
                            COUNT(DISTINCT `sessions_events`.`session_id`) AS `sessions`, 
                            COUNT(DISTINCT `sessions_events`.`visitor_id`) AS `visitors`
                        FROM 
                            `sessions_events`
                        LEFT JOIN
                            `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
                        WHERE 
                            `sessions_events`.`website_id` = {$row->website_id} 
                            AND (`sessions_events`.`date` BETWEEN '{$start_date}' AND '{$date}')
                    ")->fetch_object() ?? null;

                    $previous_basic_analytics = $this->database->query("
                        SELECT 
                            COUNT(*) AS `pageviews`, 
                            COUNT(DISTINCT `sessions_events`.`session_id`) AS `sessions`, 
                            COUNT(DISTINCT `sessions_events`.`visitor_id`) AS `visitors`
                        FROM 
                            `sessions_events`
                        LEFT JOIN
                            `websites_visitors` ON `sessions_events`.`visitor_id` = `websites_visitors`.`visitor_id`
                        WHERE 
                            `sessions_events`.`website_id` = {$row->website_id} 
                            AND (`sessions_events`.`date` BETWEEN '{$previous_start_date}' AND '{$start_date}')
                    ")->fetch_object() ?? null;
                break;
            }

            /* Get the language for the user */
            $language = Language::get($row->language);

            /* Prepare the email title */
            $email_title = sprintf(
                $language->cron->email_reports->title,
                $row->name,
                \Altum\Date::get($start_date, 2),
                \Altum\Date::get('', 2)
            );

            /* Prepare the View for the email content */
            $data = [
                'row'                       => $row,
                'basic_analytics'           => $basic_analytics,
                'previous_basic_analytics'  => $previous_basic_analytics,
                'language'                  => $language
            ];

            $email_content = (new \Altum\Views\View('partials/cron/email_reports', (array) $this))->run($data);

            /* Send the email */
            send_mail($this->settings, $row->email, $email_title, $email_content);

            /* Update the website */
            Database::update('websites', ['email_reports_last_date' => $date], ['website_id' => $row->website_id]);

            /* Insert email log */
            Database::insert('email_reports', ['user_id' => $row->user_id, 'website_id' => $row->website_id, 'date' => $date]);

            if(DEBUG) {
                echo sprintf('Email sent for user_id %s and website_id %s', $row->user_id, $row->website_id);
            }
        }

    }

}
