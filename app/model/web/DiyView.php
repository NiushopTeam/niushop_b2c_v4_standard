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

namespace app\model\web;

use app\model\BaseModel;
use app\model\system\Config as ConfigModel;
use app\model\web\WebSite as WebsiteModel;
use think\Exception;
use think\facade\Cache;

/**
 * 自定义模板
 */
class DiyView extends BaseModel
{
    /**
     * 系统页面，格式：端口，页面，关键词
     * @var array
     */
    private $page = [
        'shop' => [
            'port'           => 'shop',
            'index'          => [
                'name' => 'DIYVIEW_INDEX',
            ],
            'goods_category' => [
                'name' => 'DIYVIEW_GOODS_CATEGORY'
            ]
        ],
    ];

    /**
     * 获取系统页面
     * @return array
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * 获取自定义模板组件集合
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return array
     */
    public function getDiyViewUtilList($condition = [], $field = 'id,name,title,type,controller,value,addon_name,support_diy_view,max_count', $order = 'sort asc', $limit = null)
    {
        $res = model('diy_view_util')->getList($condition, $field, $order, '', '', '', $limit);
        return $this->success($res);
    }

    /**
     * 获取自定义模板链接集合
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return array
     */
    public function getDiyLinkList($condition = [], $field = 'lk.id,lk.addon_name,nsa.title as addon_title,lk.name,lk.title,lk.web_url,lk.wap_url,lk.icon,nsa.icon as addon_icon', $order = 'nsa.id asc', $alias = 'lk', $join = [['addon nsa', 'lk.addon_name=nsa.name', 'left']], $group = '', $limit = null)
    {
        $res = model('link')->getList($condition, $field, $order, $alias, $join, $group, $limit);
        return $this->success($res);
    }

    /**
     * 添加自定义模板
     * @param array $data
     */
    public function addSiteDiyView($data)
    {
        $res = model('site_diy_view')->add($data);
        if ($res) {
            Cache::tag("site_diy_view")->clear();
            return $this->success($res);
        } else {
            return $this->error($res);
        }
    }

