<?php

namespace Altum;

/* Simple wrapper for phpFastCache */

class Cache {
    public static $adapter;
    public static $store_adapter;

    public static function initialize($force_enable = false) {

        $driver = $force_enable ? 'Files' : (DEBUG ? 'Devnull' : 'Files');

        /* Cache adapter for phpFastCache */
        if($driver == 'Files') {
            $config = new \Phpfastcache\Drivers\Files\Config([
                'securityKey' => '66analytics',
                'path' => UPLOADS_PATH . 'cache',
            ]);
        } else {
            $config = new \Phpfastcache\Config\Config([
                'path' => UPLOADS_PATH . 'cache',
            ]);
        }

        self::$adapter = \Phpfastcache\CacheManager::getInstance($driver, $config);

    }

    public static function store_initialize() {

        $driver = 'Files';

        /* Cache adapter for phpFastCache */
        $config = new \Phpfastcache\Drivers\Files\Config([
            'securityKey' => '66analytics',
            'path' => UPLOADS_PATH . 'store'
        ]);

        self::$store_adapter = \Phpfastcache\CacheManager::getInstance($driver, $config);

    }

}
