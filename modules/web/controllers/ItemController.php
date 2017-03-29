<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: GoodsController.php
 * $Id: GoodsController.php v 1.0 2015-10-29 21:43:42 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-16 22:09:31 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\web\controllers;

use Yii;
use app\modules\admin\logic\GoodsLogic;
use app\modules\admin\logic\CommentLogic;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;

class ItemController extends BaseController {
    public $title; // see @views/layouts/main.php 
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'comment' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex($id="",$page="1"){
        $item = GoodsLogic::getGoodsById($id);
        if($item ==null){
           return $this->render("index",["notexists"=>true]);
        }
        $c = CommentLogic::getGoodsCommentCount($id);
        $this->title=$item->name;
        return $this->render("index",[
            "notexists" =>false,
            "item"=>$item,
            "subgoods"=>$item->getSubGoodsData(),
            'id' => $id,
            'page' => $page,
            "commentCount" => $c,
            "commentPageCount" => ceil($c/20),
            "comments"=>CommentLogic::getGoodsComment($id,intval($page),20)
        ]);
    }

    public function actionSupport($commentId) {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = ["status"=>CommentLogic::supportComment($commentId)];
    }

    /**
     * 添加一条评论
     * @param goodsId 商品id
     * @param content 评论内容
     */
    public function actionComment($goodsId) {
        $content = \Yii::$app->request->post("content","");
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = ["status"=>CommentLogic::addGoodsComment($goodsId, $content)];
    }

    public function actionPic($id=""){
        return $this->render("pic",[]);
    }
}
