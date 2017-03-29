<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\models\Dictionary;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\DictionarySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('Dictionary');
?>
<div class="dictionary-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idx',
            'key',
            'value',
            'description',

            ['class' => 'app\components\GridActionColumn'],
        ],
    ]); ?>

</div>
