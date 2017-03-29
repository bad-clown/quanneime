<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SubscribeMail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscribe-mail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, '_id')->hiddenInput()->label("") ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 10]) ?>

    <div class="form-group">
        <?= Html::a(I18n::text('Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton($model->isNewRecord ? I18n::text('Create') : I18n::text('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
