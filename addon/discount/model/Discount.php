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

namespace addon\discount\model;

use app\model\BaseModel;
use app\model\goods\Goods;
use app\model\system\Cron;
use think\facade\Db;

/**
 * 限时折扣
 */
class Discount extends BaseModel
{
    private $discount_status = [
        0  => '未开始',
        1  => '进行中',
        2  => '已结束',
        -1 => '已关闭（手动）',
    ];

    /**
     * 限时折扣状态
     */
    public function getDiscountStatus()
    {
        return $this->discount_status;
    }

    /**
     * 添加限时折扣活动
     * @param unknown $data
     */
    public function addDiscount($data)
    {
        $data['create_time'] = time();
        $cron                = new Cron();
        //查询是否存在活动
        $promotion_info = Db::name('promotion_discount')->where([
            ['start_time|end_time', 'between', [$data['start_time'], $data['end_time']]],
            ['site_id', '=', $data['site_id']],
            ['status', 'in', '0,1']
        ])->find();
        if (!empty($promotion_info)) {
            return $this->error('', "当前时间段内存在限时折扣活动");
        }
        $promotion_info = Db::name('promotion_discount')->where([['start_time', '<', $data['start_time']], ['end_time', '>', $data['end_time']], ['site_id', '=', $data['site_id']], ['status', 'in', '0,1']])->find();
        if (!empty($promotion_info)) {
            return $this->error('', "当前时间段内存在限时折扣活动");
        }
        if ($data['start_time'] <= time()) {
            $data['status'] = 1;//直接启动
            $discount_id    = model('promotion_discount')->add($data);
            $cron->addCron(1, 0, "限时折扣关闭", "CloseDiscount", $data['end_time'], $discount_id);
        } else {
            $discount_id = model('promotion_discount')->add($data);
            $cron->addCron(1, 0, "限时折扣开启", "OpenDiscount", $data['start_time'], $discount_id);
            $cron->addCron(1, 0, "限时折扣关闭", "CloseDiscount", $data['end_time'], $discount_id);
        }

        return $this->success($discount_id);
    }

    /**
     * 修改限时折扣活动
     * @param unknown $data //传输数据(针对已经开始之后活动不能修改时间)
     */
    public function editDiscount($data)
    {
        $discount_id   = $data['discount_id'];
        $discount_info = model('promotion_discount')->getInfo([['discount_id', '=', $discount_id], ['site_id', '=', $data['site_id']]], 'start_time,status');
        if ($discount_info['status'] != 1) {
            //针对未开始的活动进行设置
            $cron = new Cron();
            //查询是否存在活动
            $promotion_info = Db::name('promotion_discount')->where([
                ['start_time|end_time', 'between', [$data['start_time'], $data['end_time']]],
                ['site_id', '=', $data['site_id']],
                ['discount_id', '<>', $discount_id],
                ['status', 'in', '0,1'],
            ])->find();
            if (!empty($promotion_info)) {
                return $this->error('', "当前时间段内存在限时折扣活动");
            }
            $promotion_info = Db::name('promotion_discount')->where([
                ['start_time', '<', $data['start_time']],
                ['end_time', '>', $data['end_time']],
                ['site_id', '=', $data['site_id']],
                ['discount_id', '<>', $discount_id],
                ['status', 'in', '0,1'],
            ])->find();
            if (!empty($promotion_info)) {
                return $this->error('', "当前时间段内存在限时折扣活动");
            }
            if ($data['start_time'] <= time()) {
                $data['status'] = 1;//直接启动
                $res            = model('promotion_discount')->update($data, [['discount_id', '=', $discount_id], ['site_id', '=', $data['site_id']]]);
                if ($res) {
                    model('promotion_discount_goods')->update(['start_time' => $data['start_time'], 'end_time' => $data['end_time']], [['discount_id', '=', $discount_id]]);
                    //活动商品启动
                    $this->cronOpenDiscount($discount_id);
                    $cron->deleteCron([['event', '=', 'OpenDiscount'], ['relate_id', '=', $discount_id]]);
                    $cron->deleteCron([['event', '=', 'CloseDiscount'], ['relate_id', '=', $discount_id]]);
                    $cron->addCron(1, 0, "限时折扣关闭", "CloseDiscount", $data['end_time'], $discount_id);
                }

            } else {
                $res = model('promotion_discount')->update($data, [['discount_id', '=', $discount_id], ['site_id', '=', $data['site_id']]]);
                if ($res) {
                    model('promotion_discount_goods')->update(['start_time' => $data['start_time'], 'end_time' => $data['end_time']], [['discount_id', '=', $discount_id]]);
                    $cron->deleteCron([['event', '=', 'OpenDiscount'], ['relate_id', '=', $discount_id]]);
                    $cron->deleteCron([['event', '=', 'CloseDiscount'], ['relate_id', '=', $discount_id]]);
                    $cron->addCron(1, 0, "限时折扣开启", "OpenDiscount", $data['start_time'], $discount_id);
                    $cron->addCron(1, 0, "限时折扣关闭", "CloseDiscount", $data['end_time'], $discount_id);
                }

            }
        } else {
            //针对已经启动的限时折扣只能修改文字信息不能修改时间
            if (isset($data['start_time'])) unset($data['start_time']);
            if (isset($data['end_time'])) unset($data['end_time']);
            $res = model('promotion_discount')->update($data, [['discount_id', '=', $discount_id], ['site_id', '=', $data['site_id']]]);
        }
        return $this->success($res);
    }

