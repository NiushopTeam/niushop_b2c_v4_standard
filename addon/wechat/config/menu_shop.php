<?php
// +---------------------------------------------------------------------+
// | NiuCloud | [ WE CAN DO IT JUST NiuCloud ]                |
// +---------------------------------------------------------------------+
// | Copy right 2019-2029 www.niucloud.com                          |
// +---------------------------------------------------------------------+
// | Author | NiuCloud <niucloud@outlook.com>                       |
// +---------------------------------------------------------------------+
// | Repository | https://github.com/niucloud/framework.git          |
// +---------------------------------------------------------------------+
return [
    [
        'name'           => 'WECHAT_ROOT',
        'title'          => '微信公众号',
        'url'            => 'wechat://shop/wechat/setting',
        'parent'         => 'CONFIG_ROOT',
        'picture_select' => '',
        'picture'        => 'addon/wechat/shop/view/public/img/menu_icon/wechat_icon.png',
        'is_show'        => 1,
        'sort'           => 4,
        'child_list'     => [
            [
                'name'           => 'WECHAT_SETTING',
                'title'          => '公众号设置',
                'url'            => 'wechat://shop/wechat/setting',
                'is_show'        => 1,
                'picture'        => '',
                'picture_select' => '',
                'sort'           => 0,
                'child_list'     => [
                    [
                        'name'    => 'WCHAT_CONFIG',
                        'title'   => '公众平台配置',
                        'url'     => 'wechat://shop/wechat/config',
                        'is_show' => 0,
                    ],
                    [
                        'name'    => 'WECHAT_MENU',
                        'title'   => '菜单管理',
                        'url'     => 'wechat://shop/menu/menu',
                        'is_show' => 0,
                    ],
                    [
                        'name'    => 'WECHAT_MATERIAL',
                        'title'   => '消息素材',
                        'url'     => 'wechat://shop/material/lists',
                        'is_show' => 0,
                    ],
                    [
                        'name'    => 'WECHAT_MATERIAL_ADD',
                        'title'   => '添加图文',
                        'url'     => 'wechat://shop/material/add',
                        'is_show' => 0
                    ],
                    [
                        'name'    => 'WECHAT_MATERIAL_EDIT',
                        'title'   => '修改图文',
                        'url'     => 'wechat://shop/material/edit',
                        'is_show' => 0
                    ],
                    [
                        'name'    => 'WECHAT_MATERIAL_DELETE',
                        'title'   => '删除图文',
                        'url'     => 'wechat://shop/material/delete',
                        'is_show' => 0
                    ],
                    [
                        'name'    => 'WECHAT_QRCODE',
                        'title'   => '推广二维码管理',
                        'url'     => 'wechat://shop/wechat/qrcode',
                        'is_show' => 0,
                    ],
                    [
                        'name'    => 'WECHAT_QRCODE_ADD',
                        'title'   => '推广二维码添加',
                        'url'     => 'wechat://shop/wechat/addqrcode',
                        'is_show' => 0
                    ],
                    [
                        'name'    => 'WECHAT_QRCODE_EDIT',
                        'title'   => '推广二维码编辑',
                        'url'     => 'wechat://shop/wechat/editqrcode',
                        'is_show' => 0
                    ],
                    [
                        'name'    => 'WECHAT_QRCODE_DELETE',
                        'title'   => '推广二维码删除',
                        'url'     => 'wechat://shop/wechat/deleteqrcode',
                        'is_show' => 0
                    ],
                    [
                        'name'    => 'WECHAT_SHARE',
                        'title'   => '分享内容设置',
                        'url'     => 'wechat://shop/wechat/share',
                        'is_show' => 0
                    ],
                    [
                        'name'    => 'WECHAT_REPLAY_INDEX',
                        'title'   => '回复设置',
                        'url'     => 'wechat://shop/replay/replay',
                        'is_show' => 0,
                    ],
                    [
                        'name'    => 'WECHAT_REPLAY_KEYS',
                        'title'   => '关键词自动回复',
                        'url'     => 'wechat://shop/replay/replay',
                        'is_show' => 0
                    ],
                    [
                        'name'    => 'WECHAT_REPLAY_FOLLOW',
                        'title'   => '关注后自动回复',
                        'url'     => 'wechat://shop/replay/follow',
                        'is_show' => 0
                    ],
                    [
                        'name'    => 'WECHAT_MASS_INDEX',
                        'title'   => '群发设置',
                        'url'     => 'wechat://shop/wechat/mass',
                        'is_show' => 0
                    ],
                    [
                        'name'    => 'WECHAT_FANS',
                        'title'   => '粉丝管理',
                        'url'     => 'wechat://shop/fans/lists',
                        'is_show' => 0
                    ],
                    [
                        'name'    => 'WECHAT_FANS_LIST',
                        'title'   => '粉丝列表',
                        'url'     => 'wechat://shop/fans/lists',
                        'is_show' => 1
                    ],
                    [
                        'name'    => 'WECHAT_FANS_TAG_LIST',
                        'title'   => '粉丝标签',
                        'url'     => 'wechat://shop/fans/fanstaglist',
                        'is_show' => 1
                    ],
                    [
                        'name'    => 'WECHAT_MESSAGE_CONFIG',
                        'title'   => '微信消息模板',
                        'url'     => 'wechat://shop/message/config',
                        'is_show' => 0,
                    ],
                    [
                        'name'    => 'WECHAT_MESSAGE_EDIT',
                        'title'   => '编辑消息模板',
                        'url'     => 'wechat://shop/message/edit',
                        'is_show' => 0
                    ],
                ]
            ],
            /* [
                'name' => 'WECHAT_STAT',
                'title' => '访问统计',
                'url' => 'wechat://shop/wechat/stat',
                'picture_select' => '',
                'picture' => '',
                'is_show' => 1,
                'sort' => 1,
            ], */
        ]
    ],
];