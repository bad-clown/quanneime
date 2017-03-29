<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: BalanceHistory.php
 * $Id: BalanceHistory.php v 1.0 2015-11-09 22:36:22 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-12-28 18:06:27 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\models\I18n;

/**
 * This is the model class for table "BalanceHistory".
 *
 */
class BalanceHistory extends \app\components\BaseMongoActiveRecord {

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'balancehistory';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            [['userId', 'time', 'balance','comment'], 'required'],
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['userId', 'time', 'comment', 'balance']);
    }

    public function attributeLabels() {
        return [
            'userId' => '用户',
            'time' => '时间',
            'comment' => '说明',
            'balance' => '金额',
        ];
    }
}
