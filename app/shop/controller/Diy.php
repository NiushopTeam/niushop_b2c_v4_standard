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

use app\model\system\Addon;
use app\model\system\DiyTemplate;
use app\model\web\DiyView as DiyViewModel;
use app\model\system\Config as ConfigModel;
use app\model\web\DiyViewLink;

/**
 * 网站装修控制器
 */
class Diy extends BaseShop
{
    /**
     * 网站主页
     */
    public function index()
    {
        $diy_view = new DiyViewModel();
        $page     = $diy_view->getPage();

        // 查询公共组件和支持的页面
        $condition = [
            ['support_diy_view', 'like', [$page['shop']['index']['name'], '%' . $page['shop']['index']['name'] . ',%', '%' . $page['shop']['index']['name'], '%,' . $page['shop']['index']['name'] . ',%', 'DIY_VIEW_SHOP', ''], 'or']
        ];

        $data      = [
            'app_module' => $this->app_module,
            'site_id'    => $this->site_id,
            'name'       => $page['shop']['index']['name'],
            'condition'  => $condition
        ];
        $edit_view = event('DiyViewEdit', $data, true);
        return $edit_view;
    }

    /**
     * 商品分类页面
     */
    public function goodsCategory()
    {
        $diy_view = new DiyViewModel();
        $page     = $diy_view->getPage();
        // 查询公共组件和支持的页面
        $condition = [
            ['name', '=', 'GOODS_CATEGORY']
        ];

        $data      = [
            'app_module' => $this->app_module,
            'site_id'    => $this->site_id,
            'name'       => $page['shop']['goods_category']['name'],
            'condition'  => $condition
        ];
        $edit_view = event('DiyViewEdit', $data, true);
        return $edit_view;
    }

    /**
     * 编辑
     */
    public function edit()
    {
        if (request()->isAjax()) {
            $res   = 0;
            $data  = array();
            $id    = input("id", 0);
            $name  = input("name", "");
            $title = input("title", "");
            $value = input("value", "");
            if (!empty($name) && !empty($title) && !empty($value)) {
                $diy_view        = new DiyViewModel();
                $page            = $diy_view->getPage();
                $data['site_id'] = $this->site_id;
                $data['name']    = $name;
                $data['title']   = $title;
                $data['type']    = $page['shop']['port'];
                $data['value']   = $value;
                if ($id == 0 && $name != 'DIYVIEW_INDEX') {
                    $data['create_time'] = time();
                    $res                 = $diy_view->addSiteDiyView($data);
                } else {
                    $data['update_time'] = time();
                    $res                 = $diy_view->editSiteDiyView($data, [
                        ['id', '=', $id]
                    ]);
                }
            }

            return $res;
        } else {

            $id = input("id", 0);
            //查询公共系统组件
            $condition = [
                ['support_diy_view', 'like', ['', 'DIY_VIEW_SHOP'], 'or']
            ];
            $data      = [
                'app_module' => $this->app_module,
                'site_id'    => $this->site_id,
                'id'         => $id,
                'condition'  => $condition
            ];
            $edit_view = event('DiyViewEdit', $data, true);
            return $edit_view;
        }
    }

    /**
     * 微页面
     */
    public function lists()
    {
        if (request()->isAjax()) {
            $page_index = input('page', 1);
            $page_size  = input('page_size', PAGE_LIST_ROWS);
            $diy_view   = new DiyViewModel();
            $page       = $diy_view->getPage();
            $condition  = array(
                ['sdv.site_id', '=', $this->site_id],
                ['sdv.type', '=', $page['shop']['port']],
            );
            $list       = $diy_view->getSiteDiyViewPageList($condition, $page_index, $page_size, "INSTR('DIYVIEW_INDEX', sdv.name) desc, sdv.create_time desc");
            return $list;
        } else {
            return $this->fetch('diy/lists');
        }
    }

