<?php


namespace App\Graph\Enum;


use App\Support\GraphQL\Definition\EnumType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class EShowStatus extends EnumType
{

    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = "commonShowStatus";
        $attrs->desc = "展示状态";
    }

    public function values(GraphTypeFactory $types): array
    {
        return [
            'ENABLE' => $types->enumValue(1, '已显示'),
            'DISABLE' => $types->enumValue(2, '已隐藏'),
        ];
    }
}