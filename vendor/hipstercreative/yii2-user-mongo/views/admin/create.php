<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\logic\DictionaryLogic;

/**
 * @var yii\web\View $this
 * @var hipstercreative\user\models\User $model
 */

$this->title = Yii::t('user', 'Create a user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="alert alert-info hide">
            <?= Yii::t('user', 'Password and username will be sent to user by email') ?>.
            <?= Yii::t('user', 'If you want password to be generated automatically leave its field empty') ?>.
        </div>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => 25, 'autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'phoneno')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'type')->dropDownList(array_flip(DictionaryLogic::indexMap('UserType'))) ?>

        <?= $form->field($model, 'company')->textInput() ?>

        <?= $form->field($model, 'position')->textInput() ?>

        <div class="form-group">
        <input type="button" value="<?= I18n::text("Back") ?>" class="btn btn-default" onclick="window.location.href='<?= Url::to(['index']) ?>';" />
            <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
