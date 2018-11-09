<?php

namespace BBDO\Cms\app\Helpers;

use Closure;
use Illuminate\Cache\Repository;

class Cache extends Repository
{

    /**
     * Begin executing a new tags operation if the store supports it.
     *
     * @param  array|mixed  $names
     * @return \Illuminate\Cache\TaggedCache
     *
     * @throws \BadMethodCallException
     */
    public function tags($names)
    {
        if (! method_exists($this->store, 'tags')) {
            return $this;
        }

        $cache = $this->store->tags(is_array($names) ? $names : func_get_args());

        if (! is_null($this->events)) {
            $cache->setEventDispatcher($this->events);
        }

        return $cache->setDefaultCacheTime($this->default);
    }
}