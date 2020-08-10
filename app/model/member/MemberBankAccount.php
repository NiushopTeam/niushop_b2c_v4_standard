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

namespace app\model\member;

use app\model\BaseModel;
use think\facade\Cache;

/**
 * 会员提现账号
 */
class MemberBankAccount extends BaseModel
{
    private $withdraw_type = [
        'alipay'    => '支付宝',
        'bank'      => '银行卡',
        'wechatpay' => '微信'
    ];

    public function getWithdrawType()
    {
        return $this->withdraw_type;
    }


    /**
     * 添加会员提现账号
     * @param $data
     * @return array
     */
    public function addMemberBankAccount($data)
    {
        if ($data['is_default'] == 1) {
            model('member_bank_account')->update(['is_default' => 0], ['member_id' => $data['member_id']]);
        }
        $data['create_time'] = time();
        $id                  = model('member_bank_account')->add($data);
        $count               = model('member_bank_account')->getCount(['member_id' => $data['member_id']]);
        if ($count == 1)
            model('member_bank_account')->update(['is_default' => 1], ['member_id' => $data['member_id'], 'id' => $id]);
        Cache::tag("member_bank_account_" . $data['member_id'])->clear();
        return $this->success($id);
    }

    /**
     * 修改会员提现账号
     * @param $data
     * @return array
     */
    public function editMemberBankAccount($data)
    {
        if ($data['is_default'] == 1) {
            model('member_bank_account')->update(['is_default' => 0], ['member_id' => $data['member_id']]);
        }
        $data['modify_time'] = time();
        $res                 = model('member_bank_account')->update($data, ['id' => $data['id']]);
        Cache::tag("member_bank_account_" . $data['member_id'])->clear();
        return $this->success($res);
    }

    /**
     * 删除会员提现账号
     * @param array $condition
     */
    public function deleteMemberBankAccount($condition)
    {
        $check_condition = array_column($condition, 2, 0);
        $res             = model('member_bank_account')->delete($condition);
        Cache::tag("member_bank_account_" . $check_condition['member_id'])->clear();
        if ($res === false) {
            return $this->error('', 'RESULT_ERROR');
        }
        return $this->success($res);
    }

    /**
     * 设置默认会员提现账号
     * @param $id
     * @param $member_id
     * @return \multitype
     */
    public function modifyDefaultAccount($id, $member_id)
    {
        model('member_bank_account')->startTrans();
        try {
            model('member_bank_account')->update(['is_default' => 0], ['member_id' => $member_id]);
            $res = model('member_bank_account')->update(['is_default' => 1], ['member_id' => $member_id, 'id' => $id]);
            model('member_bank_account')->commit();
            Cache::tag("member_bank_account_" . $member_id)->clear();
            return $this->success($res);
        } catch (\Exception $e) {
            model('member_bank_account')->rollback();
            return $this->error('', $e->getMessage());
        }
    }

    /**
     * 获取会员提现账号信息
     * @param $condition
     * @param string $field
     * @return array
     */
    public function getMemberBankAccountInfo($condition, $field = '*')
    {
        $check_condition = array_column($condition, 2, 0);
        $data            = json_encode([$condition, $field]);
        $cache           = Cache::get("member_bank_account_getMemberBankAccountInfo_" . $data);
        if (!empty($cache)) {
            return $this->success($cache);
        }
        $res = model('member_bank_account')->getInfo($condition, $field);
        Cache::tag("member_bank_account_" . $check_condition['member_id'])->set("member_bank_account_getMemberBankAccountInfo_" . $data, $res);
        return $this->success($res);
    }

    /**
     * 获取会员提现账号分页列表
     * @param array $condition
     * @param int $page
     * @param int $page_size
     * @param string $order
     * @param string $field
     * @return array|\multitype
     */
    public function getMemberBankAccountPageList($condition = [], $page = 1, $page_size = PAGE_LIST_ROWS, $order = 'create_time desc', $field = '*')
    {
        $check_condition = array_column($condition, 2, 0);
        $data            = json_encode([$condition, $field, $order, $page, $page_size]);
        $cache           = Cache::get("member_bank_account_getMemberBankAccountPageList_" . $data);
        if (!empty($cache)) {
            return $this->success($cache);
        }
        $list = model('member_bank_account')->pageList($condition, $field, $order, $page, $page_size);
        Cache::tag("member_bank_account_" . $check_condition['member_id'])->set("member_bank_account_getMemberBankAccountPageList_" . $data, $list);
        return $this->success($list);
    }

}