<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\I18n;
use yii\helpers\Url;
use app\modules\admin\models\Dictionary;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\invite-codeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivered-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline']
    ]); ?>


    <?= $form->field($model, 'deliver') ?>
    <?= $form->field($model, 'jobname') ?>

    <div class="form-group">
        <?= Html::submitButton(I18n::text('Search'), ['class' => 'btn btn-primary']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
