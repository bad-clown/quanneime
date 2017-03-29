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

use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\modules\admin\logic\DictionaryLogic;

/**
 * Controller that manages user registration process.
 *
 * @property \hipstercreative\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RegistrationController extends Controller
{
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
                        'actions' => ['register', 'connect'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['confirm', 'resend'],
                        'roles' => ['?', '@']
                    ],
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!$this->module->confirmable && in_array($action->id, ['confirm', 'resend'])) {
                throw new NotFoundHttpException('Disabled by administrator');
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Displays the registration page.
     *
     * @return string
     */
    public function actionRegister()
    {
        $model = $this->module->manager->createUser(['scenario' => 'register']);
        $post = \Yii::$app->getRequest()->post();
        if (isset($post['User']) && isset($post['User']['type']) && $post['User']['type'] == DictionaryLogic::indexKeyValue('UserType', 'CompanyUser')) {
            $model = $this->module->manager->createUser(['scenario' => 'register_comp']);
        }

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->register()) {
            //return $this->redirect(\Yii::alias("m")."/index");
            return $this->redirect(Url::home());
        }

        return $this->render('register', [
            'model' => $model
        ]);
    }

    public function actionConnect($account_id)
    {
        $account = $this->module->manager->findAccountById($account_id);

        if ($account === null || $account->getIsConnected()) {
            throw new NotFoundHttpException('Something went wrong');
        }

        $this->module->confirmable = false;

        $model = $this->module->manager->createUser(['scenario' => 'connect']);
        if ($model->load($_POST) && $model->create()) {
            $account->user_id = $model->_id;
            $account->save(false);
            \Yii::$app->user->login($model, $this->module->rememberFor);
            $this->goBack();
        }

        return $this->render('connect', [
            'model'   => $model,
            'account' => $account
        ]);
    }

    /**
     * Confirms user's account.
     *
     * @param $id
     * @param $token
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $token)
    {
        $user = $this->module->manager->findUserByIdAndConfirmationToken(new \MongoId($id), $token);
        if ($user === null || !$user->confirm()) {
            return $this->render('invalidToken');
        }
        return $this->render('finish');
    }

    /**
     * Displays page where user can request new confirmation token.
     *
     * @return string
     */
    public function actionResend()
    {
        $model = $this->module->manager->createResendForm();

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->validate()) {
            $model->getUser()->resend();

            return $this->render('success', [
                'model' => $model
            ]);
        }

        return $this->render('resend', [
            'model' => $model
        ]);
    }


}