<?php

namespace BBDO\Cms\Helpers;

use \Carbon\Carbon;
class General
{
  public static function IsRunning($startdate,$enddate){
    $startdate = Carbon::parse($startdate);
    $enddate = Carbon::parse($enddate);

    if(Carbon::now() >= $startdate && Carbon::now() < $enddate){
      return true;
    }
    else {
      return false;
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

   public static function key_output($key){
    $key = 'answer_' . strtolower(str_replace(' ', '_', $key));
    return $key;
  }
}