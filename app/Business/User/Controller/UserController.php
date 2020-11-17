<?php

namespace App\Business\User\Controller;

use App\Business\User\Entity\QueryObject\CreateUserQo;
use App\Business\User\Service\UserService;
use App\Support\Annotation\JwtAuth;
use App\Support\Auth\AuthHelper;
use App\Support\Entity\ViewObject\ResultVo;
use App\Support\Parent\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Request;

/**
 * Class UserController
 * @package App\Http\User\Controller
 * @Controller()
 */
class UserController extends AbstractController
{
    /**
     * @Inject()
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @GetMapping("/user")
     * @JwtAuth()
     * @return ResultVo
     */
    public function getUserInfo(): ResultVo
    {
        $userInfo = $this->userService->getUserInfo(AuthHelper::getPayload()->userId);
        return ResultVo::success($userInfo);
    }

    /**
     * @PostMapping(path="/user")
     * @param Request $request
     * @return ResultVo
     */
    public function createUser(Request $request): ResultVo
    {
        $createUserQo = new CreateUserQo(
            $this->validateRequest(
                [
                    'mobile' => 'required',
                    'verifyCode' => 'required',
                    'password' => 'min:6',
                ],
                [
                    'password.min' => '密码不能小于6位',
                ],
                [
                    'mobile' => '手机号码',
                    'verifyCode' => '验证码',
                ]
            )
        );

        $createUserQo->mobilePrefix = $request->input('mobilePrefix', 86);

        $user = $this->userService->createUser($createUserQo);
        $token = $this->userService->generateToken($user);

        return ResultVo::success([
            'userId' => $user->id,
            'token' => $token,
        ]);
    }
}
