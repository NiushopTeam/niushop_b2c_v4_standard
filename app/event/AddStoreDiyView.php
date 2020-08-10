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

use app\model\web\DiyView as DiyViewModel;


/**
 * 增加默认自定义数据：网站主页
 */
class AddStoreDiyView
{

    public function handle($param)
    {
        if (!empty($param['site_id'])) {

            $diy_view_model = new DiyViewModel();

            // 添加自定义主页装修
            $value = json_encode([
                "global" => [
                    "title"         => "门店主页",
                    "openBottomNav" => false,
                    "bgColor"       => "#ffffff",
                    "bgUrl"         => ""
                ],
                "value"  => [
                    [
                        "addon_name" => "store",
                        "type"       => "STORE_INFO",
                        "name"       => "门店信息",
                        "controller" => "StoreInfo"
                    ]
                ]
            ]);

            // 门店主页
            $data = [
                'site_id' => $param['site_id'],
                'title'   => '门店主页',
                'name'    => 'DIY_STORE_' . $param['store_id'],
                'type'    => 'store',
                'value'   => $value
            ];
            $res  = $diy_view_model->addSiteDiyView($data);

            return $res;

        }

    }

}