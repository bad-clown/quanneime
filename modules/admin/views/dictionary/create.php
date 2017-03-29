<?php

use yii\helpers\Html;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Dictionary */

$this->title = I18n::text('Create Dictionary');
?>
<div class="dictionary-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
