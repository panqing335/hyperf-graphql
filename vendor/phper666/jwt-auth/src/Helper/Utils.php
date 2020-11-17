<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace Phper666\JwtAuth\Helper;

use Carbon\Carbon;
class Utils
{
    /**
     * Get the Carbon instance for the current time.
     *
     * @return \Carbon\Carbon
     */
    public static function now()
    {
        return Carbon::now('UTC');
    }

    /**
     * Get the Carbon instance for the timestamp.
     *
     * @param  int  $timestamp
     *
     * @return \Carbon\Carbon
     */
    public static function timestamp($timestamp)
    {
        return Carbon::createFromTimestampUTC($timestamp)->timezone('UTC');
    }

    /**
     * Checks if a timestamp is in the past.
     *
     * @param  int  $timestamp
     * @param  int  $leeway
     *
     * @return bool
     */
    public static function isPast($timestamp, $leeway = 0)
    {
        return static::timestamp($timestamp)->addSeconds($leeway)->isPast();
    }

    /**
     * Checks if a timestamp is in the future.
     *
     * @param  int  $timestamp
     * @param  int  $leeway
     *
     * @return bool
     */
    public static function isFuture($timestamp, $leeway = 0)
    {
        return static::timestamp($timestamp)->subSeconds($leeway)->isFuture();
    }

    /**
     * @param $claims
     * @return mixed
     */
    public static function claimsToArray($claims)
    {
        foreach($claims as $k => $v) {
            $claims[$k] = $v->getValue();
        }

        return $claims;
    }

    /**
     * 处理头部token
     * @param string $token
     * @return bool|string
     */
    public static function handleHeaderToken(string $prefix, string $token)
    {
        if (strlen($token) > 0) {
            $token = ucfirst($token);
            $arr = explode($prefix . ' ', $token);
            $token = $arr[1] ?? '';
            if (strlen($token) > 0) return $token;
        }
        return false;
    }
}
