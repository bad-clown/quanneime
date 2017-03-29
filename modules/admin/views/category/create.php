<?php

use yii\helpers\Html;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Category */

$this->title = I18n::text('Create Category');
?>
<div class="category-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
