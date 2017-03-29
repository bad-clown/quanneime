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

use hipstercreative\user\models\UserSearch;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\modules\image\logic\ImageLogic;
use app\modules\admin\logic\DictionaryLogic;
use hipstercreative\user\models\User;

/**
 * AdminController allows you to administrate users.
 *
 * @property \hipstercreative\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class AdminController extends Controller
{
    public $layout ="@app/modules/admin/views/layouts/admin.php";
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'confirm' => ['post'],
                    'delete-tokens' => ['post'],
                    'block' => ['post']
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'change-avatar', 'view-avatar', 'create', 'update', 'delete', 'block', 'confirm', 'delete-tokens', 'auth'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return in_array(\Yii::$app->user->identity->username, $this->module->admins);
                        }
                    ],
                ]
            ]
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new UserSearch();
        $dataProvider = $searchModel->search($_GET);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = $this->module->manager->createUser(['scenario' => 'create']);

        if ($model->load(\Yii::$app->request->post()) && $model->create()) {
            \Yii::$app->getSession()->setFlash('admin_user', \Yii::t('user', 'User has been created'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param  integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('admin_user', \Yii::t('user', 'User has been updated'));
            return $this->refresh();
            //return $this->redirect(["index"]);
        }else{
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionViewAvatar($id) {
        $model = $this->findModel($id);

        return $this->render('avatar', [
            'model' => $model
        ]);
    }

    public function actionChangeAvatar() {
        $id = \Yii::$app->request->post()['id'];
        $pic = UploadedFile::getInstanceByName('pic');
        $src = $pic->tempName . '.'.$pic->extension;
        rename($pic->tempName, $src);
        $dst = $pic->tempName . NOW . rand(100, 999) . '.' . $pic->extension;
        $dstFilename = substr($dst, strrpos($dst, '/')+1);
        $avatarPath = ImageLogic::saveUserAvatar($src, $dstFilename);
        $data = array('code' => DictionaryLogic::getErrorCode('Success'));
        if ($avatarPath == '') {
            $data['code'] = DictionaryLogic::getErrorCode('UploadFailed');
        }
        else {
            $user = User::findOne($id);
            $user->avatar = $avatarPath;
            $user->save();
            $data['url'] = $avatarPath;
        }

        $response = \Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $data;
    }

    /**
     * Confirms the User.
     * @param $id
     * @return \yii\web\Response
     */
    public function actionConfirm($id)
    {
        $this->findModel($id)->confirm(false);
        \Yii::$app->getSession()->setFlash('admin_user', \Yii::t('user', 'User has been confirmed'));

        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * Deletes recovery tokens.
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDeleteTokens($id)
    {
        $model = $this->findModel($id);
        $model->recovery_token = null;
        $model->recovery_sent_at = null;
        $model->save(false);
        \Yii::$app->getSession()->setFlash('admin_user', \Yii::t('user', 'All user tokens have been deleted'));

        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        \Yii::$app->getSession()->setFlash('admin_user', \Yii::t('user', 'User has been deleted'));

        return $this->redirect(['index']);
    }

    /**
     * Blocks the user.
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionBlock($id)
    {
        $user = $this->findModel($id);
        if ($user->getIsBlocked()) {
            $user->unblock();
            \Yii::$app->getSession()->setFlash('admin_user', \Yii::t('user', 'User has been unblocked'));
        } else {
            $user->block();
            \Yii::$app->getSession()->setFlash('admin_user', \Yii::t('user', 'User has been blocked'));
        }

        return $this->redirect(['index']);
    }

    public function actionAuth($id) {
        $model = $this->findModel($id);
        $model->scenario = 'auth';

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('auth', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer                    $id
     * @return \hipstercreative\user\models\User the loaded model
     * @throws NotFoundHttpException      if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var \hipstercreative\user\models\User $user */
        $user = $this->module->manager->findUserById($id);
        if ($id !== null && $user !== null) {
            return $user;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
