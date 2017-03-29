<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use app\components\I18n;
use app\modules\admin\models\Job;
use app\modules\admin\models\Dictionary;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\JobSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('Job');
?>
<div class="job-auth">

	<table class="table table-striped table-bordered detail-view">
		<tbody>
			<tr>
				<th width="20%">岗位名称</th>
				<td><?= $model->name;?></td>
			</tr>
			<tr>
				<th width="20%">招聘单位</th>
				<td><?= $model->company;?></td>
			</tr>
			<tr>
				<th width="20%">职位薪资</th>
				<td><?= $model->salary.Dictionary::indexKeyValue('SalaryType', $model->salaryType);?></td>
			</tr>
			<tr>
				<th width="20%">职位诱惑</th>
				<td><?= $model->attract;?></td>
			</tr>
			<tr>
				<th width="20%">办公地点</th>
				<td><?= $model->location;?></td>
			</tr>
			<tr>
				<th width="20%">岗位职责</th>
				<td><?php
							$str      =  $model->require;
							$order    = array( "\r\n" ,  "\n" ,  "\r" );
							$replace  =  '<br />' ;
							echo '<p id="J_require">'.str_replace ( $order ,  $replace ,  $str ).'</p>';
						?></td>
			</tr>
			<tr>
				<th width="20%">私人会面</th>
				<td><?php if($model->privateMeeting){echo '是';}else{echo '否';}; ?></td>
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
					<?= $form->field($model, 'status')->dropDownList(Dictionary::indexMap('JobStatusText')); ?>
					<div id="J_rejectmsg" style="display: none;">
						<?= $form->field($model, 'rejectMsg', ['template' => "<label class='control-label' for='job-rejectmsg'>不通过原因</label>\n{input}\n{hint}\n{error}"])->textarea() ?>
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
	$('#job-status').on('change', function() {
		var v = $(this).val();
		if(v == 1) {
			$('#J_rejectmsg').show();
		}
		else {
			$('#J_rejectmsg').hide();
		}
	})
	if($('#job-status').val() == 1) {
		$('#J_rejectmsg').show();
	}
})
</script>
<?php $this->endBlock();  ?>
</div>
