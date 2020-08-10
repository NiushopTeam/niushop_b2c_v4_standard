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

namespace addon\niusms\model;

use app\model\BaseModel;
use addon\niusms\model\Config as ConfigModel;

/**
 * 牛云短信
 */
class Sms extends BaseModel
{
    private $api = "https://www.niushop.com.cn/api";

    // 审核状态（0 未审核，1 待审核，2 审核通过， 3 审核不通过）
    private $audit_status = [
        '未审核',
        '待审核',
        '审核通过',
        '审核不通过'
    ];

    public function getAuditStatus()
    {
        return $this->audit_status;
    }

    /**
     * 查询短信签名报备状态
     * @param $data
     * @return mixed
     */
    public function querySignature($data)
    {
        $url = 'https://api.mix2.zthysms.com/sms/v1/sign/query';
        $res = $this->sendSms($url, $data);
        return $res;
    }

    /**
     * 创建子账号
     * @param $data
     * @param $site_id
     * @param $app_module
     * @return array|mixed
     */
    public function createChildAccount($data, $site_id, $app_module)
    {
        $url = $this->api . '/sms/createChildAccount';
        $res = $this->httpPost($url, $data);
        if ($res[ 'code' ] == 0) {
            $config_model = new ConfigModel();
            $data[ 'signature' ] = "";
            $config_model->setSmsConfig($data, 1, $site_id, $app_module);
        }
        return $res;
    }

    /**
     * 子账号添加短信签名
     * @param $data
     * @param $site_id
     * @param $app_module
     * @return array|mixed
     */
    public function addChildSignature($data, $site_id, $app_module)
    {
        $url = $this->api . '/sms/addChildSignature';
        $res = $this->httpPost($url, $data);
        if ($res[ 'code' ] == 0) {
            $config_model = new ConfigModel();
            $sms_config = $config_model->getSmsConfig($site_id, $app_module);
            $sms_config = $sms_config[ 'data' ][ 'value' ];
            if (!empty($sms_config)) {
                $save_data = $sms_config;
            } else {
                $save_data = $data;
            }
            $save_data[ 'signature' ] = "【" . $data[ 'signature' ] . "】";
            $config_model->setSmsConfig($save_data, 1, $site_id, $app_module);
        }
        return $res;
    }

    /**
     * 查询子账号信息
     */
    public function getChildAccountInfo($data)
    {
        $url = $this->api . '/sms/getChildAccountInfo';
        $res = $this->httpPost($url, $data);
        return $res;
    }

    /**
     * 获取短信套餐包
     * @return mixed
     */
    public function getSmsPackageList()
    {
        $url = $this->api . '/sms/getSmsPackageList';
        $res = $this->httpPost($url, '');
        return $res;
    }

    /******************************************** 短信模板 start ********************************************************************/

    /**
     * 设置短信模板开启
     * @param $template_id
     * @param $status
     * @param $sms_config
     * @return array|mixed
     */
    public function enableTemplate($template_id, $status, $sms_config)
    {
        //获取模板信息
        $template_info = model('sms_template')->getInfo([ [ 'template_id', '=', $template_id ] ]);
        if (empty($template_info)) {
            return $this->error('', '模板不存在');
        }

        if (empty($sms_config)) {
            return $this->error('', '账号不存在，请先注册牛云短信账号');
        }

        model('sms_template')->startTrans();
        try {

            if ($template_info[ 'tem_id' ] == 0 || ( $template_info[ 'audit_status' ] != 0 && $template_info[ 'audit_status' ] != 2 )) {

                $data = [
                    'username' => $sms_config[ 'username' ],
                    'signature' => $sms_config[ 'signature' ],
                    'template_name' => $template_info[ 'template_name' ],
                    'template_type' => $template_info[ 'template_type' ],
                    'template_content' => $template_info[ 'template_content' ],
                    'param_json' => $template_info[ 'param_json' ]
                ];
                $url = $this->api . '/sms/addChildSmsTemplate';
                $res = $this->httpPost($url, $data);
                if ($res[ 'code' ] < 0) {
                    model('sms_template')->rollback();
                    return $res;
                }

                //修改状态
                $template_data = [
                    'tem_id' => $res[ 'data' ][ 'temId' ],
                    'status' => $status,
                    'audit_status' => 1,
                    'update_time' => time()
                ];

            } else {
                //修改状态
                $template_data = [
                    'status' => $status,
                    'update_time' => time()
                ];
            }
            model('sms_template')->update($template_data, [ [ 'template_id', '=', $template_id ] ]);

            //判断message信息是否存在
            $message_info = model('message')->getInfo([ [ 'keywords', '=', $template_info[ 'keywords' ] ] ]);
            if ($message_info) {
                $message_data = [
                    'sms_is_open' => $status
                ];
                model('message')->update($message_data, [ [ 'keywords', '=', $template_info[ 'keywords' ] ] ]);
            } else {
                $message_data = [
                    'site_id' => 1,
                    'keywords' => $template_info[ 'keywords' ],
                    'sms_is_open' => $status
                ];
                model('message')->add($message_data);
            }

            model('sms_template')->commit();
            return $this->success();
        } catch (\Exception $e) {

            model('sms_template')->rollback();
            return $this->error('', $e->getMessage());
        }

    }

