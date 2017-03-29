<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\models\Dictionary;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\SubscribeMailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('SubscribeMail');
?>
<div class="subscribe-mail-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'content',
            [
                'attribute' => 'status',
                'value' => function ($model, $index, $widget) {
                    return Dictionary::indexKeyValue('MailStatusText', $model->status);
                }
            ],
            [
                'attribute' => 'time',
                'value' => function ($model, $index, $widget) {
                    return date('Y-m-d H:i:s', $model->time);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete} {update} {send} {test}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        if ($model->status == Dictionary::indexKeyValue('MailStatus', 'New')) {
                            return Html::a('更新', ['update', 'id' => (string)$model["_id"]], [
                                'class' => 'btn btn-xs btn-info',
                                'data-method' => 'post',
                            ]);
                        }
                        return '';
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('删除', $url, [
                            'class' => 'btn btn-xs btn-danger',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('user', 'Are you sure to delete this user?'),
                            'title' => Yii::t('yii', 'Delete'),
                        ]);
                    },
                    'send' => function ($url, $model) {
                        if ($model->status == Dictionary::indexKeyValue('MailStatus', 'New')) {
                            return Html::a('发送', ['send', 'id' => (string)$model["_id"]], [
                                'class' => 'btn btn-xs btn-success',
                                'data-method' => 'post',
                                'data-confirm' => '确定发送吗?'
                                ]);
                        }
                    },
                    'test' => function($url, $model) {
                        return Html::a('发送测试', ['test-send', 'id' => (string)$model["_id"]], [
                            'class' => 'btn btn-xs btn-info',
                            'data-method' => 'post',
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>

</div>
