<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Subscriber.php
 * $Id: Subscriber.php v 1.0 2015-11-02 20:04:32 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-03-10 17:32:44 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;
use app\modules\admin\logic\DictionaryLogic;
use hipstercreative\user\models\User;

/**
 * This is the model class for table "Subscriber".
 *
 */
class Subscriber extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'Subscriber';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            ['email', 'required', 'on' => ['create']],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique'],
            ['email', 'trim'],
        ];
    }

    public function scenarios() {
        return [
            'create'        => ['email'],
        ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['email', 'time']);
    }

    public function attributeLabels() {
        return [
            'email' => I18n::text('邮箱'),
        ];
    }

    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }

    public function afterFind() {
    }
}
