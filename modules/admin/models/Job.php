<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Job.php
 * $Id: Job.php v 1.0 2015-11-02 20:04:32 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-04-06 11:10:46 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;
use app\modules\admin\logic\DictionaryLogic;
use hipstercreative\user\models\User;

/**
 * This is the model class for table "Job".
 *
 */
class Job extends \app\components\BaseMongoActiveRecord {
    public $publisherPhone;

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'Job';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            ['name', 'required', 'on' => ['create', 'update']],
            ['name', 'string', 'min' => 2, 'max' => 14],

            ['company', 'required', 'on' => ['create', 'update']],
            ['company', 'string', 'min' => 4, 'max' => 16],

            ['comType', 'required', 'on' => ['create', 'update']],
            ['comType', function($attr) {
                if (in_array($this->$attr, array_keys(DictionaryLogic::indexMap('CompanyType'))) == false) {
                    $this->addError($attr, \Yii::t('user', '单位类型不正确'));
                }
            }, 'on' => ['create', 'update']],

            ['positionType', 'required', 'on' => ['create', 'update']],
            ['positionType', function($attr) {
                if (in_array($this->$attr, array_keys(DictionaryLogic::indexMap('PositionType'))) == false) {
                    $this->addError($attr, \Yii::t('user', '岗位类型不正确'));
                }
            }, 'on' => ['create', 'update']],

            ['salary', 'required', 'on' => ['create', 'update']],
            ['salary', 'match', 'pattern' => '/^[1-9][0-9]*(-[1-9][0-9]*)?$/'],

            ['salaryType', 'required', 'on' => ['create', 'update']],
            ['salaryType', function($attr) {
                if (in_array($this->$attr, array_keys(DictionaryLogic::indexMap('SalaryType'))) == false) {
                    $this->addError($attr, \Yii::t('user', '薪资单位不正确'));
                }
            }, 'on' => ['create', 'update']],

            ['location', 'required', 'on' => ['create', 'update']],
            ['location', 'string', 'min' => 2, 'max' => 140],

            ['attract', 'required', 'on' => ['create', 'update']],
            ['attract', 'string', 'min' => 2, 'max' => 140],

            ['require', 'required', 'on' => ['create', 'update']],

            ['status', 'required', 'on' => ['auth']],
            ['status', 'match', 'pattern' => '/^[01]$/', 'on' => ['auth']],

            /*['rejectMsg', function($attr, $params) {
                var_dump($rejectMsg);var_dump($this->status);exit;
                if ($this->status == DictionaryLogic::indexKeyValue('JobStatus', 'Reject') && strlen(trim($this->$attr)) <= 0)  {
                    $this->addError($attr, '必须有拒绝原因');
                }
            }],*/
        ];
    }

    public function beforeValidate() {
        if ($this->scenario != 'auth') {
            return true;
        }
        if ($this->status == DictionaryLogic::indexKeyValue('JobStatus', 'Reject') && strlen(trim($this->rejectMsg)) <= 0)  {
            $this->addError('rejectMsg', '必须有拒绝原因');
            return false;
        }
        return true;
    }

    public function scenarios() {
        return [
            'create'        => ['name', 'privateMeeting', 'company', 'comType', 'positionType', 'salary', 'salaryType', 'location', 'province', 'city', 'attract', 'require'],
            'update'        => ['name', 'privateMeeting', 'company', 'comType', 'positionType', 'salary', 'salaryType', 'location', 'province', 'city', 'attract', 'require'],
            'update_st'     => ['showTime', 'require'],
            'auth'     => ['status', 'rejectMsg'],
        ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['name', 'privateMeeting', 'company', 'comType', 'positionType', 'salary', 'salaryType', 'location', 'province', 'city', 'attract', 'require', 'time', 'showTime', 'publisher', 'publisherName', 'publisherAvatar', 'status', 'rejectMsg']);
    }

    public function attributeLabels() {
        return [
            'name' => I18n::text('岗位名称'), // 分类名称
            'privateMeeting' => I18n::text('私人会面'),
            'company' => I18n::text('招聘单位'),
            'comType' => I18n::text('单位类型'),
            'positionType' => I18n::text('岗位类型'),
            'salary' => I18n::text('职位薪资'),
            'salaryType' => I18n::text('薪资单位'),
            'location' => I18n::text('办公地点'),
            'province' => I18n::text('省'),
            'city' => I18n::text('市'),
            'attract' => I18n::text('职位诱惑'),
            'require' => I18n::text('岗位要求'),
            'time' => I18n::text('发布时间'),
            'publisher' => I18n::text('发布者ID'),
            'publisherName' => I18n::text('发布者名字'),
            'status' => I18n::text('审核状态'),
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            //$this->showTime = NOW;
            if ($this->privateMeeting == null) {
                $this->privateMeeting = false;
            }
            if ($insert) {
                $this->time = NOW;
                $this->showTime = NOW;
                $this->status = DictionaryLogic::indexKeyValue('JobStatus', 'Pending');
                $this->publisher = \Yii::$app->user->identity->_id;
            }
            return true;
        }
        return false;
    }

    public function afterFind() {
        $user = User::findOne($this->publisher);
        if ($user != null) {
            $this->publisherName = $user->nickname;
            $this->publisherPhone = $user->phoneno;
            $this->publisherAvatar = $user->avatar;
        }
    }
}
