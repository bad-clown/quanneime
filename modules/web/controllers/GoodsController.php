<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: GoodsController.php
 * $Id: GoodsController.php v 1.0 2015-10-29 21:43:42 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-10-29 22:38:58 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\web\controllers;

use Yii;
use app\modules\admin\logic\GoodsLogic;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;

/**
 * GoodsController implements the CRUD actions for Goods model.
 */
class GoodsController extends BaseController {
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionPagecount() {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = GoodsLogic::getPageCount();
    }

    public function actionList($page, $order = 1) {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = GoodsLogic::getGoodsList($page, $order);
    }
}
