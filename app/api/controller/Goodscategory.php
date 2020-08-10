<?php

namespace app\api\controller;

use app\model\goods\GoodsCategory as GoodsCategoryModel;
use app\model\goods\Goods as GoodsModel;

/**
 * 商品分类
 * Class Goodscategory
 * @package app\api\controller
 */
class Goodscategory extends BaseApi
{

    /**
     * 树状结构信息
     */
    public function tree()
    {
        $level    = isset($this->params['level']) ? $this->params['level'] : 3;// 分类等级 1 2 3
        $template = isset($this->params['template']) ? $this->params['template'] : 2;// 模板 1：无图，2：有图，3：有商品

        $goods                = new GoodsModel();
        $goods_category_model = new GoodsCategoryModel();
        $condition            = [
//			[ 'is_show', '=', 1 ],
            ['level', '<=', $level],
            ['site_id', '=', $this->site_id]
        ];
        $field                = "category_id,category_name,short_name,pid,level,image,category_id_1,category_id_2,category_id_3,image_adv";
        $order                = "sort desc,category_id desc";

        $list = $goods_category_model->getCategoryTree($condition, $field, $order);
        // 查询商品
        if ($level == 3 && $template == 3) {
            $goods_field = 'gs.sku_id,gs.price,gs.market_price,gs.discount_price,(gs.sale_num + gs.virtual_sale) as sale_num,gs.sku_image,gs.goods_name,gs.site_id,gs.is_free_shipping,gs.introduction,gs.promotion_type,gs.is_virtual,gs.sku_spec_format,g.goods_spec_format';

            $alias = 'gs';
            $join  = [
                ['goods g', 'gs.sku_id = g.sku_id', 'inner']
            ];
            foreach ($list['data'] as $k => $v) {
                if (!empty($v['child_list'])) {
                    foreach ($v['child_list'] as $second_k => $second_v) {
                        if (!empty($second_v['child_list'])) {
                            foreach ($second_v['child_list'] as $third_k => $third_v) {
                                $goods_condition = [
                                    ['gs.goods_state', '=', 1],
                                    ['gs.is_delete', '=', 0],
                                    ['g.category_id', 'like', [$third_v['category_id'], '%' . $third_v['category_id'] . ',%', '%' . $third_v['category_id'], '%,' . $third_v['category_id'] . ',%'], 'or']
                                ];
                                $goods_list      = $goods->getGoodsSkuPageList($goods_condition, 1, 3, 'gs.sort desc,gs.create_time desc', $goods_field, $alias, $join);
                                $goods_list      = $goods_list['data']['list'];

                                $token = $this->checkToken();
                                if ($token['code'] >= 0) {
                                    if (!empty($goods_list)) {
                                        foreach ($goods_list as $gk => $gv) {
                                            // 是否参与会员等级折扣
                                            $goods_member_price = $goods->getGoodsPrice($gv['sku_id'], $this->member_id);
                                            $goods_member_price = $goods_member_price['data'];
                                            if (!empty($goods_member_price['member_price'])) {
                                                $goods_list[$gk]['member_price'] = $goods_member_price['member_price'];
                                            }
                                        }
                                    }
                                }

                                $list['data'][$k]['child_list'][$second_k]['child_list'][$third_k]['goods_list'] = $goods_list;
                            }
                        } else {
                            $list['data'][$k]['child_list'][$second_k]['child_list'] = [];
                        }
                    }
                } else {
                    $list['data'][$k]['child_list'] = [];
                }
            }
        }

        return $this->response($list);
    }

}