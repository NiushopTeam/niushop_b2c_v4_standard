<?php
/**
 * Niushop商城系统 - 团队十年电商经验汇集巨献!
 * =========================================================
 * Copy right 2015-2025 上海牛之云网络科技有限公司, 保留所有权利。
 * ----------------------------------------------
 * 官方网址: http://www.niushop.com.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 任何企业和个人不允许对程序代码以任何形式任何目的再发布。
 * =========================================================
 */

namespace app\api\controller;

use app\model\web\DiyView as DiyViewModel;
use app\model\system\Config as ConfigModel;

/**
 * 自定义模板
 * @package app\api\controller
 */
class Diyview extends BaseApi
{

    /**
     * 基础信息
     */
    public function info()
    {
        $id   = isset($this->params['id']) ? $this->params['id'] : 0;
        $name = isset($this->params['name']) ? $this->params['name'] : '';// 门店主页name格式：DIY_STORE_stroe_id

        if (empty($id) && empty($name)) {
            return $this->response($this->error('', 'REQUEST_DIY_ID_NAME'));
        }
        $diy_view  = new DiyViewModel();
        $condition = [
            ['sdv.site_id', '=', $this->site_id]
        ];
        if (!empty($id)) {
            $condition[] = ['sdv.id', '=', $id];
        }
        if (!empty($name)) {
            $condition[] = ['sdv.name', '=', $name];
        }

        $info = $diy_view->getSiteDiyViewDetail($condition);
        return $this->response($info);
    }

    /**
     * 平台端底部导航
     * @return string
     */
    public function bottomNav()
    {
        $site_id = $this->site_id;
        if (empty($site_id)) {
            return $this->response($this->error('', 'REQUEST_SITE_ID'));
        }
        $diy_view = new DiyViewModel();
        $info     = $diy_view->getBottomNavConfig($site_id);
        return $this->response($info);
    }

    /**
     * 风格
     */
    public function style()
    {
        $site_id = $this->site_id;
        if (empty($site_id)) {
            return $this->response($this->error('', 'REQUEST_SITE_ID'));
        }
        $config_model = new ConfigModel();
        $res          = $config_model->getConfig([['site_id', '=', $this->site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'SHOP_STYLE_CONFIG']]);
        $style_theme  = empty($res['data']['value']) ? ['style_theme' => 'default'] : $res['data']['value'];
        return $this->response($this->success($style_theme));
    }
}