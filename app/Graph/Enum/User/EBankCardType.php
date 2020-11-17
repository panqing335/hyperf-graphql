<?php


namespace App\Graph\Enum\User;


use App\Support\GraphQL\Definition\EnumType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class EBankCardType extends EnumType
{
    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = 'EBankCardType';
    }

    public function values(GraphTypeFactory $types): array
    {
        return [
            'DEBIT_CARD' => $types->enumValue(1, '储蓄卡'),
            'CREDIT_CARD' => $types->enumValue(2, '信用卡'),
        ];
    }
}