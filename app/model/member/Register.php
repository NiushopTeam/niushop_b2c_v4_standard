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

use addon\wechat\model\Message as WechatMessage;
use app\model\BaseModel;
use app\model\message\Sms;

/**
 * 登录
 *
 * @author Administrator
 *
 */
class Register extends BaseModel
{

    /**
     * 用户名密码注册(必传username， password),之前检测重复性,判断用户名是否为手机，邮箱
     * @param $data
     * @return array|mixed
     */
    public function usernameRegister($data)
    {
        $config      = new Config();
        $config_info = $config->getRegisterConfig($data['site_id'], 'shop');
        if ($config_info['data']['value']['is_enable'] == 1) {
            $this->cancelBind($data);
            $member_level      = new MemberLevel();
            $member_level_info = $member_level->getMemberLevelInfo([['is_default', '=', 1], ['site_id', '=', $data['site_id']]], 'level_id,level_name');
            $member_level_info = $member_level_info['data'];

            if (isset($data['source_member']) && !empty($data['source_member'])) {
                $count = model("member")->getCount([['member_id', '=', $data['source_member']], ['site_id', '=', $data['site_id']]]);
                if (!$count) $data['source_member'] = 0;
            }

            $data_reg = [
                'site_id'           => $data['site_id'],
                'source_member'     => isset($data['source_member']) ? $data['source_member'] : 0,
                'username'          => $data['username'],
                'nickname'          => $data['username'], //默认昵称为用户名
                'password'          => data_md5($data['password']),
                'qq_openid'         => isset($data['qq_openid']) ? $data['qq_openid'] : '',
                'wx_openid'         => isset($data['wx_openid']) ? $data['wx_openid'] : '',
                'weapp_openid'      => isset($data['weapp_openid']) ? $data['weapp_openid'] : '',
                'wx_unionid'        => isset($data['wx_unionid']) ? $data['wx_unionid'] : '',
                'ali_openid'        => isset($data['ali_openid']) ? $data['ali_openid'] : '',
                'baidu_openid'      => isset($data['baidu_openid']) ? $data['baidu_openid'] : '',
                'toutiao_openid'    => isset($data['toutiao_openid']) ? $data['toutiao_openid'] : '',
                'headimg'           => isset($data['headimg']) ? $data['headimg'] : '',
                'member_level'      => $member_level_info['level_id'],
                'member_level_name' => $member_level_info['level_name'],
                'reg_time'          => time(),
                'login_time'        => time(),
                'last_login_time'   => time()
            ];
            $res      = model("member")->add($data_reg);
            if ($res) {
                //会员注册事件
                event("MemberRegister", ['member_id' => $res, 'site_id' => $data['site_id']]);
                $data['member_id'] = $res;
                $this->pullHeadimg($data);
                return $this->success($res);
            } else {
                return $this->error();
            }
        } else {
            return $this->error('', "注册拒绝");
        }
    }

    /**
     * 手机号密码注册(必传mobile， password),之前检测重复性
     * @param $data
     * @return array|mixed
     */
    public function mobileRegister($data)
    {
        $config      = new Config();
        $config_info = $config->getRegisterConfig($data['site_id'], 'shop');

        if ($config_info['data']['value']['is_enable'] == 1) {
            $this->cancelBind($data);
            $member_level      = new MemberLevel();
            $member_level_info = $member_level->getMemberLevelInfo([['is_default', '=', 1], ['site_id', '=', $data['site_id']]], 'level_id,level_name');
            $member_level_info = $member_level_info['data'];

            if (isset($data['source_member']) && !empty($data['source_member'])) {
                $count = model("member")->getCount([['member_id', '=', $data['source_member']], ['site_id', '=', $data['site_id']]]);
                if (!$count) $data['source_member'] = 0;
            }

            $data_reg = [
                'site_id'           => $data['site_id'],
                'source_member'     => isset($data['source_member']) ? $data['source_member'] : 0,
                'mobile'            => $data['mobile'],
                'nickname'          => $data['mobile'], //默认昵称为手机号
                'password'          => '',
                'qq_openid'         => isset($data['qq_openid']) ? $data['qq_openid'] : '',
                'wx_openid'         => isset($data['wx_openid']) ? $data['wx_openid'] : '',
                'weapp_openid'      => isset($data['weapp_openid']) ? $data['weapp_openid'] : '',
                'wx_unionid'        => isset($data['wx_unionid']) ? $data['wx_unionid'] : '',
                'ali_openid'        => isset($data['ali_openid']) ? $data['ali_openid'] : '',
                'baidu_openid'      => isset($data['baidu_openid']) ? $data['baidu_openid'] : '',
                'toutiao_openid'    => isset($data['toutiao_openid']) ? $data['toutiao_openid'] : '',
                'headimg'           => isset($data['headimg']) ? $data['headimg'] : '',
                'member_level'      => $member_level_info['level_id'],
                'member_level_name' => $member_level_info['level_name'],
                'reg_time'          => time(),
                'login_time'        => time(),
                'last_login_time'   => time()
            ];
            $res      = model("member")->add($data_reg);
            if ($res) {

                //会员注册事件
                event("MemberRegister", ['member_id' => $res, 'site_id' => $data['site_id']]);
                $data['member_id'] = $res;
                $this->pullHeadimg($data);
                return $this->success($res);
            } else {
                return $this->error();
            }
        } else {
            return $this->error('', "REGISTER_REFUND");
        }
    }

