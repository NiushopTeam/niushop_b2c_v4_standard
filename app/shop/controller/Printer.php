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

namespace app\shop\controller;

use app\Controller;
use app\model\order\OrderCommon as OrderCommonModel;

/**
 * 打印
 * Class Printer
 * @package app\shop\controller
 */
class Printer extends Controller
{

    /**
     * 批量打印发货单
     * @return mixed
     */
    public function batchPrintOrder()
    {
        $order_id            = input('order_id', 0);
        $order_common_model  = new OrderCommonModel();
        $order_detail_result = $order_common_model->getOrderDetail($order_id);
        $order_detail        = $order_detail_result["data"];
        $this->assign("order_detail", $order_detail);
        return $this->fetch('order/batch_print_order');
    }


}