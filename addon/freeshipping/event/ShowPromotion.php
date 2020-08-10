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

namespace addon\freeshipping\event;

/**
 * 活动展示
 */
class ShowPromotion
{
    /**
     * 活动展示
     *
     * @return multitype:number unknown
     */
    public function handle()
    {
        $data = [
            'shop' => [
                [
                    //插件名称
                    'name'        => 'freeshipping',
                    //店铺端展示分类  shop:营销活动   member:互动营销
                    'show_type'   => 'shop',
                    //展示主题
                    'title'       => '满额包邮',
                    //展示介绍
                    'description' => '满额包邮',
                    //展示图标
                    'icon'        => 'addon/freeshipping/icon.png',
                    //跳转链接
                    'url'         => 'freeshipping://shop/freeshipping/lists',
                ]
            ]

        ];
        return $data;
    }
}