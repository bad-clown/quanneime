<?php

namespace app\modules\m\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\admin\logic\JobLogic;
use app\modules\admin\models\Job;
use app\modules\admin\models\SubscribeMail;
use app\modules\admin\models\JobSearch;
use hipstercreative\user\models\User;

class JobController extends Controller
{
    public function beforeAction($action) {
        if (M == false) {
            return $this->redirect(['/site/index']);
        }
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['publish'],
                'rules' => [
                    [
                        'actions' => ['publish', 'update'],
                        'allow' => true,
                        'matchCallback' => function($rule, $action) {
                            return ! \Yii::$app->user->isGuest && \Yii::$app->user->identity->type == DictionaryLogic::indexKeyValue('UserType', 'CompanyUser');
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionSearch($key = '') {
        return $this->render('search', [
            'key' => $key
        ]);
    }

    public function actionSearchJob($key = '', $comType = null, $positionType = null, $page = 1) {
        $ret = JobLogic::search($key, $comType, $positionType, $page, 20);
        $response = \Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    public function actionDetail($id = '') {
        $job = JobLogic::getJobDetail($id);
        if ($job != null && $job->status != DictionaryLogic::indexKeyValue('JobStatus', 'Pass') && (\Yii::$app->user->isGuest || $job->publisher != \Yii::$app->user->identity->_id)) {
            return $this->goHome();
        }
        if ($job == null) {
            return $this->render('detail', [
                'job' => null,
                'publisher' => null
            ]);
        };
        $publisher = JobLogic::getPublisher($job->publisher);
        $hasDeliver = \Yii::$app->user->isGuest ? false : JobLogic::hasDeliver(\Yii::$app->user->identity->getId(), $job->_id);
        return $this->render('detail', [
            'job' => $job,
            'publisher' => $publisher,
            'hasDeliver' => $hasDeliver,
        ]);
    }
    public function actionDetailapi($id = '') {
        $job = JobLogic::getJobDetail($id);
        $response = \Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $job ;
    }

    public function actionPersonalGather($userId = '') {
        $user = User::findOne(new \MongoId($userId));
        return $this->render('personal-gather', [
            'publisher' => $user,
        ]);
    }

    public function actionPersonalJobs($userId = '', $page = 1) {
        $ret = JobLogic::getPersonalJobs($userId, $page, 10);
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    public function actionPublish()
    {
        $model = new Job(['scenario' => 'create']);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Url::to(['detail', 'id' => (string)$model->_id]));
        } else {
            return $this->render('publish', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if (\Yii::$app->user->isGuest || \Yii::$app->user->identity->_id != $model->publisher) {
            return $this->goHome();
        }
        $model->scenario = 'update';
        $model->status = DictionaryLogic::indexKeyValue('JobStatus', 'Pending');
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Url::to(['detail', 'id' => (string)$model->_id]));
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionSubscribe($email = '') {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $pattern = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        if (preg_match($pattern, $email)) {
            $ret = JobLogic::subscribe($email);
            $response->data = $ret;
        }
        else {
            $response->data = array('code' => DictionaryLogic::getErrorCode('InvalidEmail'));
        }
    }

    public function actionUnsubscribe($id) {
        if (JobLogic::unsubscribe($id)) {
            echo '退订成功';
        }
        else {
            echo '退订失败';
        }
    }

    protected function findModel($id)
    {
        if (($model = Job::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
