<?php


namespace App\Graph\Query;


use App\Graph\Query\Admin\QAdmin;
use App\Graph\Query\AfterSales\QAfterSales;
use App\Graph\Query\Index\QIndex;
use App\Graph\Query\Index\QIndexNews;
use App\Graph\Query\Leader\QLeaderAccountManage;
use App\Graph\Query\Leader\QLeaderCommissionManage;
use App\Graph\Query\Leader\QLeaderGoodsPaymentRechargeManage;
use App\Graph\Query\Leader\QLeaderGrowthTemplate;
use App\Graph\Query\Leader\QLeaderRatioTemplate;
use App\Graph\Query\Leader\QLeaderWithdraw;
use App\Graph\Query\Live\QLiveList;
use App\Graph\Query\Lottery\QLotteryActivity;
use App\Graph\Query\Lottery\QRainActivity;
use App\Graph\Query\Mall\QActivityPage;
use App\Graph\Query\Mall\QChannel;
use App\Graph\Query\Mall\QComplaint;
use App\Graph\Query\Mall\QResource;
use App\Graph\Query\Mall\QAdList;
use App\Graph\Query\Mall\QDepot;
use App\Graph\Query\Mall\QExpressCompany;
use App\Graph\Query\Mall\QGoods;
use App\Graph\Query\Mall\QGoodsAttribute;
use App\Graph\Query\Mall\QGoodsBrand;
use App\Graph\Query\Mall\QGoodsCategory;
use App\Graph\Query\Mall\QGoodsShippingTemplate;
use App\Graph\Query\Mall\QOrder;
use App\Graph\Query\Mall\QRegion;
use App\Graph\Query\Mall\QStock;
use App\Graph\Query\Material\QArticle;
use App\Graph\Query\Marketing\QCoupon;
use App\Graph\Query\Marketing\QMarketingGroup;
use App\Graph\Query\Marketing\QScore;
use App\Graph\Query\Message\QDialog;
use App\Graph\Query\Message\QMessage;
use App\Graph\Query\Marketing\QSeckillActivity;
use App\Graph\Query\Message\QNotice;
use App\Graph\Query\Supplier\QSupplier;
use App\Graph\Query\User\QUserManage;
use App\Graph\Query\Mall\QTax;
use App\Graph\Query\User\QBank;
use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;

class QRoot extends ObjectType
{
    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = 'QRoot';
        $attrs->desc = '';
    }

    public function fields(GraphTypeFactory $types): array
    {
        return [
            'userQueries' => $types->fast(QUserManage::class, '用户相关')
        ];
    }

    public function resolveField()
    {
        return [];
    }
}
