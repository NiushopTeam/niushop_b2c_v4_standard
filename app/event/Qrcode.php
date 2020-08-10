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

use app\model\system\Qrcode as QrcodeModel;

/**
 * 生成二维码
 * @author Administrator
 *
 */
class Qrcode
{
    public function handle($param)
    {
        if (in_array($param["app_type"], ['h5', 'all', 'wechat'])) {
            $param["app_type"] = 'h5';
            $qrcode            = new QrcodeModel();
            $res               = $qrcode->createQrcode($param);
            return $res;
        }
    }

}
