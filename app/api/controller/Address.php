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
 */

namespace app\api\controller;

use app\model\system\Address as AddressModel;

/**
 * 地址管理
 * @author Administrator
 *
 */
class Address extends BaseApi
{

    /**
     * 基础信息
     */
    public function info()
    {
        $id      = $this->params['id'];
        $address = new AddressModel();
        $info    = $address->getAreaInfo($id);
        return $this->response($info);
    }

    /**
     * 列表信息
     */
    public function lists()
    {
        $pid     = isset($this->params['pid']) ? $this->params['pid'] : 0;
        $address = new AddressModel();
        $list    = $address->getAreas($pid);
        return $this->response($list);
    }

    /**
     * 树状结构信息
     */
    public function tree()
    {
        $id      = $this->params['id'];
        $address = new AddressModel();
        $tree    = $address->getAreas($id);
        return $this->response($tree);
    }

}