<?php


namespace App\Graph\Type\User;


use App\Graph\Enum\User\EUserSex;
use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class TUser extends ObjectType
{
    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = "TUser";
    }

    public function fields(GraphTypeFactory $types): array
    {
        return [
            'id' => $types->fastId("用户id"),
            'userLevelId' => $types->fastId('等级ID'),
            'nickname' => $types->fastNullableString("昵称"),
            'avatarUrl' => $types->fastNullableString("头像链接"),
            'wxId' => $types->fastNullableString("微信号"),
            'sex' => $types->fast(EUserSex::class, "性别"),
            'mobile' => $types->fastString("手机号"),
            'mobilePrefix' => $types->fastString("手机号前缀"),
            'inviteUserId' => $types->fastNullableString("邀请人用户ID"),
            'inviteCode' => $types->fastNullableString("用户邀请码"),
            'levelName' => $types->fastString("等级名称"),
            'inviteUserMobile' => $types->fastNullableString("邀请人手机号"),
            'createdTime' => $types->fastString("创建时间"),
        ];
    }
}