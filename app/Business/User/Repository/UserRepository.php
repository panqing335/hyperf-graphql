<?php


namespace App\Business\User\Repository;

use App\Business\User\Entity\Model\UserModel;
use App\Business\User\Entity\QueryObject\SearchUserQo;
use App\Support\Annotation\Repository;
use App\Support\GraphQL\Entity\TPaginatorQo;


/**
 * Class UserRepository
 * @package App\Repository\User
 * @Repository()
 */
class UserRepository
{
    /**
     * 获取用户管理的分页列表
     * @param TPaginatorQo $paginatorQo
     * @param SearchUserQo $searchUserQo
     * @return \Hyperf\Contract\LengthAwarePaginatorInterface
     */
    public function getUserPaginate(TPaginatorQo $paginatorQo, SearchUserQo $searchUserQo)
    {
        $query = UserModel::query();
        $query->when($searchUserQo->mobile, fn($q) => $q->where('mobile', $searchUserQo->mobile));
        $query->orderByDesc('id');
        return $query->paginate($paginatorQo->pageSize, ['*'], 'page', $paginatorQo->currentPage);
    }

}
