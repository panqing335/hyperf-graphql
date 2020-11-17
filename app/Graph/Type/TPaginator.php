<?php


namespace App\Graph\Type;


use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class TPaginator extends ObjectType
{

    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = 'TPagination';
        $attrs->desc = '分页';
    }

    public function fields(GraphTypeFactory $typeFactory): array
    {
        return [
            'total' => $typeFactory->int(),
            'pageSize' => $typeFactory->int(),
            'currentPage' => $typeFactory->int(),
        ];
    }
}