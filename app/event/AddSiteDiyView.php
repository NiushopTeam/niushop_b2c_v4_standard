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

namespace app\event;

use app\model\web\DiyView as DiyViewModel;


/**
 * 增加默认自定义数据：网站主页、商品分类、底部导航
 */
class AddSiteDiyView
{

    public function handle($param)
    {
        if (!empty($param[ 'site_id' ])) {

            $diy_view_model = new DiyViewModel();

            // 添加自定义主页装修
            $value = json_encode([
                "global" => [
                    "bgColor" => "",
                    "title" => "网站主页",
                    "openBottomNav" => false,
                    "bgUrl" => "upload/default/diy_view/index_bg.png"
                ],
                "value" => [
                    [
                        "addon_name" => "",
                        "type" => "SEARCH",
                        "name" => "商品搜索",
                        "controller" => "Search",
                        "textColor" => "#3b3a3b",
                        "backgroundColor" => "#ff4444",
                        "bgColor" => "#ffffff",
                        "borderType" => 2,
                        "textAlign" => "left",
                        "defaultTextColor" => "#999999"
                    ],
                    [
                        "selectedTemplate" => "carousel-posters",
                        "imageClearance" => 0,
                        "padding" => 0,
                        "height" => 0,
                        "list" => [
                            [
                                "imageUrl" => "upload/default/diy_view/posters_1.png",
                                "title" => "",
                                "link" => [
                                    "name" => ""
                                ]
                            ],
                            [
                                "imageUrl" => "upload/default/diy_view/posters_2.png",
                                "title" => "",
                                "link" => [
                                    "name" => ""
                                ]
                            ]
                        ],
                        "addon_name" => "",
                        "type" => "IMAGE_ADS",
                        "name" => "图片广告",
                        "controller" => "ImageAds"
                    ],
                    [
                        "textColor" => "#666666",
                        "backgroundColor" => "#ffffff",
                        "selectedTemplate" => "imageNavigation",
                        "scrollSetting" => "fixed",
                        "imageScale" => "70",
                        "padding" => 0,
                        "list" => [
                            [
                                "imageUrl" => "upload/default/diy_view/nav/coupon.png",
                                "title" => "优惠券",
                                "link" => [
                                    "id" => 2870,
                                    "addon_name" => "",
                                    "addon_title" => null,
                                    "name" => "COUPON_LIST",
                                    "title" => "领券中心",
                                    "web_url" => "",
                                    "wap_url" => "/otherpages/goods/coupon/coupon",
                                    "icon" => "",
                                    "addon_icon" => null,
                                    "selected" => false
                                ]
                            ],
                            [
                                "imageUrl" => "upload/default/diy_view/nav/help.png",
                                "title" => "帮助",
                                "link" => [
                                    "id" => 2868,
                                    "addon_name" => "",
                                    "addon_title" => null,
                                    "name" => "HELP_LIST",
                                    "title" => "帮助中心",
                                    "web_url" => "",
                                    "wap_url" => "/otherpages/help/list/list",
                                    "icon" => "",
                                    "addon_icon" => null,
                                    "selected" => false
                                ]
                            ],
                            [
                                "imageUrl" => "upload/default/diy_view/nav/notice.png",
                                "title" => "公告",
                                "link" => [
                                    "id" => 2867,
                                    "addon_name" => "",
                                    "addon_title" => null,
                                    "name" => "NOTICE_LIST",
                                    "title" => "公告列表",
                                    "web_url" => "",
                                    "wap_url" => "/otherpages/notice/list/list",
                                    "icon" => "",
                                    "addon_icon" => null,
                                    "selected" => false
                                ]
                            ],
                            [
                                "imageUrl" => "upload/default/diy_view/nav/member.png",
                                "title" => "会员中心",
                                "link" => [
                                    "id" => 2866,
                                    "addon_name" => "",
                                    "addon_title" => null,
                                    "name" => "MEMBER_INDEX",
                                    "title" => "会员中心",
                                    "web_url" => "",
                                    "wap_url" => "/pages/member/index/index",
                                    "icon" => "",
                                    "addon_icon" => null,
                                    "selected" => false
                                ]
                            ]
                        ],
                        "addon_name" => "",
                        "type" => "GRAPHIC_NAV",
                        "name" => "图文导航",
                        "controller" => "GraphicNav",
                        "paddingTopBottom" => 0,
                        "paddingLeftRight" => 10,
                        "defaultTextColor" => "#666666",
                        "borderTopLeftRadius" => 0,
                        "borderTopRightRadius" => 0,
                        "borderBottomLeftRadius" => 10,
                        "borderBottomRightRadius" => 10
                    ],
                    [
                        "height" => 10,
                        "backgroundColor" => "#ffffff",
                        "addon_name" => "",
                        "type" => "HORZ_BLANK",
                        "name" => "辅助空白",
                        "controller" => "HorzBlank",
                        "marginLeftRight" => 10
                    ],
                    [
                        "backgroundColor" => "",
                        "addon_name" => "",
                        "type" => "HORZ_BLANK",
                        "name" => "辅助空白",
                        "controller" => "HorzBlank",
                        "height" => 10,
                        "marginLeftRight" => 0
                    ],
                    [
                        "list" => [
                            [
                                "link" => [
                                    "name" => ""
                                ],
                                "title" => "NiuShopB2cV4版上线啦！"
                            ],
                            [
                                "link" => [
                                    "name" => ""
                                ],
                                "title" => "NiuShopB2cV4版上线啦"
                            ]
                        ],
                        "addon_name" => "",
                        "type" => "NOTICE",
                        "name" => "公告",
                        "controller" => "Notice",
                        "backgroundColor" => "#ffffff",
                        "sources" => "default",
                        "paddingLeftRight" => 10,
                        "paddingTopBottom" => 0,
                        "style" => 1,
                        "isEdit" => 1,
                        "textColor" => "#333333",
                        "fontSize" => 14,
                        "noticeIds" => [],
                        "defaultTextColor" => "#333333"
                    ],
                    [
                        "backgroundColor" => "",
                        "addon_name" => "",
                        "type" => "RUBIK_CUBE",
                        "name" => "魔方",
                        "controller" => "RubikCube",
                        "selectedTemplate" => "row1-lt-of2-rt",
                        "list" => [
                            [
                                "imageUrl" => "upload/default/diy_view/mofang_zhekozhuanqu.png",
                                "link" => [
                                    "name" => ""
                                ]
                            ],
                            [
                                "imageUrl" => "upload/default/diy_view/mofang_brand_zhuanqu.png",
                                "link" => [
                                    "name" => ""
                                ]
                            ],
                            [
                                "imageUrl" => "upload/default/diy_view/mofang_xianshizheko.png",
                                "link" => [
                                    "name" => ""
                                ]
                            ]
                        ],
                        "selectedRubikCubeArray" => [],
                        "diyHtml" => "",
                        "customRubikCube" => 4,
                        "heightArray" => [
                            "74.25px",
                            "59px",
                            "48.83px",
                            "41.56px"
                        ],
                        "imageGap" => 0
                    ],
                    [
                        "style" => 1,
                        "backgroundColor" => "",
                        "padding" => 10,
                        "addon_name" => "coupon",
                        "type" => "COUPON",
                        "name" => "优惠券",
                        "controller" => "Coupon",
                        "sources" => "default",
                        "couponCount" => "6",
                        "status" => 1,
                        "couponIds" => []
                    ],
                    [
                        "style" => "10",
                        "backgroundColor" => "",
                        "addon_name" => "",
                        "type" => "TEXT",
                        "name" => "文本",
                        "controller" => "Text",
                        "title" => "精品推荐",
                        "subTitle" => "",
                        "padding" => 0,
                        "link" => [
                            "name" => ""
                        ],
                        "fontSize" => 16,
                        "fontSizeSub" => 14,
                        "colorSub" => "#999",
                        "sub" => "1",
                        "isShowMore" => 0,
                        "fontWeight" => 600,
                        "moreText" => "查看更多",
                        "moreLink" => [],
                        "btnColor" => "#999",
                        "textColor" => "#333333",
                        "defaultTextColor" => "#333333",
                        "defaultColorSub" => "#999",
                        "defaultBtnColor" => "#999"
                    ],
                    [
                        "sources" => "default",
                        "categoryId" => 0,
                        "goodsCount" => "6",
                        "goodsId" => [],
                        "style" => 1,
                        "backgroundColor" => "",
                        "paddingUpDown" => 0,
                        "paddingLeftRight" => 0,
                        "isShowCart" => 0,
                        "cartStyle" => 1,
                        "isShowGoodName" => 1,
                        "isShowMarketPrice" => 1,
                        "isShowGoodSaleNum" => 1,
                        "isShowGoodSubTitle" => 1,
                        "addon_name" => "",
                        "type" => "GOODS_LIST",
                        "name" => "商品列表",
                        "controller" => "GoodsList"
                    ]
                ]
            ]);

            // 网站主页
            $data = [
                [
                    'site_id' => $param[ 'site_id' ],
                    'title' => '网站主页',
                    'name' => 'DIYVIEW_INDEX',
                    'type' => 'shop',
                    'value' => $value
                ],
                [
                    'site_id' => $param[ 'site_id' ],
                    'title' => '商品分类',
                    'name' => "DIYVIEW_GOODS_CATEGORY",
                    'type' => "shop",
                    'value' => json_encode([
                        "global" => [
                            "title" => "商品分类",
                            "openBottomNav" => false,
                            "bgColor" => "#ffffff",
                            "bgUrl" => ""
                        ],
                        "value" => [
                            [
                                "addon_name" => "",
                                "type" => "GOODS_CATEGORY",
                                "name" => "商品分类",
                                "controller" => "GoodsCategory",
                                "level" => 3,
                                "template" => 2
                            ]
                        ]
                    ])
                ]
            ];

            $res = $diy_view_model->addSiteDiyViewList($data);

            $diy_view_bottom_nav = [
                "type" => 1,
                "backgroundColor" => "#ffffff",
                "textColor" => "#000000",
                "textHoverColor" => "#fa0036",
                "bulge" => true,
                "list" => [
                    [
                        "iconPath" => "upload/default/diy_view/bottom/index.png",
                        "selectedIconPath" => "upload/default/diy_view/bottom/index_selected.png",
                        "text" => "首页",
                        "link" => [
                            "addon_name" => "",
                            "addon_title" => null,
                            "name" => "INDEX",
                            "title" => "主页",
                            "web_url" => "",
                            "wap_url" => "/pages/index/index/index",
                            "icon" => "",
                            "addon_icon" => null,
                            "selected" => false,
                            "type" => 0
                        ]
                    ],
                    [
                        "iconPath" => "upload/default/diy_view/bottom/category.png",
                        "selectedIconPath" => "upload/default/diy_view/bottom/category_selected.png",
                        "text" => "分类",
                        "link" => [
                            "addon_name" => "",
                            "addon_title" => null,
                            "name" => "GOODS_CATEGORY",
                            "title" => "商品分类",
                            "web_url" => "",
                            "wap_url" => "/pages/goods/category/category",
                            "icon" => "",
                            "addon_icon" => null,
                            "selected" => false
                        ]
                    ],
                    [ "iconPath" => "upload/default/diy_view/bottom/cart.png",
                        "selectedIconPath" => "upload/default/diy_view/bottom/cart_selected.png",
                        "text" => "购物车",
                        "link" => [
                            "addon_name" => "",
                            "addon_title" => null,
                            "name" => "GOODS_CART",
                            "title" => "购物车",
                            "web_url" => "",
                            "wap_url" => "/pages/goods/cart/cart",
                            "icon" => "",
                            "addon_icon" => null,
                            "selected" => false
                        ],
                    ],
                    [ "iconPath" => "upload/default/diy_view/bottom/member_index.png",
                        "selectedIconPath" => "upload/default/diy_view/bottom/member_index_selected.png",
                        "text" => "我的",
                        "link" => [
                            "addon_name" => "",
                            "addon_title" => null,
                            "name" => "MEMBER_INDEX",
                            "title" => "会员中心",
                            "web_url" => "",
                            "wap_url" => "/pages/member/index/index",
                            "icon" => "",
                            "addon_icon" => null,
                            "selected" => false
                        ]
                    ]
                ]

            ];

            //底部导航
            $result = $diy_view_model->setBottomNavConfig(json_encode($diy_view_bottom_nav), $param[ 'site_id' ]);

            return $res;

        }

    }

}