    /**
     * 链接选择
     */
    public function link()
    {
        $link             = input("link", '');
        $support_diy_view = input("support_diy_view", '');//支持的自定义页面（为空表示都支持）
        $link_model       = new DiyViewLink();
        $condition        = [
            ['parent', '=', '']
        ];
        $list_result      = $link_model->getLinkList($condition, '*', 'sort ASC');
        $list             = $list_result['data'];
        foreach ($list as $k => $v) {

            $child_condition        = [
                ['parent', '=', $v['name']]
            ];
            $child_list_result      = $link_model->getLinkList($child_condition, '*', 'sort ASC');
            $child_list             = $child_list_result['data'];
            $list[$k]['child_list'] = $child_list;
        }
        $this->assign('list', $list);
        $this->assign("link", $link);
        $this->assign("support_diy_view", $support_diy_view);
        return $this->fetch('diy/link');
    }

    public function childLink()
    {
        $link             = input("link", []);
        $support_diy_view = input("support_diy_view", '');//支持的自定义页面（为空表示都支持）
        $name             = input('name', '');
        $is_array         = true;//记录是否是数组，后续判断受该变量影响
        if (!empty($link)) {
            $link     = json_decode($link, true);
            $is_array = is_array($link);
        }

        $condition   = [
            ['parent', '=', $name],
            ['support_diy_view', 'like', [$support_diy_view, '', 'DIY_VIEW_SHOP'], 'or']
        ];
        $link_model  = new DiyViewLink();
        $list_result = $link_model->getLinkList($condition, '*', 'sort ASC');
        $list        = $list_result['data'];

        $temp_link = [];

        foreach ($list as $k => $v) {
            $child_condition   = [
                ['parent', '=', $v['name']],
                ['support_diy_view', 'like', [$support_diy_view, '', 'DIY_VIEW_SHOP'], 'or']
            ];
            $child_list_result = $link_model->getLinkList($child_condition, '*', 'sort ASC');
            $child_list        = $child_list_result['data'];

            foreach ($child_list as $item => $value) {
                if ($value['addon_name'] == '') {

                    if (!empty($link) && $is_array && $link['name'] == $value['name']) {
                        //对象方式匹配
                        $child_list[$item]['selected'] = true;
                    } elseif (!empty($link) && !$is_array && strtolower($link) == strtolower($value['wap_url'])) {
                        //字符串方式匹配
                        $child_list[$item]['selected'] = true;
                        $temp_link                     = $value;
                    } else {
                        $child_list[$item]['selected'] = false;
                    }
                }
            }
            $list[$k]['child_list'] = $child_list;
        }
        if (!$is_array) {
            $link = $temp_link;
        }

        return [
            'list' => $list,
            'link' => $link
        ];

        //查询当前站点的微页面，暂时不显示
//		$page = $diy_view_model->getPage();
//		$condition = array(
//			'sdv.site_id' => $this->site_id,
//			'sdv.type' => $page['shop']['port'],
//			'ndva.addon_name' => null, //排除插件中的自定义模板
//		);

//		$site_diy_view_list = $diy_view_model->getSiteDiyViewPageList($condition, 1, 0, "sdv.create_time desc");
//		$site_diy_view_list = $site_diy_view_list['data']['list'];

//		$addon_model = new Addon();

//		foreach ($site_diy_view_list as $k => $v) {
//			$value = array();
//			$addon_info = $addon_model->getAddonInfo([ 'name' => $v['addon_name'] ], 'title,icon');
//			$addon_info = $addon_info ['data'];
//			$value['addon_name'] = $v['addon_name'];
//			$value['addon_title'] = $addon_info['title'];
//			$value['icon'] = $addon_info['icon'];
//			$value['list'] = [];
//			$column = array_column($link_list, 'addon_name');
//			if (!in_array($v['addon_name'], $column)) {
//				array_push($link_list, $value);
//			}
//		}
        //遍历微页面
//			foreach ($site_diy_view_list as $page_k => $page_v) {
//
//				if ($diy_v['addon_name'] == $page_v['addon_name']) {
//
//					$item = [
//						'id' => $page_v['id'],
//						'name' => $page_v['name'],
//						'title' => $page_v['title'],
//						'addon_icon' => "",
//						'addon_name' => $page_v['addon_name'],
//						'addon_title' => $diy_v['addon_title'],
//						'web_url' => '',
//						'wap_url' => '/pages/index/diy/diy?name=' . $page_v['name'],
//						'icon' => '',
//						'type' => 0
//					];
//
//					if (!empty($link) && $is_array && $link['name'] == $page_v['name']) {
//						//对象方式匹配
//						$item['selected'] = true;
//					} elseif (!empty($link) && !$is_array && strtolower($link) == strtolower($page_v['wap_url'])) {
//						//字符串方式匹配
//						$item['selected'] = true;
//						$temp_link = $page_v;
//					} else {
//						$item['selected'] = false;
//					}
//					array_push($link_list[ $diy_k ]['list'], $item);
//
//				}
//			}


//        $this->assign("link", $link);
//        $this->assign("link_list", $link_list);
    }

