<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: DeliveredResumeLogic.php
 * $Id: DeliveredResumeLogic.php v 1.0 2015-11-02 20:06:50 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-04-06 11:19:48 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\components\SMSVerifier;
use app\modules\admin\models\Goods;
use app\modules\admin\models\DeliveredResume;
use app\modules\admin\models\Job;
use hipstercreative\user\models\User;

class DeliveredResumeLogic extends BaseObject {
    public static function deliverResume($delivererId, $jobId) {
        $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        if (DeliveredResume::find()->where(['delivererId' => self::ensureMongoId($delivererId), 'jobId' => self::ensureMongoId($jobId)])->one() != null) {
            return $ret;
        }

        $dr = new DeliveredResume();
        $dr->delivererId = self::ensureMongoId($delivererId);
        $dr->jobId = self::ensureMongoId($jobId);
        $dr->time = NOW;
        $dr->status = DictionaryLogic::indexKeyValue('DeleveredResumeStatus', 'Delivered');
        $job = Job::findOne($jobId);
        if ($job == null) {
            $ret['code'] = DictionaryLogic::getErrorCode('JobNotExist');
            return $ret;
        }
        $dr->receiverId = $job->publisher;
        if ($dr->save() == false) {
            $ret['code'] = DictionaryLogic::getErrorCode('DeliverResumeFailed');
        }
        else {
            $user = User::findOne($job->publisher);
            $user->hasNewResume = true;
            $user->save();
            if ($user->notifyOnNewResume) {
                SMSVerifier::sendNotifyMsg($user->phoneno);
            }
        }
        return $ret;
    }

    public static function getDeliveredList($delivererId, $page = 1, $pageNum = 10) {
        $ret = array('currPage' => $page, 'pageCount' => 1, 'drList' => []);
        $query = DeliveredResume::find()->where(['delivererId' => self::ensureMongoId($delivererId)]);
        $total = $query->count();
        $all = $query->orderBy('time DESC')->offset((intval($page) - 1) * intval($pageNum))->limit(intval($pageNum))->all();
        foreach ($all as $row) {
            if ($row->job == null) {
                continue;
            }
            $dr = $row->getAttributes();
            $dr['position'] = $row->job->name;
            $dr['company'] = $row->job->company;
            $ret['drList'][] = $dr;
        }
        $ret['pageCount'] = ceil($total / intval($pageNum));
        return $ret;
    }

    public static function getUnopenedCount($receiverId) {
        $query = DeliveredResume::find()->where(['receiverId' => self::ensureMongoId($receiverId), 'status' => DictionaryLogic::indexKeyValue('DeleveredResumeStatus', 'Delivered')]);
        return $query->count();
    }

    public static function getReceivedList($receiverId, $page = 1, $pageNum = 10) {
        $ret = array('currPage' => $page, 'pageCount' => 1, 'drList' => []);
        $query = DeliveredResume::find()->where(['receiverId' => self::ensureMongoId($receiverId)]);
        $total = $query->count();
        $all = $query->orderBy('time DESC')->offset((intval($page) - 1) * intval($pageNum))->limit(intval($pageNum))->all();
        foreach ($all as $row) {
            if ($row->job == null || $row->deliverer == null) {
                continue;
            }
            $dr = $row->getAttributes();
            $dr['position'] = $row->job->name;
            $dr['company'] = $row->job->company;
            $dr['avatar'] = $row->deliverer->avatar;
            $dr['nickname'] = $row->deliverer->nickname;
            $ret['drList'][] = $dr;
        }
        $ret['pageCount'] = ceil($total / intval($pageNum));
        return $ret;
    }
}
