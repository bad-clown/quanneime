<?php

use yii\helpers\Html;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SubscribeMail */

$this->title = I18n::text('SubscribeMail') . ' ' . (string)$model->_id;
?>
<div class="subscribe-mail-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
