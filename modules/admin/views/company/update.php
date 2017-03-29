<?php

use yii\helpers\Html;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\invite-code */

$this->title = I18n::text('company') . ' ' . (string)$model->_id;
?>
<div class="company-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
