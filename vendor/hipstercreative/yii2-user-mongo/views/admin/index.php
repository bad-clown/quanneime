<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\modules\admin\logic\DictionaryLogic;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var hipstercreative\user\models\UserSearch $searchModel
 */

$this->title = Yii::t('user', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?> <?= "";/*Html::a(Yii::t('user', 'Create a user account'), ['create'], ['class' => 'btn btn-success'])*/ ?></h1>

<?php if (Yii::$app->getSession()->hasFlash('admin_user')): ?>
    <div class="alert alert-success">
        <p><?= Yii::$app->getSession()->getFlash('admin_user') ?></p>
    </div>
<?php endif; ?>

<?php  echo $this->render('_search', ['model' => $searchModel]); ?>

<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel'  => $searchModel,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'username',
        'phoneno',
        'email:email',
        'company',
        'position',
        [
            'attribute' => 'type',
            'value' => function ($model, $index, $widget) {
                if ($model->type == DictionaryLogic::indexKeyValue('UserType', 'CompanyUser')) {
                    return '公司用户';
                }
                else {
                    return '普通用户';
                }
            }
        ],
        [
            'attribute' => 'authStatus',
            'value' => function ($model, $index, $widget) {
                if ($model->type != DictionaryLogic::indexKeyValue('UserType', 'CompanyUser')) {
                    return "";
                }
                $v = DictionaryLogic::indexKeyValue('AuthStatusText', $model->authStatus);
                if ($model->authStatus == DictionaryLogic::indexKeyValue('AuthStatus', 'Reject')) {
                    $v .= '(原因：' . $model->authFailMsg . ')';
                }
                return $v;
            }
        ],
        /*[
            'attribute' => 'registered_from',
            'value' => function ($model, $index, $widget) {
                    return $model->registered_from == null ? '<span class="not-set">' . Yii::t('user', '(not set)') . '</span>' : long2ip($model->registered_from);
                },
            'format' => 'html',
        ],*/
        [
            'attribute' => 'created_at',
            'value' => function ($model, $index, $widget) {
                return date('Y-m-d H:i', $model->created_at);
                //return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
            }
        ],
        /*[
            'header' => Yii::t('user', 'Block status'),
            'value' => function ($model, $index, $widget) {
                if ($model->blocked_at!=null) {
                    return Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => (string)$model["_id"]], [
                        //'class' => 'btn btn-xs btn-success btn-block',
                        'class' => 'btn btn-xs btn-success',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure to unblock this user?')
                    ]);
                } else {
                    return Html::a(Yii::t('user', 'Block'), ['block', 'id' => (string)$model["_id"]], [
                        //'class' => 'btn btn-xs btn-danger btn-block',
                        'class' => 'btn btn-xs btn-danger',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure to block this user?')
                    ]);
                }
            },
            'format' => 'raw',
        ],*/
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{block} {delete} {view} {auth}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('更新头像', ['view-avatar', 'id' => (string)$model["_id"]], [
                        //'class' => 'btn btn-xs btn-success btn-block',
                        'class' => 'btn btn-xs btn-info',
                        //'data-method' => 'post',
                        //'data-confirm' => Yii::t('user', 'Are you sure to unblock this user?')
                        'target' => 'blank',
                    ]);
                },
                'block' => function ($url, $model) {
                    if ($model->blocked_at!=null) {
                        return Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => (string)$model["_id"]], [
                            //'class' => 'btn btn-xs btn-success btn-block',
                            'class' => 'btn btn-xs btn-success',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('user', 'Are you sure to unblock this user?')
                        ]);
                    } else {
                        return Html::a(Yii::t('user', 'Block'), ['block', 'id' => (string)$model["_id"]], [
                            //'class' => 'btn btn-xs btn-danger btn-block',
                            'class' => 'btn btn-xs btn-danger',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('user', 'Are you sure to block this user?')
                        ]);
                    }
                },
                'auth' => function ($url, $model) {
                    if ($model->type == DictionaryLogic::indexKeyValue('UserType', 'NormalUser')) {
                        return '';
                    }
                    if ($model->authStatus == DictionaryLogic::indexKeyValue('AuthStatus', 'Pending')) {
                        return Html::a('认证', $url, [
                            'class' => 'btn btn-xs btn-info',
                            'title' => Yii::t('yii', 'View'),
                            ]);
                    }
                    if ($model->authStatus == DictionaryLogic::indexKeyValue('AuthStatus', 'NoAuth')) {
                        return Html::a('主动认证', $url, [
                            'class' => 'btn btn-xs btn-info',
                            'title' => Yii::t('yii', 'View'),
                            ]);
                    }
                },
                /*'update' => function ($url, $model) {
                    return Html::a('更新', $url, [
                        'class' => 'btn btn-xs btn-info',
                        'title' => Yii::t('yii', 'Update'),
                    ]);
                },*/
                'delete' => function ($url, $model) {
                    return Html::a('删除', $url, [
                        'class' => 'btn btn-xs btn-danger',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure to delete this user?'),
                        'title' => Yii::t('yii', 'Delete'),
                    ]);
                },
            ]
        ],
    ],
]); ?>
