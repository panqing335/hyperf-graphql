<?php


namespace App\Graph\Type\User;


use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class TAddUser extends ObjectType
{

    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = "TAddUser";
    }

    public function fields(GraphTypeFactory $types): array
    {
        return [
            'mobile' => $types->fastString("手机号"),
            'nickname' => $types->fastNullableString("昵称"),
            'inviteMobile' => $types->fastNullableString("邀请手机号"),
            'mobilePrefix' => $types->fastNullableString("手机号前缀,默认为86", "86")
        ];
    }
}