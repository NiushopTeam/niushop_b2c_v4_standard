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

use app\model\express\Kdbird;

/**
 * 初始化配置信息
 * @author Administrator
 *
 */
class KdbirdTrace
{
    public function handle($data)
    {
        $express_no_data = $data["express_no_data"];

        $kdbird_model  = new Kdbird();
        $config_result = $kdbird_model->getKdbirdConfig($express_no_data["site_id"]);
        $config        = $config_result["data"];

        if ($config["is_use"]) {
            $express_no = $express_no_data["express_no"];
            $result     = $kdbird_model->trace($data["code"], $express_no, $express_no_data["site_id"]);
            return $result;
        }
    }
}
