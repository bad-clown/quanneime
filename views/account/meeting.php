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
	<div class="main">
		<div class="MeetingBox">
			<div class="Meeting-popup" id="J_Meeting">
				<div class="meeting-plan">
					<div class="plan-title"><i></i>安排私人会面</div>
					<div class="plan-tips">* 对于高层次的岗位我们将根据双方需求协助安排私人会面，当然您可以继续选择发送简历</div>
					<div class="plan-text-box">
						<?php $form = ActiveForm::begin([
							'id' => 'meeting-form'
						]); ?>

						<div class="text-tit clearfix"><i></i><span>基本信息</span><i></i></div>
						<div class="text-info-box">
							<div class="text-panel">
								<?= $form->field($model, 'title', ['inputOptions' => ['class' => 'info-text info-text-name', 'placeholder' => '您的称谓'], 'template' => "<label class='control-label'>您的称谓</label>\n{input}\n{hint}\n{error}"]) ?>
								<?= $form->field($model, 'sex', ['inputOptions' => ['class' => 'info-gender'], 'template' => "{input}\n{hint}\n{error}"])->dropDownList(Dictionary::indexMap('SexType')) ?>
								<!-- <select name="" class="info-gender">
									<option value="">先生</option>
									<option value="">女士</option>
								</select> -->
							</div>
							<div class="text-panel">
								<?= $form->field($model, 'company', ['inputOptions' => ['class' => 'info-text', 'placeholder' => '目前单位'], 'template' => "<label class='control-label'>目前单位</label>\n{input}\n{hint}\n{error}"]) ?>
							</div>
							<div class="text-panel">
								<?= $form->field($model, 'position', ['inputOptions' => ['class' => 'info-text', 'placeholder' => '目前职位'], 'template' => "<label class='control-label'>目前职位</label>\n{input}\n{hint}\n{error}"]) ?>
								<!-- <span>目前职位</span>
								<input class="info-text" type=text value="" placeholder="目前职位"> -->
							</div>
						</div>
						<div class="text-tit clearfix"><i></i><span>联系方式</span><i></i></div>
						<div class="text-info-box">
							<div class="text-panel">
								<?= $form->field($model, 'phoneno', ['inputOptions' => ['class' => 'info-text', 'placeholder' => '手机号码'], 'template' => "<label class='control-label'>联系电话</label>\n{input}\n{hint}\n{error}"]) ?>
								<!-- <span>手机号码</span>
								<input class="info-text" type=text value="" placeholder="联系电话"> -->
							</div>
							<div class="text-panel">
								<?= $form->field($model, 'verifyCode', ['inputOptions' => ['class' => 'info-text info-text-phone', 'placeholder' => '输入验证'], 'template' => "<label class='control-label'>输入验证</label>\n{input}\n<a href='javascript:void(0);'' class='getvcode' id='getvcode'>发送验证</a>\n{hint}\n{error}"]) ?>
							</div>
						</div>
						<div class="plan-submit-box">
							<?= Html::submitButton(Yii::t('user', '确定'), ['class' => 'pbtn1']) ?>
						</div>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="meeting-overlay"></div>
	</div>

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/layer.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript">
$(function() {
	$('html,body').css('overflow', 'hidden');
	$('.header').css({'position': 'fixed','top':0,'left':0})
	$('.footer').css({'position': 'fixed','bottom':0,'left':0,'width': '100%'})

	$('#J_Meeting').on('click', function(e) {
		if(e.target.id == 'J_Meeting') {
			window.location.href = '<?= $Path;?>/index.php?r=job/detail&id=<?= $jobId;?>';
		}
	})
	// 获取手机验证码
	$("#getvcode").click(function(){
		var $this = $(this),$n = $("#privatemeeting-phoneno"),$fg = $n.parents(".form-group:eq(0)");
		$n.blur();
		setTimeout(function(){
			if($fg.is(".has-error") || $this.is(".waitTime")){return}
			$('#captchaImgTxt').val('')
			$('#J_resetcaptcha').attr('src', 'http://www.quannei.me/index.php?r=site/captcha')
			$('#JCaptchaImg').show();
			$('#J_overlayAll_').show();
		}, 220)

	});

	$('#J_resetcaptcha').on('click', function() {
		$(this).attr('src', 'http://www.quannei.me/index.php?r=site/captcha')
	})

	$('#J_GetVcode').on('click', function() {
		var $this = $('#getvcode'),$n = $("#privatemeeting-phoneno"),$fg = $n.parents(".form-group:eq(0)");
		setTimeout(function(){
			$.get("<?= Url::to(["/common/index/send-verify-code","phoneno"=>""]) ?>"+$n.val()+'&captcha='+$('#captchaImgTxt').val()).then(function(data){
				if(data.status != 0) {
					layer.msg('验证码错误，请重新输入', {time: 2000});
					$('#J_resetcaptcha').attr('src', 'http://www.quannei.me/index.php?r=site/captcha');
				}
				else {
					$this.addClass("waitTime").attr("data-t",$this.html()).html("<?= str_replace("{}","60",\Yii::t("user","{} s 重新发送")) ?>");
					var interval = setInterval(function(){
						var t = parseInt($this.html(),10);
						t--;
						$this.html(t+"s 重新发送");
						if(t==0){
							$this.removeClass("waitTime").html($this.attr("data-t"));
							clearInterval(interval);
						}
					},1000)
					$('#JCaptchaImg').hide();
					$('#J_overlayAll_').hide();
				}
			});
		},220);
	})
})
</script>
<?php $this->endBlock();  ?>
