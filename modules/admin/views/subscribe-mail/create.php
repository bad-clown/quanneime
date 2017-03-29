<?php

use yii\helpers\Html;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SubscribeMail*/

$this->title = I18n::text('Create SubscribeMail');
?>
<div class="subscribe-mail-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
