<?php


namespace App\Support\GraphQL\Entity;


use App\Support\Entity\BaseEntity;
use Hyperf\Contract\PaginatorInterface;

/**
 * Class TPaginatorVo
 * @package App\Support\GraphQL\Entity
 * @property int $total
 * @property int $currentPage
 * @property int $pageSize
 * @property array $items
 */
class TPaginatorVo extends BaseEntity
{
    public function __construct($data = [])
    {
        parent::__construct($data);
        if (array_key_exists('page', $data)) {
            $this->currentPage = $data['page'];
        }
        if (array_key_exists('pageSize', $data)) {
            $this->pageSize = $data['pageSize'];
        }
    }

    public static function fill(PaginatorInterface $paginator)
    {
        $paginatorVo = new TPaginatorVo();
        $paginatorVo->total = $paginator->total();
        $paginatorVo->currentPage = $paginator->currentPage();
        $paginatorVo->pageSize = $paginator->perPage();
        $paginatorVo->items = $paginator->items();
        return $paginatorVo;
    }
}
