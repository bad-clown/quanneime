<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\models\BalanceHistory;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('Order');
?>
<div>
<button type="button" class="btn btn-default" onclick="window.location.href='<?= Url::to(["/admin/user/index"]) ?>';">返回</button>

 </div>
<div class="balance-history-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'time',
                'label' => $searchModel->getAttributeLabel('time'),
                'value' => function($model) {
                    return \Yii::$app->formatter->asDateTime($model['time']);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'balance',
                'label' => $searchModel->getAttributeLabel('balance'),
                'value' => function($model) {
                    return "￥".$model["balance"];
                },
            ],
            'comment',

        ],
    ]); ?>

</div>
