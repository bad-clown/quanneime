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
use yii\filters\VerbFilter;
use yii\authclient\ClientInterface;
use app\modules\admin\logic\DictionaryLogic;

/**
 * Controller that manages user authentication process.
 *
 * @property \hipstercreative\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SecurityController extends Controller
{
    public $enableCsrfValidation = false;
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
                        'actions' => ['login', 'login-json', 'auth'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout', 'change-account'],
                        'roles' => ['@']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'change-account' => ['post']
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'authenticate'],
            ]
        ];
    }

    /**
     * Displays the login page.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $model = $this->module->manager->createLoginForm();

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goHome();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Displays the login page.
     *
     * @return string|\yii\web\Response
     */
    public function actionLoginJson()
    {
        $model = $this->module->manager->createLoginForm();

        if ($model->load(array('login-form' => \Yii::$app->getRequest()->post())) && $model->login()) {
            echo json_encode(array('code' => DictionaryLogic::getErrorCode('Success')));
            return;
        }

        echo json_encode(array('code' => DictionaryLogic::getErrorCode('Loginfail'), 'error' => $model->getErrors()));
        return;
    }

    /**
     * Logs the user out and then redirects to the homepage.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->getUser()->logout();

        return $this->goHome();
    }

    /**
     * Logs the user out and then redirects to the homepage.
     *
     * @return \yii\web\Response
     */
    public function actionChangeAccount()
    {
        \Yii::$app->getUser()->logout();

        return $this->redirect(['login']);
    }

    /**
     * Logs the user in if this social account has been already used. Otherwise shows registration form.
     *
     * @param  ClientInterface $client
     * @return \yii\web\Response
     */
    public function authenticate(ClientInterface $client)
    {
        $attributes = $client->getUserAttributes();
        $provider   = $client->getId();
        $clientId   = $attributes['id'];

        if (null === ($account = $this->module->manager->findAccount($provider, $clientId))) {
            $account = $this->module->manager->createAccount([
                'provider'   => $provider,
                'client_id'  => $clientId,
                'properties' => json_encode($attributes)
            ]);
            $account->save(false);
        }

        if (null === ($user = $account->getUser()->one())) {
            $this->action->successUrl = Url::to(['/user/registration/connect', 'account_id' => (string)$account->_id]);
        } else {
            \Yii::$app->user->login($user, $this->module->rememberFor);
        }
    }
}
