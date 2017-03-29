<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: PrivateMeeting.php
 * $Id: PrivateMeeting.php v 1.0 2015-11-02 20:04:32 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-03-14 14:03:09 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;
use app\components\SMSVerifier;
use hipstercreative\user\models\User;

/**
 * This is the model class for table "PrivateMeeting".
 *
 */
class PrivateMeeting extends \app\components\BaseMongoActiveRecord {
    public $deliverer; // 投递人信息
    public $job; // 职位信息

    public $verifyCode;

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'PrivateMeeting';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['delivererId', 'jobId', 'title', 'sex','company', 'position', 'phoneno', 'status', 'delete']);
    }

    public function attributeLabels() {
        return [
            'delivererId' => I18n::text('投递者ID'), // 分类名称
            'jobId' => I18n::text('职位ID'), // 分类名称
            'title' => I18n::text('称谓'), // 分类名称
            'sex' => I18n::text('性别'), // 父分类
            'company' => I18n::text('单位'), // 父分类
            'position' => I18n::text('职位'), // 父分类
            'phoneno' => I18n::text('手机号码'), // 父分类
            'verifyCode' => I18n::text('验证码'), // 父分类
            'status' => I18n::text('状态'), // 父分类
        ];
    }

    public function scenarios() {
        return [
            'create'          => ['title', 'sex', 'phoneno', 'company', 'position', 'verifyCode'],
            'update'          => ['title', 'sex', 'phoneno', 'company', 'position'],
            'process'          => ['status'],
        ];
    }

    public function rules() {
        return array(
            ['title', 'required', 'on' => ['create', 'update']],
            ['title', 'string', 'min' => 1, 'max' => 5],
            ['title', 'trim'],

            ['sex', 'required', 'on' => ['create', 'update']],
            ['sex', 'match', 'pattern' => '/^[12]$/'],

            ['phoneno', 'required', 'on' => ['create', 'update']],
            ['phoneno', 'match', 'pattern' => '/^\d+$/'],
            ['phoneno', 'string', 'min' => 10, 'max' => 11],
            ['phoneno', 'trim'],

            ['company', 'required', 'on' => ['create', 'update']],
            ['company', 'string', 'min' => 4, 'max' => 14],

            ['position', 'required', 'on' => ['create', 'update']],
            ['position', 'string', 'min' => 2, 'max' => 14],

            ['verifyCode', 'required', 'on' => ['create']],
            ['verifyCode', function ($attr) {
                if (SMSVerifier::verifyCode($this->phoneno, $this->$attr, false) == false) {
                    $this->addError($attr, \Yii::t('user', 'Verify code is not valid'));
                }
            }, 'on' => ['create']],
        );
    }

    public function beforeSave($insert) {
        if ($this->delete == null) {
            $this->delete = false;
        }
        return parent::beforeSave($insert);
        //$this->jobId = BaseObject::ensureMongoId($this->jobId);
    }

    public function afterFind() {
        $this->deliverer = User::findOne($this->delivererId);
        $this->job = Job::findOne($this->jobId);
    }
}
