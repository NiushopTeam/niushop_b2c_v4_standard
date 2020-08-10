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

namespace app\shop\controller;

use app\model\system\Stat;
use Carbon\Carbon;
use app\model\system\Promotion as PrmotionModel;
use app\model\web\Help as HelpModel;
use app\model\goods\Goods as GoodsModel;
use app\model\web\Notice as NoticeModel;
use app\model\member\Member as MemberModel;

class Index extends BaseShop
{

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $shop_info = $this->shop_info;
        $time      = time();
        $this->assign('shop_status', 1);

        //基础统计信息
        $stat_shop_model = new Stat();
        $today           = Carbon::now();
        $yesterday       = Carbon::yesterday();
        $stat_today      = $stat_shop_model->getStatShop($this->site_id, $today->year, $today->month, $today->day);
        $stat_yesterday  = $stat_shop_model->getStatShop($this->site_id, $yesterday->year, $yesterday->month, $yesterday->day);
        $this->assign("stat_day", $stat_today['data']);
        $this->assign("stat_yesterday", $stat_yesterday['data']);
        $this->assign("today", $today);

        //获取总数
        $shop_stat_sum                        = $stat_shop_model->getShopStatSum($this->site_id);
        $goods_model                          = new GoodsModel();
        $goods_sum                            = $goods_model->getGoodsTotalCount(['site_id' => $this->site_id, 'is_delete' => 0]);
        $shop_stat_sum['data']['goods_count'] = $goods_sum['data'];
        $this->assign('shop_stat_sum', $shop_stat_sum['data']);

        //会员总数
        $member_model = new MemberModel();
        $member_count = $member_model->getMemberCount([['site_id', '=', $this->site_id]]);
        $this->assign('member_count', $member_count['data']);

        //营销活动
        $promotion_model = new PrmotionModel();
        $promotions      = $promotion_model->getSitePromotions($this->site_id);

        $this->assign("promotion", $promotions);

        //分销插件是否存在
        $is_fenxiao = addon_is_exit('fenxiao', $this->site_id);
        $this->assign('is_fenxiao', $is_fenxiao);


        return $this->fetch("index/index");
    }

}