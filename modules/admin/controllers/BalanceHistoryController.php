<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\BalanceHistory;
use app\modules\admin\models\User;
use app\modules\admin\models\BalanceHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;
use app\modules\admin\logic\OrderLogic;
use app\modules\admin\logic\DictionaryLogic;
use yii\helpers\ArrayHelper;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class BalanceHistoryController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BalanceHistorySearch();
        $params = Yii::$app->request->queryParams;
        if( isset($params["BalanceHistorySearch"]) && isset($params["BalanceHistorySearch"]["userId"]) ){
            $params["BalanceHistorySearch"]["userId"] = new \MongoId($params["BalanceHistorySearch"]["userId"]);
        }
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
