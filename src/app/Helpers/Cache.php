<?php

namespace BBDO\Cms\app\Helpers;

use Closure;

class Cache
{
    /**
     * @param string $tags
     * @param string $key
     * @param int $minutes
     * @param Closure $callback
     * @return mixed
     * @throws \Exception
     */
    public static function cacheWithTags(...$params) {

        if(func_num_args() == 4) {
            list($tags, $key, $minutes, $callback) = func_get_args();
        } elseif(func_num_args() == 3) {
            list($key, $minutes, $callback) = func_get_args();
        }

        if (isset($tags) && method_exists(cache()->getStore(), 'tags')) {
            return \Cache::tags($tags)->remember($key, $minutes, $callback);
        } else {
            return cache()->remember($key, $minutes, $callback);
        }
    }
}