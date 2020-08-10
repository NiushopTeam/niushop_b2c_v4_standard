<?php
/**
 * Goodssku.php
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

use app\model\goods\Goods;
use app\model\goods\GoodsService;
use addon\coupon\model\CouponType;

/**
 * 商品sku
 * @author Administrator
 *
 */
class Goodssku extends BaseApi
{

    /**
     * 基础信息
     */
    public function info()
    {
        $sku_id = isset($this->params['sku_id']) ? $this->params['sku_id'] : 0;
        if (empty($sku_id)) {
            return $this->response($this->error('', 'REQUEST_SKU_ID'));
        }
        $goods = new Goods();
        $field = 'goods_id,sku_id,goods_name,sku_name,sku_spec_format,price,market_price,discount_price,promotion_type,start_time,end_time,stock,sku_image,sku_images,goods_spec_format';
        $info  = $goods->getGoodsSkuInfo([['sku_id', '=', $sku_id], ['site_id', '=', $this->site_id]], $field);

        $token = $this->checkToken();
        if ($token['code'] >= 0) {
            // 是否参与会员等级折扣
            $goods_member_price = $goods->getGoodsPrice($sku_id, $this->member_id);
            $goods_member_price = $goods_member_price['data'];
            if (!empty($goods_member_price['member_price'])) {
                $info['data']['member_price'] = $goods_member_price['member_price'];
            }
        }
        return $this->response($info);
    }

    /**
     * 详情信息
     */
    public function detail()
    {
        $sku_id = isset($this->params['sku_id']) ? $this->params['sku_id'] : 0;
        if (empty($sku_id)) {
            return $this->response($this->error('', 'REQUEST_SKU_ID'));
        }

        $res = [];

        $goods                   = new Goods();
        $goods_sku_detail        = $goods->getGoodsSkuDetail($sku_id, $this->site_id);
        $goods_sku_detail        = $goods_sku_detail['data'];
        $res['goods_sku_detail'] = $goods_sku_detail;

        if (empty($goods_sku_detail)) return $this->response($this->error($res));

        $res['goods_sku_detail']['purchased_num'] = 0; // 该商品已购数量

        $token = $this->checkToken();
        if ($token['code'] >= 0) {
            // 是否参与会员等级折扣
            $goods_member_price = $goods->getGoodsPrice($sku_id, $this->member_id);
            $goods_member_price = $goods_member_price['data'];
            if (!empty($goods_member_price['member_price'])) {
                $res['goods_sku_detail']['member_price'] = $goods_member_price['member_price'];
            }
            if ($goods_sku_detail['max_buy'] > 0) $res['goods_sku_detail']['purchased_num'] = $goods->getGoodsPurchasedNum($goods_sku_detail['goods_id'], $this->member_id);
        }

        // 商品服务
        $goods_service                            = new GoodsService();
        $goods_service_list                       = $goods_service->getServiceList([['site_id', '=', $this->site_id], ['id', 'in', $res['goods_sku_detail']['goods_service_ids']]], 'service_name,desc');
        $res['goods_sku_detail']['goods_service'] = $goods_service_list['data'];

        return $this->response($this->success($res));
    }

