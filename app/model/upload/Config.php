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

namespace app\model\upload;

use app\model\system\Config as ConfigModel;
use app\model\BaseModel;

/**
 * 上传设置
 */
class Config extends BaseModel
{
    /**
     * 上传配置
     * array $data
     */
    public function setUploadConfig($data, $site_id = 1, $app_module = 'shop')
    {
        $config = new ConfigModel();
        $res    = $config->setConfig($data, '上传设置', 1, [['site_id', '=', $site_id], ['app_module', '=', $app_module], ['config_key', '=', 'UPLOAD_CONFIG']]);
        return $res;
    }

    /**
     * 查询上传配置
     */
    public function getUploadConfig($site_id = 1, $app_module = 'shop')
    {
        $config = new ConfigModel();
        $res    = $config->getConfig([['site_id', '=', $site_id], ['app_module', '=', $app_module], ['config_key', '=', 'UPLOAD_CONFIG']]);
        if (empty($res['data']['value'])) {
            $res['data']['value'] =
                [
                    "upload" => [
                        "max_filesize"     => "0",
                        "image_allow_ext"  => "",
                        "image_allow_mime" => ""
                    ],
                    "thumb"  => [
                        "thumb_position"     => "top",
                        "thumb_big_width"    => "700",
                        "thumb_big_height"   => "700",
                        "thumb_mid_width"    => "400",
                        "thumb_mid_height"   => "400",
                        "thumb_small_width"  => "100",
                        "thumb_small_height" => "100"
                    ],
                    "water"  => [
                        "is_watermark"          => "0",
                        "watermark_type"        => "1",
                        "watermark_source"      => "",
                        "watermark_position"    => "top-left",
                        "watermark_x"           => "",
                        "watermark_y"           => "",
                        "watermark_opacity"     => "",
                        "watermark_rotate"      => "",
                        "watermark_text"        => "",
                        "watermark_text_file"   => "",
                        "watermark_text_size"   => "",
                        "watermark_text_color"  => "",
                        "watermark_text_align"  => "left",
                        "watermark_text_valign" => "top",
                        "watermark_text_angle"  => ""
                    ]
                ];
        }
        return $res;
    }
}