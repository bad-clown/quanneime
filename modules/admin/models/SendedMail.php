<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: SendedMail.php
 * $Id: SendedMail.php v 1.0 2015-11-02 20:04:32 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-03-10 14:36:04 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;

/**
 * This is the model class for table "SendedMail".
 *
 */
class SendedMail extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'SendedMail';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            [['mailId', 'subscriberId', 'time'], 'safe'],
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['mailId', 'subscriberId', 'time']);
    }

    public function attributeLabels() {
        return [
            'mailId' => I18n::text('邮件ID'), // 分类名称
            'subscriberId' => I18n::text('订阅者ID'), // 父分类
        ];
    }

    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }
}
