<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: SubscribeMail.php
 * $Id: SubscribeMail.php v 1.0 2015-11-02 20:04:32 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-03-11 12:20:56 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;

/**
 * This is the model class for table "SubscribeMail".
 *
 */
class SubscribeMail extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'SubscribeMail';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            [['title', 'content', 'status', 'time'], 'safe'],
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['title', 'content','status', 'time', 'locked']);
    }

    public function attributeLabels() {
        return [
            'title' => I18n::text('邮件标题'),
            'content' => I18n::text('邮件内容'),
            'status' => I18n::text('邮件状态'),
            'time' => I18n::text('创建日期'),
        ];
    }

    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }
}
