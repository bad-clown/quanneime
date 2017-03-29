<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\admin\logic\SubscribeLogic;
use app\modules\admin\models\SubscribeMail;
use app\modules\admin\models\SubscribeMailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;

/**
 * SubscribeMailController implements the CRUD actions for SubscribeMail model.
 */
class SubscribeMailController extends BaseController
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

    public function actionHelp() {
        return $this->render('help.html');
    }

    /**
     * Lists all SubscribeMail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubscribeMailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubscribeMail model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SubscribeMail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SubscribeMail();
        $model->time = NOW;
        $model->status = DictionaryLogic::indexKeyValue('MailStatus', 'New');
        $model->locked = false;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing SubscribeMail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing SubscribeMail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSend($id) {
        $model = $this->findModel($id);
        $model->status = DictionaryLogic::indexKeyValue('MailStatus', 'Sending');
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionTestSend($id) {
        $mail = $this->findModel($id);
        if ($mail == null) {
            return;
        }
        $content = SubscribeLogic::buildContent($mail, '123123123');
        foreach (\Yii::$app->params['testMailList'] as $recv) {
            SubscribeLogic::sendMailTo($recv, $mail->title, $content);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the SubscribeMail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SubscribeMail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubscribeMail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
