<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\models\Order;
use app\modules\admin\logic\OrderLogic;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('Order');
?>
<div class="order-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'orderId',
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'userId',
                'label' => $searchModel->getAttributeLabel('userId'),
                'value' => function($model) use($users) {
                    return isset($users[(string)$model->userId])?$users[(string)$model->userId]:"";
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'time',
                'label' => $searchModel->getAttributeLabel('time'),
                'value' => function($model) {
                    //return date('Y-m-d H:i:s', $model['time']);;
                    return \Yii::$app->formatter->asDateTime($model['time']);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'status',
                'label' => $searchModel->getAttributeLabel('status'),
                'value' => function($model) {
                    return OrderLogic::getOrderStatusAndTextMap()[$model['status']];
                },
            ],
            'totalMoney',
            'cashBack',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>"{view}",
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
                            'class' => 'btn btn-xs btn-info',
                            'title' => Yii::t('yii', 'View'),
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>
