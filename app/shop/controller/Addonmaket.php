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

namespace app\shop\controller;

use app\model\system\AddonQuick;
use app\model\system\Upgrade as UpgradeModel;

class Addonmaket extends BaseShop
{

    /**
     * 插件市场
     */
    public function addonMarket()
    {
        //获取官网所有插件
        $upgrade_model = new UpgradeModel();
        $list_result = $upgrade_model->getPluginGoodsList();
        $list = $list_result['data']['data'];

        $addon_quick_model = new AddonQuick();
        if(!empty($list)){
            foreach ($list as $k => $v) {

                //判断是否已设置快捷
                $addon_quick_info = $addon_quick_model->getAddonQuickModeInfo([ ['name', '=', $v['code']] ], 'id');
                if (empty($addon_quick_info['data'])) {
                    $list[$k]['is_quick'] = 0;
                } else {
                    $list[$k]['id'] = $addon_quick_info['data']['id'];
                    $list[$k]['is_quick'] = 1;
                }
            }
        }

        $sort_key = array_column($list,'is_quick');
        array_multisort($sort_key,SORT_DESC,$list);
        $this->assign('list',$list);

        $this->forthMenu();
        return $this->fetch('addonmaket/addon_market');
    }

    /**
     * 添加快捷方式
     */
    public function addAddonQuick()
    {
        if (request()->isAjax()) {

            $addon_quick_model = new AddonQuick();
            $data = [
                'name' => input('name',''),
                'package_name' => input('package_name',''),
                'type' => input('type',''),
                'icon' => input('icon',''),
                'title' => input('title',''),
                'description' => input('description',''),
                'author' => input('author',''),
                'version' => input('version',''),
                'version_no' => input('version_no',''),
                'content' => input('content',''),
            ];

            $res = $addon_quick_model->addAddonQuickMode($data);
            return $res;
        }
    }

    /**
     * 删除快捷方式
     */
    public function deleteAddonQuickMode()
    {
        if (request()->isAjax()) {

            $id = input('id');
            $condition[] = [ 'id', '=', $id ];

            $addon_quick_model = new AddonQuick();
            $res = $addon_quick_model->deleteAddonQuickMode($condition);
            return $res;
        }
    }



}