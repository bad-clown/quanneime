<?php

use yii\helpers\Html;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Dictionary */

$this->title = I18n::text('Dictionary') . ' ' . (string)$model->_id;
?>
<div class="dictionary-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
