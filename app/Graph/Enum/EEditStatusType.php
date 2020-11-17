<?php


namespace App\Graph\Enum;


use App\Support\GraphQL\Definition\EnumType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class EEditStatusType extends EnumType
{

    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = 'EEditType';
        $attrs->desc = '用于变更时标记字段状态';
    }

    public function values(GraphTypeFactory $types): array
    {
        return [
            'NO_CHANGE' => $types->enumValue(0, '无更改'),
            'MODIFIED' => $types->enumValue(1, '已被更改'),
            'REMOVED' => $types->enumValue(2, '已删除'),
            'CREATED' => $types->enumValue(3, '新创建'),
        ];
    }
}