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

use app\model\goods\Goods as GoodsModel;
use app\model\goods\GoodsAttribute as GoodsAttributeModel;
use app\model\goods\GoodsCategory as GoodsCategoryModel;
use app\model\goods\VirtualGoods as VirtualGoodsModel;
use app\model\goods\GoodsService as GoodsServiceModel;
use app\model\goods\GoodsLabel as GoodsLabelModel;


/**
 * 虚拟商品
 * Class Virtualgoods
 * @package app\shop\controller
 */
class Virtualgoods extends BaseShop
{

    public function __construct()
    {
        //执行父类构造函数
        parent::__construct();
    }

    /**
     * 添加商品
     * @return mixed
     */
    public function addGoods()
    {
        if (request()->isAjax()) {
            $goods_name       = input("goods_name", "");// 商品名称
            $goods_attr_class = input("goods_attr_class", "");// 商品类型id
            $goods_attr_name  = input("goods_attr_name", "");// 商品类型名称
            $category_id      = input("category_id", 0);// 分类id
            $category_json    = json_encode($category_id);//分类字符串
            $category_id      = ',' . implode(',', $category_id) . ',';

            $goods_image       = input("goods_image", "");// 商品主图路径
            $goods_content     = input("goods_content", "");// 商品详情
            $goods_state       = input("goods_state", "");// 商品状态（1.正常0下架）
            $goods_stock       = input("goods_stock", 0);// 商品库存（总和）
            $goods_stock_alarm = input("goods_stock_alarm", 0);// 库存预警
            $goods_spec_format = input("goods_spec_format", "");// 商品规格格式
            $goods_attr_format = input("goods_attr_format", "");// 商品属性格式
            $introduction      = input("introduction", "");// 促销语
            $keywords          = input("keywords", "");// 关键词
            $unit              = input("unit", "");// 单位
            $sort              = input("sort", 0);// 排序
            $video_url         = input("video_url", "");// 视频
            $goods_sku_data    = input("goods_sku_data", "");// SKU商品数据
            $virtual_indate    = input("virtual_indate", "");// 虚拟商品有效期
            $goods_service_ids = input("goods_service_ids", '');// 商品服务id集合
            $label_id          = input("label_id", '');// 商品分组id
            $virtual_sale      = input("virtual_sale", 0);// 虚拟销量
            $max_buy           = input("max_buy", 0);// 限购
            $min_buy           = input("min_buy", 0);// 起售

            //单规格需要
            $price        = input("price", 0);// 商品价格（取第一个sku）
            $market_price = input("market_price", 0);// 市场价格（取第一个sku）
            $cost_price   = input("cost_price", 0);// 成本价（取第一个sku）
            $sku_no       = input("sku_no", "");// 商品sku编码

            $data                = [
                'goods_name'        => $goods_name,
                'goods_attr_class'  => $goods_attr_class,
                'goods_attr_name'   => $goods_attr_name,
                'site_id'           => $this->site_id,
                'category_id'       => $category_id,
                'category_json'     => $category_json,
                'goods_image'       => $goods_image,
                'goods_content'     => $goods_content,
                'goods_state'       => $goods_state,
                'price'             => $price,
                'market_price'      => $market_price,
                'cost_price'        => $cost_price,
                'sku_no'            => $sku_no,
                'virtual_indate'    => $virtual_indate,
                'goods_stock'       => $goods_stock,
                'goods_stock_alarm' => $goods_stock_alarm,
                'goods_spec_format' => $goods_spec_format,
                'goods_attr_format' => $goods_attr_format,
                'introduction'      => $introduction,
                'keywords'          => $keywords,
                'unit'              => $unit,
                'sort'              => $sort,
                'video_url'         => $video_url,
                'goods_sku_data'    => $goods_sku_data,
                'goods_service_ids' => $goods_service_ids,
                'label_id'          => $label_id,
                'virtual_sale'      => $virtual_sale,
                'max_buy'           => $max_buy,
                'min_buy'           => $min_buy
            ];
            $virtual_goods_model = new VirtualGoodsModel();
            $res                 = $virtual_goods_model->addGoods($data);
            return $res;
        } else {

            //获取一级商品分类
            $goods_category_model = new GoodsCategoryModel();
            $condition            = [
                ['pid', '=', 0],
                ['site_id', '=', $this->site_id]
            ];

            $goods_category_list = $goods_category_model->getCategoryList($condition, 'category_id,category_name,level,commission_rate');
            $goods_category_list = $goods_category_list['data'];
            $this->assign("goods_category_list", $goods_category_list);

            //获取商品类型
            $goods_attr_model = new GoodsAttributeModel();
            $attr_class_list  = $goods_attr_model->getAttrClassList([['site_id', '=', $this->site_id]], 'class_id,class_name');
            $attr_class_list  = $attr_class_list['data'];
            $this->assign("attr_class_list", $attr_class_list);

            // 商品服务
            $goods_service_model = new GoodsServiceModel();
            $service_list        = $goods_service_model->getServiceList([['site_id', '=', $this->site_id]], 'id,service_name');
            $service_list        = $service_list['data'];
            $this->assign("service_list", $service_list);

            // 商品分组
            $goods_label_model = new GoodsLabelModel();
            $label_list        = $goods_label_model->getLabelList([['site_id', '=', $this->site_id]], 'id,label_name', 'create_time desc');
            $label_list        = $label_list['data'];
            $this->assign("label_list", $label_list);

            return $this->fetch("virtualgoods/add_goods");
        }
    }

