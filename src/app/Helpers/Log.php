<?php

namespace BBDO\Cms\Helpers;

use BBDO\Cms\Domain\Logging;

class Log
{
    /**
     * @param string $module
     * @param string $action
     * @param int $itemId
     * @param string $lang
     * @param string $data
     */
    public static function action($module, $action, $itemId = null, $lang = null, $data = null)
    {
        $service = new Logging;
        $service->action($module, $action, $itemId, $lang, \Request::ip(), $data);
    }
}
