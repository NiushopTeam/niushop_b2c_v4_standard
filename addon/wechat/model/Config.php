<?php
// +---------------------------------------------------------------------+
// | NiuCloud | [ WE CAN DO IT JUST NiuCloud ]                |
// +---------------------------------------------------------------------+
// | Copy right 2019-2029 www.niucloud.com                          |
// +---------------------------------------------------------------------+
// | Author | NiuCloud <niucloud@outlook.com>                       |
// +---------------------------------------------------------------------+
// | Repository | https://github.com/niucloud/framework.git          |
// +---------------------------------------------------------------------+

namespace addon\wechat\model;

use app\model\system\Config as ConfigModel;
use app\model\BaseModel;

/**
 * 微信公众号配置
 */
class Config extends BaseModel
{
    /******************************************************************** 微信公众号配置 start ****************************************************************************/
    /**
     * 设置微信公众号配置
     * @return multitype:string mixed
     */
    public function setWechatConfig($data, $is_use, $site_id)
    {
        $config = new ConfigModel();
        $res    = $config->setConfig($data, '微信公众号设置', $is_use, [['site_id', '=', $site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'WECHAT_CONFIG']]);
        return $res;
    }

    /**
     * 获取微信公众号配置信息
     * @return multitype:string mixed
     */
    public function getWechatConfig($site_id)
    {
        $config = new ConfigModel();
        $res    = $config->getConfig([['site_id', '=', $site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'WECHAT_CONFIG']]);
        return $res;
    }
    /******************************************************************** 微信公众号配置 end ****************************************************************************/


    /******************************************************************** 分享内容配置 start ****************************************************************************/
    /**
     * 设置分享内容
     * @return multitype:string mixed
     */
    public function setShareConfig($data, $is_use, $site_id)
    {
        $config = new ConfigModel();
        $res    = $config->setConfig($data, '分享内容设置', $is_use, [['site_id', '=', $site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'WECHAT_SHARE_CONFIG']]);
        return $res;
    }

    /**
     * 获取分享内容
     * @return multitype:string mixed
     */
    public function getShareConfig($site_id)
    {
        $config = new ConfigModel();
        $res    = $config->getConfig([['site_id', '=', $site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'WECHAT_SHARE_CONFIG']]);
        return $res;
    }
    /******************************************************************** 分享内容配置 end ****************************************************************************/

}