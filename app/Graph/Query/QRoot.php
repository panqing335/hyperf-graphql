<?php


namespace App\Graph\Query;


use App\Graph\Query\User\QUserManage;
use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class QRoot extends ObjectType
{
    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = 'QRoot';
        $attrs->desc = '';
    }

    public function fields(GraphTypeFactory $types): array
    {
        return [
            'userQueries' => $types->fast(QUserManage::class, '用户相关')
        ];
    }

    public function resolveField()
    {
        return [];
    }
}
