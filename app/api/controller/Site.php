<?php
/**
 * Index.php
 * Niushop商城系统 - 团队十年电商经验汇集巨献!
 * =========================================================
 * Copy right 2015-2025 上海牛之云网络科技有限公司, 保留所有权利。
 * ----------------------------------------------
 * 官方网址: http://www.niushop.com.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 任何企业和个人不允许对程序代码以任何形式任何目的再发布。
 * =========================================================
 * @author : niuteam
 * @date : 2015.1.17
 * @version : v1.0.0.0
 */

namespace app\api\controller;

use app\model\system\Site as SiteModel;

/**
 * 店铺
 * @author Administrator
 *
 */
class Site extends BaseApi
{

    /**
     * 基础信息
     */
    public function info()
    {
        $field                       = 'site_id,site_domain,site_name';
        $website_model               = new SiteModel();
        $info                        = $website_model->getSiteInfo([['site_id', '=', $this->site_id]], $field);
        $info['data']['shop_status'] = 1;
        return $this->response($info);
    }

    /**
     * 是否显示店铺相关功能，用于审核小程序
     */
    public function isShow()
    {
        $res = 1;// 0 隐藏，1 显示
        return $this->response($this->success($res));
    }

}