<?php

namespace BBDO\Cms\Helpers;

class Helpers
{
    public static function urlLang($parts)
    {
        $path = '';

        if (is_array($parts)) {
            foreach ($parts as $part) {
                $path .= '/' . $part;
            }
        } else if (!is_null($parts)) {
            $path = '/' . $parts;
        }

        return url(\App::getLocale() . $path);
    }

    public static function activeClass($check, $strict = true)
    {
        if (isUrl($check, $strict)) {
            echo ' class="active" ';
        }
    }

    public static function isUrl($check, $strict = true)
    {
        if ($strict) {
            return $check == Helpers::cleanSegments();
        }

        $check = strlen($check) === 0 ? '§' : $check;

        return strpos(Helpers::cleanSegments(), $check) > -1;
    }

    public static function cleanSegments()
    {
        $segments = \Request::segments();


        if (count($segments) > 0 /*&& is_dir(app_path() . '/lang/' . $segments[0])*/) {
            # Removes lang part from segments
            $segments = array_splice($segments, 1);
        }

        $uri_keys = array_keys(array_flip($segments));

        foreach ($uri_keys as $key => $value) {
            # Page names like step2 (word+number) are not accepted and discarded here
            if ((preg_match('/[A-Za-z]/', $value) && preg_match('/[0-9]/', $value)) || is_numeric($value)) {
                unset($uri_keys[$key]);
            }
        }
        $result = implode('/', $uri_keys);

        return ($result == "") ? "home" : $result;
    }

    public static function arrayToObject($d)
    {
        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return (object)array_map(array('Helpers', 'arrayToObject'), $d);
        } else {
            return $d;
        }
    }

}