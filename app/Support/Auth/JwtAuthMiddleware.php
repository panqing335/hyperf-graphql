<?php
declare(strict_types=1);

namespace App\Support\Auth;


use App\Business\Admin\Entity\Model\AdministratorModel;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;
use Phper666\JwtAuth\Exception\JWTException;
use Phper666\JwtAuth\Exception\TokenValidException;
use Phper666\JwtAuth\Jwt;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Throwable;

/**
 * Class JwtAuthMiddleware
 * @package App\Http\Middleware
 */
class JwtAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var Jwt
     * @Inject()
     */
    protected Jwt $jwt;

    /**
     * @var HttpResponse
     * @Inject()
     */
    protected HttpResponse $httpResponse;

    /**
     * @var string $prefix
     */
    private string $prefix = 'Bearer ';

    /**
     * jwt验证解析
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getHeader('Authorization')[0] ?? $request->getQueryParams()["token"] ?? "";
        // 验证
        if (!empty($token) && $token != "debug") {
            $token = str_replace($this->prefix, '', $token);
            try {
                if ($this->jwt->checkToken($token)) {
                    $jwtData = $this->jwt->getParserData($token);
                    $authPayload = new AuthPayloadEntity($jwtData);
                    $adminModel = AdministratorModel::findFromCache($authPayload->administratorId);
                    if ($adminModel->passwordVersion != $authPayload->passwordVersion) {
                        throw new BusinessException(ErrorCode::TOKEN_VALIDATE_ERROR, '您的密码已被修改，请重新登录');
                    }

                    $request = Context::override(ServerRequestInterface::class, function () use ($request, $authPayload) {
                        return $request->withAttribute(config('jwt.request_attr_field'), $authPayload);
                    });
                    return $handler->handle($request);
                } else {
                    throw new BusinessException(ErrorCode::TOKEN_VALIDATE_ERROR, ['Parse failed[-1]']);
                }
            } catch (TokenValidException $tokenValidException) {
                // 验证异常
                throw new BusinessException(ErrorCode::TOKEN_VALIDATE_ERROR, ['Check failed']);
            } catch (JWTException $JWTException) {
                // 解析异常
                throw new BusinessException(ErrorCode::TOKEN_VALIDATE_ERROR, ['Parse failed']);
            } catch (Throwable $e) {
                if ($e->getMessage() == 'The JWT string must have two dots') {
                    throw new BusinessException(ErrorCode::TOKEN_VALIDATE_ERROR, ['Parse failed[-2]']);
                }

                // 其他异常
                throw $e;
            }
        } else {
            $params = $request->getQueryParams();
            if (isset($params['debug']) && $params['token'] == 'debug' && env('APP_ENV') != 'production') {

                $authPayload = new AuthPayloadEntity(['administratorId' => 11]);

                $request = Context::override(ServerRequestInterface::class, function () use ($request, $authPayload) {
                    return $request->withAttribute(config('jwt.request_attr_field'), $authPayload);
                });

                return $handler->handle($request);
            }

        }

        throw new BusinessException(ErrorCode::TOKEN_VALIDATE_ERROR, ['Token not found']);
    }
}
