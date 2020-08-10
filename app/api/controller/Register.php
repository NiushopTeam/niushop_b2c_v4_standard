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

use app\model\member\Config;
use app\model\member\Register as RegisterModel;

class Register extends BaseApi
{
    /**
     * 注册设置
     */
    public function config()
    {
        $register = new Config();
        $info     = $register->getRegisterConfig($this->site_id, 'shop');
        return $this->response($info);
    }

    /**
     * 注册协议
     */
    public function aggrement()
    {
        $register = new Config();
        $info     = $register->getRegisterDocument($this->site_id, 'shop');
        return $this->response($info);
    }

    /**
     * 用户名密码注册
     */
    public function username()
    {
        $register = new RegisterModel();
        $exist    = $register->usernameExist($this->params['username'], $this->site_id);
        if ($exist) {
            return $this->response($this->error("", "用户名已存在"));
        } else {
            // 校验验证码
            $captcha   = new Captcha();
            $check_res = $captcha->checkCaptcha();
            if ($check_res['code'] < 0) return $this->response($check_res);

            $res = $register->usernameRegister($this->params);
            //生成access_token
            if ($res['code'] >= 0) {
                $token = $this->createToken($res['data']);
                return $this->response($this->success(['token' => $token]));
            }
            return $this->response($res);
        }

    }

    /**
     * 检测存在性
     */
    public function exist()
    {
        $type     = $this->params['type'];
        $register = new RegisterModel();
        switch ($type) {
            case "username" :
                $res = $register->usernameExist($this->params['username'], $this->site_id);
                break;
            case "mobile" :
                $res = $register->mobileExist($this->params['mobile'], $this->site_id);
                break;
            default:
                $res = 0;
                break;
        }
        if ($res) {
            return $this->response($this->error("", "账户已存在"));
        } else {
            return $this->response($this->success());
        }
    }

}