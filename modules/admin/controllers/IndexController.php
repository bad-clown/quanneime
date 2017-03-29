<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: GoodsController.php
 * $Id: GoodsController.php v 1.0 2015-10-29 21:43:42 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-12-04 11:21:47 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;

class IndexController extends BaseController {
    public $title; // see @views/layouts/main.php 
    public $notContentPage; 
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(){
        $this->title="后台";
        $this->notContentPage = true;
        return $this->render("index",[
        ]);
    }

    public function actionFrame(){
        return $this->render('frame',[]);
    }

}
