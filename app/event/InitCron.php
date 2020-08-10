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

use think\facade\Config;
use think\facade\Cache;

/**
 * 初始化计划任务启动
 * @author Administrator
 *
 */
class InitCron
{
    public function handle()
    {
        if (defined('BIND_MODULE') && BIND_MODULE === 'install') {
            return;
        }
        $last_time = Cache::get("cron_last_load_time");
        if (empty($last_time)) {
            $last_time = 0;
        }
        $module = request()->module();
        if ($module != 'cron') {
            if (!defined('CRON_EXECUTE') && time() - $last_time > 100) {
                defined('CRON_EXECUTE') or define('CRON_EXECUTE', 1);
                $url = url('cron/task/phpCron');
                $ch  = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_exec($ch);
            }
        }
    }
}
