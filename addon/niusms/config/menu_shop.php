<?php
// +----------------------------------------------------------------------
// | 平台端菜单设置
// +----------------------------------------------------------------------
return [
    [
        'name'           => 'NIU_SMS_CONFIG',
        'title'          => '牛云短信配置',
        'url'            => 'niusms://shop/sms/index',
        'parent'         => 'SMS_MANAGE',
        'is_show'        => 0,
        'is_control'     => 1,
        'is_icon'        => 0,
        'picture'        => '',
        'picture_select' => '',
        'sort'           => 1,
    ],
    [
        'name'           => 'NIU_MESSAGE_SMS_EDIT',
        'title'          => '编辑牛云短信模板',
        'url'            => 'niusms://shop/message/edit',
        'parent'         => 'MESSAGE_LISTS',
        'is_show'        => 0,
        'picture'        => '',
        'picture_select' => '',
        'sort'           => 1,
        'child_list'     => [],
    ],
];
