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

namespace addon\wechat\api\controller;

use addon\wechat\model\Wechat as WechatModel;
use app\api\controller\BaseApi;
use app\model\system\Site;
use think\facade\Cache;
use addon\wechat\model\Config as ConfigModel;

class Wechat extends BaseApi
{

    /**
     * 获取openid
     */
    public function authCodeToOpenid()
    {
        $weapp_model = new WechatModel($this->site_id);
        $res         = $weapp_model->getAuthByCode($this->params);
        return $this->response($res);
    }

    /**
     * 获取网页授权code
     */
    public function authcode()
    {
        $redirect_url = $this->params['redirect_url'] ?? '';
        $weapp_model  = new WechatModel($this->site_id);
        $res          = $weapp_model->getAuthCode($redirect_url);
        return $this->response($res);
    }

    /**
     * 获取jssdk配置
     */
    public function jssdkConfig()
    {
        $url         = $this->params['url'] ?? '';
        $weapp_model = new WechatModel($this->site_id);
        $res         = $weapp_model->getJssdkConfig($url);
        return $this->response($res);
    }

    /**
     * 分享设置
     */
    public function share()
    {
        $data = [];

        $url          = $this->params['url'] ?? '';
        $weapp_model  = new WechatModel($this->site_id);
        $jssdk_config = $weapp_model->getJssdkConfig($url);
        if ($jssdk_config['code'] < 0) return $this->response($jssdk_config);
        $data['jssdk_config'] = $jssdk_config['data'];

        $config_model = new ConfigModel();
        $share_config = $config_model->getShareConfig($this->site_id);
        $share_config = $share_config['data']['value'];

        $site_model = new Site();
        $shop_info  = $site_model->getSiteInfo([['site_id', '=', $this->site_id]], 'site_name,logo');

        $share_config['site_name'] = $shop_info['data']['site_name'];
        $share_config['site_logo'] = $shop_info['data']['logo'];
        $data['share_config']      = $share_config;

        return $this->response($this->success($data));
    }

    /**
     * 绑定店铺openid
     * @return false|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function shopBindOpenid()
    {
        $key         = $this->params["key"];
        $weapp_model = new WechatModel($this->site_id);
        $res         = $weapp_model->authCodeToOpenid($this->params);
        if ($res["code"] >= 0) {
            Cache::set($key, $res["data"]["openid"]);
            return $this->response($res);
        }

    }
}