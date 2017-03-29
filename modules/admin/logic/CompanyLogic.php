<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: CompanyLogic.php
 * $Id: CompanyLogic.php v 1.0 2015-12-27 20:54:12 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-03-11 23:08:13 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\modules\admin\models\Company;
use app\modules\admin\models\Job;
use app\modules\admin\models\AutoInc;
use hipstercreative\user\models\User;

class CompanyLogic extends BaseObject {
    public static function getCompany($compId) {
        return Company::findOne(self::ensureMongoId($compId));
    }

    public static function getCompJobsInfo($compId, $page, $pageNum) {
        $company = self::getCompany($compId);
        $ret = array('currPage' => $page, 'pageCount' => 1, 'jobList' => []);
        if ($company == null || $company->keys == null || trim($company->keys) == '') {
            return $ret;
        }
        $keyList = preg_split('/ +/', $company->keys);
        $orCond = array('or');
        foreach ($keyList as $k) {
            $orCond[] = array('like', 'company', $k);
        }
        $all = Job::find()->where($orCond)->andWhere(['status' => DictionaryLogic::indexKeyValue('JobStatus', 'Pass')])->orderBy('showTime DESC')->offset((intval($page)-1) * intval($pageNum))->limit(intval($pageNum))->all();
        foreach ($all as $row) {
            $job = $row->getAttributes();
            $job['publisherName'] = $row->publisherName;
            $ret['jobList'][] = $job;
        }
        $pageCount = ceil($company->jobCount / intval($pageNum));
        $ret['pageCount'] = $pageCount;
        return $ret;
    }

    public function getPublishers($compId, $cnt, $auth) {
        $company = self::getCompany($compId);
        $ret = array('pubCount' => 0, 'publishers' => array());
        if ($company == null || $company->keys == null || trim($company->keys) == '') {
            return $ret;
        }
        $keyList = preg_split('/ +/', $company->keys);
        $orCond = array('or');
        foreach ($keyList as $k) {
            $orCond[] = array('like', 'company', $k);
        }
        $query = User::find()->where($orCond);
        if ($auth == true) {
            $query->andWhere(array('authStatus' => DictionaryLogic::indexKeyValue('AuthStatus', 'Pass')));
        }
        $count = $query->count();
        if ($cnt > 0) {
            $query->limit(intval($cnt));
        }
        $all = $query->all();
        foreach ($all as $row) {
            $pub = $row->getAttributes();
            //$pub['jobCount'] = $row->jobCount;
        }
        $ret['publishers'] = $all;
        $ret['pubCount'] = $count;
        return $ret;
    }

    public function getAllCompany() {
        return Company::find()->all();
    }
}
