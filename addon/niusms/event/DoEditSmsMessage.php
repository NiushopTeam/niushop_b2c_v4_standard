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

use addon\niusms\model\Config as ConfigModel;

/**
 * 短信模板  (后台调用)
 */
class DoEditSmsMessage
{
    /**
     * 短信发送方式方式及配置
     */
    public function handle()
    {
        $config_model  = new ConfigModel();
        $config_result = $config_model->getSmsConfig();
        $config        = $config_result["data"];
        if ($config["is_use"] == 1) {
            return ["edit_url" => "niusms://shop/message/edit", "shop_url" => "niusms://shop/message/edit"];
        }

    }
}