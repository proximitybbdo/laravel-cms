<?php

namespace BBDOCms\Domain;

use BBDOCms\Models;

class Settings
{
    public static function getByKey($key) {
        if(self::isKeyExists($key)) {
            return Models\Settings::where('key', $key)->first()->value;
        }
    }

    public static function saveKey($key, $value) {
        if(self::isKeyExists($key)) {
            return Models\Settings::where('key', $key)->update(['value' => $value]);
        } else {
            return Models\Settings::create(['key' => $key, 'value' => $value]);
        }
    }

    public static function isKeyExists($key) {
        return(!empty(Models\Settings::where('key', $key)->first()));
    }
}
