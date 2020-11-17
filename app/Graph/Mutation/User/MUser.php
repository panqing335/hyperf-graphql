<?php


namespace App\Graph\Mutation\User;


use App\Business\User\Service\UserService;
use App\Graph\Type\User\TAddUser;
use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;
use Hyperf\Di\Annotation\Inject;

class MUser extends ObjectType
{
    /**
     * @Inject()
     * @var UserService
     */
    protected UserService $userService;
    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = "MUser";
        $attrs->desc = "用户操作";
    }

    public function fields(GraphTypeFactory $types): array
    {
        return [
            'addUser' => $types->fastResult(
                "添加用户",
                [
                    'userInfo' => $types->fastNonNull(
                        $types->input(TAddUser::class),
                        "添加用户"
                    )
                ]
            ),
        ];
    }

    public function resolveAddUser($var, array $args)
    {
        return true;
    }
}