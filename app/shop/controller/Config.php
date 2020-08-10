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

use app\model\goods\Config as GoodsConfigModel;
use app\model\system\Pay;
use app\model\web\Config as ConfigModel;
use app\model\system\Api;
use extend\RSA;
use app\model\system\Upgrade;

/**
 * 设置 控制器
 */
class Config extends BaseShop
{
    public function copyright()
    {
        $upgrade_model = new Upgrade();
        $auth_info     = $upgrade_model->authInfo();

        $config_model = new ConfigModel();
        $copyright    = $config_model->getCopyright($this->site_id, $this->app_module);
        if (request()->isAjax()) {
            $logo = input('logo', '');
            $data = [
                'icp'        => input('icp', ''),
                'gov_record' => $gov_record = input('gov_record', ''),
                'gov_url'    => input('gov_url', ''),
                'logo' => '',
                'company_name' => '',
                'copyright_link' => '',
                'copyright_desc' => ''
            ];
            if ($auth_info['code'] >= 0) {
                $data['logo'] = $logo ?: $copyright['data']['value']['logo'];
                $data['company_name'] = input('company_name', '');
                $data['copyright_link'] = input('copyright_link', '');
                $data['copyright_desc'] = input('copyright_desc', '');
            }
            $this->addLog("修改版权配置");
            $res = $config_model->setCopyright($data, $this->site_id, $this->app_module);
            return $res;
        }
        $this->assign('is_auth', ($auth_info['code'] >= 0 ? 1 : 0));
        $this->assign('copyright_config', $copyright['data']['value']);
        return $this->fetch('config/copyright');
    }

    /**
     * 支付管理
     */
    public function pay()
    {
        if (request()->isAjax()) {
            $pay_model = new Pay();
            $list      = $pay_model->getPayType([]);
            return $list;
        } else {
            return $this->fetch('config/pay');
        }
    }

    /**
     * 默认图设置
     */
    public function defaultPicture()
    {
        $upload_config_model = new ConfigModel();
        if (request()->isAjax()) {
            $data = array(
                "default_goods_img" => input("default_goods_img", ""),
                "default_headimg"   => input("default_headimg", ""),
            );
            $this->addLog("修改默认图配置");
            $res = $upload_config_model->setDefaultImg($data, $this->site_id, $this->app_module);

            return $res;
        } else {
            $upload_config_result = $upload_config_model->getDefaultImg($this->site_id, $this->app_module);
            $this->assign("default_img", $upload_config_result['data']['value']);
            return $this->fetch('config/default_picture');
        }
    }

    /*
     * 售后保障
     */
    public function aftersale()
    {
        $goods_config_model = new GoodsConfigModel();
        if (request()->isAjax()) {
            $content = input('content', '');//售后保障协议
            return $goods_config_model->setAfterSaleConfig('售后保障协议', $content, $this->site_id);
        } else {
            $content = $goods_config_model->getAfterSaleConfig($this->site_id);
            $this->assign('content', $content);
            return $this->fetch('config/aftersale');
        }
    }

    /**
     * 验证码设置
     */
    public function captcha()
    {
        $config_model = new ConfigModel();
        if (request()->isAjax()) {
            $data = [
                'shop_login' => input('shop_login', 0),//后台登陆验证码是否启用 1：启用 0：不启用
            ];
            return $config_model->setCaptchaConfig($data);
        } else {
            $config_info = $config_model->getCaptchaConfig();
            $this->assign('config_info', $config_info['data']['value']);
            return $this->fetch('config/captcha');
        }
    }

    /**
     * api安全
     */
    public function api()
    {
        $api_model = new Api();
        if (request()->isAjax()) {
            $is_use      = input("is_use", 0);
            $public_key  = input("public_key", "");
            $private_key = input("private_key", "");
            $data        = array(
                "public_key"  => $public_key,
                "private_key" => $private_key,
            );
            $result      = $api_model->setApiConfig($data, $is_use);
            return $result;
        } else {
            $config_result = $api_model->getApiConfig();
            $config        = $config_result["data"];
            $this->assign("config", $config);
            return $this->fetch('config/api');
        }
    }

    public function generateRSA()
    {
        if (request()->isAjax()) {
            return RSA::getSecretKey();
        }
    }
}