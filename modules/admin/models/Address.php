<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Address.php
 * $Id: Address.php v 1.0 2015-11-06 14:51:05 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-06 17:18:45 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;

/**
 * This is the model class for table "Goods".
 *
 */
class Address extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'address';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            [["name", "phone", "province", "city", "district", "detail"],"required"],
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['userId', 'name', 'phone', 'zip', 'province', 'city', 'district', 'detail', 'default']);
    }

    public function attributeLabels() {
        return [
            'userId' => I18n::text('UserId'), // 用户id
            'name' => I18n::text('ReceiverName'), // 收件人姓名
            'phone' => I18n::text('ReceiverPhone'), // 联系电话
            'zip' => I18n::text('Zip Code'), // 邮编
            'province' => I18n::text('Province'), // 省
            'city' => I18n::text('City'), // 市
            'district' => I18n::text('District'), // 区
            'detail' => I18n::text('Detail Address'), // 详细地址
            'default' => I18n::text('Default Address'), // 默认地址
        ];
    }
}
