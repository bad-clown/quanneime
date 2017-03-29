<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: DeliveredController.php
 * $Id: DeliveredController.php v 1.0 2016-07-17 13:13:20 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-07-17 13:24:27 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\DeliveredResume;
use app\modules\admin\models\DeliveredResumeSearch;
use app\modules\admin\models\Job;
use app\modules\admin\logic\DeliveredResumeLogic;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;

/**
 * DeliveredController implements the CRUD actions for Company model.
 */
class DeliveredController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all DeliveredResume models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeliveredResumeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
}
