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
		<div class="Usercenter_Box">
			<div class="UserCenter-title">管理中心</div>
			<div class="Usercenter-content clearfix">
				<div class="Usercenter-menu">
					<?= $this->render('_cnav') ?>
				</div>
				<div class="Usercenter-notify Usercenter-right">
					<div class="notify-setting">
						<?php $form = ActiveForm::begin([
							'id' => 'notify-form',
						]); ?>
						<h3 class="title">短信提醒</h3>
						<?= $form->field($model, 'notifyOnNewResume', ['template' => "<span class='control-span'>收到新的简历时，请短信通知我</span>\n{input}\n<span class='control-tips'>（ 可随时开启或关闭 ）</span>\n{hint}\n{error}"])->checkbox() ?>
						<?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'submit-btn']) ?>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 内容 end -->

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/layer.js"></script>
<?php if($success) {?>
<script>
$(function() {
	layer.msg('保存成功！', {
		time: 2000
	});
})
</script>
<?php } ?>
<script>
$(function() {
	$('#J_Notify').addClass('menu-cur')
})
</script>
<?php $this->endBlock();  ?>
