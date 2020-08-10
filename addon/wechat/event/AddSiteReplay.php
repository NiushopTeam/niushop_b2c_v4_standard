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
// +---------------------------------------------------------------------+
// | NiuCloud | [ WE CAN DO IT JUST NiuCloud ]                |
// +---------------------------------------------------------------------+
// | Copy right 2019-2029 www.niucloud.com                          |
// +---------------------------------------------------------------------+
// | Author | NiuCloud <niucloud@outlook.com>                       |
// +---------------------------------------------------------------------+
// | Repository | https://github.com/niucloud/framework.git          |
// +---------------------------------------------------------------------+

namespace addon\wechat\event;

use addon\wechat\model\Replay;

/**
 * 增加站点关注回复
 */
class AddSiteReplay
{

    public function handle($param)
    {
        if (!empty($param['site_id'])) {

            $replay = new Replay();
            $data   = [
                'site_id'       => $param['site_id'],
                'rule_name'     => '关注回复',
                'rule_type'     => 'AFTER',
                'keywords_json' => '',
                'replay_json'   => '[{"type":"text","reply_content":"关注回复"}]',
                'create_time'   => time()
            ];
            $res    = $replay->addRule($data);
            return $res;

        }

    }

}