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

<div class="invite-code-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline']
    ]); ?>


    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'status')->dropDownList(Dictionary::indexMap('InviteCodeStatus'),["prompt"=>I18n::text("All")]) ?>

    <div class="form-group">
        <?= Html::submitButton(I18n::text('Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::button(I18n::text('Create'), ['class' => 'btn btn-info',"data-toggle"=>"modal","data-target"=>"#dlgcreate"]) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
