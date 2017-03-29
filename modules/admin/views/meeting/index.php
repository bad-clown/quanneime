<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\admin\models\PrivateMeeting;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\PrivateMeetingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('PrivateMeeting');
?>
<div class="meeting-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'title',
                'value' => function ($model, $index, $widget) {
                    return $model->title . DictionaryLogic::indexKeyValue('SexType', $model->sex);
                }
            ],
            'phoneno',
            'company',
            'position',
            [
                'header' => '职位发布者电话',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    if ($model->job == null) {
                        return '';
                    }
                    else {
                        return $model->job->publisherPhone;
                    }
                }
            ],
            [
                'header' => '投递职位',
                'format' => 'raw',
                'value' => function ($model, $index, $widget) {
                    if ($model->job == null) {
                        return '职位已删除';
                    }
                    else {
                        $v = $model->job->company . ' 的 ' . $model->job->name . ' ';
                        $v .= Html::a('查看', ['/job/detail', 'id' => (string)$model->job->_id], [
                            //'class' => 'btn btn-xs btn-success btn-block',
                            'class' => 'btn btn-xs btn-success',
                            'target' => 'blank',
                            ]);
                        return $v;
                    }
                }
            ],

            [
                'attribute' => 'status',
                'value' => function ($model, $index, $widget) {
                    return DictionaryLogic::indexKeyValue('MeetingStatusText', $model->status);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete} {process}',
                'buttons' => [
                    'process' => function ($url, $model) {
                        if ($model->status == DictionaryLogic::indexKeyValue('MeetingStatus', 'New')) {
                            return Html::a('标记处理', $url, [
                                'class' => 'btn btn-xs btn-success',
                                ]);
                        }
                    },
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

</div>
