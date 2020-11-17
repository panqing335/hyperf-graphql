<?php


namespace App\Graph\Mutation;

use App\Graph\Mutation\User\MUser;
use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class MRoot extends ObjectType
{
    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = 'MRoot';
        $attrs->desc = '';
    }

    public function fields(GraphTypeFactory $types): array
    {
        return [
            'user' => $types->fast(MUser::class, '用户相关')
        ];
    }

    public function resolveField()
    {
        return [];
    }
}