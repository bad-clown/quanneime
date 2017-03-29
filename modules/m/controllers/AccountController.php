<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: AccountController.php
 * $Id: GoodsController.php v 1.0 2015-10-29 21:43:42 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-04-08 15:58:21 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\m\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\BaseObject;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\image\logic\ImageLogic;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;
use app\components\SMSVerifier;
use app\modules\admin\logic\PointsLogic;
use app\modules\admin\logic\JobLogic;
use app\modules\admin\logic\DeliveredResumeLogic;
use app\modules\admin\models\DeliveredResume;
use app\modules\admin\models\Resume;
use app\modules\admin\models\Job;
use app\modules\admin\models\PrivateMeeting;
use hipstercreative\user\models\User;

class AccountController extends BaseController {
    public function beforeAction($action) {
        if (M == false) {
            return $this->redirect(['/site/index']);
        }
        return parent::beforeAction($action);
    }

    //public $title; // see @views/layouts/main.php
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    /*'deleteJob' => ['post'],
                    'add-address' => ['post'],
                    'create-charge' => ['post'],
                    'update-order-addr' => ['post'],*/
                ],
            ],
            'access' => [ // 本controller 中所有action 都需要登录才可访问
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['comp-account', 'comp-auth', 'update-position-and-comp',
                                    'comp-received-resume', 'comp-points', 'comp-publish', 'comp-password', 'comp-point-rule', 'comp-set-notify', 'comp-resume-list',  'comp-delete-resume', 'comp-view-resume',
                                    'comp-published-jobs', 'delete-job', 'upload-card', 'update-show-time', 'update-attr', 'update-comp-desc',],
                        'allow' => true,
                        'matchCallback' => function($rule, $action) {
                            return ! \Yii::$app->user->isGuest && \Yii::$app->user->identity->type == DictionaryLogic::indexKeyValue('UserType', 'CompanyUser');
                        }
                    ],
                    [
                        'actions' => ['personal-account', 'personal-gather', 'personal-points', 'personal-resume',
                                     'deliver', 'deliver-with-resume', 'deliver-success', 'priv-meeting', 'personal-delivered-resume', 'personal-password', 'personal-point-rule', 'upload-resume-avatar', 'upload-resume-attachment'],
                        'allow' => true,
                        'matchCallback' => function($rule, $action) {
                            return ! \Yii::$app->user->isGuest && \Yii::$app->user->identity->type == DictionaryLogic::indexKeyValue('UserType', 'NormalUser');
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['points-record', 'change-avatar', 'change-phone', 'bind-email'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCompAccount() {
        $model = User::findOne(\Yii::$app->user->identity->getId());
        $model->scenario = 'update_info';

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->save()) {
            //$this->refresh();
            return $this->render('company_account', [
                'model' => $model,
                'success' => true,
                ]);
        }
        return $this->render('company_account', [
            'model' => $model,
            'success' => false,
            ]);
    }

    public function actionChangeAvatar() {
        $pic = UploadedFile::getInstanceByName('pic');
        $src = $pic->tempName . '.'.$pic->extension;
        rename($pic->tempName, $src);
        $dst = $pic->tempName . NOW . rand(100, 999) . '.' . $pic->extension;
        $dstFilename = substr($dst, strrpos($dst, '/')+1);
        $avatarPath = ImageLogic::saveUserAvatar($src, $dstFilename);
        $data = array('code' => DictionaryLogic::getErrorCode('Success'));
        if ($avatarPath == '') {
            $data['code'] = DictionaryLogic::getErrorCode('UploadFailed');
        }
        else {
            $user = User::findOne(\Yii::$app->user->identity->_id);
            $user->avatar = $avatarPath;
            $user->save();
            $data['url'] = $avatarPath;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $data;
    }

    public function actionCompAuth() {
        $model = User::findOne(\Yii::$app->user->identity->getId());
        return $this->render('company_authentication', [
            'model' => $model,
            ]);
    }

    public function actionUpdatePositionAndComp($comp = null, $position = null) {
        $model = User::findOne(\Yii::$app->user->identity->getId());
        $mod = false;
        if ($comp != null) {
            $model->company = $comp;
            $mod = true;
        }
        if ($position != null) {
            $model->position = $position;
            $mod = true;
        }
        $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        if ($mod) {
            $model->authentication = false;
            $model->authStatus = DictionaryLogic::indexKeyValue('AuthStatus', 'Pending');
            $model->save();
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    public function actionUploadCard($url) {
        $model = User::findOne(\Yii::$app->user->identity->getId());
        $model->card = $url;
        $model->authentication = false;
        $model->authStatus = DictionaryLogic::indexKeyValue('AuthStatus', 'Pending');
        $model->save();

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = array('code' => DictionaryLogic::getErrorCode('Success'));
    }

    public function actionChangePhone($phoneno, $code) {
        $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        if (SMSVerifier::verifyCode($phoneno, $code) == false) {
            $ret['code'] = DictionaryLogic::getErrorCode('VerifyCodeFail');
        }
        else {
            $model = User::findOne(\Yii::$app->user->identity->getId());
            $model->scenario = 'update_phone';
            $model->phoneno = $phoneno;
            $model->authentication = false;
            $model->authStatus = DictionaryLogic::indexKeyValue('AuthStatus', 'Pending');
            if ($model->validate() == false) {
                $ret['code'] = DictionaryLogic::getErrorCode('Fail');
            }
            else {
                $model->save();
            }
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    public function actionBindEmail($email) {
        $model = User::findOne(\Yii::$app->user->identity->getId());
        $model->scenario = 'update_email';
        $model->unconfirmed_email = $email;
        $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        if ($model->updateEmail() == false) {
            $ret = array('code' => DictionaryLogic::getErrorCode('UpdateFail'));
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    public function actionCompPoints() {
        return $this->render('company_mypoints');
    }

    public function actionCompPublish() {
        return $this->render('company_mypublish');
    }

    public function actionCompPassword() {
        return $this->render('company_passowrd');
    }

    public function actionCompPointRule() {
        return $this->render('company_pointsrule');
    }

    public function actionCompSetNotify() {
        $model = User::findOne(\Yii::$app->user->identity->getId());
        $model->scenario = 'update_info';

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->render('company_notify_setting', [
                'model' => $model,
                'success' => true,
                ]);
        }
        return $this->render('company_notify_setting', [
            'model' => $model,
            'success' => false,
            ]);
    }

    public function actionCompResumeList() {
        return $this->render('company_resumelist');
    }

    public function actionCompReceivedResume($page = 1, $pageNum = 10) {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = DeliveredResumeLogic::getReceivedList(\Yii::$app->user->identity->getId(), intval($page), intval($pageNum));
    }

    public function actionCompDeleteResume($id) {
        $delivered = DeliveredResume::findOne($id);
        $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        if ($delivered != null) {
            $delivered->delete();
        }
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    public function actionCompViewResume($delivererId, $jobId) {
        $resume = Resume::find()->where(['userId' => BaseObject::ensureMongoId($delivererId)])->one();
        $delivered = DeliveredResume::find()->where(['jobId' => BaseObject::ensureMongoId($jobId), 'delivererId' => BaseObject::ensureMongoId($delivererId), 'receiverId' => \Yii::$app->user->identity->getId()])->one();
        if ($resume != null && $delivered != null) {
            $delivered->status = DictionaryLogic::indexKeyValue('DeleveredResumeStatus', 'Viewed');
            $delivered->save();
        }
        if ($delivered == null) {
            $resume == new Resume();
        }

        return $this->render('resume', [
            'model' => $resume,
            ]);
    }

    public function actionPersonalAccount() {
        $model = User::findOne(\Yii::$app->user->identity->getId());
        $model->scenario = 'update_info';

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->render('personal_account', [
                'model' => $model,
                'success' => true,
                ]);
        }
        return $this->render('personal_account', [
            'model' => $model,
            'success' => false,
            ]);
    }

    public function actionUploadResumeAttachment() {
        $file = UploadedFile::getInstanceByName('file');
        $dst = $file->tempName . NOW . rand(100, 999) . '.' . $file->extension;
        $dstFilename = substr($dst, strrpos($dst, '/')+1);
        $url = '/data/attachment/' . $dstFilename;
        $dstPath = WEBROOT . $url;
        if (is_dir(dirname($dstPath)) == false) {
            mkdir(dirname($dstPath), 0777, true);
        }
        $data = array('code' => DictionaryLogic::getErrorCode('Success'));
        if (rename($file->tempName, $dstPath) == false) {
            $data['code'] = DictionaryLogic::getErrorCode('UploadFailed');
        }
        else {
            $data['url'] = $url;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $data;
    }

    public function actionUploadResumeAvatar() {
        $pic = UploadedFile::getInstanceByName('pic');
        $dst = $pic->tempName . NOW . rand(100, 999) . '.' . $pic->extension;
        $dstFilename = substr($dst, strrpos($dst, '/')+1);
        $url = '/data/resume_avatar/' . $dstFilename;
        $dstPath = WEBROOT . $url;
        if (is_dir(dirname($dstPath)) == false) {
            mkdir(dirname($dstPath), 0777, true);
        }
        $data = array('code' => DictionaryLogic::getErrorCode('Success'));
        if (rename($pic->tempName, $dstPath) == false) {
            $data['code'] = DictionaryLogic::getErrorCode('UploadFailed');
        }
        else {
            $data['url'] = $url;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $data;
    }

    public function actionPersonalGather() {
        return $this->render('personal_mydeliver');
    }

    public function actionPersonalDeliveredResume($page = 1, $pageNum = 10) {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = DeliveredResumeLogic::getDeliveredList(\Yii::$app->user->identity->getId(), intval($page), intval($pageNum));
    }

    public function actionPersonalPoints() {
        return $this->render('personal_mypoints');
    }

    public function actionPersonalResume() {
        $model = Resume::find()->where(['userId' => \Yii::$app->user->identity->_id])->one();
        if ($model == null) {
            $model = new Resume();
            $model->userId = \Yii::$app->user->identity->_id;
        }
        $model->scenario = 'update';

        //var_dump(\Yii::$app->getRequest()->post());exit;

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->render('personal_myresume', [
                'model' => $model,
                'success' => true,
                ]);
        }
        return $this->render('personal_myresume', [
            'model' => $model,
            'success' => false,
            ]);
    }

    public function actionPersonalPassword() {
        return $this->render('personal_passowrd');
    }

    public function actionPersonalPointRule() {
        return $this->render('personal_pointsrule');
    }

    ///////////////////////////
    public function actionPointsRecord($page = 1) {
        $ret = PointsLogic::getRecord(\Yii::$app->user->identity->_id, $page, 10);
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    public function actionCompPublishedJobs($page = 1) {
        $ret = JobLogic::getPersonalPublishedJobs(\Yii::$app->user->identity->_id, $page, 10);
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    public function actionDeleteJob($id) {
        $ret = JobLogic::delete($id);
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    public function actionUpdateShowTime($id) {
        $ret = JobLogic::updateShowTime($id);
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    public function actionUpdateAttr($id) {
        $ret = JobLogic::updateAttr($id, \Yii::$app->request->get());
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;

    }

    public function actionUpdateCompDesc($compDesc) {
        $user = User::findOne(\Yii::$app->user->identity->_id);
        $user->compDesc = $compDesc;
        $user->save();
        $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;

    }

    public function actionDeliver($jobId) {
        $resume = Resume::find()->where(['userId' => \Yii::$app->user->identity->_id])->one();
        if ($resume == null) {
            return $this->redirect(array('deliver-with-resume', 'jobId' => $jobId));
        }
        $ret = DeliveredResumeLogic::deliverResume(\Yii::$app->user->identity->_id, $jobId);
        /*if ($ret['code'] == DictionaryLogic::getErrorCode('Success')) {
            return $this->redirect(array('deliver-success', 'jobId' => $jobId));
        }*/

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    public function actionDeliverWithResume($jobId) {
        $model = Resume::find()->where(['userId' => \Yii::$app->user->identity->_id])->one();
        if ($model == null) {
            $model = new Resume();
            $model->userId = \Yii::$app->user->identity->_id;
        }
        $model->scenario = 'update';

        //var_dump(\Yii::$app->getRequest()->post());exit;

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->save()) {
            $ret = DeliveredResumeLogic::deliverResume(\Yii::$app->user->identity->_id, $jobId);
            return $this->redirect(array('deliver-success', 'jobId' => $jobId));
        }
        $job = Job::findOne($jobId);
        $hasDeliver = JobLogic::hasDeliver(\Yii::$app->user->identity->_id, $jobId);
        return $this->render('delivery_resume', [
            'model' => $model,
            'jobId' => $jobId,
            'job' => $job,
            'hasDeliver' => $hasDeliver,
            ]);
    }

    public function actionDeliverSuccess($jobId = '') {
        return $this->render('success', ['jobId' => $jobId]);
    }

    public function actionPrivMeeting($jobId) {
        $model = new PrivateMeeting();
        $model->scenario = 'create';
        $model->delivererId = \Yii::$app->user->identity->getId();
        $model->jobId = BaseObject::ensureMongoId($jobId);
        $model->status = DictionaryLogic::indexKeyValue('MeetingStatus', 'New');

        //var_dump($model->load(\Yii::$app->request->post()));
        //var_dump($model->save());exit;
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['deliver-success', 'jobId' => (string)$model->jobId]);
        } else {
            return $this->render('meeting', [
                'model' => $model,
                'jobId' => $jobId,
            ]);
        }
    }
}
