<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
//$Path = DictionaryLogic::indexKeyValue('App', 'Host', false);
$Path = \Yii::$app->request->hostInfo;
/*if(!ereg('/https/', $Path)) {
	$Path = preg_replace('/http/', 'https', $Path);
}*/
?>
	<!-- 内容 start -->
	<div class="main">
		<div class="release-recruit">
			<div class="release-title">发布岗位</div>
			<div class="release-text-box clearfix">
					<?php $form = ActiveForm::begin([
						'id' => 'job-form'
					]); ?>
					<div class="line"></div>
					<ul>
						<li>
							<i class="num num1 act-num">1</i>
							<strong class="jobTitle">岗位信息</strong>
						</li>
						<li>
							<?= $form->field($model, 'name', ['inputOptions' => ['placeholder' => '岗位名称', 'data-state' => '0', 'class' => 'job-text j_step_1']]) ?>
							<?= $form->field($model, 'privateMeeting')->checkbox() ?>
						</li>
						<li>
							<?= $form->field($model, 'company', ['inputOptions' => ['placeholder' => '招聘单位', 'data-state' => '0', 'class' => 'job-text j_step_1']]) ?>
						</li>
						<li>
							<?php $comType = DictionaryLogic::indexMap('CompanyType');ksort($comType) ?>
							<?= $form->field($model, 'comType', ['inputOptions' => ['data-state' => '0', 'class' => 'job-select j_step_1']])->dropDownList($comType, ['prompt'=>I18n::text("选择单位类型")]) ?>
						</li>
						<li>
							<?php $positionType = DictionaryLogic::indexMap('PositionType');ksort($positionType) ?>
							<?= $form->field($model, 'positionType', ['inputOptions' => ['data-state' => '0', 'class' => 'job-select j_step_1']])->dropDownList($positionType, ['prompt'=>I18n::text("选择岗位类型")]) ?>
						</li>
						<li>
							<div class="jobSalary-box">
								<?= $form->field($model, 'salary', ['inputOptions' => ['placeholder' => '输入月薪', 'data-state' => '0', 'class' => 'j_step_1']]) ?>
								<?php $SalaryType = DictionaryLogic::indexMap('SalaryType');ksort($SalaryType) ?>
								<?= $form->field($model, 'salaryType', ['template' => "{input}\n{hint}\n{error}"])->dropDownList($SalaryType) ?>
								<!-- <span class="j_g_salary" style="display:none;" data-u="万元 / 年">元 / 月</span> -->
								<!-- <span class="salary-msg">可以填写区间如“2000-8000”<br>点击可以切换月薪和年薪</span> -->
							</div>
						</li>
						<li>
							<?= $form->field($model, 'province', ['inputOptions' => ['data-state' => '0', 'class' => 'job-select j_step_1'], 'template' => "<label class='control-label' for='job-province'>选择省份</label>\n{input}\n{hint}\n{error}"])->dropDownList(['prompt'=>I18n::text("请选择工作省份")]) ?>
						</li>
						<li>
							<?= $form->field($model, 'city', ['inputOptions' => ['data-state' => '0', 'class' => 'job-select j_step_1'], 'template' => "<label class='control-label' for='job-city'>选择城市</label>\n{input}\n{hint}\n{error}"])->dropDownList(['prompt'=>I18n::text("请选择工作的城市")]) ?>
						</li>
						<li>
							<?= $form->field($model, 'location', ['inputOptions' => ['placeholder' => '办公地点', 'data-state' => '0', 'class' => 'job-text w340 j_step_1']]) ?>
						</li>
						<li>
							<?= $form->field($model, 'attract', ['inputOptions' => ['placeholder' => '一句话描述职位诱惑如16薪、高提成、前景好', 'data-state' => '0', 'class' => 'job-text w340 j_step_1']]) ?>
						</li>
						<li>
							<i class="num num2">2</i>
							<div class="jobAsk">
								<?= $form->field($model, 'require', ['inputOptions' => ['placeholder' => '岗位职责和任职要求', 'data-state' => '0', 'data-placeholder' => '输入年薪', 'class' => 'job-textarea j_step_2'], 'template' => "<label class='control-label' for='job-require'>职位详情</label>\n{input}\n{hint}\n{error}"])->textarea() ?>
								<!-- \n<span class=\"jobAsk-msg\">岗位职责和任职要求</span> -->
							</div>
						</li>
					</ul>
					<div class="job-submit-box">
						<i class="num num3">3</i>
						<?= Html::submitButton(Yii::t('user', '立即发布'), ['class' => 'submit-btn', 'id' => 'J_p_submit']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
	<!-- 内容 end -->
<!-- 高级岗位提示 start -->
<div class="leader-work-pop popup">
	<div class="leader-work-tit">
		<i></i><strong>私人会面说明</strong>
	</div>
	<div class="leader-work-msg">
		<p>勾选私人会面功能：如您在招聘高级岗位，我们将推荐与该岗位相匹配的资深人才直接与您线下会面。</p>
	</div>
	<div class="leader-work-btn">
		<a href="javascript:void(0)" class="lbtn1">知道了</a>
	</div>
	<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
</div>
<!-- 高级岗位提示 end -->

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/geo.js"></script>
<script>
$.includePath=$_Path;
$.include(['/css/common/reset.css']);
</script>
<script type="text/javascript">
$(function() {
	$("#J_Release_").addClass('active');
	setup()
	preselect('<?= $model->province;?>');
	$("#job-city").val('<?= $model->city;?>').triggerHandler("change");
	$("#job-salarytype").val('<?= $model->salaryType;?>').triggerHandler("change");

	/*薪资切换*/
	$('#job-salarytype').on('change', function() {
		var _v = ['输入月薪','输入年薪','输入日薪']
		$('#job-salary').attr('placeholder', _v[($(this).val()-1)])
	})
	/*私人会面提示*/
	$('#job-privatemeeting').on('change', function() {
		console.log($(this).is(':checked'))
		if($(this).is(':checked')) {
			$('.leader-work-pop').show();
			$('._overlayAll_').show();
		}
	})
	/*私人会面关闭*/
	$('.lbtn1').on('click', function() {
		$('.J_close_pop').click()
	})

	/*判断不为空*/
	$('.j_step_1,.j_step_2').on('change', function(e) {
		if($.trim($(this).val())) {
			$(this).data('state', 1)
		}
		else {
			$(this).data('state', 0)
		}
	})
	/*输入框焦点边框改变*/
	$('.j_step_1').on('focus', function() {
		$(this).parents('.form-group:eq(0)').removeClass('blur-input');
		$(this).parents('.form-group:eq(0)').addClass('focus-input');
		$(this).nextAll('.help-block').hide();
		$('.num1').addClass('act-num')
		$('.num2').removeClass('act-num')
		$('.num3').removeClass('act-icon')
	})
	$('.j_step_2').on('focus', function() {
		$(this).parents('.form-group:eq(0)').removeClass('blur-input');
		$(this).parents('.form-group:eq(0)').addClass('focus-input');
		$(this).nextAll('.help-block').hide();
		$('.num1').removeClass('act-num')
		$('.num2').addClass('act-num')
		$('.num3').removeClass('act-icon')
	})
	$('.j_step_1').on('blur', function() {
		$(this).parents('.form-group:eq(0)').removeClass('focus-input');
		$(this).nextAll('.help-block').show();
		VerifySuccess()
	})
	$('.j_step_2').on('blur', function() {
		$(this).parents('.form-group:eq(0)').removeClass('focus-input');
		$(this).nextAll('.help-block').show();
		VerifySuccess()
	})
	/*是否已输入完成*/
	function VerifySuccess() {
		var _oInput = $('.j_step_1');
		var _oTexta = $('.j_step_2');
		var _iLen = _oInput.length;
		var _judeg = true;

		for(var i=0;i<_iLen;i++) {
			if(!_oInput.eq(i).data('state')) {
				_judeg = false;
				return;
			}
		}

		if(_oTexta.data('state') && _judeg) {
			$('.num1').removeClass('act-num')
			$('.num2').removeClass('act-num')
			$('.num3').addClass('act-icon')
			$('#J_p_submit').removeClass('submit-btn-disabled');
		}
	}
})
</script>
<?php $this->endBlock();  ?>