    /**
     * 设置短信模板关闭
     * @param $template_ids
     * @param $status
     * @param $sms_config
     * @return array|mixed
     */
    public function disableTemplate($template_ids, $status, $sms_config)
    {
        //获取模板信息
        $template_list = model('sms_template')->getList([ [ 'template_id', 'in', $template_ids ] ]);
        if (empty($template_list)) {
            return $this->error('', '模板不存在');
        }

        if (empty($sms_config)) {
            return $this->error('', '账号不存在，请先注册牛云短信账号');
        }

        model('sms_template')->startTrans();
        try {

            //修改状态
            $template_data = [
                'status' => $status,
                'update_time' => time()
            ];

            model('sms_template')->update($template_data, [ [ 'template_id', 'in', $template_ids ] ]);

            foreach ($template_list as $k => $v) {
                //判断message信息是否存在
                $message_info = model('message')->getInfo([ [ 'keywords', '=', $v[ 'keywords' ] ] ]);
                if ($message_info) {
                    $message_data = [
                        'sms_is_open' => $status
                    ];
                    model('message')->update($message_data, [ [ 'keywords', '=', $v[ 'keywords' ] ] ]);
                } else {
                    $message_data = [
                        'site_id' => 1,
                        'keywords' => $v[ 'keywords' ],
                        'sms_is_open' => $status
                    ];
                    model('message')->add($message_data);
                }
            }

            model('sms_template')->commit();
            return $this->success();
        } catch (\Exception $e) {

            model('sms_template')->rollback();
            return $this->error('', $e->getMessage());
        }

    }

    /**
     * 修改审核状态
     * @param $audit_status
     * @param $template_id
     * @return array
     */
    public function modifyAuditStatus($audit_status, $template_id)
    {
        $res = model('sms_template')->update([ 'audit_status' => $audit_status ], [ [ 'template_id', '=', $template_id ] ]);
        return $this->success($res);
    }

    /**
     * 查询短信模板审核状态
     * @param $data
     * @return array|mixed
     */
    public function queryTemplate($data)
    {
        $url = 'https://api.mix2.zthysms.com/sms/v2/template/query';
        $res = $this->sendSms($url, $data);
        return $res;
    }

    /**
     * 获取短信模板分页列表
     * @param array $condition
     * @param int $page
     * @param int $page_size
     * @param string $order
     * @param string $field
     * @return array
     */
    public function getSmsTemplatePageList($condition = [], $page = 1, $page_size = PAGE_LIST_ROWS, $order = 'template_id desc', $field = '*')
    {
        $list = model('sms_template')->pageList($condition, $field, $order, $page, $page_size);
        return $this->success($list);
    }

    /**
     * 发送短信
     * @param $params
     */
    public function send($params)
    {
        $config_model = new ConfigModel();
        $sms_config = $config_model->getSmsConfig($params[ 'site_id' ], 'shop');
        $sms_config = $sms_config[ 'data' ];
        if ($sms_config[ 'is_use' ]) {
            $config = $sms_config[ 'value' ];
            if (empty($config)) return $this->error([], "牛云短信尚未配置");

            $sms_info = $params[ "message_info" ][ "sms_json_array" ];//消息类型模板 短信模板信息
            if (empty($sms_info)) return $this->error([], "消息模板尚未配置");
            if ($sms_info['audit_status'] != 2)  return $this->error([], "消息模板尚未审核通过");

            $time = time();
            $data = [
                'username' => $config[ 'username' ],
                'password' => md5(md5($config[ 'password' ]) . $time),
                'signature' => $config[ 'signature' ],
                'tKey' => $time,
                'tpId' => $sms_info[ 'tem_id' ],
                'records' => [
                    'mobile' => $params[ 'sms_account' ],
                    'tpContent' => $params[ 'var_parse' ]
                ]
            ];
            $result = $this->sendSms('https://api.mix2.zthysms.com/v2/sendSmsTp', $data);
            if ($result[ 'code' ] == 200) {
                return $this->success([ "addon" => "niusms", "addon_name" => "牛云短信", "content" => $sms_info[ "template_content" ] ]);
            } else {
                return $this->error([], $result[ 'msg' ]);
            }
        }
    }

    /******************************************** 短信模板 end ********************************************************************/

    /**
     * 数据请求
     * @param $url
     * @param $data
     * @return mixed
     */
    public function httpPost($url, $data)
    {

        // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER[ 'HTTP_USER_AGENT' ]); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HEADER, false); //开启header
        curl_setopt($curl, CURLOPT_HTTPHEADER, array (
            'Content-Type: application/json; charset=utf-8',
        )); //类型为json
        //类型为json
        $result = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            return $this->error('', '系统错误，请联系平台进行处理');
        }
        curl_close($curl); // 关键CURL会话
        $return = json_decode($result, true); // 返回数据
        if (!empty($return)) {
            if ($return[ 'code' ] < 0) {
                return $this->error($return[ 'code' ], $return[ 'message' ]);
            } else {
                return $this->success($return[ 'data' ]);
            }
        } else {
            return $result;
        }
    }

    public function sendSms($url, $data)
    {
        // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER[ 'HTTP_USER_AGENT' ]); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HEADER, false); //开启header
        curl_setopt($curl, CURLOPT_HTTPHEADER, array (
            'Content-Type: application/json; charset=utf-8',
        )); //类型为json
        //类型为json
        $result = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            return $this->error('', '系统错误，请联系平台进行处理');
        }
        curl_close($curl); // 关键CURL会话
        return json_decode($result, true); // 返回数据
    }

}
