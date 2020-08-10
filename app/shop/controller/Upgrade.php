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

use app\model\system\Database;
use app\model\system\DiyTemplate;
use app\model\system\H5;
use app\model\system\Menu;
use app\model\system\Upgrade as UpgradeModel;
use think\facade\Cache;
use think\facade\Db;
use app\model\system\Addon;

/**
 * 系统升级
 * @author Administrator
 * 版本 1.0.1
 */
class Upgrade extends BaseShop
{
    /**
     * 系统授权信息
     */
    public function auth()
    {
        $this->forthMenu();

        //系统信息 获取自配置文件
        $app_info = config('info');
        $this->assign('app_info', $app_info);

        //授权文件 获取自接口
        $upgrade_model = new UpgradeModel();
        $auth_info     = $upgrade_model->authInfo();
        $this->assign('auth_info', $auth_info);

        return $this->fetch('upgrade/auth');
    }

    /************************************************系统升级 START*****************************************/

    /**
     * 获取真实存在的目录 检测权限使用
     * @param $path
     * @return false|string
     */
    protected function getRealPath($path)
    {
        while (!is_dir($path) && strrpos($path, "/")) {
            $path = substr($path, 0, strrpos($path, "/"));
        }
        return $path;
    }

    /**
     * 系统升级
     */
    public function upgrade()
    {
        if (request()->isAjax()) {
            $upgrade_model = new UpgradeModel();
            $res           = $upgrade_model->getSystemUpgradeInfo();
            $errors        = $this->checkSystemUpgradeRight($res['data']);
            session('system_upgrade_info_ready', $res['data']);
            return success(0, '操作成功', [
                'system_upgrade_info_ready' => $res['data'],
                'right_check'               => $errors,
            ]);
        } else {
            return $this->fetch('upgrade/upgrade');
        }
    }

