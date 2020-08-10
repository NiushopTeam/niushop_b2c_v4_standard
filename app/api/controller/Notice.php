<?php
/**
 * Niushop商城系统 - 团队十年电商经验汇集巨献!
 * =========================================================
 * Copy right 2015-2025 上海牛之云网络科技有限公司, 保留所有权利。
 * ----------------------------------------------
 * 官方网址: http://www.niushop.com.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 任何企业和个人不允许对程序代码以任何形式任何目的再发布。
 * =========================================================
 */

namespace app\api\controller;

use app\model\web\Notice as NoticeModel;

/**
 *
 * @author Administrator
 *
 */
class Notice extends BaseApi
{

    /**
     * 基础信息
     */
    public function info()
    {
        $id = isset($this->params['id']) ? $this->params['id'] : 0;
        if (empty($id)) {
            return $this->response($this->error('', 'REQUEST_ID'));
        }
        $notice = new NoticeModel();
        $info   = $notice->getNoticeInfo([['id', '=', $id], ['site_id', '=', $this->site_id]]);
        return $this->response($info);
    }

    public function lists()
    {
        $id_arr = isset($this->params['id_arr']) ? $this->params['id_arr'] : '';//id数组

        $notice    = new NoticeModel();
        $condition = [
            ['receiving_type', 'like', '%mobile%'],
            ['site_id', '=', $this->site_id],
            ['id', 'in', $id_arr]
        ];
        $list      = $notice->getNoticeList($condition);
        return $this->response($list);
    }

    public function page()
    {
        $page      = isset($this->params['page']) ? $this->params['page'] : 1;
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] : PAGE_LIST_ROWS;
        $notice    = new NoticeModel();
        $list      = $notice->getNoticePageList([['receiving_type', 'like', '%mobile%'], ['site_id', '=', $this->site_id]], $page, $page_size);
        return $this->response($list);
    }

}