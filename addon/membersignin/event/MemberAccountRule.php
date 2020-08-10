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

use addon\membersignin\model\Signin;

/**
 * 会员账户变化规则
 */
class MemberAccountRule
{

    public function handle($data)
    {
        $config = new Signin();
        $info   = $config->getConfig($data['site_id']);
        $return = '';
        if ($data['account'] == 'point') {
            if ($info['data']['is_use'] == 1) {
                foreach ($info['data']['value'] as $v) {

                    $return .= "会员签到" . $v['day'] . "天，赠送" . $v['point'] . "积分；";
                }
            }
        }
        if ($data['account'] == 'growth') {
            if ($info['data']['is_use'] == 1) {
                foreach ($info['data']['value'] as $v) {

                    $return .= "会员签到" . $v['day'] . "天，赠送" . $v['growth'] . "成长值；";
                }
            }
        }
        return isset($return) ? $return : '';

    }
}