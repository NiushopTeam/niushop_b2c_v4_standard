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

use app\model\system\Addon;
use think\facade\Event;
use think\facade\Cache;

/**
 * 初始化插件
 */
class InitAddon
{
    // 行为扩展的执行入口必须是run
    public function handle()
    {
        if (defined('BIND_MODULE') && BIND_MODULE === 'install')
            return;
        $this->initEvent();
    }

    /**
     * 初始化事件
     */
    private function InitEvent()
    {
        $cache = Cache::get("addon_event_list");

        if (empty($cache)) {
            $addon_model = new Addon();
            $addon_data  = $addon_model->getAddonList([], 'name');

            $listen_array = [];
            foreach ($addon_data['data'] as $k => $v) {
                if (file_exists('addon/' . $v['name'] . '/config/event.php')) {
                    $addon_event = require_once 'addon/' . $v['name'] . '/config/event.php';

                    $listen = isset($addon_event['listen']) ? $addon_event['listen'] : [];
                    if (!empty($listen)) {
                        $listen_array[] = $listen;
                    }
                }

            }
            Cache::tag("addon")->set("addon_event_list", $listen_array);
        } else {
            $listen_array = $cache;
        }

        if (!empty($listen_array)) {
            foreach ($listen_array as $k => $listen) {
                if (!empty($listen)) {
                    Event::listenEvents($listen);
                }

            }
        }

    }

}