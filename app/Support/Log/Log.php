<?php


namespace App\Support\Log;


use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Log\LoggerInterface;

/**
 * log助手类
 * Class Log
 * @package App\Support\Helper\Log
 */
class Log
{
    /**
     * @param  string  $name
     * @return LoggerInterface
     */
    public static function get(string $name = 'share-life')
    {
        return ApplicationContext::getContainer()->get(LoggerFactory::class)->get($name);
    }
}