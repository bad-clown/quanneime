<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\models\Goods;
use app\modules\admin\models\Dictionary;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\GoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('Goods');
?>
<div class="goods-index">

    <?php  echo $this->render('_search', ['model' => $searchModel,'categories'=>$categories]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'class'=>'yii\grid\DataColumn',
                'attribute'=>"category",
                'label'=>$searchModel->getAttributeLabel("category"),
                'value' =>function($model) use ($categories){
                    if (isset($categories[(string)$model->category])) {
                        return $categories[(string)$model->category];
                    }
                    else {
                        return '已删除';
                    }
                }
            ],
            'price',
            'cashBack',
            [
                'class'=>'yii\grid\DataColumn',
                'attribute'=>"createTime",
                'label'=>$searchModel->getAttributeLabel("createTime"),
                'value' =>function($model){
                    return \Yii::$app->formatter->asDateTime($model->createTime);
                }
            ],
            [
                'class'=>'yii\grid\DataColumn',
                'attribute'=>"status",
                'label'=>$searchModel->getAttributeLabel("status"),
                'value' =>function($model){
                    return Dictionary::indexKeyValue("GoodsStatus",$model->status);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon">&#xe105;</span>', Url::to(["/web/item/index","id"=>(string)$model->_id]), [
                            'title' =>( 'View'),
                            'data-pjax' => '0',
                            'target'=>"_blank"
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>

</div>