    /**
     * 编辑商品
     * @return mixed
     */
    public function editGoods()
    {
        $virtual_goods_model = new VirtualGoodsModel();
        if (request()->isAjax()) {
            $goods_id          = input("goods_id", 0);// 商品id
            $goods_name        = input("goods_name", "");// 商品名称
            $goods_attr_class  = input("goods_attr_class", "");// 商品类型id
            $goods_attr_name   = input("goods_attr_name", "");// 商品类型名称
            $category_id       = input("category_id", 0);// 分类id
            $category_json     = json_encode($category_id);//分类字符串
            $category_id       = ',' . implode(',', $category_id) . ',';
            $goods_image       = input("goods_image", "");// 商品主图路径
            $goods_content     = input("goods_content", "");// 商品详情
            $goods_state       = input("goods_state", "");// 商品状态（1.正常0下架）
            $goods_stock       = input("goods_stock", 0);// 商品库存（总和）
            $goods_stock_alarm = input("goods_stock_alarm", 0);// 库存预警
            $goods_spec_format = input("goods_spec_format", "");// 商品规格格式
            $goods_attr_format = input("goods_attr_format", "");// 商品属性格式
            $introduction      = input("introduction", "");// 促销语
            $keywords          = input("keywords", "");// 关键词
            $unit              = input("unit", "");// 单位
            $sort              = input("sort", 0);// 排序
            $video_url         = input("video_url", "");// 视频
            $goods_sku_data    = input("goods_sku_data", "");// SKU商品数据
            $virtual_indate    = input("virtual_indate", "");// 虚拟商品有效期
            $goods_service_ids = input("goods_service_ids", '');// 商品服务id集合
            $label_id          = input("label_id", '');// 商品分组id
            $virtual_sale      = input("virtual_sale", 0);// 虚拟销量
            $max_buy           = input("max_buy", 0);// 限购
            $min_buy           = input("min_buy", 0);// 起售

            //单规格需要
            $price        = input("price", 0);// 商品价格（取第一个sku）
            $market_price = input("market_price", 0);// 市场价格（取第一个sku）
            $cost_price   = input("cost_price", 0);// 成本价（取第一个sku）
            $sku_no       = input("sku_no", "");// 商品sku编码

            $data = [
                'goods_id'          => $goods_id,
                'goods_name'        => $goods_name,
                'goods_attr_class'  => $goods_attr_class,
                'goods_attr_name'   => $goods_attr_name,
                'site_id'           => $this->site_id,
                'category_id'       => $category_id,
                'category_json'     => $category_json,
                'goods_image'       => $goods_image,
                'goods_content'     => $goods_content,
                'goods_state'       => $goods_state,
                'price'             => $price,
                'market_price'      => $market_price,
                'cost_price'        => $cost_price,
                'sku_no'            => $sku_no,
                'virtual_indate'    => $virtual_indate,
                'goods_stock'       => $goods_stock,
                'goods_stock_alarm' => $goods_stock_alarm,
                'goods_spec_format' => $goods_spec_format,
                'goods_attr_format' => $goods_attr_format,
                'introduction'      => $introduction,
                'keywords'          => $keywords,
                'unit'              => $unit,
                'sort'              => $sort,
                'video_url'         => $video_url,
                'goods_sku_data'    => $goods_sku_data,
                'goods_service_ids' => $goods_service_ids,
                'label_id'          => $label_id,
                'virtual_sale'      => $virtual_sale,
                'max_buy'           => $max_buy,
                'min_buy'           => $min_buy
            ];
            $res  = $virtual_goods_model->editGoods($data);
            return $res;
        } else {

            $goods_model = new GoodsModel();
            $goods_id    = input("goods_id", 0);
            $goods_info  = $goods_model->editGetGoodsInfo([['goods_id', '=', $goods_id], ['site_id', '=', $this->site_id]]);
            $goods_info  = $goods_info['data'];

            $goods_sku_list         = $virtual_goods_model->getGoodsSkuList([['goods_id', '=', $goods_id], ['site_id', '=', $this->site_id]], "sku_id,sku_name,sku_no,sku_spec_format,price,market_price,cost_price,stock,virtual_indate,sku_image,sku_images,goods_spec_format,spec_name", '');
            $goods_sku_list         = $goods_sku_list['data'];
            $goods_info['sku_list'] = $goods_sku_list;
            $this->assign("goods_info", $goods_info);

            //获取一级商品分类
            $goods_category_model = new GoodsCategoryModel();
            $condition            = [
                ['pid', '=', 0],
                ['site_id', '=', $this->site_id]
            ];
            $goods_category_list  = $goods_category_model->getCategoryList($condition, 'category_id,category_name,level,commission_rate');
            $goods_category_list  = $goods_category_list['data'];
            $this->assign("goods_category_list", $goods_category_list);

            //获取商品类型
            $goods_attr_model = new GoodsAttributeModel();
            $attr_class_list  = $goods_attr_model->getAttrClassList([['site_id', '=', $this->site_id]], 'class_id,class_name');
            $attr_class_list  = $attr_class_list['data'];
            $this->assign("attr_class_list", $attr_class_list);

            // 商品服务
            $goods_service_model = new GoodsServiceModel();
            $service_list        = $goods_service_model->getServiceList([['site_id', '=', $this->site_id]], 'id,service_name');
            $service_list        = $service_list['data'];
            $this->assign("service_list", $service_list);

            // 商品分组
            $goods_label_model = new GoodsLabelModel();
            $label_list        = $goods_label_model->getLabelList([['site_id', '=', $this->site_id]], 'id,label_name', 'create_time desc');
            $label_list        = $label_list['data'];
            $this->assign("label_list", $label_list);

            return $this->fetch("virtualgoods/edit_goods");
        }
    }

}