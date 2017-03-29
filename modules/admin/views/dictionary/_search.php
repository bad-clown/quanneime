<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\I18n;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\DictionarySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dictionary-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline']
    ]); ?>


    <?= $form->field($model, 'idx') ?>

    <?= $form->field($model, 'key') ?>

    <div class="form-group">
        <?= Html::submitButton(I18n::text('Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::button(I18n::text('Create'), ['class' => 'btn btn-info',"onclick"=>"window.location.href='".Url::to(['create'])."';"]) ?>
        <a href="<?= Url::to(['/admin/dictionary/help']); ?>" target="_blank">帮助文档</a>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
