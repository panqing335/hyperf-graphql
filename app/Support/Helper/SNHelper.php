<?php


namespace App\Support\Helper;


class SNHelper
{
    public static function generateTimeSN($prefix = ''): string
    {
        list($mSec, $sec) = explode(' ', microtime());
        $mSecTime = date('YmdHis') . sprintf('%03d', $mSec * 1000);

        return $prefix . $mSecTime . rand(1000, 9999);
    }
}