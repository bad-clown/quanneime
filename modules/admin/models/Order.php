<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Order.php
 * $Id: Order.php v 1.0 2015-11-09 22:36:22 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-12 22:34:32 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\models\I18n;

/**
 * This is the model class for table "Order".
 *
 */
class Order extends \app\components\BaseMongoActiveRecord {
    public $goodsList; // 用来临时存放订单详情里的商品用

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'order';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), [
            // 订单基础数据，这里的orderId单纯就一个和其他支付系统挂钩的号码
            // 本系统中其他地方的orderId，都是指order标的_id字段。
            'orderId', 'userId', 'time', 'totalMoney', 'cashBack','totalPay','useBalance', // totalPay:实付 useBalance : 使用账户余额
            // 订单状态和状态记录
            'status', 'statusRecord',
            // 快递信息
            'expressComp', 'expressId',
            // 收件人信息
            'receiverName', 'receiverAddr', 'receiverPhone',
            // 支付相关信息
            'chargeId', 'channel', 'paid', 'paidTime',
            // 假删除
            'deleted'
            ]);
    }

    public function attributeLabels() {
        return [
            'orderId' => '订单号',
            'userId' => '用户',
            'time' => '创建时间',
            'totalMoney' => '支付金额',
            'useBalance'=>'使用账户余额',
            'totalPay' => '实付',
            'cashBack' => '返现',
            'status' => '状态',
        ];
    }
}
