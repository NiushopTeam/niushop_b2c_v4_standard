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
return [
    'template' => [
//		[
//			'name' => 'DIYVIEW_INDEX',
//			'title' => '网站主页',
//			'value' => '',
//			'type' => 'SHOP',
//			'icon' => ''
//		],
    ],
    'util' => [
        [
            'name' => 'TEXT',
            'title' => '文本',
            'type' => 'SYSTEM',
            'controller' => 'Text',
            'value' => '{ title : "『文本』", textColor : "#333333", "defaultTextColor": "#333333", subTitle : "副标题", "padding": 0, backgroundColor : "", "link" : {}, "fontSize" : 16, "fontSizeSub" : 14, "colorSub": "#999", "defaultColorSub": "#999", "style": 1, "sub": 0, "isShowMore": 0, "fontWeight": 600, "moreText": "查看更多", "moreLink" : {}, "btnColor": "#999", "defaultBtnColor": "#999" }',
            'sort' => '10000',
            'support_diy_view' => '',
            'max_count' => 0
        ],
        [
            'name' => 'TEXT_NAV',
            'title' => '文本导航',
            'type' => 'SYSTEM',
            'controller' => 'TextNav',
            'value' => '{ fontSize : 14, textColor : "#333333", "defaultTextColor" : "#333333", textAlign : "left", backgroundColor : "", arrangement : "vertical", list : [{ text : "『文本导航』",secondText : "","link" : {}}] }',
            'sort' => '10001',
            'support_diy_view' => '',
            'max_count' => 0
        ],
        [
            'name' => 'NOTICE',
            'title' => '公告',
            'type' => 'SYSTEM',
            'controller' => 'Notice',
            'value' => '{ "sources": "default","backgroundColor": "", "paddingLeftRight": 0, "paddingTopBottom": 10, "style": 1, "isEdit": 1, "textColor": "#333333", "defaultTextColor": "#333333", "fontSize": 14,"list": [{"title": "公告","link": {}},{"title": "公告","link": {}}], "noticeIds": []}',
            'sort' => '10002',
            'support_diy_view' => '',
            'max_count' => 0
        ],
        [
            'name' => 'GRAPHIC_NAV',
            'title' => '图文导航',
            'type' => 'SYSTEM',
            'controller' => 'GraphicNav',
            'value' => '{ "textColor": "#666666","defaultTextColor": "#666666","backgroundColor": "","selectedTemplate": "imageNavigation","scrollSetting": "fixed","imageScale": 100,padding : 0, "paddingTopBottom": 0, "paddingLeftRight": 0, "list": [{"imageUrl": "","title": "","link": {}},{"imageUrl": "","title": "","link": {}},{"imageUrl": "","title": "","link": {}},{"imageUrl": "","title": "","link": {}}], "borderTopLeftRadius": 0, "borderTopRightRadius": 0, "borderBottomLeftRadius": 0, "borderBottomRightRadius": 0}',
            'sort' => '10003',
            'support_diy_view' => '',
            'max_count' => 0
        ],
        [
            'name' => 'IMAGE_ADS',
            'title' => '图片广告',
            'type' => 'SYSTEM',
            'controller' => 'ImageAds',
            'value' => '{ selectedTemplate : "carousel-posters", imageClearance : 0, padding : 0, height : 0, list : [ { imageUrl : "", title : "", "link" : {}} ] }',
            'sort' => '10004',
            'support_diy_view' => '',
            'max_count' => 0
        ],
        [
            'name' => 'SEARCH',
            'title' => '商品搜索',
            'type' => 'SYSTEM',
            'controller' => 'Search',
            'value' => '{ "textColor": "#999999", "textAlign" : "left", "backgroundColor" : "", "bgColor": "", "defaultTextColor": "#999999", "borderType": 2 }',
            'sort' => '10005',
            'support_diy_view' => '',
            'max_count' => 1
        ],
        [
            'name' => 'TITLE',
            'title' => '顶部标题',
            'type' => 'SYSTEM',
            'controller' => 'Title',
            'value' => '{ "title": "『顶部标题』","backgroundColor": "","textColor": "#000000","defaultTextColor": "#000000","isOpenOperation" : false,"leftLink" : {},"rightLink" : {},"operationName" : "操作","fontSize" : 16}',
            'sort' => '10006',
            'support_diy_view' => '',
            'max_count' => 1
        ],
        [
            'name' => 'RICH_TEXT',
            'title' => '富文本',
            'type' => 'SYSTEM',
            'controller' => 'RichText',
            'value' => '{ "backgroundColor": "","padding": 10,"html" : ""}',
            'sort' => '10007',
            'support_diy_view' => '',
            'max_count' => 0
        ],
        [
            'name' => 'RUBIK_CUBE',
            'title' => '魔方',
            'type' => 'SYSTEM',
            'controller' => 'RubikCube',
            'value' => '{ "selectedTemplate": "row1-of2","backgroundColor": "","list": [{ imageUrl : "", link : {} },{ imageUrl : "", link : {} }], "selectedRubikCubeArray" : [] ,"diyHtml": "","customRubikCube": 4,"heightArray": ["74.25px","59px","48.83px","41.56px"],"imageGap": 0}',
            'sort' => '10008',
            'support_diy_view' => '',
            'max_count' => 0
        ],
//		[
//			'name' => 'CUSTOM_MODULE',
//			'title' => '自定义模块',
//			'type' => 'SYSTEM',
//			'controller' => '',
//			'value' => '',
//			'sort' => '10009',
//			'support_diy_view' => '',
//			'max_count' => 0
//		],
        [
            'name' => 'POP_WINDOW',
            'title' => '弹窗广告',
            'type' => 'SYSTEM',
            'controller' => 'PopWindow',
            'value' => '{ "imageUrl":"","link":{}, "showCount": 3}',
            'sort' => '10010',
            'support_diy_view' => '',
            'max_count' => 1
        ],
        [
            'name' => 'HORZ_LINE',
            'title' => '辅助线',
            'type' => 'SYSTEM',
            'controller' => 'HorzLine',
            'value' => '{ "color" : "#e5e5e5", "defaultColor" : "#e5e5e5", "margin" : 0, "borderStyle": "solid", "padding": "no-padding" }',
            'sort' => '10011',
            'support_diy_view' => '',
            'max_count' => 0
        ],
        [
            'name' => 'HORZ_BLANK',
            'title' => '辅助空白',
            'type' => 'SYSTEM',
            'controller' => 'HorzBlank',
            'value' => '{ height : 10, backgroundColor : "", "marginLeftRight": 0 }',
            'sort' => '10012',
            'support_diy_view' => '',
            'max_count' => 0
        ],
//		[
//			'name' => 'VIDEO',
//			'title' => '视频',
//			'type' => 'SYSTEM',
//			'controller' => '',
//			'value' => '',
//			'sort' => '10013',
//			'support_diy_view' => '',
//			'max_count' => 0
//		],
//		[
//			'name' => 'VOICE',
//			'title' => '语音',
//			'type' => 'SYSTEM',
//			'controller' => '',
//			'value' => '',
//			'sort' => '10014',
//			'support_diy_view' => '',
//			'max_count' => 0
//		],
        [
            'name' => 'GOODS_LIST',
            'title' => '商品列表',
            'type' => 'SYSTEM',
            'controller' => 'GoodsList',
            'value' => '{ "sources" : "default", "categoryId" : 0, "goodsCount" : "6", "goodsId": [], "style": 1, "backgroundColor": "", "paddingUpDown": 0, "paddingLeftRight": 0, "isShowCart": 0, "cartStyle": 1, "isShowGoodName": 1, "isShowMarketPrice": 1, "isShowGoodSaleNum": 1, "isShowGoodSubTitle": 0 }',
            'sort' => '10016',
            'support_diy_view' => '',
            'max_count' => 0
        ],
        [
            'name' => 'GOODS_CATEGORY',
            'title' => '商品分类',
            'type' => 'SYSTEM',
            'controller' => 'GoodsCategory',
            'value' => '{"level":"1","template":"1"}',
            'sort' => '10021',
            'support_diy_view' => '',
            'max_count' => 1
        ],
        [
            'name' => 'FLOAT_BTN',
            'title' => '浮动按钮',
            'type' => 'SYSTEM',
            'controller' => 'FloatBtn',
            'value' => '{ "textColor": "#ffffff", "backgroundColor": "", subTitle : "", "list": [{"imageUrl": "","title": "","link": {}}]}',
            'sort' => '10022',
            'support_diy_view' => '',
            'max_count' => 1
        ],
        [
            'name' => 'TOP_CATEGORY',
            'title' => '顶部分类条',
            'type' => 'SYSTEM',
            'controller' => 'TopCategory',
            'value' => '{"title":"首页","selectColor":"#FF4544","nsSelectColor":"#333333",backgroundColor : "",}',
            'sort' => '10023',
            'support_diy_view' => '',
            'max_count' => 1
        ],
    ],
    'link' => [
        [
            'name' => 'MALL_PAGE',
            'title' => '商城页面',
            'parent' => '',
            'wap_url' => '',
            'web_url' => '',
            'sort' => 1,
            'child_list' => [
                [
                    'name' => 'MALL_LINK',
                    'title' => '商城链接',
                    'parent' => '',
                    'wap_url' => '',
                    'web_url' => '',
                    'sort' => 0,
                    'child_list' => [
                        [
                            'name' => 'BASICS_LINK',
                            'title' => '基础链接',
                            'parent' => '',
                            'wap_url' => '',
                            'web_url' => '',
                            'sort' => 0,
                            'child_list' => [
                                [
                                    'name' => 'INDEX',
                                    'title' => '主页',
                                    'parent' => '',
                                    'wap_url' => '/pages/index/index/index',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'SHOP_CATEGORY',
                                    'title' => '商品分类',
                                    'parent' => '',
                                    'wap_url' => '/pages/goods/category/category',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'SHOPPING_TROLLEY',
                                    'title' => '购物车',
                                    'parent' => '',
                                    'wap_url' => '/pages/goods/cart/cart',
                                    'web_url' => '',
                                    'sort' => 0
                                ]
                            ]
                        ],
                        [
                            'name' => 'MEMBER',
                            'title' => '会员中心',
                            'parent' => '',
                            'wap_url' => '',
                            'web_url' => '',
                            'sort' => 0,
                            'child_list' => [
                                [
                                    'name' => 'MEMBER_CENTER',
                                    'title' => '会员中心',
                                    'parent' => '',
                                    'wap_url' => '/pages/member/index/index',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'OBLIGATION_ORDER',
                                    'title' => '待付款订单',
                                    'parent' => '',
                                    'wap_url' => '/pages/order/list/list?status=waitpay',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'DELIVER_ORDER',
                                    'title' => '待发货订单',
                                    'parent' => '',
                                    'wap_url' => '/pages/order/list/list?status=waitsend',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'TAKE_DELIVER_ORDER',
                                    'title' => '待收货订单',
                                    'parent' => '',
                                    'wap_url' => '/pages/order/list/list?status=waitconfirm',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'EVALUATE_ORDER',
                                    'title' => '待评价订单',
                                    'parent' => '',
                                    'wap_url' => '/pages/order/list/list?status=waitrate',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'REFUND_ORDER',
                                    'title' => '退款订单',
                                    'parent' => '',
                                    'wap_url' => '/pages/order/activist/activist',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'MEMBER_INFO',
                                    'title' => '会员资料',
                                    'parent' => '',
                                    'wap_url' => '/pages/member/info/info',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'SHIPPING_ADDRESS',
                                    'title' => '收货地址',
                                    'parent' => '',
                                    'wap_url' => '/otherpages/member/address/address',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'BALANCE',
                                    'title' => '我的余额',
                                    'parent' => '',
                                    'wap_url' => '/otherpages/member/balance/balance',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'MEMBER_INTEGRAL',
                                    'title' => '我的积分',
                                    'parent' => '',
                                    'wap_url' => '/otherpages/member/point/point',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'SIGN_IN',
                                    'title' => '签到',
                                    'parent' => '',
                                    'wap_url' => '/otherpages/member/signin/signin',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'MEMBER_LEVEL',
                                    'title' => '会员等级',
                                    'parent' => '',
                                    'wap_url' => '/otherpages/member/level/level',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'FOOTPRINT',
                                    'title' => '我的足迹',
                                    'parent' => '',
                                    'wap_url' => '/otherpages/member/footprint/footprint',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                                [
                                    'name' => 'ATTENTION',
                                    'title' => '我的关注',
                                    'parent' => '',
                                    'wap_url' => '/otherpages/member/collection/collection',
                                    'web_url' => '',
                                    'sort' => 0
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'name' => 'MARKETING_LINK',
                    'title' => '营销链接',
                    'parent' => '',
                    'wap_url' => '/pages/index/index/index',
                    'web_url' => '',
                    'sort' => 0,
                    'child_list' => []
                ]
            ]
        ],
        [
            'name' => 'COMMODITY',
            'title' => '商品',
            'parent' => '',
            'wap_url' => '',
            'web_url' => '',
            'sort' => 2,
            'child_list' => [
                [
                    'name' => 'ALL_GOODS',
                    'title' => '全部商品',
                    'parent' => '',
                    'wap_url' => '',
                    'web_url' => '',
                    'child_list' => [],
                    'sort' => 1,
                ]
            ]
        ],
        [
            'name' => 'CUSTOM_LINK',
            'title' => '自定义链接',
            'parent' => '',
            'wap_url' => '',
            'web_url' => '',
            'sort' => 3,
            'child_list' => []
        ],
        [
            'name' => 'GAME',
            'title' => '小游戏',
            'parent' => '',
            'wap_url' => '',
            'web_url' => '',
            'sort' => 4,
            'child_list' => []
        ]
    ]
];