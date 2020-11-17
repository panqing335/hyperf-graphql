<?php


namespace App\Graph\Query\User;


use App\Business\User\Entity\QueryObject\SearchUserQo;
use App\Business\User\Service\UserService;
use App\Graph\Type\User\TUser;
use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\Entity\TPaginatorQo;
use App\Support\GraphQL\Entity\TPaginatorVo;
use App\Support\GraphQL\GraphTypeFactory;
use Hyperf\Di\Annotation\Inject;

class QUserManage extends ObjectType
{
    /**
     * @Inject()
     * @var UserService
     */
    protected UserService $userService;

    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = "QUserManage";
        $attrs->desc = "用户管理";
    }

    public function fields(GraphTypeFactory $types): array
    {
        return [
            'userList' => $types->paginator(TUser::class, '用户列表', [
                'mobile' => $types->fastNullableString('手机号')
            ])
        ];
    }

    public function resolveUserList($val, array $args)
    {
        $page = $this->userService->page(new TPaginatorQo($args), new SearchUserQo($args));
        return TPaginatorVo::fill($page);
    }
}