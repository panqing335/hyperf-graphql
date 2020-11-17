<?php


namespace App\Business\User\Repository;

use App\Business\User\Entity\Model\UserModel;
use App\Business\User\Entity\QueryObject\GetUserManageQo;
use App\Support\Annotation\Repository;
use App\Support\GraphQL\Entity\TPaginatorVo;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Contract\PaginatorInterface;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;
use Hyperf\Utils\Collection;

/**
 * Class UserRepository
 * @package App\Repository\User
 * @Repository()
 */
class UserRepository
{
    /**
     * 通过用户Id查询用户
     * @param string $id
     * @return UserModel|\App\Support\Parent\Model\Model|Model|null
     */
    public function find(string $id)
    {
        return UserModel::findFromCache($id);
    }

    public function getBuilder()
    {
        return UserModel::query();
    }

    /**
     * 通过手机号码查询用户
     * @param string $mobile
     * @param int $mobilePrefix
     * @return Model|UserModel
     */
    public function getUserByMobile(string $mobile, int $mobilePrefix = 86): ?UserModel
    {
        return UserModel::query()
            ->where('mobile', $mobile)
            ->where('mobile_prefix', $mobilePrefix)
            ->first();
    }

    /**
     * 获取用户管理的分页列表
     * @param TPaginatorVo $paginatorVo
     * @param GetUserManageQo $userManageQo
     * @return LengthAwarePaginatorInterface|PaginatorInterface
     */
    public function getUserPaginate(TPaginatorVo $paginatorVo, GetUserManageQo $userManageQo)
    {
        return $this->getQuery($userManageQo)
            ->orderBy('u_user.created_time', 'desc')
            ->paginate($paginatorVo->pageSize, ['*'], 'page', $paginatorVo->currentPage);
    }

    /**
     * 获取用户列表
     * @param GetUserManageQo $userManageQo
     * @return Builder[]|\Hyperf\Database\Model\Collection
     */
    public function getUserList(GetUserManageQo $userManageQo)
    {
        return $this->getQuery($userManageQo)->get();
    }

    /**
     * 用户查询
     * @param GetUserManageQo $userManageQo
     * @return Builder|Builder
     */
    public function getQuery(GetUserManageQo $userManageQo)
    {
        $query = UserModel::query()->from('u_user')
            ->join('u_user_leader as ul', 'ul.user_id', '=', 'u_user.id')
            ->join('u_user_level as l', 'l.id', '=', 'ul.user_level_id')
            ->select(['u_user.*', 'l.name as levelName', 'ul.user_level_id as userLevelId', 'ul.invite_user_id as inviteUserId', 'u1.mobile as inviteUserMobile'])
            ->leftJoin('u_user as u1', 'u1.id', '=', 'ul.invite_user_id');
        if (!empty($userManageQo->mobile)) {
            $query->where('u_user.mobile', "LIKE", "%{$userManageQo->mobile}%");
        }
        if (!empty($userManageQo->inviteUserMobile)) {
            $query->where('u1.mobile', "LIKE", "%{$userManageQo->inviteUserMobile}%");
        }
        if (!empty($userManageQo->inviteCode)) {
            $query->where('u_user.invite_code', $userManageQo->inviteCode);
        }
        $query->when(!empty($userManageQo->userLevelId), function ($q) use ($userManageQo) {
            $q->where('ul.user_level_id', $userManageQo->userLevelId);
        });
        if (!empty($userManageQo->start)) {
            $query->where('u_user.created_time', '>=', $userManageQo->start);
        }
        if (!empty($userManageQo->end)) {
            $query->where('u_user.created_time', '<=', $userManageQo->end);
        }
        $query->when($userManageQo->mobiles, fn(Builder $query) => $query->whereIn("u_user.mobile", $userManageQo->mobiles));
        $query->when($userManageQo->ids, fn(Builder $query) => $query->whereIn("u_user.id", $userManageQo->ids));
        return $query;
    }

    /**
     * 多手机号查询用户id列表
     * @param array $mobiles
     * @return Collection
     */
    public function getUserIdListByMobiles(array $mobiles)
    {
        return UserModel::query()->whereIn('mobile', $mobiles)->pluck('id');
    }

    /**
     * @param string $mobile
     * @return Builder|Model|object|null|UserModel
     */
    public function getUser(string $mobile): ?UserModel
    {
        return UserModel::query()
            ->where('mobile', $mobile)
            ->first();
    }
}
