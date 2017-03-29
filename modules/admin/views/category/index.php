<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('Category');
?>
<div class="category-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',

            ['class' => 'app\components\GridActionColumn'],
        ],
    ]); ?>

</div>
