<?php
declare(strict_types=1);

namespace App\Support\Auth;


use Hyperf\Utils\Context;
use Psr\Http\Message\ServerRequestInterface;

class AuthHelper
{
    /**
     * 获取 Token 解析后的数组
     * @return array|null
     */
    public static function getPayload(): ?AuthPayloadEntity
    {
        /** @var ServerRequestInterface $request */
        $request = Context::get(ServerRequestInterface::class);
        return isset($request) ? $request->getAttribute(config('jwt.request_attr_field')) : null;
    }
}