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

use app\model\member\MemberLevel as MemberLevelModel;

class Memberlevel extends BaseApi
{
    /**
     * 列表信息
     */
    public function lists()
    {
        $member_level_model = new MemberLevelModel();
        $member_level_list  = $member_level_model->getMemberLevelList([['site_id', '=', $this->site_id]], 'level_id,level_name,growth,remark,consume_discount,is_free_shipping,point_feedback,send_point,send_balance,send_coupon', 'growth asc');
        return $this->response($member_level_list);
    }

}