    /**
     * 手动关闭限时折扣
     * @param unknown $discount_id
     * @param unknown $site_id
     * @return multitype:string
     */
    public function closeDiscount($discount_id, $site_id)
    {
        $discount_info = model('promotion_discount')->getInfo([['discount_id', '=', $discount_id], ['site_id', '=', $site_id]], 'start_time,status');
        if (!empty($discount_info)) {
            //针对正在进行的活动
            if ($discount_info['status'] == 1) {
                $res = model('promotion_discount')->update(['status' => -1], [['discount_id', '=', $discount_id]]);

                // 删除商品营销活动标识
                $this->deleteGoodsPromotionAddon($discount_id);

                //商品恢复原价
                Db::name('promotion_discount_goods')->alias('npdg')
                    ->leftjoin("goods_sku ngs", "npdg.sku_id = ngs.sku_id")
                    ->where([['npdg.discount_id', '=', $discount_id]])
                    ->update(
                        [
                            "ngs.discount_price" => Db::raw('ngs.price'),
                            "ngs.promotion_type" => 0,
                            "ngs.start_time"     => 0,
                            "ngs.end_time"       => 0
                        ]);
                return $this->success($res);
            } else {
                return $this->error("", "正在进行的活动才能进行关闭操作");
            }
        } else {
            return $this->error("", "活动不存在");
        }
    }

    /**
     * 启动限时折扣事件
     * @param unknown $discount_id
     */
    public function cronOpenDiscount($discount_id)
    {
        $discount_info = model('promotion_discount')->getInfo([['discount_id', '=', $discount_id]], 'start_time,status');
        if (!empty($discount_info)) {
            if ($discount_info['start_time'] <= time() && $discount_info['status'] == 0) {
                $res = model('promotion_discount')->update(['status' => 1], [['discount_id', '=', $discount_id]]);

                // 删除商品营销活动标识
                $this->deleteGoodsPromotionAddon($discount_id);
                Db::name('promotion_discount_goods')->alias('npdg')
                    ->leftjoin("goods_sku ngs", "npdg.sku_id = ngs.sku_id")
                    ->where([['npdg.discount_id', '=', $discount_id]])
                    ->update(
                        [
                            "ngs.discount_price" => Db::raw('npdg.discount_price'),
                            "ngs.promotion_type" => 1,
                            "ngs.start_time"     => Db::raw('npdg.start_time'),
                            "ngs.end_time"       => Db::raw('npdg.end_time')
                        ]);
                return $this->success($res);
            } else {
                return $this->error("", "限时折扣活动已开启或者关闭");
            }

        } else {
            return $this->error("", "限时折扣活动不存在");
        }
    }

    /**
     * 结束限时折扣事件
     * @param unknown $discount_id
     */
    public function cronCloseDiscount($discount_id)
    {
        $discount_info = model('promotion_discount')->getInfo([['discount_id', '=', $discount_id]], 'start_time,status');
        if (!empty($discount_info)) {
            //针对正在进行的活动
            if ($discount_info['status'] == 1) {
                $res = model('promotion_discount')->update(['status' => 2], [['discount_id', '=', $discount_id]]);

                // 删除商品营销活动标识
                $this->deleteGoodsPromotionAddon($discount_id);

                //商品恢复原价
                Db::name('promotion_discount_goods')->alias('npdg')
                    ->leftjoin("goods_sku ngs", "npdg.sku_id = ngs.sku_id")
                    ->where([['npdg.discount_id', '=', $discount_id]])
                    ->update(
                        [
                            "ngs.discount_price" => Db::raw('ngs.price'),
                            "ngs.promotion_type" => 0,
                            "ngs.start_time"     => 0,
                            "ngs.end_time"       => 0
                        ]);
                return $this->success($res);
            } else {
                return $this->error("", "正在进行的活动才能进行关闭操作");
            }
        } else {
            return $this->error("", "活动不存在");
        }
    }

