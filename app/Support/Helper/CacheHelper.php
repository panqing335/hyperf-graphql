<?php


namespace App\Support\Helper;


use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use Redis;

class CacheHelper
{
    const TYPE_REDIS = 1;
    const TYPE_MEMORY_OBJECT = 2;
    const TYPE_MEMORY_SERIALIZE = 3;

    // 有效期仅当前会话
    const EXPIRE_SESSION = -100;
    // 随机有效期（30-60秒）
    const EXPIRE_RANDOM = -101;
    // 随机
    const EXPIRE_LARGE_RANDOM = -102;

    /**
     * 快速缓存
     * @param string|array $cacheKey
     * @param int $expire
     * @param callable $dataGetter
     * @param int $cacheType
     * @return mixed|string
     */
    public static function fast($cacheKey, int $expire, callable $dataGetter, int $cacheType = self::TYPE_REDIS)
    {
        if (!is_string($cacheKey)) {
            $hash = md5(serialize($cacheKey));
            if (is_array($cacheKey) && count($cacheKey) >= 2) {
                $hash = $cacheKey[0] . ':' . $hash;
            }

            $cacheKey = $hash;
        }

        $cacheKey = "__Cache:{$cacheKey}";

        // 内存缓存
        if ($cacheType != self::TYPE_REDIS && Context::has($cacheKey)) {
            if ($cacheType == self::TYPE_MEMORY_SERIALIZE) {
                return unserialize(Context::get($cacheKey));
            } elseif ($cacheType == self::TYPE_MEMORY_OBJECT) {
                return Context::get($cacheKey);
            }
            return null;
        }

        /** @var Redis $connection */
        $connection = ApplicationContext::getContainer()->get(Redis::class);

        // 从redis中获取
        $serialized = $connection->get($cacheKey);
        $redisCacheAble = env('REDIS_CACHE_ABLE', true);
        if (!empty($serialized) && $redisCacheAble) {
            $data = unserialize($serialized);
        } else {
            $data = call_user_func($dataGetter);
            $serialized = serialize($data);
            // 随机有效期
            if ($expire == self::EXPIRE_RANDOM) {
                $expire = rand(30, 60);
            }
            if ($expire == self::EXPIRE_LARGE_RANDOM) {
                $expire = rand(120, 180);
            }

            // 非会话有效期
            if ($expire != self::EXPIRE_SESSION) {
                $connection->set($cacheKey, $serialized, $expire);
            }
        }

        // 在内存中建立缓存
        if ($cacheType == self::TYPE_MEMORY_SERIALIZE) {
            Context::set($cacheKey, $serialized);
        } elseif ($cacheType == self::TYPE_MEMORY_OBJECT) {
            Context::set($cacheKey, $data);
        }

        return $data;
    }
}