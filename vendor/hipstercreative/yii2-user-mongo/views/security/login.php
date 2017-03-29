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
	<div class="login-wrap">
		<div class="bg-picture" id="bg-picture-box">
			<!-- <img id="bg-pic" src="/images/denglu-bg-2016041.jpg" alt=""> -->
		</div>
		<div class="black-bg"></div>
		<div class="login-cont">
			<div class="logo"><a href="<?= $Path;?>/index.php" title="圈内觅"></a></div>
			<?php $form = ActiveForm::begin([
					'id' => 'login-form',
				]); ?>
			<div class="login-form">
				<?= $form->field($model, 'login', ['inputOptions' => ['placeholder' => '用户名 / 手机 / 邮箱', 'class' => 'user-text'], 'template' => "{input}\n{hint}\n{error}"]) ?>
				<?= $form->field($model, 'password', ['inputOptions' => ['placeholder' => '登录密码', 'class' => 'user-text'], 'template' => "{input}\n{hint}\n{error}"])->passwordInput() ?>
				<div class="form-group">
					<?= Html::submitButton(Yii::t('user', 'Sign in'), ['class' => 'submit-btn']) ?>
				</div>
			</div>
			<div class="tool clearfix">
				<div class="txt_l">
					<?= $form->field($model, 'rememberMe')->checkbox() ?>
					<!-- <label><input type="checkbox" name="" id="rememberMe" value="">&nbsp;&nbsp;记住我</label> -->
				</div>
				<div class="txt_r">
					<a href="<?= $Path.'/index.php?r=user/recovery/reset';?>" title="密码找回">密码找回</a> |
					<a href="<?= $Path.'/index.php?r=user/registration/register';?>" title="注册账号">注册账号</a>
				</div>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
		<div class="m-logbm">
			<div class="logbm2" id="systemLogin">
				<a href="javascript:;" class="itm opa by opentag" title=""><span>背景作品来自：</span><b class="opentag"></b></a>
				<a href="javascript:;" class="itm prv opentag">&nbsp;</a>
				<a href="javascript:;" class="itm nxt opentag">&nbsp;</a>
			</div>
		</div>
	</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript">$.include(['/css/common/reset.css']);</script>
<script src="<?= $Path;?>/js/jquery.backstretch.min.js"></script>
<script type="text/javascript">
$(function() {
	var oNum = 0;
	var oImg = [{'name' : '摄影师菜花','img' : '/images/denglu-bg-2016041.jpg','title' : '微信号：caiyupeng0127'},{'name' : '摄影师菜花','img' : '/images/denglu-beij-20140410-2.jpg','title' : '微信号：caiyupeng0127'}]
	$('.nxt').on('click', function(e) {
		e.preventDefault();
		oNum++;
		if(oNum>oImg.length-1){oNum = 0}
		swicthPic()
	})
	$('.prv').on('click', function(e) {
		e.preventDefault();
		oNum--;
		if(oNum<0){oNum = oImg.length-1}
		swicthPic()
	})

	function swicthPic() {
		$('#bg-picture-box').backstretch(oImg[oNum].img, {speed: 500});
		$('#systemLogin').find('.opa').attr('title', oImg[oNum].title);
		$('#systemLogin').find('.opa>b').html(oImg[oNum].name);
	}
	swicthPic()
})
</script>
<?php $this->endBlock();  ?>