    /**
     * 检测系统升级权限
     */
    protected function checkSystemUpgradeRight($system_upgrade_info_ready)
    {
        $errors = [];

        //检测下载文件目录权限
        $download_root = "upload/upgrade";
        $download_root = $this->getRealPath($download_root);
        if (!is_writeable($download_root)) {
            $errors[] = [
                'path'      => $download_root,
                'type'      => 'dir',
                'type_name' => '文件夹',
            ];
        }

        //检测备份文件目录权限
        $backup_root = "upload/backup";
        $backup_root = $this->getRealPath($backup_root);
        if (!is_writeable($backup_root)) {
            $errors[] = [
                'path'      => $backup_root,
                'type'      => 'dir',
                'type_name' => '文件夹',
            ];
        }

        //检测证书文件权限
        $cert_key_path = "./cert.key";
        if (!is_writeable($cert_key_path)) {
            $errors[] = [
                'path'      => $cert_key_path,
                'type'      => 'file',
                'type_name' => '文件',
            ];
        }

        foreach ($system_upgrade_info_ready as $info) {
            //判断文件夹是否可写
            if ($info['type'] == 'addon' && $info['action'] == 'install') {
                $addon_path = "addon";
                if (!is_writeable($addon_path)) {
                    $errors[] = [
                        'path'      => $addon_path,
                        'type'      => 'dir',
                        'type_name' => '文件夹',
                    ];
                }
            }
            //遍历文件 检测权限
            foreach ($info['files'] as $val) {
                $file_path = '';
                if ($info['action'] == 'upgrade') {
                    if ($info['type'] == 'system') {
                        $file_path = $val['file_path'];
                    } else {
                        $file_path = "addon/{$val['file_path']}";
                    }
                }
                if ($info['action'] == 'download' && $info['type'] == 'client') {
                    $file_path = "public/{$val['file_path']}";
                }
                if ($file_path) {
                    if (file_exists($file_path)) {
                        if (!is_writeable($file_path)) {
                            $errors[] = [
                                'path'      => $file_path,
                                'type'      => 'file',
                                'type_name' => '文件',
                            ];
                        }
                    } else {
                        $dir_path = dirname($file_path);
                        $dir_path = $this->getRealPath($dir_path);
                        if (!is_writeable($dir_path)) {
                            $errors[] = [
                                'path'      => $dir_path,
                                'type'      => 'dir',
                                'type_name' => '文件夹',
                            ];
                        }
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * 升级操作页面
     */
    public function upgradeAction()
    {
        if (request()->isAjax()) {
            $system_upgrade_info_ready = session('system_upgrade_info_ready');
            //将系统和插件的文件及sql都整合到一起
            $files      = [];
            $sqls       = '';
            $upgrade_no = uniqid();

            //合并文件和sql
            foreach ($system_upgrade_info_ready as $info) {
                foreach ($info['files'] as $val) {
                    $val['type'] = $info['type'];
                    $val['code'] = $info['code'];
                    $files[]     = $val;
                }
                $sqls .= "\n";//防止脚本没有换行导致sql解析完成后将多条sql一起执行,导致出错
                $sqls .= $info['sqls'];
            }

            $system_upgrade_info = [
                'files'      => $files,
                'sqls'       => $sqls,
                'upgrade_no' => $upgrade_no,
            ];

            session('system_upgrade_info', $system_upgrade_info);
            return success(0, '操作成功', session('system_upgrade_info'));
        } else {
            $system_upgrade_info_ready = session('system_upgrade_info_ready');
            if (empty($system_upgrade_info_ready)) {
                $this->error('没有可以升级的内容');
            }
            return $this->fetch('upgrade/upgrade_action');
        }
    }

    /**
     * 升级操作--备份原文件
     * @return array
     */
    public function backupFile()
    {
        $system_upgrade_info = session('system_upgrade_info');
        $upgrade_no          = $system_upgrade_info['upgrade_no'];
        //备份文件的根目录
        $backup_root = "upload/backup/{$upgrade_no}/file";
        if (!is_dir($backup_root)) {
            dir_mkdir($backup_root);
        }
        try {
            if (!empty($system_upgrade_info)) {
                foreach ($system_upgrade_info['files'] as $k => $v) {
                    $type = $v['type'];
                    if ($type == 'system') {
                        //如果是系统升级 备份的文件是和根目录比对 下载文件类似 b2c_saas/index.php 要把前面的b2c_saas去掉
                        $file_path = $v['file_path'];
                    } else {
                        //如果是插件升级 备份的文件是和插件目录比对 下载文件类似 test/index.php 需要补充前缀addon
                        $file_path = "addon/{$v['file_path']}";
                    }
                    if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $file_path) > 0) {
                        $file_path = iconv('utf-8', 'gbk', $file_path);
                    }
                    if (file_exists($file_path)) {
                        $dest_file_path = "{$backup_root}/{$file_path}";
                        $dest_dir_path  = substr($dest_file_path, 0, strrpos($dest_file_path, '/'));
                        //要先创建文件夹才可以执行copy操作
                        if (!is_dir($dest_dir_path)) {
                            dir_mkdir($dest_dir_path);
                        }
                        copy($file_path, $dest_file_path);
                    }
                }
                //备份客户端
                $client_type_arr = ['h5', 'weapp'];
                foreach ($client_type_arr as $client_type) {
                    $client_path = "{$backup_root}/public/{$client_type}";
                    if (!is_dir($client_path)) {
                        dir_mkdir($client_path);
                    }
                    dir_copy("public/{$client_type}", $client_path);
                }
            }
            return success();
        } catch (\Exception $e) {
            return error(-1, [], $e->getMessage());
        }
    }

    /**
     * 升级操作---备份数据库
     */
    public function backupSql()
    {
        try {
            $system_upgrade_info = session('system_upgrade_info');
            $upgrade_no          = $system_upgrade_info['upgrade_no'];

            $database = new Database();
            ini_set('memory_limit', '500M');
            $size       = 300;
            $volumn     = 1024 * 1024 * 2;
            $dump       = '';
            $last_table = input('last_table', '');
            $series     = max(1, input('series', 1));
            if (empty($last_table)) {
                $catch = true;
            } else {
                $catch = false;
            }
            $back_sql_root = "upload/backup/{$upgrade_no}/sql";
            if (!is_dir($back_sql_root)) {
                dir_mkdir($back_sql_root);
            }
            $tables = $database->getDatabaseList();
            if (empty($tables)) {
                return success();
            }
            foreach ($tables as $table) {
                $table = array_shift($table);
                if (!empty($last_table) && $table == $last_table) {
                    $catch = true;
                }
                if (!$catch) {
                    continue;
                }
                if (!empty($dump)) {
                    $dump .= "\n\n";
                }
                if ($table != $last_table) {
                    $row  = $database->getTableSchemas($table);
                    $dump .= $row;
                }
                $index = 0;
                if (!empty(input('index'))) {
                    $index = input('index');
                }
                //枚举所有表的INSERT语句
                while (true) {
                    $start  = $index * $size;
                    $result = $database->getTableInsertSql($table, $start, $size);
                    if (!empty($result)) {
                        $dump .= $result['data'];
                        if (strlen($dump) > $volumn) {
                            $bakfile = "{$back_sql_root}/backup-{$series}.sql";
                            $dump    .= "\n\n";
                            file_put_contents($bakfile, $dump);
                            ++$series;
                            ++$index;
                            $current        = array(
                                'is_backup_end' => 0,
                                'last_table'    => $table,
                                'index'         => $index,
                                'series'        => $series,
                            );
                            $current_series = $series - 1;
                            return success(0, '正在导出数据, 请不要关闭浏览器, 当前第 ' . $current_series . ' 卷.', $current);
                        }
                    }
                    if (empty($result) || count($result['result']) < $size) {
                        break;
                    }
                    ++$index;
                }
            }
            $back_file = "{$back_sql_root}/backup-{$series}.sql";
            $dump      .= "\n\n----MySQL Dump End";
            file_put_contents($back_file, $dump);
            return success(0, '数据库备份完成', ['is_backup_end' => 1]);
        } catch (\Exception $e) {
            return error(-1, $e->getMessage());
        }
    }

    /**
     * 升级操作---下载文件
     */
    public function download()
    {
        ini_set("memory_limit", "-1");
        set_time_limit(300);

        $system_upgrade_info = session('system_upgrade_info');
        $download_file_index = input('download_file_index', 0);
        $file_path           = $system_upgrade_info['files'][$download_file_index]['file_path'];
        $token               = $system_upgrade_info['files'][$download_file_index]['token'];
        $type                = $system_upgrade_info['files'][$download_file_index]['type'];
        $code                = $system_upgrade_info['files'][$download_file_index]['code'];
        $upgrade_no          = $system_upgrade_info['upgrade_no'];

        if ($type == 'system') {
            $download_root = "upload/upgrade/{$upgrade_no}";//框架
        } else if ($type == 'client') {
            $download_root = "upload/upgrade/{$upgrade_no}/public/{$code}";//客户端
        } else if ($type == 'addon') {
            $download_root = "upload/upgrade/{$upgrade_no}/addon";//插件
        }

        try {
            $up_model = new UpgradeModel();
            $data     = array(
                'file'  => $file_path,
                "token" => $token
            );

            $info = $up_model->download($data);//异步下载更新文件

            //下载文件失败
            if ($info["code"] < 0) {
                return json($info);
            }

            $dir_path = dirname($file_path);
            $dir_make = dir_mkdir($download_root . '/' . $dir_path);
            if ($dir_make) {
                if (!empty($info)) {
                    $temp_path = $download_root . '/' . $file_path;
                    if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $temp_path) > 0) {
                        $temp_path = iconv('utf-8', 'gbk', $temp_path);
                    }
                    file_put_contents($temp_path, $info['data']);
                    return json(['code' => 0, 'message' => $file_path, 'download_root' => "upload/upgrade/{$upgrade_no}/"]);
                } else {
                    return json(['code' => -1, 'message' => '升级文件不存在']);
                }
            } else {
                return json(['code' => -1, 'message' => '文件读写权限不足']);
            }
        } catch (\Exception $e) {
            return json(['code' => -1, 'message' => $e->getMessage()]);
        }
    }

    /**
     * 升级操作---更新文件覆盖
     */
    public function executeFile()
    {
        $system_upgrade_info = session('system_upgrade_info');
        $upgrade_no          = $system_upgrade_info['upgrade_no'];
        try {
            //下载目录和要覆盖的目录
            $download_root = "upload/upgrade/{$upgrade_no}";
            $to_path       = './';

            //文件替换
            dir_copy($download_root, $to_path);

            return json(['code' => 0, 'message' => '操作成功']);
        } catch (\Exception $e) {
            //升级失败
            $upgrade_model = new UpgradeModel();
            $upgrade_model->editUpgradeLog(['status' => 2, 'error_message' => $e->getMessage()], ['upgrade_no' => $upgrade_no]);
            return json(['code' => 0, 'message' => $e->getMessage()]);
        }
    }

    /**
     * 更新操作---sql执行
     */
    public function executeSql()
    {
        $system_upgrade_info = session('system_upgrade_info');
        $sqls                = $system_upgrade_info['sqls'];
        $upgrade_no          = $system_upgrade_info['upgrade_no'];

        try {
            if (!empty($sqls)) {
                $sqls = str_replace("{{prefix}}", config("database.connections.mysql.prefix"), $sqls);
                file_put_contents("upload/upgrade/{$upgrade_no}/upgrade.sql", $sqls);

                //执行sql
                $sql_arr = parse_sql($sqls);
                foreach ($sql_arr as $k => $v) {
                    $v = trim($v);
                    if (!empty($v) && $v != "") {
                        Db::execute($v);
                    }
                }
                return json(success());
            } else {
                return json(success());
            }
        } catch (\Exception $e) {
            //升级失败
            $upgrade_model = new UpgradeModel();
            $upgrade_model->editUpgradeLog(['status' => 2, 'error_message' => $e->getMessage()], ['upgrade_no' => $upgrade_no]);
            return json(error(-1, $e->getMessage()));
        }
    }

    /**
     * 升级开始
     * @return array
     */
    public function upgradeStart()
    {
        $system_upgrade_info_ready = session('system_upgrade_info_ready');
        $system_upgrade_info       = session('system_upgrade_info');
        $upgrade_no                = $system_upgrade_info['upgrade_no'];

        // 添加升级日志
        $version_info = [];
        foreach ($system_upgrade_info_ready as $key => $val) {
            $version_info[] = [
                'action'               => $val['action'],
                'action_name'          => $val['action_name'],
                'type'                 => $val['type'],
                'type_name'            => $val['type_name'],
                'current_version_name' => $val['current_version_name'],
                'latest_version_name'  => $val['latest_version_name'],
                'scripts'              => $val['scripts'],
                'goods_name'           => $val['goods_name'],
            ];
        }

        $data = [
            'upgrade_no'    => $upgrade_no,
            'upgrade_time'  => time(),
            'backup_root'   => "upload/backup/{$upgrade_no}",
            'download_root' => "upload/download_root/{$upgrade_no}",
            'version_info'  => json_encode($version_info),
            'status'        => 0
        ];

        $upgrade_model = new UpgradeModel();
        $res           = $upgrade_model->addUpgradeLog($data);

        return $res;
    }

    /**
     * 升级完成
     */
    public function upgradeEnd()
    {
        $system_upgrade_info_ready = session('system_upgrade_info_ready');
        $system_upgrade_info       = session('system_upgrade_info');
        $upgrade_no                = $system_upgrade_info['upgrade_no'];

        $upgrade_model = new UpgradeModel();
        try {
            //更新系统菜单
            $menu = new Menu();
            $menu->refreshMenu('shop', '');

            //修改插件信息
            $addon_model = new Addon();
            $addon_model->refreshDiyView('');
            foreach ($system_upgrade_info_ready as $key => $val) {
                if ($val['type'] == 'addon') {
                    if ($val['action'] == 'upgrade') {
                        $addon_model->uninstall($val['code']);
                        $addon_model->install($val['code']);
                    } else {
                        $addon_model->install($val['code']);
                    }
                }
            }

            //升级成功
            $upgrade_model->editUpgradeLog(['status' => 1], ['upgrade_no' => $upgrade_no]);

            //刷新h5
            $h5 = new H5();
            $h5->refresh();

            // 刷新内置模板
            $template = new DiyTemplate();
            $template->refresh();

            //清空session数据
            session('system_upgrade_info_ready', null);
            session('system_upgrade_info', null);

            return json(success());
        } catch (\Exception $e) {
            //升级失败
            $upgrade_model->editUpgradeLog(['status' => 2, 'error_message' => $e->getMessage()], ['upgrade_no' => $upgrade_no]);
            return json(error(-1, $e->getMessage()));
        }
    }

    /**
     * 执行恢复
     * @return \think\response\Json
     */
    public function executeRecovery()
    {
        $system_upgrade_info_ready = session('system_upgrade_info_ready');
        $system_upgrade_info       = session('system_upgrade_info');
        $upgrade_no                = $system_upgrade_info['upgrade_no'];
        try {
            $upgrade_model = new UpgradeModel();
            $log_info      = $upgrade_model->getUpgradeLogInfo(['upgrade_no' => $upgrade_no]);
            if (empty($log_info)) {
                return json(['code' => -1, '回滚信息有误']);
            }
            $backup_file_path = "{$log_info['backup_root']}/file/";
            $backup_sql_path  = "{$log_info['backup_root']}/sql/";

            //回滚备份的文件
            if (dir_is_empty($backup_file_path)) {
                return json(['code' => -1, '没有可回滚的备份文件!']);
            }
            dir_copy($backup_file_path, './');

            //回滚执行的sql语句
            $flag = \FilesystemIterator::KEY_AS_FILENAME;
            $glob = new \FilesystemIterator($backup_sql_path, $flag);
            foreach ($glob as $name => $sql) {
                $sql_path = $backup_sql_path . '/' . $name;
                $sql      = file_get_contents($sql_path);
                //执行sql
                $sql_arr = parse_sql($sql);
                foreach ($sql_arr as $k => $v) {
                    $v = trim($v);
                    if (!empty($v) && $v != "") {
                        Db::execute($v);
                    }
                }
            }

            //删除已安装的插件
            foreach ($system_upgrade_info_ready as $val) {
                if ($val['action'] == 'install' && $val['type'] == 'addon') {
                    $addon_dir_path = "addon/{$val['code']}";
                    if (is_dir($addon_dir_path)) {
                        deleteDir($addon_dir_path);
                        @rmdir($addon_dir_path);
                    }
                }
            }
            return json(['code' => 0, 'message' => '备份回滚成功!']);
        } catch (\Exception $e) {
            return json(['code' => -1, 'message' => $e->getMessage()]);
        }
    }

    /************************************************系统升级 END*****************************************/

    /************************************************系统回滚 START*****************************************/

    /**
     * 系统回滚
     */
    public function recovery()
    {
        $upgrade_model    = new UpgradeModel();
        $upgrade_log_list = $upgrade_model->getUpgradeLogList(['status' => 1], '*', 'upgrade_time desc', '1');

        if (!empty($upgrade_log_list['data'])) {
            $upgrade_log_info                 = $upgrade_log_list['data'][0];
            $upgrade_log_info['version_info'] = json_decode($upgrade_log_info['version_info'], true);
        } else {
            $upgrade_log_info = null;
        }
        if (request()->isAjax()) {
            try {
                $log_info = $upgrade_log_info;
                if (empty($log_info)) {
                    return json(['code' => -1, '回滚信息有误']);
                }
                $backup_file_path = "{$log_info['backup_root']}/file/";
                $backup_sql_path  = "{$log_info['backup_root']}/sql/";

                //回滚备份的文件
                if (dir_is_empty($backup_file_path)) {
                    return json(['code' => -1, '没有可回滚的备份文件!']);
                }
                dir_copy($backup_file_path, './');

                //回滚执行的sql语句
                $flag = \FilesystemIterator::KEY_AS_FILENAME;
                $glob = new \FilesystemIterator($backup_sql_path, $flag);
                foreach ($glob as $name => $sql) {
                    $sql_path = $backup_sql_path . '/' . $name;
                    $sql      = file_get_contents($sql_path);
                    //执行sql
                    $sql_arr = parse_sql($sql);
                    foreach ($sql_arr as $k => $v) {
                        $v = trim($v);
                        if (!empty($v) && $v != "") {
                            Db::execute($v);
                        }
                    }
                }

                return json(['code' => 0, 'message' => '备份回滚成功!']);
            } catch (\Exception $e) {
                return json(['code' => -1, 'message' => $e->getMessage()]);
            }
        } else {
            $this->assign('upgrade_log_info', $upgrade_log_info);
            return $this->fetch('upgrade/recovery');
        }
    }

    /************************************************系统回滚 END*****************************************/

    /************************************************ 更新日志 START*****************************************/

    public function versionLog()
    {
        if (request()->isAjax()) {
            $page      = input('page', 1);
            $page_size = input('page_size', PAGE_LIST_ROWS);

            $upgrade_model = new UpgradeModel();
            $info          = $upgrade_model->getVersionLog($page, $page_size);
            return $info;
        } else {
            return $this->fetch('upgrade/version_log');
        }
    }

    /************************************************ 更新日志 END*****************************************/

    /**
     * 版本详情
     */
    public function version()
    {
        $version_release = input('version_release');
        if (empty($version_release)) {
            return $this->error('参数错误', url('admin/upgrade/upgrade'));
        }
        $upgrade_model = new UpgradeModel();
        $version_info  = $upgrade_model->getUpgradeInfo($version_release);
        if (!empty($version_info["data"])) {
            $version_info["data"]["error"] = [];
            try {
                //判断upload/upgrade可写权限
                $upgrade_root = 'upload/upgrade/' . $version_info['data']['sys_version'] . '/' . $version_info['data']['sys_release'];
                dir_mkdir($upgrade_root);
                if (!is_writeable($upgrade_root)) {
                    $version_info["data"]["error"][] = $upgrade_root;
                }
                //备份路径
                $backup_path = 'upload/backup';
                dir_mkdir($backup_path);
                if (!is_writeable($backup_path)) {
                    $version_info["data"]["error"][] = $backup_path;
                }
                //本地要覆盖的文件路径 => mds加密
                $two_dev_files = [];
                //文件重置
                foreach ($version_info['data']['files'] as $k => $v) {
                    if (!sp_exist_dir($v)) {
                        $version_info["data"]["error"][] = $v;
                    } else {
                        if (file_exists($v) && !is_writeable($v)) {
                            $version_info["data"]["error"][] = $v;
                        }
                        if (file_exists($v)) {
                            $two_dev_files[$v] = md5(file_get_contents($v));
                        } else {
                            $two_dev_files[$v] = '';
                        }
                    }
                    if (file_exists('upload/upgrade/' . $version_info['data']['sys_version'] . '/' . $version_info['data']['sys_release'] . '/release/' . $v)) {
                        unset($version_info['data']['files'][$k]);
                    }
                }

                $two_dev_files                         = $upgrade_model->getTowDevFiles($two_dev_files);
                $version_info['data']['two_dev_files'] = $two_dev_files['data'];

                if (!empty($version_info['data']['files'])) {
                    sort($version_info['data']['files']);
                }
                if (file_exists('upload/upgrade/' . $version_info['data']['sys_version'] . '/' . $version_info['data']['sys_release'] . '/database.sql')) {
                    unlink('upload/upgrade/' . $version_info['data']['sys_version'] . '/' . $version_info['data']['sys_release'] . '/database.sql');
                }
                //生成升级sql文件
                $dir_make = dir_mkdir('upload/upgrade/' . $version_info['data']['sys_version'] . '/' . $version_info['data']['sys_release']);

                if ($dir_make) {
                    $res = file_put_contents('upload/upgrade/' . $version_info['data']['sys_version'] . '/' . $version_info['data']['sys_release'] . '/db_upgrade.sql', charset2utf8($version_info['data']['sqls']));
                } else {
                    $version_info["data"]["error"][] = 'upload/upgrade/' . $version_info['data']['sys_version'] . '/' . $version_info['data']['sys_release'] . '/db_upgrade.sql';
                }
                $version_info["data"]['script_count'] = count($version_info["data"]['scripts']);
                $version_info["data"]['file_count']   = count($version_info["data"]['files']);
                if (!empty($version_info['data']['two_dev_files'])) {
                    $version_info['data']['tow_dev_file_count'] = count($version_info['data']['two_dev_files']);
                } else {
                    $version_info['data']['tow_dev_file_count'] = 0;
                }

                session("version_update", $version_info['data']);//记录更新文件序列
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        } else {
            if ($version_info["code"] < 0) {
                $message = $version_info["message"];
                if ($version_info["code"] == -100) {
                    $message .= " 服务续费请跳转到官网<a href='https://www.niushop.com.cn/member/index.html'>续费</a>";
                }
                return $this->error($message);
            }
        }
        $current_version = [
            'SYS_RELEASE'    => SYS_RELEASE,
            'SYS_VERSION_NO' => SYS_VERSION_NO
        ];
        $this->assign('current_verison', $current_version);
        $this->assign('version_info', $version_info['data']);
        return $this->fetch('system/version');
    }

    /**
     * 版本恢复
     */
    public function versionRecovery()
    {
        try {
            //回滚备份的文件
            if (dir_is_empty('upload/backup/')) {
                return json(['code' => -1, '没有可回滚的备份!']);
            }
            dir_copy('upload/backup/release', './');
            //回滚执行的sql语句
            $path = "upload/backup/sql";
            $flag = \FilesystemIterator::KEY_AS_FILENAME;
            $glob = new \FilesystemIterator($path, $flag);
            foreach ($glob as $name => $sql) {
                $sql_path = $path . '/' . $name;
                $sql      = file_get_contents($sql_path);
                Db::execute($sql);
            }
            return json(['code' => 0, 'message' => '备份回滚成功!']);
        } catch (\Exception $e) {
            return json(['code' => -1, 'message' => $e->getMessage()]);
        }
    }


    /************************************************同步授权文件 start*****************************************/

    public function syncCert()
    {
        if (request()->isAjax()) {
            $upgrade_model = new UpgradeModel();

            if (!file_exists('./cert.key')) {
                return $this->error('没有此文件');
            }

            if (!is_writeable('./cert.key')) {
                return $this->error('文件不可写');
            }

            $res = $upgrade_model->getCert();

            if ($res['code'] > 0) {
                $cert_content = $res['data'];
                file_put_contents('./cert.key', $cert_content);
                return $res;
            } else {
                return $this->error('同步失败');
            }
        }
    }

    /************************************************同步授权文件 end  *****************************************/
}
