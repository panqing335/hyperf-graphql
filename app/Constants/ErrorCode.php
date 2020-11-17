<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 * @method static string getMessage($code, array $replaceParams = [])
 */
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("success")
     */
    const SUCCESS = 0;

    /**
     * @Message("服务器走丢啦，请稍后再试")
     */
    const SERVER_ERROR = 1001;

    /**
     * @Message("请求错误")
     */
    const BAD_REQUEST = 1002;

    /**
     * @Message("身份验证失败 [%s]")
     */
    const TOKEN_VALIDATE_ERROR = 2001;

    /**
     * @Message("用户名或密码错误")
     */
    const ADMIN_WRONG_PASSWORD = 2002;

    /**
     * @Message("管理员不存在")
     */
    const ADMIN_NOT_FOUND = 2003;

    /**
     * @Message("商品后台类目不存在")
     */
    const GOODS_BACK_CATEGORY_NOT_FOUND = 2004;

    /**
     * @Message("商品后台类目超限")
     */
    const GOODS_BACK_CATEGORY_EXCEEDS_LIMIT = 2005;

    /**
     * @Message("商品后台类目名称已存在")
     */
    const GOODS_BACK_CATEGORY_NAME_EXISTS = 2006;

    /**
     * @Message("创建失败")
     */
    const CREATED_FAILED = 2007;


    /**
     * @Message("类目下存在商品")
     */
    const GOODS_BACK_CATEGORY_GOODS_EXISTS = 2008;

    /**
     * @Message("编辑失败")
     */
    const EDIT_FAILED = 2009;

    /**
     * @Message("未知操作")
     */
    const UNKNOWN_OPERATION = 2010;

    /**
     * @Message("删除失败")
     */
    const REMOVED_FAILED = 2011;

    /**
     * @Message("参数错误,[%s]")
     */
    const PARAMETER_ERROR = 2012;

    /**
     * @Message("抱歉，您暂无权限操作或查看")
     */
    const NOT_OWNER = 2013;

    /**
     * @Message("%s资源不存在，请刷新重试")
     */
    const RESOURCE_NOT_FOUND = 2014;

    /**
     * @Message("%s不可重复")
     */
    const UNIQUE_ERROR = 2015;

    /**
     * @Message("上传失败")
     */
    const UPLOAD_ERROR = 2016;

    /**
     * @Message("请求频繁")
     */
    const TOO_FAST = 2017;
}
