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

namespace app\model\goods;

use think\facade\Db;
use think\facade\Cache;
use app\model\BaseModel;

/**
 * 商品评价
 */
class GoodsEvaluate extends BaseModel
{
    private $evaluate_status = [
        0 => '未评价',
        1 => '已评价',
        2 => '已追评'
    ];

    /**
     * 添加评价
     * @param array $data
     */
    public function addEvaluate($data, $site_id)
    {
        $res = model('goods_evaluate')->getInfo([['order_id', '=', $data['order_id']]], 'evaluate_id');
        if (empty($res)) {
            $data_arr = [];
            foreach ($data['goods_evaluate'] as $k => $v) {
                $item       = [
                    'order_id'       => $data['order_id'],
                    'order_no'       => $data['order_no'],
                    'member_id'      => $data['member_id'],
                    'member_name'    => $data['member_name'],
                    'member_headimg' => $data['member_headimg'],
                    'is_anonymous'   => $data['is_anonymous'],
                    'order_goods_id' => $v['order_goods_id'],
                    'goods_id'       => $v['goods_id'],
                    'sku_id'         => $v['sku_id'],
                    'site_id'        => $site_id,
                    'sku_name'       => $v['sku_name'],
                    'sku_price'      => $v['sku_price'],
                    'sku_image'      => $v['sku_image'],
                    'content'        => !empty($v['content']) ? $v['content'] : '此用户没有填写评价。',
                    'images'         => $v['images'],
                    'scores'         => $v['scores'],
                    'explain_type'   => $v['explain_type'],
                    'create_time'    => time()
                ];
                $data_arr[] = $item;

                $evaluate           = 0; //评价
                $evaluate_shaitu    = 0; //晒图
                $evaluate_shipin    = 0; //视频
                $evaluate_haoping   = 0; //好评
                $evaluate_zhongping = 0; //中评
                $evaluate_chaping   = 0; //差评
                if ($v['explain_type'] == 1) {
                    //好评
                    $evaluate         = 1; //评价
                    $evaluate_haoping = 1; //好评

                } elseif ($v['explain_type'] == 2) {
                    //中评
                    $evaluate           = 1; //评价
                    $evaluate_zhongping = 1; //中评

                } elseif ($v['explain_type'] == 3) {
                    //差评
                    $evaluate         = 1; //评价
                    $evaluate_chaping = 1; //差评
                }
                if (!empty($v['images'])) {
                    $evaluate_shaitu = 1; //晒图
                }
                Db::name('goods')->where([['goods_id', '=', $v['goods_id']]])
                    ->update(
                        [
                            "evaluate"           => Db::raw('evaluate+' . $evaluate),
                            "evaluate_shaitu"    => Db::raw('evaluate_shaitu+' . $evaluate_shaitu),
                            "evaluate_haoping"   => Db::raw('evaluate_haoping+' . $evaluate_haoping),
                            "evaluate_zhongping" => Db::raw('evaluate_zhongping+' . $evaluate_zhongping),
                            "evaluate_chaping"   => Db::raw('evaluate_chaping+' . $evaluate_chaping),
                        ]);
                Db::name('goods_sku')->where([['sku_id', '=', $v['sku_id']]])
                    ->update(
                        [
                            "evaluate"           => Db::raw('evaluate+' . $evaluate),
                            "evaluate_shaitu"    => Db::raw('evaluate_shaitu+' . $evaluate_shaitu),
                            "evaluate_haoping"   => Db::raw('evaluate_haoping+' . $evaluate_haoping),
                            "evaluate_zhongping" => Db::raw('evaluate_zhongping+' . $evaluate_zhongping),
                            "evaluate_chaping"   => Db::raw('evaluate_chaping+' . $evaluate_chaping),
                        ]);
            }

//			修改订单表中的评价标识
            model("order")->update(['is_evaluate' => 1, 'evaluate_status' => 1, 'evaluate_status_name' => $this->evaluate_status[1]], [['order_id', '=', $data['order_id']]]);
            $evaluate_id = model('goods_evaluate')->addList($data_arr);
            Cache::tag("goods_evaluate")->clear();
            return $this->success($evaluate_id);
        } else {
            return $this->error();
        }

    }

    /**
     * 评价回复
     * @param unknown $data
     */
    public function evaluateApply($data)
    {
        $res = model("goods_evaluate")->update($data, [['evaluate_id', '=', $data['evaluate_id']]]);
        Cache::tag("goods_evaluate")->clear();
        return $this->success($res);
    }

