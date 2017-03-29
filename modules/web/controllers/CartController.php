<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: GoodsController.php
 * $Id: GoodsController.php v 1.0 2015-10-29 21:43:42 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-24 10:57:35 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\web\controllers;

use Yii;
use yii\filters\AccessControl;
use app\modules\admin\logic\CartLogic;
use app\modules\admin\logic\GoodsLogic;
use app\modules\admin\logic\CommentLogic;
use app\modules\admin\logic\CategoryLogic;
use app\modules\admin\logic\OrderLogic;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\admin\models\Order;
use app\modules\admin\models\OrderItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;

class CartController extends BaseController {
    public $title; // see @views/layouts/main.php 
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'submit-order' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['submit-order'],
                'rules' => [
                    [
                        'actions' => ['submit-order'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionItemsCount(){
        $this->renderJSON(0,"",CartLogic::getGoodsCount());
    }
    public function actionItems(){
        $items = CartLogic::getGoodsList();
        foreach ($items as &$item) {
            $item['buyCount'] = GoodsLogic::getGoodsCountInOrder($item['_id']);
        }
        if(Yii::$app->request->isAjax){
            $this->renderJSON(0,"",$items);
        }else{
            return $this->render("items",["items"=>$items]);
        }
    }

    //修改商品数量
    public function actionUpdateItemCount($id, $count) {
        $code = CartLogic::updateGoodsCount($id, $count);
        $this->renderJSON($code, DictionaryLogic::getErrorMsg($code));
    }

    //删除商品
    public function actionDelItem($id){
        $arr = explode(",",$id);
        foreach ($arr as $i) {
            $code = CartLogic::delGoods($i);
        }
        $this->renderJSON(0);
    }

    public function actionAddToCart($id, $count){
        $code = CartLogic::addGoodsToCart($id, $count);
        return $this->actionItems();
    }

    public function actionSubmitOrder(){
        $request = \Yii::$app->request;
        $response = \Yii::$app->response;
        /*if(\Yii::$app->user->isGuest){
            return $response->redirect(["/user/security/login"]);
        }*/
        $data = json_decode($request->post("data"),"[]");//[{"id":"5638c504d4b1b7be228b4567","count":"3"},{"id":"5638c507d4b1b7be228b45d2","count":"1"}]
        $orderId = OrderLogic::generateOrderId();
        $result = CartLogic::submitOrder($orderId, $data);
        if ($result['status'] != 0) {
            $response->format = \yii\web\Response::FORMAT_JSON;
            $response->data = $result;
        } else {
            //var_dump($result);exit;
            //$this->renderJSON($result["status"], DictionaryLogic::getErrorMsg($result["status"]), $result["id"]);
            $response->redirect([\Yii::getAlias("@m")."/account/pay","id"=>$result["id"]]);
        }
    }

}
