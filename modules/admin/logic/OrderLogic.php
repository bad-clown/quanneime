<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: OrderLogic.php
 * $Id: OrderLogic.php v 1.0 2015-11-09 23:18:32 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-20 21:08:08 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\components\Pingxx;
use app\modules\admin\models\Order;
use app\modules\admin\models\OrderItem;
use app\modules\admin\models\AutoInc;

class OrderLogic extends BaseObject {
    /**
     * 获取用户订单列表
     */
    public static function getOrderList($key = '', $page = 1, $pageNum = 15) {
        if (\Yii::$app->user->isGuest) {
            return [];
        }
        $offset = (intval($page) - 1) * intval($pageNum);
        $query = Order::find()->select(['orderId', 'time', 'totalMoney', 'cashBack', 'status']);
        $query->where(['userId' => \Yii::$app->user->identity->_id, 'deleted' => null]);
        $query->orderBy('time DESC')->offset($offset)->limit(intval($pageNum));
        return $query->all();
    }

    /**
     * 获取用户订单列表
     */
    public static function getOrderCount($key = '') {
        if (\Yii::$app->user->isGuest) {
            return 0;
        }
        $query = Order::find();
        $query->where(['userId' => \Yii::$app->user->identity->_id, 'deleted' => null]);
        $count = $query->count();
        return $count;
    }

    /**
     * 获取订单详情
     */
    public static function getOrderDetail($orderId) {
        $order = Order::findOne(self::ensureMongoId($orderId));
        if ($order == null) {
            return null;
        }
        $order->goodsList = OrderItem::find()->where(['orderId' => $order["orderId"]])->all();
        return $order;
    }

    /**
     * 生成一个订单号
     */
    public static function generateOrderId() {
        $orderId = DictionaryLogic::indexKeyValue('App', 'OrderIdPrefix', false);
        $orderId .= date('ymdHis', NOW);
        $next = AutoInc::next() % 100000; // 保证是 [0-99999]
        $next = ($next < 10000 ? $next + 10000 : $next); // 保证是5为数
        $orderId .= $next;
        return $orderId;
    }

    public static function updateOrder($id,$params){
        $order = Order::findOne(self::ensureMongoId($id));
        foreach ($params as $k=>$v) {
            $order->$k = $v;
        }
        return $order->save();
    }