    /**
     * 修改评价
     * @param $data
     * @param array $condition
     * @return array
     */
    public function editEvaluate($data, $condition = [])
    {
        $res = model("goods_evaluate")->update($data, $condition);
        Cache::tag("goods_evaluate")->clear();
        return $this->success($res);
    }

    /**
     * 追评
     * @param unknown $data
     * @return multitype:string
     */
    public function evaluateAgain($data)
    {
        foreach ($data['goods_evaluate'] as $k => $v) {
            $item = [
                'order_id'       => $data['order_id'],
                'order_goods_id' => $v['order_goods_id'],
                'goods_id'       => $v['goods_id'],
                'sku_id'         => $v['sku_id'],
                'again_content'  => $v['again_content'],
                'again_images'   => $v['again_images'],
                'again_time'     => time()
            ];
            $res  = model("goods_evaluate")->update($item, [['order_goods_id', '=', $v['order_goods_id']]]);
            if ($res) {
                model("goods")->setInc([['goods_id', '=', $v['goods_id']]], 'evaluate_zhuiping', 1);
                model("goods_sku")->setInc([['sku_id', '=', $v['sku_id']]], 'evaluate_zhuiping', 1);
            }
        }
        model("order")->update(['is_evaluate' => 0, 'evaluate_status' => 2, 'evaluate_status_name' => $this->evaluate_status[2]], [['order_id', '=', $data['order_id']]]);
        Cache::tag("goods_evaluate")->clear();
        return $this->success($res);
    }

    /**
     * 删除评价
     * @param unknown $condition
     */
    public function deleteEvaluate($evaluate_id)
    {
        $res = model('goods_evaluate')->delete(['evaluate_id' => $evaluate_id]);
        Cache::tag("goods_evaluate")->clear();
        return $this->success($res);
    }

    /**
     * 获取评价信息
     * @param $condition
     * @param $field
     * @param $order
     * @return \multitype
     */
    public function getFirstEvaluateInfo($condition, $field = 'evaluate_id,order_goods_id,goods_id,sku_id,sku_name,sku_price,content,images,explain_first,member_name,member_headimg,member_id,is_anonymous,again_content,again_images,again_explain,create_time,again_time', $order = "create_time desc")
    {
        $data  = json_encode([$condition, $field]);
        $cache = Cache::get("goods_evaluate_getFirstEvaluateInfo_" . $data);
        if (!empty($cache)) {
            return $this->success($cache);
        }
        $info = model('goods_evaluate')->getFirstData($condition, $field, $order);
        Cache::tag("goods_evaluate")->set("goods_evaluate_getFirstEvaluateInfo_" . $data, $info);
        return $this->success($info);
    }

    /**
     * 获取评价列表
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param string $limit
     */
    public function getEvaluateList($condition = [], $field = 'evaluate_id, order_id, order_no, order_goods_id, goods_id, sku_id, sku_name, sku_price, sku_image, content, images, explain_first, member_name, member_id, is_anonymous, scores, again_content, again_images, again_explain, explain_type, is_show, create_time, again_time,shop_desccredit,shop_servicecredit,shop_deliverycredit', $order = '', $limit = null)
    {
        $data  = json_encode([$condition, $field, $order, $limit]);
        $cache = Cache::get("goods_evaluate_getEvaluateList_" . $data);
        if (!empty($cache)) {
            return $this->success($cache);
        }
        $list = model('goods_evaluate')->getList($condition, $field, $order, '', '', '', $limit);
        Cache::tag("goods_evaluate")->set("goods_evaluate_getEvaluateList_" . $data, $list);
        return $this->success($list);
    }

    /**
     * 获取评价分页列表
     * @param array $condition
     * @param number $page
     * @param string $page_size
     * @param string $order
     * @param string $field
     */
    public function getEvaluatePageList($condition = [], $page = 1, $page_size = PAGE_LIST_ROWS, $order = 'create_time desc', $field = 'evaluate_id, order_id, order_no, order_goods_id, goods_id, sku_id, sku_name, sku_price, sku_image, content, images, explain_first, member_name,member_headimg, member_id, is_anonymous, scores, again_content, again_images, again_explain, explain_type, is_show, create_time, again_time,shop_desccredit,shop_servicecredit,shop_deliverycredit')
    {
        $data  = json_encode([$condition, $field, $order, $page, $page_size]);
        $cache = Cache::get("goods_evaluate_getEvaluatePageList_" . $data);
        if (!empty($cache)) {
            return $this->success($cache);
        }
        $list = model('goods_evaluate')->pageList($condition, $field, $order, $page, $page_size);
        Cache::tag("goods_evaluate")->set("goods_evaluate_getEvaluatePageList_" . $data, $list);
        return $this->success($list);
    }

}