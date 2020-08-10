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

namespace app\model\web;

use app\model\system\Config as ConfigModel;
use app\model\BaseModel;

/**
 * 网站系统性设置
 */
class Config extends BaseModel
{
    //缓存类型
    private $cache_list = [
        [
            'name' => '数据缓存',
            'desc' => '数据缓存',
            'key'  => 'content',
            'icon' => 'public/static/img/cache/data.png'
        ],
        [
            'name' => '数据表缓存',
            'desc' => '数据表缓存',
            'key'  => 'data_table_cache',
            'icon' => 'public/static/img/cache/data_table.png'
        ],
        [
            'name' => '模板缓存',
            'desc' => '模板缓存',
            'key'  => 'template_cache',
            'icon' => 'public/static/img/cache/template.png'
        ],
    ];

    /**
     * 验证码设置
     * array $data
     */
    public function setCaptchaConfig($data, $site_id = 1, $app_module = 'shop')
    {
        $config = new ConfigModel();
        $res    = $config->setConfig($data, '验证码设置', 1, [['site_id', '=', $site_id], ['app_module', '=', $app_module], ['config_key', '=', 'CAPTCHA_CONFIG']]);
        return $res;
    }

    /**
     * 查询验证码设置
     */
    public function getCaptchaConfig($site_id = 1, $app_module = 'shop')
    {
        $config = new ConfigModel();
        $res    = $config->getConfig([['site_id', '=', $site_id], ['app_module', '=', $app_module], ['config_key', '=', 'CAPTCHA_CONFIG']]);
        if (empty($res['data']['value'])) {
            $res['data']['value'] = [
                'shop_login' => 1
            ];
        }
        return $res;
    }

    /**
     * 默认图上传配置
     * array $data
     */
    public function setDefaultImg($data, $site_id = 0, $app_module = 'admin')
    {
        $config = new ConfigModel();
        $res    = $config->setConfig($data, '默认图设置', 1, [['site_id', '=', $site_id], ['app_module', '=', $app_module], ['config_key', '=', 'DEFAULT_IMAGE']]);
        return $res;
    }

    /**
     * 默认图查询上传配置
     */
    public function getDefaultImg($site_id, $app_model = 'shop')
    {
        $config = new ConfigModel();
        $res    = $config->getConfig([['site_id', '=', $site_id], ['app_module', '=', $app_model], ['config_key', '=', 'DEFAULT_IMAGE']]);
        if (empty($res['data']['value'])) {
            $res['data']['value'] = [
                "default_goods_img" => "upload/default/default_img/goods.png",
                "default_headimg"   => "upload/default/default_img/head.png"
            ];
        }
        return $res;
    }

    /**
     * 获取缓存类型
     */
    public function getCacheList()
    {
        return $this->cache_list;
    }

    public function setCopyright($data, $site_id = 1, $app_model = 'shop')
    {
        $config = new ConfigModel();
        $res    = $config->setConfig($data, '版权设置', 1, [['site_id', '=', $site_id], ['app_module', '=', $app_model], ['config_key', '=', 'COPYRIGHT']]);
        return $res;
    }

    /**
     * 获取版权信息
     * @return array
     */
    public function getCopyright($site_id = 1, $app_module = 'shop')
    {
        $config = new ConfigModel();
        $res    = $config->getConfig([['site_id', '=', $site_id], ['app_module', '=', $app_module], ['config_key', '=', 'COPYRIGHT']]);
        if (empty($res['data']['value'])) {
            $res['data']['value'] = [
                'logo'           => '',
                'company_name'   => '',
                'copyright_link' => '',
                'copyright_desc' => '',
                'icp'            => '',
                'gov_record'     => '',
                'gov_url'        => '',
            ];
        }
        return $res;
    }
}