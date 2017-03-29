<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: AddressLogic.php
 * $Id: AddressLogic.php v 1.0 2015-11-06 15:30:58 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-12 14:26:44 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\modules\admin\models\Address;

class AddressLogic extends BaseObject {
    /**
     * 添加一个收货地址
     */
    public static function addAddress($params) {
        $addr = new Address;
        foreach ($params as $k => $v) {
            $addr->$k = $v;
        }
        $addr->userId = \Yii::$app->user->identity->_id;
        $addrCount = self::getAddressCount();
        $addr->default = ($addrCount  <= 0 ? true : false);
        $addr->save();
        return $addr->_id;
    }

    /**
     * 获取用户总地址条数
     */
    public static function getAddressCount() {
        return Address::find()->where(['userId' => \Yii::$app->user->identity->_id])->count();
    }

    /**
     * 获取用户地址列表
     */
    public static function getAllAddress() {
        return Address::find()->where(['userId' => \Yii::$app->user->identity->_id])->all();
    }

    /**
     * 获取默认地址
     */
    public static function getDefaultAddress() {
        return Address::find()->where(['userId' => \Yii::$app->user->identity->_id, 'default' => true])->one();
    }

    /**
     * 修改一条地址
     */
    public static function updateAddress($addrId, $param) {
        $addr = Address::findOne(self::ensureMongoId($addrId));
        if ($addr == null) {
            return false;
        }
        $default = $addr->default;
        foreach ($param as $k => $v) {
            $addr->$k = $v;
        }
        $addr->_id = self::ensureMongoId($addrId);
        $addr->default = $default;
        return $addr->save();
    }

    /**
     * 删除一条收货地址
     */
    public static function deleteAddress($addrId) {
        $addr = Address::findOne(self::ensureMongoId($addrId));
        if($addr == null) {
            return false;
        }
        return $addr->delete();
    }

    /**
     * 设置为默认地址
     */
    public static function setDefaultAddress($addrId) {
        $addr = Address::findOne(self::ensureMongoId($addrId));
        if($addr == null) {
            return false;
        }
        $old = self::getDefaultAddress();
        if ($old) {
            $old->default = false;
            if ($old->save() == false) {
                return false;
            }
        }
        $addr->default = true;
        if ($addr->save() == false) {
            // 回滚$old的操作
            if ($old) {
                $old->default = true;
                $old->save();
            }
            return false;
        }
        return true;
    }
}
