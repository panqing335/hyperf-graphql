<?php


namespace App\Graph\Mutation;


use App\Graph\Mutation\Admin\MAdmin;
use App\Graph\Mutation\AfterSales\MAfterSales;
use App\Graph\Mutation\Index\MIndexNews;
use App\Graph\Mutation\Leader\MLeaderGoodsPaymentRechargeManage;
use App\Graph\Mutation\Leader\MLeaderGrowthTemplate;
use App\Graph\Mutation\Leader\MLeaderRatioTemplate;
use App\Graph\Mutation\Leader\MLeaderWithdraw;
use App\Graph\Mutation\Live\MLive;
use App\Graph\Mutation\Lottery\MLotteryActivity;
use App\Graph\Mutation\Lottery\MRainActivity;
use App\Graph\Mutation\Mall\MActivityPage;
use App\Graph\Mutation\Mall\MChannel;
use App\Graph\Mutation\Mall\MGoodsBrand;
use App\Graph\Mutation\Mall\MOrder;
use App\Graph\Mutation\Mall\MResource;
use App\Graph\Mutation\Mall\MAdList;
use App\Graph\Mutation\Mall\MDepotStock;
use App\Graph\Mutation\Mall\MExpressCompany;
use App\Graph\Mutation\Mall\MGoods;
use App\Graph\Mutation\Mall\MGoodsAttribute;
use App\Graph\Mutation\Mall\MGoodsCategory;
use App\Graph\Mutation\Mall\MGoodsShippingTemplate;
use App\Graph\Mutation\Mall\MHomePage;
use App\Graph\Mutation\Mall\MTax;
use App\Graph\Mutation\Marketing\MCoupon;
use App\Graph\Mutation\Marketing\MMarketingGroup;
use App\Graph\Mutation\Message\MDialog;
use App\Graph\Mutation\Message\MMessage;
use App\Graph\Mutation\Marketing\MSeckillActivity;
use App\Graph\Mutation\Material\MArticle;
use App\Graph\Mutation\Message\MNotice;
use App\Graph\Mutation\Supplier\MSupplier;
use App\Graph\Mutation\User\MUser;
use App\Support\GraphQL\Definition\ObjectType;
use App\Support\GraphQL\Entity\GraphTypeAttrs;
use App\Support\GraphQL\GraphTypeFactory;
use DateTime;

class MRoot extends ObjectType
{
    public function attrs(GraphTypeAttrs &$attrs): void
    {
        $attrs->name = 'MRoot';
        $attrs->desc = '';
    }

    public function fields(GraphTypeFactory $types): array
    {
        return [
            'user' => $types->fast(MUser::class, '用户相关')
        ];
    }

    public function resolveField()
    {
        return [];
    }
}