    /**
     * 删除限时折扣活动(针对未进行中)
     * @param unknown $discount_id
     */
    public function deleteDiscount($discount_id, $site_id)
    {
        $res = model('promotion_discount')->delete([['discount_id', '=', $discount_id], ['site_id', '=', $site_id], ['status', '<>', 1]]);
        if ($res) {

            // 删除商品营销活动标识
            $this->deleteGoodsPromotionAddon($discount_id);
            model('promotion_discount_goods')->delete([['discount_id', '=', $discount_id]]);
            return $this->success($res);
        } else {
            return $this->error('', "正在进行中或者权限不足");
        }
    }

    /**
     * 添加限时折扣商品
     * @param unknown $discount_id
     * @param unknown $sku_ids sku_id组（添加时不设置价格）
     */
    public function addDiscountGoods($discount_id, $site_id, $sku_ids)
    {
        $discount_info = model('promotion_discount')->getInfo([['discount_id', '=', $discount_id], ['site_id', '=', $site_id]], 'start_time,end_time,status');
        if (!empty($discount_info)) {
            $sku_array = explode(',', $sku_ids);
            $goods     = new Goods();
            foreach ($sku_array as $k => $v) {
                $sku_info             = model("goods_sku")->getInfo([['sku_id', '=', $v]], 'goods_id, sku_id, sku_name,price,sku_image');
                $discount_goods_count = model('promotion_discount_goods')->getCount(['discount_id' => $discount_id, 'goods_id' => $sku_info['goods_id'], 'sku_id' => $sku_info['sku_id']]);

                if (!empty($sku_info) && $discount_goods_count == 0) {
                    $discount_goods_data = [
                        'discount_id'    => $discount_id,
                        'start_time'     => $discount_info['start_time'],
                        'end_time'       => $discount_info['end_time'],
                        'goods_id'       => $sku_info['goods_id'],
                        'sku_id'         => $sku_info['sku_id'],
                        'price'          => $sku_info['price'],
                        'discount_price' => $sku_info['price'],
                        'sku_name'       => $sku_info['sku_name'],
                        'sku_image'      => $sku_info['sku_image']
                    ];
                    model('promotion_discount_goods')->add($discount_goods_data);
                    if ($discount_info['status'] == 1) {
                        model("goods_sku")->update(['promotion_type' => 1, 'start_time' => $discount_info['start_time'], 'end_time' => $discount_info['end_time']], [['sku_id', '=', $v]]);
                    }

                    // 更新营销商品活动标识
                    $goods->modifyPromotionAddon($sku_info['goods_id'], ['discount' => $discount_id]);
                }

            }
        }
        return $this->success();
    }

    /**
     * 修改折扣商品价
     */
    public function updateDiscountGoods($discount_id, $sku_id, $site_id, $discount_price)
    {
        $discount_info = model('promotion_discount')->getInfo([['discount_id', '=', $discount_id], ['site_id', '=', $site_id]], 'start_time,end_time,status');
        if (!empty($discount_info)) {

            $discount_goods_data = [
                'discount_price' => $discount_price,
            ];
            $res                 = model('promotion_discount_goods')->update($discount_goods_data, [['discount_id', '=', $discount_id], ['sku_id', '=', $sku_id]]);
            if ($res && $discount_info['status'] == 1) {
                model("goods_sku")->update(['discount_price' => $discount_price], [['sku_id', '=', $sku_id]]);
            }
            return $this->success($res);
        } else {
            return $this->error('', '活动不存在');
        }
    }

    /**
     * 删除限时折扣商品
     */
    public function deleteDiscountGoods($discount_id, $sku_id, $site_id)
    {
        $discount_info = model('promotion_discount')->getInfo([['discount_id', '=', $discount_id], ['site_id', '=', $site_id]], 'status');
        if (!empty($discount_info)) {

            // 删除商品营销活动标识
            $this->deleteGoodsPromotionAddon($discount_id, $sku_id);

            $res = model('promotion_discount_goods')->delete([['discount_id', '=', $discount_id], ['sku_id', '=', $sku_id]]);
            if ($res && $discount_info['status'] == 1) {
                model("goods_sku")->update(
                    [
                        "discount_price" => Db::raw('price'),
                        "promotion_type" => 0,
                        "start_time"     => 0,
                        "end_time"       => 0,
                    ],
                    [['sku_id', '=', $sku_id]]);
            }
            return $this->success($res);
        } else {
            return $this->error('', '活动不存在');
        }
    }

