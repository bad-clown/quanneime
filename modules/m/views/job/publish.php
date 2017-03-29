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

<div data-role="page" data-quicklinks="true" id="pageMain">
	<div class="ui-panel-wrapper">
		<div data-role="header" data-theme="b">
			<h1><img src="/images/mobile/m-logo.png" width="100%" alt=""></h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageSearch" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-search ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">搜索</a>
		</div><!-- /header -->
		<div data-role="content">
			<div class="release-title">您要招聘什么人才？</div>
				<?php $form = ActiveForm::begin([
					'id' => 'job-form'
				]); ?>
				<div class="release-input">
					<div class="tit">岗位信息</div>
					<ul>
						<li>
							<?= $form->field($model, 'name', ['inputOptions' => ['placeholder' => '岗位名称']]) ?>
						</li>
						<li>
							<?= $form->field($model, 'company', ['inputOptions' => ['placeholder' => '招聘单位']]) ?>
						</li>
						<li>
							<?php $comType = DictionaryLogic::indexMap('CompanyType');ksort($comType) ?>
							<?= $form->field($model, 'comType')->dropDownList($comType, ['prompt'=>I18n::text("选择单位类型")]) ?>
						</li>
						<li>
							<?php $positionType = DictionaryLogic::indexMap('PositionType');ksort($positionType) ?>
							<?= $form->field($model, 'positionType')->dropDownList($positionType, ['prompt'=>I18n::text("选择岗位类型")]) ?>
						</li>
						<li>
							<div class="jobSalary-box">
								<?= $form->field($model, 'salary', ['inputOptions' => ['placeholder' => '输入薪资可填区间']]) ?>
								<?php $SalaryType = DictionaryLogic::indexMap('SalaryType');ksort($SalaryType) ?>
								<?= $form->field($model, 'salaryType', ['inputOptions' => ['data-mini' => 'true', 'data-inline' => 'true']])->dropDownList($SalaryType) ?>
							</div>
						</li>
					</ul>
				</div>
				<div class="release-input">
					<div class="tit">更多信息</div>
					<ul>
						<li>
							<?= $form->field($model, 'province', ['template' => "<label class='control-label' for='job-province'>选择省份</label>\n{input}\n{hint}\n{error}"])->dropDownList(['prompt'=>I18n::text("请选择工作省份")]) ?>
						</li>
						<li>
							<?= $form->field($model, 'city', ['template' => "<label class='control-label' for='job-city'>选择城市</label>\n{input}\n{hint}\n{error}"])->dropDownList(['prompt'=>I18n::text("请选择工作的城市")]) ?>
						</li>
						<li>
							<?= $form->field($model, 'location', ['inputOptions' => ['placeholder' => '办公地点']]) ?>
						</li>
						<li>
							<?= $form->field($model, 'attract', ['inputOptions' => ['placeholder' => '一句话描述职位诱惑']]) ?>
						</li>
					</ul>
				</div>
				<div class="release-input">
					<div class="tit">职位详情</div>
					<ul>
						<li>
							<?= $form->field($model, 'require', ['inputOptions' => ['placeholder' => '岗位职责和任职要求']])->textarea() ?>
						</li>
						<!-- <li>
							<?= $form->field($model, 'privateMeeting')->checkbox() ?>
						</li> -->
						<li>
							<?= Html::submitButton(Yii::t('user', '立即发布'), ['class' => 'submit-btn']) ?>
						</li>
					</ul>
				</div>
				<?php ActiveForm::end(); ?>
		</div>
	</div>
</div><!-- /page -->

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
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/geo.js"></script>
<script type="text/javascript">
$(function() {
	$( "body>[data-role='panel']" ).panel();
	setup()
})
</script>
<?php $this->endBlock();  ?>

