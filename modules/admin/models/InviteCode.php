<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: InviteCode.php
 * $Id: InviteCode.php v 1.0 2015-12-27 20:12:58 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-12-27 23:33:09 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;

/**
 * This is the model class for table "InviteCode".
 *
 */
class InviteCode extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'invitecode';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            [["code", "status" ],"required"],
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['code', 'userId', 'status']);
    }

    public function attributeLabels() {
        return [
            'code' => I18n::text('邀请码'),
            'userid' => I18n::text('用户ID'),
            'status' => I18n::text('状态'),
        ];
    }
}
