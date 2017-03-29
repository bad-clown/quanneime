<?php

use yii\helpers\Html;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Order */

$this->title = I18n::text('Create Order');
?>
<div class="order-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
