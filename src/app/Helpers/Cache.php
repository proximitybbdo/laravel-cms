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

        $cache = \Cache::class;

        if (isset($tags) && method_exists(cache()->getStore(), 'tags')) {
            $cache->tags($tags);
        }

        return $cache->remember($key, $minutes, $callback);
    }
}