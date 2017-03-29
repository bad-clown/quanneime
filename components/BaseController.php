<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: BaseController.php
 * $Id: BaseController.php v 1.0 2015-10-28 20:31:15 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-09 22:47:55 $
 * @brief
 *
 ******************************************************************/

namespace app\components;


use Yii;
use yii\web\Controller;

/**
 * project base controller
 */
class BaseController extends Controller {
    public function beforeAction($action) {
        return parent::beforeAction($action);
    }

    public function renderJSON($status,$message='',$data=false){
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = ["status"=>$status,"message"=>$message,"data"=>$data];
    }
}
