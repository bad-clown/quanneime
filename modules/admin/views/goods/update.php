<?php

use yii\helpers\Html;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Goods */

$this->title = I18n::text('Goods') . ' ' . (string)$model->_id;
?>
<div class="goods-update">


    <?= $this->render('_form', [
        'model' => $model,
        'subgoods' => $subgoods,
        'categories' => $categories,
    ]) ?>

</div>