    /**
     * 列表信息
     */
    public function page()
    {
        $page             = isset($this->params['page']) ? $this->params['page'] : 1;
        $page_size        = isset($this->params['page_size']) ? $this->params['page_size'] : PAGE_LIST_ROWS;
        $goods_id_arr     = isset($this->params['goods_id_arr']) ? $this->params['goods_id_arr'] : '';//goods_id数组
        $keyword          = isset($this->params['keyword']) ? $this->params['keyword'] : '';//关键词
        $category_id      = isset($this->params['category_id']) ? $this->params['category_id'] : 0;//分类
        $min_price        = isset($this->params['min_price']) ? $this->params['min_price'] : 0;//价格区间，小
        $max_price        = isset($this->params['max_price']) ? $this->params['max_price'] : 0;//价格区间，大
        $is_free_shipping = isset($this->params['is_free_shipping']) ? $this->params['is_free_shipping'] : 0;//是否免邮
        $order            = isset($this->params['order']) ? $this->params['order'] : "create_time";//排序（综合、销量、价格）
        $sort             = isset($this->params['sort']) ? $this->params['sort'] : "desc";//升序、降序
        $coupon           = isset($this->params['coupon']) ? $this->params['coupon'] : 0;//优惠券

        $condition   = [];
        $condition[] = ['gs.site_id', '=', $this->site_id];

        if (!empty($goods_id_arr)) {
            $condition[] = ['gs.goods_id', 'in', $goods_id_arr];
        }

        if (!empty($keyword)) {
            $condition[] = ['g.goods_name|gs.sku_name|gs.keywords', 'like', '%' . $keyword . '%'];
        }

        if (!empty($category_id)) {
            $condition[] = ['g.category_id', 'like', [$category_id, '%' . $category_id . ',%', '%' . $category_id, '%,' . $category_id . ',%'], 'or'];
        }

        if ($min_price != "" && $max_price != "") {
            $condition[] = ['gs.discount_price', 'between', [$min_price, $max_price]];
        } elseif ($min_price != "") {
            $condition[] = ['gs.discount_price', '>=', $min_price];
        } elseif ($max_price != "") {
            $condition[] = ['gs.discount_price', '<=', $max_price];
        }

        if (!empty($is_free_shipping)) {
            $condition[] = ['gs.is_free_shipping', '=', $is_free_shipping];
        }

        // 非法参数进行过滤
        if ($sort != "desc" && $sort != "asc") {
            $sort = "";
        }

        // 非法参数进行过滤
        if ($order != '') {
            if ($order != "sale_num" && $order != "discount_price") {
                $order = 'gs.create_time';
            } else {
                $order = 'gs.' . $order;
            }
            $order_by = $order . ' ' . $sort;
        } else {
            $order_by = 'gs.sort desc,gs.create_time desc';
        }

        // 优惠券
        if (!empty($coupon)) {
            $coupon_type      = new CouponType();
            $coupon_type_info = $coupon_type->getInfo([
                ['coupon_type_id', '=', $coupon],
                ['site_id', '=', $this->site_id],
                ['goods_type', '=', 2]
            ], 'goods_ids');
            $coupon_type_info = $coupon_type_info['data'];
            if (isset($coupon_type_info['goods_ids']) && !empty($coupon_type_info['goods_ids'])) {
                $condition[] = ['g.goods_id', 'in', explode(',', trim($coupon_type_info['goods_ids'], ','))];
            }
        }

        $condition[] = ['gs.goods_state', '=', 1];
        $condition[] = ['gs.is_delete', '=', 0];
        $field       = 'gs.goods_id,gs.sku_id,gs.sku_name,gs.price,gs.market_price,gs.discount_price,gs.stock,(gs.sale_num + gs.virtual_sale) as sale_num,gs.sku_image,gs.goods_name,gs.site_id,gs.is_free_shipping,gs.introduction,gs.promotion_type,g.goods_image,g.promotion_addon,gs.is_virtual,g.goods_spec_format';

        $alias = 'gs';
        $join  = [
            ['goods g', 'gs.sku_id = g.sku_id', 'inner']
        ];

        $goods = new Goods();
        $list  = $goods->getGoodsSkuPageList($condition, $page, $page_size, $order_by, $field, $alias, $join);

        $token = $this->checkToken();
        if ($token['code'] >= 0) {
            if (!empty($list['data']['list'])) {
                foreach ($list['data']['list'] as $k => $v) {

                    // 是否参与会员等级折扣
                    $goods_member_price = $goods->getGoodsPrice($v['sku_id'], $this->member_id);
                    $goods_member_price = $goods_member_price['data'];
                    if (!empty($goods_member_price['member_price'])) {
                        $list['data']['list'][$k]['member_price'] = $goods_member_price['member_price'];
                    }
                }
            }
        }

        return $this->response($list);
    }

    /**
     * 商品推荐
     * @return string
     */
    public function recommend()
    {
        $page      = isset($this->params['page']) ? $this->params['page'] : 1;
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] : PAGE_LIST_ROWS;
        $condition = [
            ['gs.goods_state', '=', 1],
            ['gs.is_delete', '=', 0],
            ['gs.site_id', '=', $this->site_id]
        ];
        $goods     = new Goods();
        $field     = 'gs.goods_id,gs.sku_id,gs.sku_name,gs.price,gs.market_price,gs.discount_price,gs.stock,gs.sale_num,gs.sku_image,gs.goods_name,gs.site_id,gs.is_free_shipping,gs.introduction,gs.promotion_type,g.goods_image';
        $alias     = 'gs';
        $join      = [
            ['goods g', 'gs.sku_id = g.sku_id', 'inner']
        ];
        $order_by  = 'gs.sort desc,gs.create_time desc';
        $list      = $goods->getGoodsSkuPageList($condition, $page, $page_size, $order_by, $field, $alias, $join);

        $token = $this->checkToken();
        if (!empty($list['data']['list'])) {
            foreach ($list['data']['list'] as $k => $v) {

                if ($token['code'] >= 0) {
                    // 是否参与会员等级折扣
                    $goods_member_price = $goods->getGoodsPrice($v['sku_id'], $this->member_id);
                    $goods_member_price = $goods_member_price['data'];
                    if (!empty($goods_member_price['member_price'])) {
                        $list['data']['list'][$k]['member_price'] = $goods_member_price['member_price'];
                    }
                }
            }
        }
        return $this->response($list);
    }
}