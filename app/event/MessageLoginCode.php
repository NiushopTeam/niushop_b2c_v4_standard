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

namespace app\event;

use app\model\member\Login;


/**
 * 登录发送验证码
 */
class MessageLoginCode
{

    public function handle($param)
    {
        //发送订单消息
        if ($param["keywords"] == "LOGIN_CODE") {
            $login_model = new Login();
            $result      = $login_model->loginCode($param);
            return $result;
        }
    }
}