    /**
     * 清除账号绑定(用户重新进行绑定)
     * @param $data
     * @return array
     */
    public function cancelBind($data)
    {
        $data = [
            'qq_openid'      => isset($data['qq_openid']) ? $data['qq_openid'] : '',
            'wx_openid'      => isset($data['wx_openid']) ? $data['wx_openid'] : '',
            'weapp_openid'   => isset($data['weapp_openid']) ? $data['weapp_openid'] : '',
            'wx_unionid'     => isset($data['wx_unionid']) ? $data['wx_unionid'] : '',
            'ali_openid'     => isset($data['ali_openid']) ? $data['ali_openid'] : '',
            'baidu_openid'   => isset($data['baidu_openid']) ? $data['baidu_openid'] : '',
            'toutiao_openid' => isset($data['toutiao_openid']) ? $data['toutiao_openid'] : '',
            'site_id'        => $data['site_id']
        ];
        if (!empty($data['qq_openid'])) {
            model("member")->update(['qq_openid' => ''], [['qq_openid', '=', $data['qq_openid']], ['site_id', '=', $data['site_id']]]);
        }
        if (!empty($data['wx_openid'])) {
            model("member")->update(['wx_openid' => ''], [['wx_openid', '=', $data['wx_openid']], ['site_id', '=', $data['site_id']]]);
        }
        if (!empty($data['weapp_openid'])) {
            model("member")->update(['weapp_openid' => ''], [['weapp_openid', '=', $data['weapp_openid']], ['site_id', '=', $data['site_id']]]);
        }
        if (!empty($data['wx_unionid'])) {
            model("member")->update(['wx_unionid' => ''], [['wx_unionid', '=', $data['wx_unionid']], ['site_id', '=', $data['site_id']]]);
        }
        if (!empty($data['ali_openid'])) {
            model("member")->update(['ali_openid' => ''], [['ali_openid', '=', $data['ali_openid']], ['site_id', '=', $data['site_id']]]);
        }
        if (!empty($data['baidu_openid'])) {
            model("member")->update(['baidu_openid' => ''], [['baidu_openid', '=', $data['baidu_openid']], ['site_id', '=', $data['site_id']]]);
        }
        if (!empty($data['toutiao_openid'])) {
            model("member")->update(['toutiao_openid' => ''], [['toutiao_openid', '=', $data['toutiao_openid']], ['site_id', '=', $data['site_id']]]);
        }
        return $this->success();

    }

    /**
     * 检测用户存在性(用户名)
     * @param $username
     * @return int
     */
    public function usernameExist($username, $site_id)
    {
        $member_info = model("member")->getInfo([['username', '=', $username], ['site_id', '=', $site_id]], 'member_id');
        if (!empty($member_info)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 检测用户存在性(用户名) 存在返回1
     * @param $mobile
     * @return int
     */
    public function mobileExist($mobile, $site_id)
    {
        $member_info = model("member")->getInfo([['mobile', '=', $mobile], ['site_id', '=', $site_id]], 'member_id');
        if (!empty($member_info)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 注册发送验证码
     * @param $data
     * @return array|mixed|void
     */
    public function registerCode($data)
    {
        //发送短信
        $sms_model           = new Sms();
        $var_parse           = array(
            "code" => $data["code"],//验证码
        );
        $data["sms_account"] = $data["mobile"] ?? '';//手机号
        $data["var_parse"]   = $var_parse;
        $sms_result          = $sms_model->sendMessage($data);
        if ($sms_result["code"] < 0)
            return $sms_result;

        return $this->success();
    }

    /**
     * 注册发送验证码
     * @param $data
     * @return array|mixed|void
     */
    public function registerSuccess($data)
    {

        $member_model       = new Member();
        $member_info_result = $member_model->getMemberInfo([["member_id", "=", $data["member_id"]]], "username,mobile,email,reg_time,wx_openid,last_login_type");
        $member_info        = $member_info_result["data"];
        //发送短信
        $var_parse           = [
            "shopname" => replaceSpecialChar($data['site_name']),   //商城名称
            "username" => replaceSpecialChar($member_info["username"]),    //会员名称
        ];
        $data["sms_account"] = $member_info["mobile"] ?? '';//手机号
        $data["var_parse"]   = $var_parse;
        $sms_model           = new Sms();
        $sms_result          = $sms_model->sendMessage($data);
//        if ($sms_result["code"] < 0) return $sms_result;

        //发送模板消息
        $wechat_model   = new WechatMessage();
        $data["openid"] = $member_info["wx_openid"];

        $data["template_data"] = [
            'keyword1' => $member_info["username"],
            'keyword2' => time_to_date($member_info["reg_time"]),
        ];
        $data["page"]          = '';
        $wechat_model->sendMessage($data);

        return $this->success();
    }

    /**
     * 拉取用户头像
     * @param unknown $info
     */
    private function pullHeadimg($data)
    {
        if (!empty($data['headimg']) && is_url($data['headimg'])) {
            $url = __ROOT__ . '/api/member/pullhaedimg?member_id=' . $data['member_id'];
            http($url, 1);
        }
    }
}