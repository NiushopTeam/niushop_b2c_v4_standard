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

namespace addon\membersignin\event;

use app\model\member\Member as MemberModel;
use app\model\member\MemberAccount as MemberAccountModel;
use app\model\member\MemberSignin as MemberSigninModel;

/**
 * 会员签到奖励
 */
class MemberSignin
{
    /**
     * @param $param
     * @return string|\multitype
     */
    public function handle($param)
    {
        $member_model         = new MemberModel();
        $member_signin_model  = new MemberSigninModel();
        $member_account_model = new MemberAccountModel();

        // 查询当前用户连签天数
        $member_info = $member_model->getMemberInfo([['member_id', '=', $param['member_id']]], 'sign_days_series,site_id');
        $member_info = $member_info['data'];

        $award = $member_signin_model->getAward($member_info['site_id']);
        $award = $award['data'];

        $res = [];
        if (!empty($award)) {

            $is_end = true;//是否超出连签天数，取最大的奖励

            foreach ($award as $k => $v) {
                if ($member_info['sign_days_series'] == $v['day']) {
                    $is_end = false;
                    $res    = $v;
                    break;
                }
            }

            if ($is_end) {
                $res = $award[count($award) - 1];
            }

            foreach ($res as $curr_k => $curr_v) {
                if ($curr_k != 'day') {
                    $adjust_num   = $curr_v;
                    $account_type = $curr_k;
                    $remark       = '签到送' . $adjust_num . $this->accountType($curr_k);
                    $member_account_model->addMemberAccount($param['site_id'], $param['member_id'], $account_type, $adjust_num, 'signin', 0, $remark);
                }
            }

        }
        return $res;
    }

    private function accountType($key)
    {
        $type = [
            'point'  => '积分',
            'growth' => '成长值',
            'coupon' => '优惠券'
        ];
        return $type[$key];
    }

}