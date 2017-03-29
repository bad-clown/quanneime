<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: PingxxController.php
 * $Id: PingxxController.php v 1.0 2015-11-06 22:38:15 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-12-23 20:23:25 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\webhooks\controllers;

use Yii;
use app\modules\admin\logic\OrderLogic;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;

/**
 * PingxxController implements the webhooks of ping++.
 */
class PingxxController extends BaseController {
    public $enableCsrfValidation = false;

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

    /**
     * 支付宝支付成功的success_url回调
     */
    public function actionSuccess() {
        return $this->redirect("/m/account/success",[]);
    }

    /**
     * 支付宝取消支付的cancel_url回调
     */
    public function actionCancel() {
        return $this->redirect("/m/account/cancel",[]);
    }

    /**
     * 银联支付的result_url回调
     */
    public function actionResult() {
        return $this->redirect("/m/account/result",[]);
    }

    /**
     * 支付成功回调
     */
    public function actionChargeSucceeded() {
        $event = json_decode(@file_get_contents('php://input'));
        OrderLogic::onChargeSucceeded($event);
    }

    /*
     * 退款成功回调
     */
    public function actionRefundSucceeded() {
        $event = json_decode(@file_get_contents('php://input'));
        OrderLogic::onRefundSucceeded($event);
    }

    /**
     * 企业付款成功回调
     */
    public function actionTransferSucceeded() {
        // 暂时不用
    }

    /**
     * 红包发送成功回调
     */
    public function actionRedEvelopeSent() {
        // 暂时不用
    }

    /**
     * 红包已领取回调
     */
    public function actionRedEvelopeReceived() {
        // 暂时不用
    }
}
