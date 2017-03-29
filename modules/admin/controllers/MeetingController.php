<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\PrivateMeeting;
use app\modules\admin\models\PrivateMeetingSearch;
use app\modules\admin\models\Dictionary;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;

/**
 * MeetingController implements the CRUD actions for PrivateMeeting model.
 */
class MeetingController extends BaseController
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

    /*public function actionHelp() {
        return $this->render('help.html');
    }*/

    /**
     * Lists all PrivateMeeting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrivateMeetingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProcess($id) {
        $model = $this->findModel($id);
        $model->scenario = 'process';
        $model->status = Dictionary::indexKeyValue('MeetingStatus', 'Processed');
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Displays a single PrivateMeeting model.
     * @param string $id
     * @return mixed
     */
    /*public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/

    /**
     * Creates a new PrivateMeeting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    /*public function actionCreate()
    {
        $model = new PrivateMeeting();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Updates an existing PrivateMeeting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    /*public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Deletes an existing PrivateMeeting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        //$model->delete = true;;
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PrivateMeeting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PrivateMeeting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrivateMeeting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
