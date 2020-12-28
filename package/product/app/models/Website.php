<?php

namespace Altum\Models;

use Altum\Database\Database;

class Website extends Model {

    public function get_website_by_pixel_key($pixel_key) {

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('website?pixel_key=' . $pixel_key);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $data = Database::get('*', 'websites', ['pixel_key' => $pixel_key]);

            if($data) {
                /* Save to cache */
                \Altum\Cache::$adapter->save(
                    $cache_instance->set($data)->expiresAfter(43200)->addTag('users')->addTag('user_id=' . $data->user_id)->addTag('website_id=' . $data->website_id)
                );
            }

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

    public function get_websites_by_user_id($user_id) {

        $cache_instance = \Altum\Cache::$adapter->getItem('websites_' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            $result = Database::$database->query("SELECT * FROM `websites` WHERE `user_id` = {$user_id}");
            $data = [];

            while($row = $result->fetch_object()) {

                $data[$row->website_id] = $row;

            }

            \Altum\Cache::$adapter->save($cache_instance->set($data)->expiresAfter(86400));

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

    public function get_websites_by_websites_ids(array $websites_ids = []) {

        $websites_ids_query = implode(',', $websites_ids);
        $result = Database::$database->query("SELECT * FROM `websites` WHERE `website_id` IN ({$websites_ids_query}) ");
        $data = [];

        while($row = $result->fetch_object()) {

            $data[$row->website_id] = $row;

        }

        return $data;
    }
}
