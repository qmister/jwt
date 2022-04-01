<?php

namespace Whereof\Jwt\Support;

use Carbon\Carbon;

/**
 * Class Utils
 * @author zhiqiang
 * @package Whereof\Jwt\Support
 */
class Utils
{
    /**
     * Get the Carbon instance for the current time.
     *
     * @return Carbon
     */
    public static function now()
    {
        return Carbon::now();
    }

    /**
     * Get the Carbon instance for the timestamp.
     *
     * @param  int  $timestamp
     * @return Carbon
     */
    public static function timestamp($timestamp)
    {
        return Carbon::createFromTimeStampUTC($timestamp);
    }
}