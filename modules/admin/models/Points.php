<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Points.php
 * $Id: Points.php v 1.0 2015-11-06 14:51:05 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-02-25 21:36:03 $
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
class Points extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'Points';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            // type是增加积分的类型，1是登陆
            [["userId", "time", "points", "reason", "type"],"required"],
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['userId', 'time', 'points', 'reason', 'type']);
    }

    public function attributeLabels() {
        return [
            'userId' => I18n::text('UserId'), // 用户id
        ];
    }
}
