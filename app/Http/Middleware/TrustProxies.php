<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Only the local dev-tunnel agent (php artisan serve is reached via
     * loopback) is trusted to set forwarded headers - never an arbitrary
     * internet client hitting the app directly.
     *
     * @var array|string|null
     */
    protected $proxies = ['127.0.0.1', '::1'];

    /**
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO;
}
