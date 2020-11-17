<?php

declare(strict_types=1);

namespace App\Support\Entity\ViewObject;


use App\Constants\ErrorCode;
use App\Support\Entity\BaseEntity;

/**
 * @property int $code
 * @property string $message
 * @property object $data
 */
class ResultVo extends BaseEntity
{
    public function __construct(int $code, string $message, $payload = null)
    {
        parent::__construct([]);
        $this->code = $code;
        $this->message = $message;
        $this->data = $payload;
    }

    public static function respond(int $code, $payload, string $message = null)
    {
        if (empty($message)) {
            $message = ErrorCode::getMessage($code) ?? '服务器走丢啦，请稍后再试';
        }

        return new ResultVo($code, $message, $payload);
    }

    public static function success($payload, string $message = null)
    {
        return self::respond(ErrorCode::SUCCESS, $payload, $message);
    }

    public static function error(int $code, string $message = null, $payload = null)
    {
        return self::respond($code, $payload, $message);
    }
}