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

use yii\authclient\ClientInterface;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\modules\admin\models\BalanceHistory;
use app\modules\admin\logic\BalanceLogic;
use app\modules\admin\logic\DictionaryLogic;

/**
 * SettingsController manages updating user settings (e.g. profile, email and password).
 *
 * @property \hipstercreative\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SettingsController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * @inheritdoc
     */
    public $defaultAction = 'profile';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'reset' => ['post'],
                    'disconnect' => ['post']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['profile', 'email', 'password', 'networks', 'reset', 'connect', 'disconnect'],
                        'roles' => ['@']
                    ],
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'connect' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'connect'],
            ]
        ];
    }

    /**
     * Shows profile settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionProfile($page = "1")
    {
        $model = $this->module->manager->findProfileById(\Yii::$app->user->identity->getId());

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('settings_saved', \Yii::t('user', 'Profile updated successfully'));
            return $this->refresh();
        }
        $page  = intval($page);
        $pageNum = 10;
        $count = BalanceLogic::getBalanceHistoryCount(\Yii::$app->user->identity->getId());
        $pageCount =ceil($count / $pageNum);
        $balanceHistory = BalanceLogic::getBalanceHistory(\Yii::$app->user->identity->getId(), $page, $pageNum);

        return $this->render('profile', [
            'model' => $model,
            'page'=>$page,
            'pageCount'=>$pageCount,
            'balanceHistory'=>$balanceHistory
        ]);
    }

    /**
     * Shows email settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionEmail()
    {
        $model = $this->module->manager->findUserById(\Yii::$app->user->identity->getId());
        $model->scenario = 'update_email';

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->updateEmail()) {
            $this->refresh();
        }

        return $this->render('email', [
            'model' => $model
        ]);
    }

    /**
     * Resets email update.
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionReset()
    {
        if ($this->module->confirmable) {
            $model = $this->module->manager->findUserById(\Yii::$app->user->identity->getId());
            $model->resetEmailUpdate();
            \Yii::$app->getSession()->setFlash('settings_saved', \Yii::t('user', 'Email change has been cancelled'));

            return $this->redirect(['email']);
        }

        throw new NotFoundHttpException;
    }

    /**
     * Shows password settings form.
     *
     * @return string|\yii\web\Response
     */
    public function actionPassword()
    {
        $model = $this->module->manager->findUser(['_id' => \Yii::$app->user->identity->getId()])->one();
        $model->scenario = 'update_password';

        $ret= array();
        if ($model->load(array('User' => \Yii::$app->getRequest()->post())) && $model->save()) {
            //\Yii::$app->getSession()->setFlash('settings_saved', \Yii::t('user', 'Password has been changed'));
            //$this->refresh();
            $ret = array('code' => DictionaryLogic::getErrorCode('Success'));
        }
        else {
            $ret = array('code' => DictionaryLogic::getErrorCode('ChangePasswordFail'), 'error' => $model->getErrors());
        }
        $response = \Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $ret;
    }

    /**
     * Displays list of connected network accounts.
     * 
     * @return string
     */
    public function actionNetworks()
    {
        $user = $this->module->manager->findUser(['_id' => \Yii::$app->user->id])->with('accounts')->one();

        return $this->render('accounts', [
            'user' => $user
        ]);
    }

    /**
     * Disconnects a network account from user.
     *
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDisconnect($id)
    {
        $account = $this->module->manager->findAccountById($id);
        if ($account === null) {
            throw new NotFoundHttpException;
        }
        if ($account->user_id != \Yii::$app->user->id) {
            throw new ForbiddenHttpException;
        }
        $account->delete();

        return $this->redirect(['networks']);
    }

    /**
     * Connects social account to user.
     *
     * @param  ClientInterface $client
     * @return \yii\web\Response
     */
    public function connect(ClientInterface $client)
    {
        $attributes = $client->getUserAttributes();
        $provider   = $client->getId();
        $clientId   = $attributes['id'];

        if (null === ($account = $this->module->manager->findAccount($provider, $clientId))) {
            $account = $this->module->manager->createAccount([
                'provider'   => $provider,
                'client_id'  => $clientId,
                'properties' => json_encode($attributes),
                'user_id'    => \Yii::$app->user->id
            ]);
            $account->save(false);
            \Yii::$app->session->setFlash('account_connected', \Yii::t('user', 'Account has successfully been connected'));
        } else {
            \Yii::$app->session->setFlash('account_not_connected', \Yii::t('user', 'This account has already been connected to another user'));
        }
        return $this->redirect(['networks']);
    }
}
