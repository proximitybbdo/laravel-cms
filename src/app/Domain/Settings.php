<?php

namespace BBDO\Cms\Domain;

use BBDO\Cms\Models;

class Settings
{
    public static function getByKey($key) {
        if(Models\Settings::isKeyExists($key)) {
            return self::where('key', $key)->first()->value;
        }
    }

    public static function saveKey($key, $value) {
        if(Models\Settings::isKeyExists($key)) {
            return self::where('key', $key)->update(['value' => $value]);
        } else {
            return self::create(['key' => $key, 'value' => $value]);
        }
    }

    public static function isKeyExists($key) {
        return(!empty(Models\Settings::where('key', $key)->first()));
    }
}
