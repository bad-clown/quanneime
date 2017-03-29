<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Order;
use app\modules\admin\models\User;
use app\modules\admin\models\OrderSearch;
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
class OrderController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'deliver' => ['post'],
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
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $orders = $dataProvider->getModels();
        $userIds = ArrayHelper::getColumn($orders,"userId");
        $users = User::find()->select(["_id","username"])->where(["_id"=>$userIds])->all();
        $users = ArrayHelper::map($users,function($row){return (string)$row["_id"];},"username");

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'users' => $users,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'order' => OrderLogic::getOrderDetail($id),
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();
        $model->parent = null;
        /*if($model->isNewRecord){
            $model["_id"] = (new \yii\db\Query())->select("uuid()")->scalar();
        }*/

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$model->parent = null;

        //var_dump(Yii::$app->request->post());exit;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeliver() {
        $post = Yii::$app->request->post();
        $code = OrderLogic::deliver($post['id'], $post['expressComp'], $post['expressId']);
        $this->renderJSON($code, DictionaryLogic::getErrorMsg($code), (string)null);
    }

    public function actionCancel() {
        $post = Yii::$app->request->post();
        $code = OrderLogic::cancel($post['id'], $post['comment']);
        $this->renderJSON($code, DictionaryLogic::getErrorMsg($code), (string)null);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
