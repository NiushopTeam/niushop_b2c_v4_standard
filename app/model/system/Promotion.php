<?php
/**
 * Niushop商城系统 - 团队十年电商经验汇集巨献!
 * =========================================================
 * Copy right 2019-2029 上海牛之云网络科技有限公司, 保留所有权利。
 * ----------------------------------------------
 * 官方网址: https://www.niushop.com.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 任何企业和个人不允许对程序代码以任何形式任何目的再发布。
 * =========================================================
 */

namespace app\model\system;

use app\model\BaseModel;

/**
 * 活动整体管理
 */
class Promotion extends BaseModel
{
    /**
     * 获取营销活动展示
     */
    public function getPromotions()
    {
        $show           = event("ShowPromotion", []);
        $shop_promotion = [];
        foreach ($show as $k => $v) {
            if (!empty($v['shop'])) {
                $shop_promotion = array_merge($shop_promotion, $v['shop']);
            }
        }
        return [
            'shop' => $shop_promotion
        ];
    }

    /**
     * 获取站点营销活动展示
     * @param $site_id
     */
    public function getSitePromotions($site_id)
    {
        $show      = event("ShowPromotion", []);
        $promotion = [];
        foreach ($show as $k => $v) {
            if (!empty($v['shop'])) {
                $promotion = array_merge($promotion, $v['shop']);
            }
        }
        return $promotion;
    }

    /**
     * 获取营销类型
     */
    public function getPromotionType()
    {
        $promotion_type   = event("PromotionType");
        $promotion_type[] = ["type" => "empty", "name" => "无营销活动"];
        return $promotion_type;
    }
}