    /**
     * 获取限时折扣基础信息
     */
    public function getDiscountInfo($discount_id, $site_id)
    {
        $info = model('promotion_discount')->getInfo([['discount_id', '=', $discount_id], ['site_id', '=', $site_id]], 'discount_id, site_id, discount_name, status, remark, start_time, end_time, create_time, modify_time');
        return $this->success($info);
    }

    public function getDiscountGoods($discount_id)
    {
        $list = model('promotion_discount_goods')->getList([['discount_id', '=', $discount_id]], 'id, discount_id, start_time, end_time, goods_id, sku_id, price, discount_price, sku_name, sku_image', 'id desc');
        return $this->success($list);
    }

    /**
     * 获取限时折扣信息
     * @param $where
     * @param $field
     * @return mixed
     */
    public function getDiscountGoodsInfo($condition, $field)
    {
        $alias = 'pdg';
        $join  = [
            [
                'promotion_discount pd',
                'pd.discount_id = pdg.discount_id',
                'inner'
            ],
        ];
        $info  = model('promotion_discount_goods')->getInfo($condition, '*', $alias, $join);
        return $this->success($info);
    }

    /**
     * 获取参与限时折扣商品分页列表
     * @param array $condition
     * @param int $page
     * @param int $list_rows
     * @param string $field
     * @param string $order
     * @return array
     */
    public function getDiscountGoodsPageList($condition = [], $page = 1, $list_rows = PAGE_LIST_ROWS, $field = 'pdg.id,pdg.discount_id,pdg.start_time,pdg.end_time,pdg.goods_id,pdg.sku_id,pdg.discount_price,pdg.sku_name,pdg.sku_image,sku.price', $order = 'pdg.id desc')
    {
        $alias = 'pdg';
        $join  = [
            ['goods_sku sku', 'sku.sku_id=pdg.sku_id', 'inner']
        ];
        $list  = model('promotion_discount_goods')->pageList($condition, $field, $order, $page, $list_rows, $alias, $join);
        return $this->success($list);
    }

    /**
     * 获取限时折扣商品详情
     * @param $discount_id
     * @param $goods_id
     * @param $sku_id
     * @return array
     */
    public function getDiscountGoodsDetail($discount_id, $goods_id, $sku_id)
    {
        $alias     = 'pd';
        $join      = [
            ['promotion_discount_goods pdg', 'pd.discount_id=pdg.discount_id', 'inner']
        ];
        $condition = [
            ['pd.discount_id', '=', $discount_id],
            ['pd.status', '=', 1],
            ['pdg.goods_id', '=', $goods_id],
            ['pdg.sku_id', '=', $sku_id],
        ];

        $info = model('promotion_discount')->getInfo($condition, 'pd.discount_id,pd.site_id,pd.discount_name,pdg.goods_id,pdg.sku_id,pdg.price,pdg.discount_price', $alias, $join);
        return $this->success($info);
    }

    /**
     * 获取限时折扣列表
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param string $limit
     */
    public function getDiscountList($condition = [], $field = '*', $order = '', $limit = null)
    {
        $list = model('promotion_discount')->getList($condition, $field, $order, '', '', '', $limit);
        return $this->success($list);
    }

    /**
     * 获取限时折扣分页列表
     * @param array $condition
     * @param number $page
     * @param string $page_size
     * @param string $order
     * @param string $field
     */
    public function getDiscountPageList($condition = [], $page = 1, $page_size = PAGE_LIST_ROWS, $order = '', $field = '*')
    {
        $list = model('promotion_discount')->pageList($condition, $field, $order, $page, $page_size);
        return $this->success($list);
    }

    /**
     * 删除商品营销活动标识
     * @param $discount_id
     * @param int $sku_id
     */
    private function deleteGoodsPromotionAddon($discount_id, $sku_id = 0)
    {
        $goods     = new Goods();
        $condition = [
            ['discount_id', '=', $discount_id]
        ];
        if (!empty($sku_id)) {
            $condition[] = ['sku_id', '=', $sku_id];
        }
        $goods_list = model('promotion_discount_goods')->getList($condition, 'goods_id');
        foreach ($goods_list as $k => $v) {
            $goods->modifyPromotionAddon($v['goods_id'], ['discount' => $discount_id], true);
        }

    }
}