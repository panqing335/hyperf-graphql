<?php


namespace App\Graph\Enum\User;


use App\Support\GraphQL\Definition\EnumType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class EUserSex extends EnumType
{

    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = 'EUserSex';
        $attrs->desc = '用户性别';
    }

    public function values(GraphTypeFactory $types): array
    {
       return [
           'MAN' => $types->enumValue(1, "男性"),
           'FEMALE' => $types->enumValue(2, "女性"),
           //'UNKOWN' => $types->enumValue(null, "未知"),
       ];
    }
}