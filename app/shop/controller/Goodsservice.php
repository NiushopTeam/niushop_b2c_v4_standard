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

use app\model\goods\GoodsService as GoodsServiceModel;

class Goodsservice extends BaseShop
{

    /**
     * 商品服务列表
     */
    public function lists()
    {
        if (request()->isAjax()) {
            $page_index  = input('page', 1);
            $page_size   = input('page_size', PAGE_LIST_ROWS);
            $search_keys = input('search_keys', "");
            $condition   = [];
            $condition[] = ['site_id', '=', $this->site_id];
            if (!empty($search_keys)) {
                $condition[] = ['service_name', 'like', '%' . $search_keys . '%'];
            }
            $goods_attr_model = new GoodsServiceModel();
            $list             = $goods_attr_model->getServicePageList($condition, $page_index, $page_size);
            return $list;
        } else {

            return $this->fetch('goodsservice/lists');
        }
    }

    /**
     * 商品服务添加
     */
    public function add()
    {
        if (request()->isAjax()) {
            $service_name = input('service_name', '');
            $data         = [
                'site_id'      => $this->site_id,
                'service_name' => $service_name,
                'desc'         => input('desc', 0)
            ];
            $model        = new GoodsServiceModel();
            $res          = $model->addService($data);
            return $res;
        }
    }

    /**
     * 商品服务编辑
     */
    public function edit()
    {
        if (request()->isAjax()) {
            $data  = [
                'id'           => input('id', ''),
                'site_id'      => $this->site_id,
                'service_name' => input('service_name', ''),
                'desc'         => input('desc', 0)
            ];
            $model = new GoodsServiceModel();
            $res   = $model->editService($data);
            return $res;
        }
    }

    /**
     * 商品服务删除
     */
    public function delete()
    {
        if (request()->isAjax()) {
            $id     = input("id", 0);
            $model  = new GoodsServiceModel();
            $result = $model->deleteService([['id', '=', $id], ['site_id', '=', $this->site_id]]);
            return $result;
        }
    }

}