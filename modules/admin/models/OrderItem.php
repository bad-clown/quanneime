<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: OrderItem.php
 * $Id: OrderItem.php v 1.0 2015-11-09 22:36:27 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-09 23:42:57 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\models\I18n;

/**
 * This is the model class for table "orderitem".
 *
 */
class OrderItem extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'orderitem';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            ];
    }

    public function attributes() {
        // orderId关联的是order表的主键_id
        return array_merge($this->primaryKey(), ['orderId', 'goodsId', 'name', 'price', 'totalMoney', 'cashBack', 'count']);
    }

    public function attributeLabels() {
        return [
        ];
    }
}
