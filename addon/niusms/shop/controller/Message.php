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

namespace addon\niusms\shop\controller;

use addon\niusms\model\Config as ConfigModel;
use addon\niusms\model\Sms as SmsModel;
use addon\niusms\model\Template;
use app\model\message\Message as MessageModel;
use app\model\message\MessageTemplate as MessageTemplateModel;
use app\shop\controller\BaseShop;

/**
 * 牛云短信消息管理
 */
class Message extends BaseShop
{

    /**
     * 编辑模板消息
     * @return array|mixed|string
     */
    public function edit()
    {
        $message_model = new Template();
        $sms_model = new SmsModel();
        $keywords = input("keywords", "");

        $info_result = $message_model->getTemplateInfo($this->site_id, $keywords);
        $info = $info_result[ "data" ];
        if (request()->isAjax()) {
            if (empty($info))
                return error("", "不存在的模板信息!");
        } else {
            if (empty($info))
                $this->error("不存在的模板信息!");

            $audit_status = $sms_model->getAuditStatus();
            $info[ 'audit_status_name' ] = $audit_status[ $info[ 'audit_status' ] ];

            $this->assign("info", $info);
            $this->assign("keywords", $keywords);

            $this->assign('sms_is_open', $info[ 'sms_is_open' ]);

            $template_info = $this->queryTemplate($info[ 'tem_id' ]);
            $this->assign('template_info', $template_info);
            return $this->fetch('message/edit');
        }
    }


    /**
     * 查询短信模板审核状态
     * @return mixed
     */
    public function queryTemplate($tem_id = '')
    {
        $sms_model = new SmsModel();
        $config_model = new ConfigModel();
        $sms_config = $config_model->getSmsConfig($this->site_id, $this->app_module);
        $sms_config = $sms_config[ 'data' ][ 'value' ];
        $tKey = time();
        $data = [
            'username' => $sms_config[ 'username' ],
            'password' => md5(md5($sms_config[ 'password' ]) . $tKey),
            'tKey' => $tKey,
            'sign' => input('signature', $sms_config[ 'signature' ]),//短信签名
            'temId' => input('tem_id', $tem_id)//模板id
        ];
        $res = $sms_model->queryTemplate($data);
        return $res;
    }

}