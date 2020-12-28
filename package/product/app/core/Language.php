<?php

namespace Altum;

class Language {
    public static $language;
    public static $language_code;
    public static $languages = [];
    public static $path;
    public static $default_language;
    public static $default_language_code;
    public static $language_objects = [];

    public static function initialize($path) {

        self::$path = $path;

        /* Determine all the langauges available in the directory */
        foreach(glob(self::$path . '*.json') as $file) {
            $file = explode('/', $file);
            $file_name = str_replace('.json', '', trim(end($file)));

            /* Language name & code */
            list($language_name, $language_code) = explode('#', $file_name);

            self::$languages[$language_code] = $language_name;
        }

    }

    public static function get($language = null) {

        if(!$language) $language = self::$language;

        /* Make sure we have access to the requested language */
        if(!in_array($language, self::$languages)) {

            /* Try and use the default one if available */
            if(in_array(self::$default_language, self::$languages)) {
                $language = self::$default_language;
            } else {
                die('Requested language "' . $language . '" does not exist and the default language "' . self::$default_language . '" does not exist as well.');
            }

        }

        /* Check if we already processed the language file */
        if(isset(self::$language_objects[$language])) {
            return self::$language_objects[$language];
        }

        /* Include the language file */
        $language_code = array_search($language, self::$languages);
        self::$language_objects[$language] = json_decode(file_get_contents(self::$path . $language . '#' . $language_code . '.json'));

        /* Check the language file */
        if(is_null(self::$language_objects[$language])) {
            die('The language file is corrupted. Please make sure your JSON Language file is JSON Validated ( you can do that with an online JSON Validator by searching on Google ).');
        }

        return self::$language_objects[$language];
    }

    public static function set_by_name($language, $cookie = true) {

        if(in_array($language, self::$languages)) {
            if($cookie) {
                setcookie('language', $language, time() + 60 * 60 * 24 * 3);
            }
            self::$language = $language;
            self::$language_code = array_search($language, self::$languages);
        }

    }

    public static function set_by_code($language_code, $cookie = true) {

        if(array_key_exists($language_code, self::$languages)) {
            if($cookie) {
                setcookie('language', self::$languages[$language_code], time()+60*60*24*3);
            }
            self::$language = self::$languages[$language_code];
            self::$language_code = $language_code;
        }

    }

    public static function set_default($language) {
        self::$default_language = $language;
        self::$default_language_code = array_search($language, self::$languages);

        if(!isset(self::$language)) {
            self::$language = $language;
            self::$language_code = self::$default_language_code;
        }
    }
}
