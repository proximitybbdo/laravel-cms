<?php

namespace BBDO\Cms\Domain;

use BBDO\Cms\Models\ActionLog;
use Sentinel;

class Logging
{
    /**
     * @param string $module_type
     * @param string $action
     * @param int $itemId
     * @param string $lang
     * @param string $ip
     * @param string $data
     *
     * @return bool
     */
    public function action($module_type, $action, $itemId = null, $lang, $ip, $data)
    {
        $userId = null;

        // check if a user is logged in
        // there might be no user in case of a command
        // @todo Should we log this as 'command'? 
        if ($user = Sentinel::getUser()) {
            $userId = $user->id;
        }

        ActionLog::create([
            'user_id' => $userId,
            'module' => $module_type,
            'action' => $action,
            'item_id' => $itemId,
            'lang' => $lang,
            'ip' => $ip,
            'data' => $data
        ]);

        return true;
    }
}