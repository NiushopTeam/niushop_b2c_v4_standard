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

namespace addon\coupon\model;

use app\model\BaseModel;

/**
 * 优惠券
 */
class MemberCoupon extends BaseModel
{

    /**
     * 获取会员已领取优惠券优惠券
     * @param array $member_id
     */
    public function getMemberCouponList($member_id, $state, $site_id = 0, $order = "fetch_time desc")
    {
        $condition = array(
            ["member_id", "=", $member_id],
            ["state", "=", $state],
        );
        if ($site_id > 0) {
            $condition[] = ["site_id", "=", $site_id];
        }
        $list = model("promotion_coupon")->getList($condition, "*", $order, '', '', '', 0);
        return $this->success($list);
    }

    /**
     * 使用优惠券
     * @param $coupon_id
     */
    public function useMemberCoupon($coupon_id, $member_id, $order_id = 0)
    {
        //优惠券处理方案
        $result = model('promotion_coupon')->update(['use_order_id' => $order_id, 'state' => 2, 'use_time' => time()], [['coupon_id', '=', $coupon_id], ["member_id", "=", $member_id], ['state', '=', 1]]);
        if ($result === false) {
            return $this->error();
        }
        return $this->success();
    }

    /**
     * 获取会员已领取优惠券优惠券数量
     * @param unknown $member_id
     * @param unknown $state
     * @param number $site_id
     * @return multitype:number unknown
     */
    public function getMemberCouponNum($member_id, $state, $site_id = 0)
    {
        $condition = array(
            ["member_id", "=", $member_id],
            ["state", "=", $state],
        );
        if ($site_id > 0) {
            $condition[] = ["site_id", "=", $site_id];
        }
        $num = model("promotion_coupon")->getCount($condition);
        return $this->success($num);
    }

    /**
     * 会员是否可领取该优惠券
     */
    public function receivedNum($coupon_type_id, $member_id)
    {
        $received_num = model('promotion_coupon')->getCount([['coupon_type_id', '=', $coupon_type_id], ['member_id', '=', $member_id]]);
        return $this->success($received_num);
    }
}