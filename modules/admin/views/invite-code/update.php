<?php

use yii\helpers\Html;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\invite-code */

$this->title = I18n::text('invite-code') . ' ' . (string)$model->_id;
?>
<div class="invite-code-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
