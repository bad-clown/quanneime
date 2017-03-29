<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\I18n;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Dictionary */

?>
<div class="dictionary-view">


    <p>
        <?= Html::a(I18n::text('Back'), ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a(I18n::text('Update'), ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(I18n::text('Delete'), ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => I18n::text('confirmdelete'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idx',
            'key',
            'value',
            'description',
        ],
    ]) ?>

</div>
