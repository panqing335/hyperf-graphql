<?php


namespace App\Graph\Query\User;


use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class QUserManage extends ObjectType
{

    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = "QUserManage";
        $attrs->desc = "用户管理";
    }

    public function fields(GraphTypeFactory $types): array
    {
        return [
            'userList' => $types->fastResult('1212', [])
        ];
    }

    public function resolveUserList($val, array $args)
    {
        return true;
    }
}