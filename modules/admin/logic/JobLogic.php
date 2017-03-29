<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: JobLogic.php
 * $Id: JobLogic.php v 1.0 2016-01-29 20:44:15 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-05-12 11:57:56 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\modules\admin\models\Job;
use app\modules\admin\models\Subscriber;
use app\modules\admin\models\DeliveredResume;
use app\modules\admin\models\PrivateMeeting;
use hipstercreative\user\models\User;

class JobLogic extends BaseObject {
    public static function getJobList($comType, $positionType, $page, $pageNum = 10) {
        $page = max(1, intval($page));
        $ret = array();
        $query = Job::find()
            ->andFilterWhere(['comType' => $comType])
            ->andFilterWhere(['positionType' => $positionType])
            ->andFilterWhere(['status' => DictionaryLogic::indexKeyValue('JobStatus', 'Pass')]);
        $total = $query->count();
        $query->orderBy('showTime DESC')
            ->offset((intval($page)-1) * intval($pageNum))
            ->limit(intval($pageNum));
        $all = $query->all();
        $ret['jobList'] = array();
        foreach ($all as $row) {
            $job = $row->getAttributes();
            //$job['publisherName'] = $row->publisherName;
            $ret['jobList'][] = $job;
        }
        $pageCount = ceil($total / intval($pageNum));
        $ret['pageCount'] = $pageCount;
        $ret['currPage'] = $page;
        $ret['totalJobCount'] = $total;
        return $ret;
    }

    public static function search($key, $comType, $positionType, $page, $pageNum = 8) {
        $query = Job::find()->where(array('or', array('like', 'name', $key), array('like', 'company', $key)))
            ->andFilterWhere(['comType' => $comType])
            ->andFilterWhere(['positionType' => $positionType])
            ->andFilterWhere(['status' => DictionaryLogic::indexKeyValue('JobStatus', 'Pass')]);
        $count = $query->count();
        $query->orderBy('showTime DESC')
            ->offset((intval($page) - 1) * intval($pageNum))
            ->limit(intval($pageNum));
        $all = $query->all();
        $ret = array();
        $ret['jobList'] = array();
        foreach ($all as $row) {
            $job = $row->getAttributes();
            $job['publisherName'] = $row->publisherName;
            $ret['jobList'][] = $job;
        }
        $pageCount = ceil($count / intval($pageNum));
        $ret['pageCount'] = $pageCount;
        $ret['currPage'] = $page;
        $ret['totalJobCount'] = $count;
        $ret['key'] = $key;
        return $ret;
    }

    public static function getTotalJobCount() {
        return Job::find()->count();
    }

    public static function getJobDetail($id) {
        return Job::findOne(self::ensureMongoId($id));
    }

    public static function getPublisher($publisherId) {
        return User::findOne(self::ensureMongoId($publisherId));
    }

    public static function getPersonalJobs($userId, $page, $pageNum) {
        $ret = array('currPage' => $page, 'pageCount' => 1, 'jobList' => []);
        $query = Job::find()->where(['publisher' => self::ensureMongoId($userId), 'status' => DictionaryLogic::indexKeyValue('JobStatus', 'Pass')]);
        $totalCnt = $query->count();
        $all = $query->orderBy('showTime DESC')->offset((intval($page)-1) * intval($pageNum))->limit(intval($pageNum))->all();
        foreach ($all as $row) {
            $job = $row->getAttributes();
            $job['publisherName'] = $row->publisherName;
            $ret['jobList'][] = $job;
        }
        $pageCount = ceil($totalCnt / intval($pageNum));
        $ret['pageCount'] = $pageCount;
        return $ret;
    }

    public static function getPersonalPublishedJobs($userId, $page, $pageNum) {
        $ret = array('currPage' => $page, 'pageCount' => 1, 'jobList' => []);
        $query = Job::find()->where(['publisher' => self::ensureMongoId($userId)]);
        $totalCnt = $query->count();
        $all = $query->orderBy('showTime DESC')->offset((intval($page)-1) * intval($pageNum))->limit(intval($pageNum))->all();
        foreach ($all as $row) {
            $job = $row->getAttributes();
            $job['publisherName'] = $row->publisherName;
            $ret['jobList'][] = $job;
        }
        $pageCount = ceil($totalCnt / intval($pageNum));
        $ret['pageCount'] = $pageCount;
        return $ret;
    }

    public static function delete($id) {
        $job = Job::findOne(self::ensureMongoId($id));
        $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        if ($job == null) {
            $ret['code'] = DictionaryLogic::getErrorCode('DeleteJobFail');
        }
        else if ($job->publisher != \Yii::$app->user->identity->_id) {
            $ret['code'] = DictionaryLogic::getErrorCode('DeleteJobFail');
        }
        else if ($job->delete() == false) {
            $ret['code'] = DictionaryLogic::getErrorCode('DeleteJobFail');
        }

        return $ret;
    }

    public static function updateShowTime($id) {
        $job = Job::findOne(self::ensureMongoId($id));
        $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        if ($job == null) {
            $ret['code'] = DictionaryLogic::getErrorCode('UpdateJobFail');
        }
        else if ($job->publisher != \Yii::$app->user->identity->_id) {
            $ret['code'] = DictionaryLogic::getErrorCode('UpdateJobFail');
        }
        else {
            $job->scenario = 'update_st';
            $job->showTime = NOW;
            $job->save();
        }

        return $ret;
    }

    public static function updateAttr($id, $data) {
        $job = Job::findOne(self::ensureMongoId($id));
        $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        if ($job == null) {
            $ret['code'] = DictionaryLogic::getErrorCode('UpdateJobFail');
        }
        else if ($job->publisher != \Yii::$app->user->identity->_id) {
            $ret['code'] = DictionaryLogic::getErrorCode('UpdateJobFail');
        }
        else {
            $job->scenario = 'update';
            $job->status = DictionaryLogic::indexKeyValue('JobStatus', 'Pending');
            foreach ($data as $k => $v) {
                if (isset($job->$k)) {
                    $job->$k = $v;
                }
            }
            $job->save();
        }

        return $ret;
    }

    public static function hasDeliver($userId, $jobId) {
        return DeliveredResume::find()->where(['delivererId' => self::ensureMongoId($userId), 'jobId' => self::ensureMongoId($jobId)])->one() != null
            || PrivateMeeting::find()->where(['delivererId' => self::ensureMongoId($userId), 'jobId' => self::ensureMongoId($jobId)])->one() != null;
    }

    public static function subscribe($email) {
        if (Subscriber::find()->where(['email' => trim($email)])->one() != null) {
            // 已经订阅过了，直接返回成功
            return array('code' => DictionaryLogic::getErrorCode('Success'));
        }
        $sub = new Subscriber(['scenario' => 'create']);
        $sub->email =  $email;
        $sub->time =  NOW;
        if ($sub->save()) {
            return array('code' => DictionaryLogic::getErrorCode('Success'));
        }
        else {
            return array('code' => DictionaryLogic::getErrorCode('SubscribeFail'));
        }
    }

    public function unsubscribe($id) {
        $sub = Subscriber::findOne($id);
        if ($sub == null) {
            return false;
        }
        return $sub->delete();
    }
}
