<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: PointsLogic.php
 * $Id: PointsLogic.php v 1.0 2015-11-06 15:30:58 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-02-25 22:17:12 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\modules\admin\models\Points;
use hipstercreative\user\models\User;

class PointsLogic extends BaseObject {
    public static function getRecord($userId, $page, $pageNum) {
        $ret = array('currPage' => $page, 'pageCount' => 1, 'records' => []);
        $query = Points::find()->select(['_id' => false, 'userId' => false, 'type' => false])->where(['userId' => self::ensureMongoId($userId)]);
        $total = $query->count();
        $all = $query->orderBy('time DESC')->offset((intval($page)-1) * intval($pageNum))->limit(intval($pageNum))->all();
        foreach ($all as $row) {
            $ret['records'][] = $row->getAttributes();
        }
        $ret['pageCount'] = ceil($total / intval($pageNum));
        return $ret;
    }

    public static function addPoints($userId, $type) {
        $points = new Points();
        $points->userId = self::ensureMongoId($userId);
        $points->time = NOW;
        $points->reason = DictionaryLogic::indexKeyValue('PointsAddReason', $type);
        $points->type = DictionaryLogic::indexKeyValue('PointsAddType', $type);
        $points->points = intval(DictionaryLogic::indexKeyValue('PointsAddNum', $type));
        $points->save();
        $user = User::findOne(self::ensureMongoId($userId));
        if ($user != null) {
            $user->points += $points->points;
            $user->save();
        }
    }
}
