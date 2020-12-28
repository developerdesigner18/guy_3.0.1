<?php

namespace Altum;

use Altum\Database\Database;

class Logger {

    public static function users($user_id, $type, $public = 1) {

        $ip = get_ip();

        Database::insert('users_logs', [
            'user_id'   => $user_id,
            'type'      => $type,
            'date'      => Date::$date,
            'ip'        => $ip,
            'public'    => $public
        ]);
    }

}
