<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Goods;
use app\modules\admin\models\GoodsSearch;
use app\modules\admin\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;
use yii\helpers\ArrayHelper;

/**
 * GoodsController implements the CRUD actions for Goods model.
 */
class GoodsController extends BaseController
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
     * Lists all Goods models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodsSearch();
        $catSearchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categories' => $catSearchModel->searchMap(["pagesize"=>99999]),
        ]);
    }

    /**
     * Displays a single Goods model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'subgoods' => $model->getSubGoodsData(),
        ]);
    }

    /**
     * Creates a new Goods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Goods();
        $model->recomIndex=10;
        $model->delete = false;
        $searchModel = new CategorySearch();
        $post = Yii::$app->request->post();
        //$model->vipPrice = isset($post["vipPrice"])?$post["vipPrice"]:[];
        if ($model->load($post) && $model->save() && $model->saveSubGoods($post['subgoods'])) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'categories' => $searchModel->searchMap(["pagesize"=>99999]),
            ]);
        }
    }

    /**
     * Updates an existing Goods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $req = \Yii::$app->request;
        $searchModel = new CategorySearch();
        $post = Yii::$app->request->post();
        //$model->vipPrice = isset($post["vipPrice"])?$post["vipPrice"]:[];
        if ($model->load($post) && $model->save() && $model->saveSubGoods(isset($post['subgoods'])?$post['subgoods']:[])) {
            if($req->post("returnUrl",'')==""){
                return $this->redirect(['index']);
            }else{
                return $this->redirect($req->post("returnUrl"));
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'categories' => $searchModel->searchMap(["pagesize"=>99999]),
                'subgoods' => $model->getSubGoodsData(),
            ]);
        }
    }

    /**
     * Deletes an existing Goods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete = true;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Goods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Goods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Goods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
