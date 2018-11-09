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

        if($minutes <= 0) {
            return $callback();
        }

        if (isset($tags) && method_exists(cache()->getStore(), 'tags')) {
            return \Cache::tags($tags)->remember($key, $minutes, $callback);
        } else {
            return cache()->remember($key, $minutes, $callback);
        }
    }

    /**
     * @return array
     */
    public static function getTagsList() {
        $appNameRedis = \Cache::getPrefix();

        $taggedCache  = \Cache::getRedis()->connection()->keys($appNameRedis. 'tag:*');

        $tags = [];
        foreach($taggedCache as $redisTagLine) {
            preg_match('#^'.$appNameRedis.'tag:(.*?):#', $redisTagLine, $matches);
            $tags[] = $matches[1];
        }

        return $tags;
    }
}