    public static function deliver($orderId, $expressComp, $expressId) {
        $order = Order::findOne(self::ensureMongoId($orderId));
        if ($order == null) {
            return DictionaryLogic::indexKeyValue('ErrorCode', 'NoSuchOrder');
        }
        if ($order->expressId != null) {
            return DictionaryLogic::indexKeyValue('ErrorCode', 'DupDeliver');
        }
        if (DictionaryLogic::indexKeyValue('ExpressCompany', $expressComp) == '') {
            return DictionaryLogic::indexKeyValue('ErrorCode', 'ExpressCompNotSupport');
        }
        $order->expressComp = $expressComp;
        $order->expressId = $expressId;
        $order->status = (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Delivered', false);
        $record = $order->statusRecord;
        $record[] = array($order->status, NOW);
        $order->statusRecord = $record;
        $order->save();
        return DictionaryLogic::indexKeyValue('ErrorCode', 'Success');
    }

    public static function cancel($orderId,$comment) {
        $order = Order::findOne(self::ensureMongoId($orderId));
        if ($order == null) {
            return DictionaryLogic::indexKeyValue('ErrorCode', 'NoSuchOrder');
        }
        if ($order->status >= (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Delivered', false)) {
            return DictionaryLogic::indexKeyValue('ErrorCode', 'CannotCancel', false);
        }
        if ($order->status == (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Paid', false)) { // 这个订单已经支付，退款到余额
            if(BalanceLogic::refund($order) == false) {
                return DictionaryLogic::indexKeyValue('ErrorCode', 'RefundFail');
            }
        }
        $order->status = (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Cancelled', false);
        $record = $order->statusRecord;
        $record[] = array($order->status, NOW,\Yii::$app->user->identity->username,$comment);
        $order->statusRecord = $record;
        $order->save();
        // 将订单中的商品数量回归到库存中
        $orderItemList = OrderItem::find()->where(['orderId' => $order->orderId])->all();
        foreach ($orderItemList as $item) {
            $goods = GoodsLogic::getGoodsById($item->goodsId);
            if ($goods != null) {
                $goods->count += $item->count;
                $goods->save();
            }
        }
        return DictionaryLogic::indexKeyValue('ErrorCode', 'Success');
    }

    public static function delete($orderId) {
        $order = Order::findOne(self::ensureMongoId($orderId));
        if ($order == null) {
            return DictionaryLogic::indexKeyValue('ErrorCode', 'NoSuchOrder');
        }
        if ($order->status < (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Completed', false)) {
            return DictionaryLogic::indexKeyValue('ErrorCode', 'CannotCancel', false);
        }
        $order->deleted = true;
        $order->save();
        return DictionaryLogic::indexKeyValue('ErrorCode', 'Success');
    }

    public static function receivedGoods($orderId) {
        $order = Order::findOne(self::ensureMongoId($orderId));
        if ($order == null) {
            return DictionaryLogic::indexKeyValue('ErrorCode', 'NoSuchOrder');
        }
        if ($order->status != (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Delivered', false)) {
            return DictionaryLogic::indexKeyValue('ErrorCode', 'CannotConfirmReceived');
        }
        $order->status = (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Completed', false);
        $record = $order->statusRecord;
        $record[] = array((int)DictionaryLogic::indexKeyValue('OrderStatus', 'Received', false), NOW, \Yii::$app->user->identity->username);
        $record[] = array($order->status, NOW, \Yii::$app->user->identity->username);
        $order->statusRecord = $record;
        $order->save();
        BalanceLogic::cashBack($order);
        return DictionaryLogic::indexKeyValue('ErrorCode', 'Success');
    }

    //////////////////////支付相关接口/////////////////////////////
    /**
     * 创建一个支付对象，如果不满足支付条件或者创建失败，返回null
     */
    public static function createCharge($orderNo, $channel, $clientIp,$useBalance="1") {
        $result = array();
        $result['status'] = DictionaryLogic::getErrorCode('Success');
        $result['charge'] = null;
        $order = Order::find()->where(['orderId' => $orderNo, 'userId' =>\Yii::$app->user->identity->_id])->one();
        if ($order == null) {
            $result['status'] = DictionaryLogic::getErrorCode('NoSuchOrder');
            return $result;
        }
        if (($order->status != (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Submitted', false)) &&
            ($order->status != (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Paying', false))) {
            $result['status'] = DictionaryLogic::getErrorCode('OrderHasPaid');
            return $result;
            }
        $payingOrders = Order::find()->where(['userId' =>\Yii::$app->user->identity->_id, 'status' => (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Paying', false)])->all();
        foreach ($payingOrders as $po) {
            if ($po->orderId != $orderNo) {
                $result['status'] = DictionaryLogic::getErrorCode('MultiPayingOrder');
                return $result;
            }
        }
        if ($order->receiverName == null) {
            $result['status'] = DictionaryLogic::getErrorCode('ReceiverNotSet');
            return $result;
        }

        $amount = max(0,$order->totalMoney - ($useBalance=="1"? BalanceLogic::getBalance(\Yii::$app->user->identity->_id):0));
        if ($amount <= 0) { // 余额已经足够支付，不需要再生生成支付对象
            $order->chargeId = null;
            $order->channel = null;
            $order->paid = true;
            $order->paidTime = NOW;
            $order->totalPay = 0;
            $order->useBalance = $order->totalMoney;

            if (BalanceLogic::payForOrder($order) == false) {
                $result['status'] = DictionaryLogic::getErrorCode('UseBalanceFailed');
                return $result;
            }

            $order->status = (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Paid', false);
            $record = $order->statusRecord;
            $record[] = array($order->status, $order->paidTime);
            $order->statusRecord = $record;
            $order->save();
            $result['status'] = DictionaryLogic::getErrorCode('PaySuccess');
            return $result;
        }
        $charge = Pingxx::createCharge($orderNo, $amount, $channel, $clientIp);
        if ($charge == null) {
            $result['status'] = DictionaryLogic::getErrorCode('CreateChargeFailed');
            return $result;
        }
        $order->chargeId = $charge->id;
        $order->channel = $channel;
        $order->paid = false;
        $order->totalPay = $amount;
        $order->useBalance = $order->totalMoney - $amount;
        // 增加支付中状态，同一个用户只能有一个订单处于支付中状态
        $order->status = (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Paying', false);
        $record = $order->statusRecord;
        $record[] = array($order->status, NOW);
        $order->statusRecord = $record;
        $order->save();
        $result['charge'] = $charge;
        return $result;
    }

    /**
     * 支付成功的回调
     */
    public static function onChargeSucceeded($event) {
        $order = Order::find()->where(['orderId' => $event->data->object->order_no])->one();
        if ($order == null) {
            return;
        }
        $order->paid = $event->data->object->paid;
        $order->paidTime = $event->data->object->time_paid;
        if ($order->paid) {
            $order->status = (int)DictionaryLogic::indexKeyValue('OrderStatus', 'Paid', false);
            $record = $order->statusRecord;
            $record[] = array($order->status, $order->paidTime);
            $order->statusRecord = $record;
        }
        $order->save();
        if ($order->useBalance > 0) {
            BalanceLogic::payForOrder($order);
        }
    }

    /**
     * 退款成功的回调
     */
    public static function onRefundSucceeded($event) {
        //TODO: 后续加退款功能再说
    }

    public static function getOrderStatusAndTextMap() {
        $statusMap = DictionaryLogic::indexMap('OrderStatus');
        $statusTextMap = DictionaryLogic::indexMap('OrderStatusText');
        $map = array();
        foreach ($statusMap as $status => $value) {
            $map[$value] = $statusTextMap[$status];
        }
        return $map;
    }

    public static function getValidOrders($userId) {
        return Order::find()->select(['orderId', '_id' => false])->where(['userId' => self::ensureMongoId($userId)])->all();
    }

    public static function getSumOfOrderItems($userId) {
        $orderList = self::getValidOrders($userId);
        $orderIds = array();
        foreach ($orderList as $order) {
            $orderIds[] = $order->orderId;
        }
        $orderItemList = OrderItem::find()->select(['count', '_id'=>false])->where(['in', 'orderId', $orderIds])->all();
        $total = 0;
        foreach ($orderItemList as $orderItem) {
            $total += $orderItem->count;
        }
        return $total;
    }
}
