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

}
