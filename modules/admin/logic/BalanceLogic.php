<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: BalanceLogic.php
 * $Id: BalanceLogic.php v 1.0 2015-12-25 22:32:03 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-12-28 17:42:56 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\modules\admin\models\BalanceHistory;
use hipstercreative\user\models\User;

class BalanceLogic extends BaseObject {
    /** 使用余额支付订单 */
    public static function payForOrder($order) {
        $comment = '使用余额支付订单' . $order->orderId . '，金额：' . $order->useBalance;
        return BalanceLogic::changeBalance($order->userId, -$order->useBalance, $comment);
    }

    /** 返现 */
    public static function cashBack($order) {
        $comment = '订单' . $order->orderId . '完成返现：' . $order->cashBack;
        return BalanceLogic::changeBalance($order->userId, $order->cashBack, $comment);
    }

    /** 取消订单退款 */
    public static function refund($order) {
        $comment = '取消订单' . $order->orderId . '退款：' . $order->totalMoney;
        return BalanceLogic::changeBalance($order->userId, $order->totalMoney, $comment);
    }

    /**
     * 变更余额
     */
    public static function changeBalance($userId, $balance, $comment = '') {
        $user = User::find()->where(['_id' => self::ensureMongoId($userId)])->one();
        if ($user == null) {
            return false;
        }
        if ($user->balance + $balance < 0) {
            return false;
        }
        $balance = round((float)$balance, 2);
        $bh = new BalanceHistory();
        $bh->userId = self::ensureMongoId($userId);
        $bh->time = NOW;
        $bh->comment = $comment;
        $bh->balance = $balance;
        $bh->save();

        $user->balance += $balance;
        $user->save();
        return true;
    }

    public static function getBalance($userId) {
        $user = User::find()->where(['_id' => self::ensureMongoId($userId)])->one();
        if ($user == null) {
            return 0;
        }
        return (float)$user->balance;
    }

    public static function getBalanceHistory($userId, $page, $pageNum = 15) {
        $offset = (intval($page) - 1) * intval($pageNum);
        return BalanceHistory::find()->where(["userId"=>self::ensureMongoId($userId)])
            ->orderBy(["time"=>SORT_DESC])
            ->offset($offset)->limit($pageNum)->all();
    }

    public static function getBalanceHistoryCount($userId) {
        return BalanceHistory::find()->where(['userId' => self::ensureMongoId($userId)])->count();
    }
}
