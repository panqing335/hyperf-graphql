<?php


namespace App\Business\User\Entity\Model;


use App\Support\Parent\Model\Model;

/**
 * Class UserModel
 * @package App\Business\User\Entity\Model
 * @property int $aiId
 * @property int $id
 * @property string $nickname
 * @property string $mobile
 * @property string $mobilePrefix
 * @property string $password
 * @property string $passwordSalt
 * @property int $passwordVersion
 * @property int $inviteUserId 邀请人用户ID
 * @property string $inviteCode 邀请码
 * @property string $avatarUrl 头像
 * @property string $wxUnionId 微信unionId
 * @property string $wxNickname 微信昵称
 */
class UserModel extends Model
{
    protected $table = "u_user";
    protected $guarded = [];
    protected $keyType = "string";

    public function setPrimaryKey($key)
    {
        $this->primaryKey = $key;
    }


    public function setLevelNameAttribute($value)
    {
        $this->attributes['levelName'] = strtolower($value);
    }
}
