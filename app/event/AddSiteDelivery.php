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

namespace app\event;

use app\model\express\Config as ConfigModel;

/**
 * 增加默认配送管理数据
 */
class AddSiteDelivery
{

    public function handle($param)
    {
        if (!empty($param['site_id'])) {

            $config_model = new ConfigModel();
            $data         = array();
            $res          = $config_model->setExpressConfig($data, 1, $param['site_id']);
            return $res;

        }
    }
}