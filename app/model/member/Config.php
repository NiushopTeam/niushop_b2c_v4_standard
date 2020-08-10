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

namespace app\model\member;

use app\model\system\Document;
use app\model\system\Config as ConfigModel;
use app\model\BaseModel;

/**
 * 会员设置
 */
class Config extends BaseModel
{
    /**
     * 注册协议
     * @param unknown $site_id
     * @param unknown $name
     * @param unknown $value
     */
    public function setRegisterDocument($title, $content, $site_id, $app_module = 'shop')
    {
        $document = new Document();
        $res      = $document->setDocument($title, $content, [['site_id', '=', $site_id], ['app_module', '=', $app_module], ['document_key', '=', 'REGISTER_AGREEMENT']]);
        return $res;
    }

    /**
     * 查询注册协议
     * @param unknown $where
     * @param unknown $field
     * @param unknown $value
     */
    public function getRegisterDocument($site_id, $app_module = 'shop')
    {
        $document = new Document();
        $info     = $document->getDocument([['site_id', '=', $site_id], ['app_module', '=', $app_module], ['document_key', '=', 'REGISTER_AGREEMENT']]);
        return $info;
    }

    /**
     * 注册规则
     * array $data
     */
    public function setRegisterConfig($data, $site_id, $app_module = 'shop')
    {
        $config = new ConfigModel();
        $res    = $config->setConfig($data, '注册规则', 1, [['site_id', '=', $site_id], ['app_module', '=', $app_module], ['config_key', '=', 'REGISTER_CONFIG']]);
        return $res;
    }

    /**
     * 查询注册规则
     */
    public function getRegisterConfig($site_id, $app_module = 'shop')
    {
        $config = new ConfigModel();
        $res    = $config->getConfig([['site_id', '=', $site_id], ['app_module', '=', $app_module], ['config_key', '=', 'REGISTER_CONFIG']]);
        if (empty($res['data']['value'])) {
            //默认值设置
            $res['data']['value'] = [
                'is_enable'          => 1,
                'type'               => 'plain',
                'keyword'            => '',
                'pwd_len'            => 6,
                'pwd_complexity'     => '',
                'dynamic_code_login' => 0
            ];
        }
        return $res;
    }
}