    /**
     * 删除自定义模板页面
     */
    public function deleteSiteDiyView()
    {
        if (request()->isAjax()) {
            $diy_view  = new DiyViewModel();
            $id_array  = input("id", 0);
            $condition = [
                ['id', 'in', $id_array]
            ];
            $res       = $diy_view->deleteSiteDiyView($condition);
            return $res;
        }
    }

    /**
     * 底部导航
     */
    public function bottomNavDesign()
    {
        $diy_view = new DiyViewModel();
        if (request()->isAjax()) {
            $value = input("value", "");
            $res   = $diy_view->setBottomNavConfig($value, $this->site_id);
            return $res;
        } else {
            $bottom_nav_info = $diy_view->getBottomNavConfig($this->site_id);
            $this->assign("bottom_nav_info", $bottom_nav_info['data']['value']);
            return $this->fetch('diy/bottom_nav_design');
        }

    }

    /**
     * 推广链接
     */
    public function promote()
    {
        if (request()->isAjax()) {
            $id       = input("id", 0);
            $diy_view = new DiyViewModel();
            $res      = $diy_view->qrcode([
                ['site_id', '=', $this->site_id],
                ['id', '=', $id]
            ]);
            return $res;
        }
    }

    /**
     * 店铺风格
     */
    public function style()
    {
        $config_model = new ConfigModel();
        if (request()->isAjax()) {
            $data = [
                'style_theme' => input('style_theme', '')
            ];

            $res = $config_model->setConfig($data, '店铺风格设置', '1', [['site_id', '=', $this->site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'SHOP_STYLE_CONFIG']]);
            return $res;
        }

        $res         = $config_model->getConfig([['site_id', '=', $this->site_id], ['app_module', '=', 'shop'], ['config_key', '=', 'SHOP_STYLE_CONFIG']]);
        $style_theme = empty($res['data']['value']) ? [] : $res['data']['value'];
        $this->assign('style_theme', $style_theme);
        return $this->fetch('diy/style');
    }

    public function template()
    {
        $diy_view = new DiyViewModel();
        $page     = $diy_view->getPage();

        if (request()->isAjax()) {
            $diy_view   = new DiyTemplate();
            $page_index = input('page', 1);
            $page_size  = input('page_size', PAGE_LIST_ROWS);
            $condition  = [
                ['type', '=', $page['shop']['index']['name']]
            ];
            $data       = $diy_view->getTemplatePageList($condition, '*', 'id asc', $page_index, $page_size);
            return $data;
        } else {
            return $this->fetch('diy/template');
        }
    }

    /**
     * 创建
     */
    public function create()
    {
        if (request()->isAjax()) {
            $res   = 0;
            $name  = input("name", "");
            $title = input("title", "");
            $value = input("value", "");
            $id    = input("id", 0);
            if (!empty($name) && !empty($title) && !empty($value)) {
                $diy_view = new DiyViewModel();
                $page     = $diy_view->getPage();
                $data     = [
                    'site_id'     => $this->site_id,
                    'name'        => $name,
                    'title'       => $title,
                    'type'        => $page['shop']['port'],
                    'value'       => $value,
                    'template_id' => $id,
                    'create_time' => time()
                ];
                $res      = $diy_view->addSiteDiyViewByTemplate($data);
            }
            return $res;
        } else {
            $template_id = input('id', 0);
            $data        = [
                'app_module'  => $this->app_module,
                'site_id'     => $this->site_id,
                'template_id' => $template_id
            ];
            $edit_view   = event('DiyViewCreate', $data, true);
            return $edit_view;
        }
    }

    /**
     * 设为主页
     */
    public function homePage()
    {
        if (request()->isAjax()) {
            $diy_view = new DiyViewModel();
            $id       = input('id', 0);
            $res      = $diy_view->setPage('shop', 'index', $id, $this->site_id);
            return $res;
        }
    }
}