<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\admin\models\Subscriber;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\SubscriberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('Subscriber');
?>
<div class="subscriber-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'email',
            [
                'attribute' => 'time',
                'value' => function ($model, $index, $widget) {
                    return date('Y-m-d H:i', $model->time);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
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
