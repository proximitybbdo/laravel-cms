<?php

namespace BBDO\Cms\Helpers;

class Helpers
{
  public static function clean_segments() {
    $segments = \Request::segments();
   

    if(count($segments) > 0 /*&& is_dir(app_path() . '/lang/' . $segments[0])*/) {
      # Removes lang part from segments
      $segments = array_splice($segments, 1);
    }

    $uri_keys = array_keys(array_flip($segments));

    foreach($uri_keys as $key => $value) {
      # Page names like step2 (word+number) are not accepted and discarded here
      if((preg_match('/[A-Za-z]/', $value) && preg_match('/[0-9]/', $value)) || is_numeric($value)) {
        unset($uri_keys[$key]);
      }
    }
    $result = implode('/', $uri_keys);

    return ($result == "") ? "home" : $result;
  }

  public static function url_lang($parts) {
    $path = '';

    if(is_array($parts)) {
      foreach($parts as $part) {
        $path .= '/' . $part;
      }
    } else if(!is_null($parts)) {
      $path = '/' . $parts;
    }

    return url(\App::getLocale() . $path);
  }

  public static function active_class($check, $strict = true) {
    if(is_url($check, $strict)) {
      echo ' class="active" ';
    }
  }

  public static function is_url($check, $strict = true) {
    if($strict) {
      return $check == Helpers::clean_segments();
    }

    $check = strlen($check) === 0 ? '§' : $check;

    return strpos(\Helpers::clean_segments(), $check) > -1;
  }

  public static function arrayToObject($d) {
   if (is_array($d)) {
   /*
   * Return array converted to object
   * Using __FUNCTION__ (Magic constant)
   * for recursive call
   */
   return (object) array_map(array('Helpers', 'arrayToObject'), $d);
   }
   else {
   // Return object
   return $d;
   }
  }

  //GENERATE A KEY IN THIS FORMAT => A69CA6AF-7DBA-D47A-21D8-87641708FECE
  public static function gen_key() {
    mt_srand((double)microtime()*10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);
    $guid = substr($charid, 0, 8) . $hyphen
      .substr($charid, 8, 4) . $hyphen
      .substr($charid,12, 4) . $hyphen
      .substr($charid,16, 4) . $hyphen
      .substr($charid,20,12);
    return $guid;
  }
}

function active_class($check, $strict = true) {
  return Helpers::active_class($check, $strict);
}

function is_url($check, $strict = true) {
  return Helpers::is_url($check, $strict);
}

function url_lang($parts) {
  return Helpers::url_lang($parts);
}
