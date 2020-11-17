<?php


namespace App\Graph\Type;


use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class TMutationResult extends ObjectType
{

    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = 'TMutationResult';
        $attrs->desc = '公用返回类型';
    }

    public function fields(GraphTypeFactory $types): array
    {
        return [
            'message' => $types->fast($types->string()),
        ];
    }

    public function resolveMessage($val, array $args)
    {
        return "success";
    }
}