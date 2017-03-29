<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Job;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\company */

?>
<div class="company-view">



    <?php /*DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'nickname',
            'phoneno',
            'email',
            'company',
            'position',
            'card',
        ],
    ])*/ ?>

	<table class="table table-striped table-bordered detail-view">
		<tbody>
			<tr>
				<th width="20%">用户名</th>
				<td><?= $model->username;?></td>
			</tr>
			<tr>
				<th width="20%">昵称</th>
				<td><?= $model->nickname;?></td>
			</tr>
			<tr>
				<th width="20%">手机号码</th>
				<td><?= $model->phoneno;?></td>
			</tr>
			<tr>
				<th width="20%">邮箱地址</th>
				<td><?= $model->email;?></td>
			</tr>
			<tr>
				<th width="20%">公司</th>
				<td><?= $model->company;?></td>
			</tr>
			<tr>
				<th width="20%">职位</th>
				<td><?= $model->position;?></td>
			</tr>
			<tr>
				<th width="20%">片名</th>
				<td><?php if($model->card){ ?><img src="<?= DictionaryLogic::indexKeyValue('App', 'Host', false).$model->card; ?>" width="600" alt=""><?php };?></td>
			</tr>
		</tbody>
	</table>

	<?php $form = ActiveForm::begin([
		'id' => 'auth-form',
	]); ?>
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th width="20%">审核</th>
				<td>
					<?= $form->field($model, 'authStatus')->dropDownList(Dictionary::indexMap('AuthStatusText')); ?>
					<div id="J_authStatus" style="display: none;">
						<?= $form->field($model, 'authFailMsg', ['template' => "<label class='control-label' for='job-rejectmsg'>认证失败原因</label>\n{input}\n{hint}\n{error}"])->textarea() ?>
					</div>
				</td>
			</tr>
			<tr>
				<th width="20%"></th>
				<td><?= Html::submitButton(Yii::t('user', '提交'), ['class' => 'btn btn-primary']) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= Html::a(I18n::text('Back'), ['index'], ['class' => 'btn btn-default']) ?></td>
			</tr>
		</tbody>
	</table>
	<?php ActiveForm::end(); ?>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function() {
	$('#user-authstatus').on('change', function() {
		var v = $(this).val();
		if(v == 3) {
			$('#J_authStatus').show();
		}
		else {
			$('#J_authStatus').hide();
		}
	})
	if($('#user-authstatus').val() == 3) {
		$('#J_authStatus').show();
	}
})
</script>
<?php $this->endBlock();  ?>
