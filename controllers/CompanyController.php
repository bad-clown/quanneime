<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\admin\logic\CompanyLogic;

class CompanyController extends Controller
{
    public function beforeAction($action) {
        if (M) {
            return $this->redirect(['/m/site/index']);
        }
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
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

    public function actionGather($id = '') {
        $comp = CompanyLogic::getCompany($id);
        return $this->render('gather', [
            'company' => $comp
        ]);
    }

    public function actionJobs($id = '', $page = 1) {
        $jobsInfo = CompanyLogic::getCompJobsInfo($id, intval($page), 8);
        $response = \Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $jobsInfo;
    }

    public function actionAuthPublishers($id = '', $cnt = 4) {
        $pubs = CompanyLogic::getPublishers($id, $cnt, true);
        $response = \Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $pubs;
    }

    public function actionList($page = 1) {
        $compList = CompanyLogic::getAllCompany();
        return $this->render('list', [
            'compList' => $compList,
        ]);
    }

     public function actionContact($page = 1) {
        $compList = CompanyLogic::getAllCompany();
        return $this->render('contact', [
            'compList' => $compList,
        ]);
    }

}
