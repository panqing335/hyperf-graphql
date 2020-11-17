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

namespace App\Exception;

use App\Constants\ErrorCode;
use Hyperf\Server\Exception\ServerException;
use Hyperf\Utils\Arr;
use Throwable;

class BusinessException extends ServerException
{
    public function __construct(int $code = 0, $message = null, Throwable $previous = null)
    {
        if (is_null($message) || is_array($message)) {
            $message = ErrorCode::getMessage($code, is_array($message) ? Arr::first($message) : '');
        }

        parent::__construct($message, $code, $previous);
    }
}
