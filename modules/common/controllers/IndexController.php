<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: IndexController.php
 * $Id: IndexController.php v 1.0 2015-01-13 21:43:42 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-06-01 15:55:05 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\common\controllers;

use Yii;
use app\modules\admin\logic\GoodsLogic;
use app\modules\admin\logic\CommentLogic;
use app\modules\admin\logic\CategoryLogic;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\captcha\CaptchaValidator;
use app\components\BaseController;
use app\components\SMSVerifier;

class IndexController extends BaseController {
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

    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 50,
                'width' => 80,
                'minLength' => 4,
                'maxLength' => 4
            ),
        );
    }

    public function actionSendVerifyCode($phoneno, $captcha) {
        $cv = new CaptchaValidator;
        $cv->captchaAction = \Yii::$app->params['captcha'];
        $ca = $cv->createCaptchaAction();
        $valid = !is_array($captcha) && $ca->validate($captcha, $cv->caseSensitive);
        if ($valid) {
            SMSVerifier::sendVerifyCode($phoneno);
            $this->renderJSON(0);
        }
        else {
            $this->renderJSON(1);
        }
    }
}
