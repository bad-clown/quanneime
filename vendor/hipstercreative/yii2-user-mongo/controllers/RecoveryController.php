<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace hipstercreative\user\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\base\InvalidParamException;
use app\components\SMSVerifier;
use app\modules\admin\logic\DictionaryLogic;

/**
 * RecoveryController manages password recovery process.
 *
 * @property \hipstercreative\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RecoveryController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['request', 'reset', 'reset-view', 'reset-pwd'],
                        'roles' => ['?']
                    ],
                ]
            ],
        ];
    }

    /**
     * Displays page where user can request new recovery message.
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRequest()
    {
        $model = $this->module->manager->createRecoveryRequestForm();

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->sendRecoveryMessage()) {
            return $this->render('messageSent', [
                'model' => $model
            ]);
        }

        return $this->render('request', [
            'model' => $model
        ]);
    }

    public function actionResetView() {
        return $this->render('password_set');
    }

    public function actionResetPwd(/*$phoneno, $verifyCode, $passwd*/) {
        $post = \Yii::$app->getRequest()->post();
        $phoneno = $post['phoneno'];
        $verifyCode = $post['verifyCode'];
        $passwd = $post['passwd'];
        $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        /*if (SMSVerifier::verifyCode($phoneno, $verifyCode, false) == false) {
            $ret = array('code' => DictionaryLogic::getErrorCode('Fail'));
            $ret['error'] = array('verifyCode' => '验证码错误');
        }else {*/
            SMSVerifier::deleteCodeCache($phoneno);
            $model = $this->module->manager->findUserByPhoneNo($phoneno);
            if ($model == null) {
                $ret = array('code' => DictionaryLogic::getErrorCode('Fail'));
                $ret['error'] = array('phoneno' => '手机号未注册');
            }
            else {
                $model->resetPassword($passwd);
            }
        //}
        $response = \Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    /**
     * Displays page where user can reset password.
     *
     * @param $id
     * @param $token
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionReset()
    {
        $model = $this->module->manager->createRecoveryForm([]);
        /*try {
            $model = $this->module->manager->createRecoveryForm([]);
        } catch (InvalidParamException $e) {
            return $this->render('invalidToken');
        }*/

        if ($model->load(\Yii::$app->getRequest()->post(), '') && $model->resetPassword()) {
            return $this->render('finish');
        }

        return $this->render('password_set', [
            'model' => $model
        ]);
    }
}
