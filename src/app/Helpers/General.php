<?php

namespace BBDO\Cms\Helpers;

use Carbon\Carbon;

class General
{
    /**
     * @param $startdate
     * @param $enddate
     * @return bool
     */
    public static function IsRunning($startDate, $endDate)
    {
        return (Carbon::now() >= Carbon::parse($startDate) && Carbon::now() < Carbon::parse($endDate));
    }

    /**
     * Generate a key with format A69CA6AF-7DBA-D47A-21D8-87641708FECE
     * @return string
     */
    public static function generateKey()
    {
        mt_srand((double)microtime() * 10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        $guid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
        return $guid;
    }

    /**
     * @param $key
     * @return string
     */
    public static function keyOutput($key)
    {
        return 'answer_' . strtolower(str_replace(' ', '_', $key));
    }
}