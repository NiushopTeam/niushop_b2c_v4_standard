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

namespace addon\membersignin\model;

use app\model\system\Config as ConfigModel;
use app\model\BaseModel;

/**
 * 会员签到
 */
class Signin extends BaseModel
{
    /**
     * 会员签到奖励设置
     * @param $data
     * @param $is_use
     * @return array
     */
    public function setConfig($data, $is_use, $site_id)
    {
        $config = new ConfigModel();
        $res    = $config->setConfig($data, '会员签到奖励设置', $is_use, [['site_id', '=', $site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'MEMBER_SIGNIN_REWARD_CONFIG']]);
        return $res;
    }

    /**
     * 会员签到奖励设置
     */
    public function getConfig($site_id)
    {
        $config = new ConfigModel();
        $res    = $config->getConfig([['site_id', '=', $site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'MEMBER_SIGNIN_REWARD_CONFIG']]);
        if (empty($res['data']['value'])) {
            $res['data']['value'] = [
                [
                    "point"  => 0,
                    "growth" => 0,
                    'coupon' => 0,
                    "day"    => 1
                ]
            ];
        }
        return $res;
    }

}