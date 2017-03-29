<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: DeliveredResume.php
 * $Id: DeliveredResume.php v 1.0 2015-11-02 20:04:32 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-07-17 17:12:25 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;
use hipstercreative\user\models\User;

/**
 * This is the model class for table "DeliveredResume".
 *
 */
class DeliveredResume extends \app\components\BaseMongoActiveRecord {
    public $deliverer; // 投递人信息
    public $job; // 职位信息

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'DeliveredResume';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            [['delivererId', 'receiverId', 'jobId', 'status', 'time'], 'safe'],
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['delivererId', 'receiverId', 'jobId', 'status', 'time']);
    }

    public function attributeLabels() {
        return [
            'delivererId' => I18n::text('投递者ID'),
            'receiverId' => I18n::text('接收简历者ID'),
            'jobId' => I18n::text('职位ID'),
            'status' => I18n::text('状态'),
            'time' => I18n::text('投递时间'),
        ];
    }

    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }

    public function afterFind() {
        $this->deliverer = User::findOne($this->delivererId);
        $this->job = Job::findOne($this->jobId);
    }
}
