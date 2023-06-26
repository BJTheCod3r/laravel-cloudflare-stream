<?php

namespace Bjthecod3r\CloudflareStream\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * CloudflareStream Facade class
 *
 * @author Bolaji Ajani <fabulousbj@hotmail.com>
 *
 * @see \Bjthecod3r\CloudflareStream\CloudflareStream
 */
class CloudflareStream extends Facade
{
    /**
     * Get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cloudflare-stream';
    }
}
