<?php


namespace App\Business\User\Service;

use App\Business\User\Entity\QueryObject\SearchUserQo;
use App\Business\User\Repository\UserRepository;
use App\Support\Annotation\Service;
use App\Support\GraphQL\Entity\TPaginatorQo;
use Hyperf\Di\Annotation\Inject;

/**
 * Class UserService
 * @package App\Business\User\Service
 * @Service()
 */
class UserService
{
    /**
     * @Inject()
     * @var UserRepository
     */
    protected UserRepository $userRepo;

    /**
     * 获取用户管理的分页列表
     * @param TPaginatorQo $paginatorQo
     * @param SearchUserQo $searchUserQo
     * @return \Hyperf\Contract\LengthAwarePaginatorInterface
     */
    public function page(TPaginatorQo $paginatorQo, SearchUserQo $searchUserQo)
    {
        return $this->userRepo->getUserPaginate($paginatorQo, $searchUserQo);
    }
}
