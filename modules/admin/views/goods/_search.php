<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\components\I18n;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\GoodsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline']
    ]); ?>


    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'category')->dropDownList($categories,['prompt'=>I18n::text("全部分类")]) ?>

    <div class="form-group">
        <?= Html::submitButton(I18n::text('Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::button(I18n::text('Create'), ['class' => 'btn btn-info',"onclick"=>"window.location.href='".Url::to(['create'])."';"]) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
