<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\I18n;
use yii\helpers\Url;
use app\modules\admin\logic\OrderLogic;
use app\components\DateRangeWidget;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class'=>'form-inline']
    ]); ?>


    <?= $form->field($model, 'orderId') ?>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'status')->dropDownList(OrderLogic::getOrderStatusAndTextMap(), ['prompt'=>I18n::text("All")]) ?>
    <?= DateRangeWidget::widget(['attribute'=>'time','model'=>$model]) ?>

    <div class="form-group">
        <?= Html::submitButton(I18n::text('Search'), ['class' => 'btn btn-primary']) ?>
        <div class="help-block"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
