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

namespace addon\niusms\event;

use addon\niusms\model\Config;

/**
 * 获取短信模板数据
 */
class SmsTemplateInfo
{
    /**
     * 获取短信模板数据
     */
    public function handle($param)
    {
        $config_model = new Config();
        $sms_config = $config_model->getSmsConfig($param['site_id'], 'shop');
        $sms_config = $sms_config[ 'data' ];
        if ($sms_config['is_use']) {
            $template_info = model('sms_template')->getInfo([ ['keywords', '=', $param['keywords'] ]]);
            return $template_info;
        }
    }
}