<?php

namespace BBDO\Cms\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '*/api/*',
        'icontrol/items/*',
        'icontrol/geturlfriendlytext',
        'icontrol/files/*'
        // 'sync/*'
    ];
}
