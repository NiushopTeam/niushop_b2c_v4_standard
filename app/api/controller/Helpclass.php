<?php
/**
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

use app\model\web\Help as HelpModel;

class Helpclass extends BaseApi
{

    /**
     * 列表信息
     */
    public function lists()
    {
        $help      = new HelpModel();
        $condition = [
            ['site_id', '=', $this->site_id]
        ];
        $list      = $help->getHelpClassList($condition, 'class_id, class_name', 'sort desc');
        $order     = 'create_time desc';
        $field     = 'id,title';
        if (!empty($list['data'])) {

            foreach ($list['data'] as $k => $v) {
                $condition                      = [
                    ['class_id', '=', $v['class_id']],
                    ['site_id', '=', $this->site_id]
                ];
                $child_list                     = $help->getHelpList($condition, $field, $order);
                $child_list                     = $child_list['data'];
                $list['data'][$k]['child_list'] = $child_list;
            }
        }
        return $this->response($list);
    }

}