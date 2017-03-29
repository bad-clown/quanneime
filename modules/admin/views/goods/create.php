<?php

use yii\helpers\Html;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Goods */

$this->title = I18n::text('Create Goods');
?>
<div class="goods-create">


    <?= $this->render('_form', [
        'model' => $model,
        'subgoods' => $subgoods,
        'categories' => $categories,
    ]) ?>

</div>
