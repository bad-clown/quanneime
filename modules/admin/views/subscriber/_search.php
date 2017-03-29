<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\I18n;
use yii\helpers\Url;
use app\modules\admin\logic\DictionaryLogic;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SubscriberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscriber-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline']
    ]); ?>

    <?= $form->field($model, 'email') ?>

    <div class="form-group">
        <?= Html::submitButton(I18n::text('Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
