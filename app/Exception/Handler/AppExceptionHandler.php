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

namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use App\Support\Auth\CsrfMiddleware;
use App\Support\Entity\ViewObject\ResultVo;
use App\Exception\BusinessException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Response;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $hyperfResponse = CsrfMiddleware::addCsrfHeaders(new Response($response));

        if ($throwable instanceof BusinessException) {
            $this->stopPropagation();

            $httpStatus = 416;

            if ($throwable->getCode() == ErrorCode::TOKEN_VALIDATE_ERROR) {
                $httpStatus = 401;
            }

            return $hyperfResponse->withStatus($httpStatus)->json(
                ResultVo::error(
                    $throwable->getCode(),
                    $throwable->getMessage()
                )
            );
        } elseif ($throwable instanceof ValidationException) {
            $this->stopPropagation();

            $errorBody = $throwable->validator->errors()->first();
            return $hyperfResponse->withStatus(400)->json(
                ResultVo::error(
                    ErrorCode::BAD_REQUEST,
                    $errorBody
                )
            );
        }

        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());
        /** @var RequestInterface $request */
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        return $hyperfResponse->withStatus(500)->json(
            ResultVo::error(
                ErrorCode::SERVER_ERROR,
                '系统错误，请稍后再试',
                $request->has('debug') ?
                    [
                        'error' => $throwable->getMessage(),
                    ] :
                    null
            )
        );
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
