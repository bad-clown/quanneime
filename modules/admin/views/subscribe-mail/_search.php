<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\logic\DictionaryLogic;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\SubscribeMailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscribe-mail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline']
    ]); ?>


    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'status')->dropDownList(DictionaryLogic::indexMap('MailStatusText'),['prompt'=>I18n::text("全部状态")]) ?>

    <div class="form-group">
        <?= Html::submitButton(I18n::text('Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::button(I18n::text('Create'), ['class' => 'btn btn-info',"onclick"=>"window.location.href='".Url::to(['create'])."';"]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
