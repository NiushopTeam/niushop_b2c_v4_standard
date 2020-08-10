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

namespace addon\niusms\model;

use app\model\system\Config as ConfigModel;
use app\model\BaseModel;

/**
 * 牛云短信配置
 */
class Config extends BaseModel
{
    /**
     * 设置短信配置
     * array $data
     */
    public function setSmsConfig($data, $is_use, $site_id = 1, $app_module = 'shop')
    {
        $config = new ConfigModel();
        $res = $config->setConfig($data, '牛云短信配置', $is_use, [ [ 'site_id', '=', $site_id ], [ 'app_module', '=', $app_module ], [ 'config_key', '=', 'NIU_SMS_CONFIG' ] ]);
        event('EnableCallBack', [ 'sms_type' => 'niusms', 'is_use' => $is_use, 'site_id' => $site_id ]);
        return $res;
    }

    /**
     * 获取短信配置
     */
    public function getSmsConfig($site_id = 1, $app_module = 'shop')
    {
        $config = new ConfigModel();
        $res = $config->getConfig([ [ 'site_id', '=', $site_id ], [ 'app_module', '=', $app_module ], [ 'config_key', '=', 'NIU_SMS_CONFIG' ] ]);
        return $res;
    }

    /**
     * 修改开关状态
     * array $data
     */
    public function modifyConfigIsUse($is_use, $site_id = 1, $app_module = 'shop')
    {
        $config = new ConfigModel();
        $res = $config->modifyConfigIsUse($is_use, [ [ 'site_id', '=', $site_id ], [ 'app_module', '=', $app_module ], [ 'config_key', '=', 'NIU_SMS_CONFIG' ] ]);
        event('EnableCallBack', [ 'sms_type' => 'niusms', 'is_use' => $is_use, 'site_id' => $site_id ]);
        return $res;
    }
}