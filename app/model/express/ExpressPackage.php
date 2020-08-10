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

namespace app\model\express;

use app\model\BaseModel;

/**
 * 物流配送
 */
class ExpressPackage extends BaseModel
{

    /**
     * 获取物流包裹列表
     * @param $condition
     * @param string $field
     */
    public function getExpressDeliveryPackageList($condition, $field = "*")
    {
        $list = model("express_delivery_package")->getList($condition, $field);
        return $this->success($list);
    }

    /**
     * 获取包裹信息
     * @param $condition
     */
    public function package($condition)
    {
        $list_result = $this->getExpressDeliveryPackageList($condition);
        $list        = $list_result["data"];
        $trace_model = new Trace();
        foreach ($list as $k => $v) {
            $temp_array = explode(",", $v["goods_id_array"]);
            if (!empty($temp_array)) {

                foreach ($temp_array as $temp_k => $temp_v) {
                    $temp_str                 = str_replace("http://", "http//", $temp_v);
                    $temp_str                 = str_replace("https://", "https//", $temp_str);
                    $temp_item                = explode(":", $temp_str);
                    $sku_image                = str_replace("https//", "https://", $temp_item["3"]);
                    $sku_image                = str_replace("http//", "http://", $sku_image);
                    $list[$k]["goods_list"][] = ["sku_name" => $temp_item["2"], "num" => $temp_item["1"], "sku_image" => $sku_image, "sku_id" => $temp_item["0"]];
                }
            }
            $trace_list        = $trace_model->trace($v["delivery_no"], $v["express_company_id"], $v['site_id']);
            $list[$k]["trace"] = $trace_list["data"];
        }

        return $list;

    }

    /**
     * 获取订单分页列表
     * @param array $condition
     * @param number $page
     * @param string $page_size
     * @param string $order
     * @param string $field
     */
    public function getExpressDeliveryPackagePageList($condition = [], $page = 1, $page_size = PAGE_LIST_ROWS, $order = '', $field = '*')
    {
        $order_list = model('order')->pageList($condition, $field, $order, $page, $page_size);
        if (!empty($order_list['list'])) {
            foreach ($order_list['list'] as $k => $v) {
                $order_goods_list                      = model("order_goods")->getList([
                    'order_id' => $v['order_id']
                ]);
                $order_list['list'][$k]['order_goods'] = $order_goods_list;
            }
        }

        return $this->success($order_list);
    }

}