    /**
     * 添加自定义模板
     * @param array $data
     */
    public function addSiteDiyViewByTemplate($data)
    {
        $diy_view_info = model('site_diy_view')->getInfo([['site_id', '=', $data['site_id']], ['name', '=', $data['name']]], 'id');
        if (empty($diy_view_info)) {
            $res = model('site_diy_view')->add($data);
            if ($res) {
                Cache::tag("site_diy_view")->clear();
                return $this->success($res);
            } else {
                return $this->error($res);
            }
        } else {
            try {
                model('site_diy_view')->startTrans();
                model('site_diy_view')->update(['name' => 'DIY_VIEW_RANDOM_' . time()], [['id', '=', $diy_view_info['id']]]);
                model('site_diy_view')->add($data);
                Cache::tag("site_diy_view")->clear();
                model('site_diy_view')->commit();
                return $this->success();
            } catch (\Exception $e) {
                model('site_diy_view')->rollback();
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 添加多条自定义模板数据
     * @param $data
     * @return array
     */
    public function addSiteDiyViewList($data)
    {
        $res = model('site_diy_view')->addList($data);
        if ($res) {
            Cache::tag("site_diy_view")->clear();
            return $this->success($res);
        } else {
            return $this->error($res);
        }
    }

    /**
     * 修改自定义模板
     * @param array $data
     * @param array $condition
     * @return array
     */
    public function editSiteDiyView($data, $condition)
    {
        $res = model('site_diy_view')->update($data, $condition);
        if ($res) {
            Cache::tag("site_diy_view")->clear();
            return $this->success($res);
        } else {
            return $this->error($res);
        }
    }

    /**
     * 删除站点微页面
     * @param array $condition
     * @return array
     */
    public function deleteSiteDiyView($condition = [])
    {
        $res = model('site_diy_view')->delete($condition);
        if ($res) {
            Cache::tag("site_diy_view")->clear();
            return $this->success($res);
        } else {
            return $this->error($res);
        }
    }

    /**
     * 获取自定义模板分页数据集合
     * @param array $condition
     * @param number $page
     * @param string $page_size
     * @param string $order
     * @param string $field
     * @return array
     */
    public function getSiteDiyViewPageList($condition = [], $page = 1, $page_size = PAGE_LIST_ROWS, $order = '', $field = 'sdv.*,ndva.addon_name as addon_name_temp')
    {
        $data  = json_encode([$condition, $field, $order, $page, $page_size]);
        $cache = Cache::get("site_diy_view_getSiteDiyViewPageList_" . $data);
        if (!empty($cache)) {
            return $this->success($cache);
        }
        $alias = "sdv";
        $join  = [
            [
                'diy_view_temp ndva',
                'sdv.name=ndva.name',
                'left'
            ]
        ];

        $res = model('site_diy_view')->rawPageList($condition, $field, $order, $page, $page_size, $alias, $join);
        Cache::tag("site_diy_view")->set("site_diy_view_getSiteDiyViewPageList_" . $data, $res);
        return $this->success($res);
    }

    /**
     * 获取自定义模板信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getSiteDiyViewInfo($condition = [], $field = 'id,site_id,name,title,value,type')
    {
        $data  = json_encode($condition);
        $cache = Cache::get("site_diy_view_getSiteDiyViewInfo_" . $data);
        if (!empty($cache)) {
            return $this->success($cache);
        }

        $info = model('site_diy_view')->getInfo($condition, $field);

        Cache::tag("site_diy_view")->set("diy_view_getSiteDiyViewInfo_" . $data, $info);
        return $this->success($info);
    }

    /**
     * 获取自定义模板详细信息
     * @param array $condition
     * @return array
     */
    public function getSiteDiyViewDetail($condition = [])
    {
        $data  = json_encode($condition);
        $cache = Cache::get("site_diy_view_getSiteDiyViewDetail_" . $data);
        if (!empty($cache)) {
            return $this->success($cache);
        }
        $alias = 'sdv';
        $join  = [
            [
                'diy_view_temp dvt',
                'sdv.name=dvt.name',
                'left'
            ]
        ];
        $field = 'sdv.id,sdv.site_id,sdv.name,sdv.title,sdv.value,sdv.type,dvt.addon_name';

        $info = model('site_diy_view')->getInfo($condition, $field, $alias, $join);

        Cache::tag("site_diy_view")->set("diy_view_getSiteDiyViewDetail_" . $data, $info);
        return $this->success($info);
    }

    /**
     * 组件分类
     * @param $type
     * @return mixed
     */
    public function getTypeName($type)
    {
        $arr = [
            'SYSTEM' => '系统组件',
            'ADDON'  => '营销插件',
            'OTHER'  => '其他插件',
        ];
        return $arr[$type];
    }

    /**
     * 获取平台端的底部导航配置
     * @param $site_id
     * @return array|array
     */
    public function getBottomNavConfig($site_id)
    {
        $config = new ConfigModel();
        $res    = $config->getConfig([['site_id', '=', $site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'DIY_VIEW_SHOP_BOTTOM_NAV_CONFIG_SHOP_' . $site_id]]);
        return $res;
    }

    /**
     * 设置平台端的底部导航配置
     * @param $data
     * @param $site_id
     * @return array
     */
    public function setBottomNavConfig($data, $site_id)
    {
        $config = new ConfigModel();
        $res    = $config->setConfig($data, '店铺端自定义底部导航', 1, [['site_id', '=', $site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'DIY_VIEW_SHOP_BOTTOM_NAV_CONFIG_SHOP_' . $site_id]]);
        return $res;
    }

    /**
     * 推广二维码
     * @param $condition
     * @param string $type
     * @return array
     */
    public function qrcode($condition, $type = "create")
    {
        $check_condition = array_column($condition, 2, 0);
        $site_id         = isset($check_condition['site_id']) ? $check_condition['site_id'] : 0;

        $diy_view_info = $this->getSiteDiyViewInfo($condition, 'site_id,name');
        $page          = $this->getPage();
        $diy_view_info = $diy_view_info['data'];
        $data          = [
            'app_type'    => "all", // all为全部
            'type'        => $type, // 类型 create创建 get获取
            'site_id'     => $site_id,
            'data'        => [
                "name" => $diy_view_info['name'] . '_' . $site_id
            ],
            'page'        => '/otherpages/diy/diy/diy',
            'qrcode_path' => 'upload/qrcode/diy',
            'qrcode_name' => "diy_qrcode_" . $diy_view_info['name'] . '_' . $site_id,
        ];

        // 网站主页
        if ($diy_view_info['name'] == $page['shop']['index']['name']) {
            $data['page'] = '/pages/index/index/index';
        }

        event('Qrcode', $data, true);
        $app_type_list = config('app_type');

        $path = [];

        $config = new ConfigModel();

        foreach ($app_type_list as $k => $v) {
            switch ($k) {
                case 'h5':
                    $wap_domain         = getH5Domain();
                    $path[$k]['status'] = 1;
                    if ($diy_view_info['name'] == $page['shop']['index']) {
                        // 网站主页
                        $path[$k]['url'] = $wap_domain . $data['page'];
                    } else {
                        //自定义
                        $path[$k]['url'] = $wap_domain . $data['page'] . '?name=' . $diy_view_info['name'];
                    }
                    $path[$k]['img'] = "upload/qrcode/diy/diy_qrcode_" . $diy_view_info['name'] . '_' . $site_id . "_" . $k . ".png";
                    break;
                case 'weapp' :
                    $res = $config->getConfig([['site_id', '=', $site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'WEAPP_CONFIG']]);
                    if (!empty($res['data'])) {
                        if (empty($res['data']['value']['qrcode'])) {
                            $path[$k]['status']  = 2;
                            $path[$k]['message'] = '未配置微信小程序';
                        } else {
                            $path[$k]['status'] = 1;
                            $path[$k]['img']    = $res['data']['value']['qrcode'];
                        }

                    } else {
                        $path[$k]['status']  = 2;
                        $path[$k]['message'] = '未配置微信小程序';
                    }
                    break;

                case 'wechat' :
                    $res = $config->getConfig([['site_id', '=', $site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'WECHAT_CONFIG']]);
                    if (!empty($res['data'])) {
                        if (empty($res['data']['value']['qrcode'])) {
                            $path[$k]['status']  = 2;
                            $path[$k]['message'] = '未配置微信公众号';
                        } else {
                            $path[$k]['status'] = 1;
                            $path[$k]['img']    = $res['data']['value']['qrcode'];
                        }
                    } else {
                        $path[$k]['status']  = 2;
                        $path[$k]['message'] = '未配置微信公众号';
                    }
                    break;
            }

        }

        $return = [
            'path' => $path
        ];

        return $this->success($return);
    }

    /**
     * 获取列表
     */
    public function getTemplate()
    {
        $dirs = array_map('basename', glob('public/diy_view/*', GLOB_ONLYDIR));
        $list = [];
        foreach ($dirs as $key => $value) {
            $config_json = file_get_contents('public/diy_view/' . $value . '/config.json');
            $list[]      = json_decode($config_json, true);

        }
        return $this->success($list);
    }

    /**
     * 设置为系统页面
     * @param $port
     * @param $type
     * @param $id
     * @param $site_id
     * @return array
     */
    public function setPage($port, $type, $id, $site_id)
    {
        model('site_diy_view')->startTrans();
        try {
            $name = $this->page[$port][$type]['name'];
            model('site_diy_view')->update(['name' => 'DIY_VIEW_RANDOM_' . time()], [['name', '=', $name], ['site_id', '=', $site_id]]);
            model('site_diy_view')->update(['name' => $name], [['id', '=', $id], ['site_id', '=', $site_id]]);
            Cache::tag("site_diy_view")->clear();
            model('site_diy_view')->commit();
            return $this->success();
        } catch (\Exception $e) {
            model('site_diy_view')->rollback();
            return $this->error($e->getMessage());
        }
    }
}