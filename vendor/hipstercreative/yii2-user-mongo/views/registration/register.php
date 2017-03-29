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

	<div class="topBar clearfix">
		<div class="topBar-left">创建您的帐户!</div>
		<div class="topBar-right">
			<a href="<?= $Path;?>/index.php?r=user/security/login" class="loginBtn">登录</a>
		</div>
	</div>
	<div class="main">
		<div class="welcome-text">
			<div class="welcome-tit">欢迎来<b>圈内觅</b></div>
			<div class="welcome-msg"><span>发现地产圈职业机会 &nbsp;&nbsp;Quannei.me</span></div>
		</div>
		<div class="register-box">
			<div class="subNav clearfix">
				<div class="Navbtn Nav1 Navcur" data-type="0">我要求职</div>
				<div class="Navbtn Nav2" data-type="1">我要招人</div>
			</div>
			<!-- 注册填写 -->
			<div class="registerContent" id="J_RegisterCont_">
				<div class="panel-title">求职用户注册</div>
				<div class="panel-textBox">
					<?php $form = ActiveForm::begin([
						'id' => 'registration-form',
					]); ?>
					<ul>
						<li>
							<?= $form->field($model, 'type', ['inputOptions' => ['value' => '0']])->hiddenInput() ?>
							<?= $form->field($model, 'username', ['inputOptions' => ['placeholder' => '昵称', 'class' => 'info-text'], 'template' => "<label class='control-label'>昵称</label>\n{input}\n{hint}\n{error}"]) ?>
						</li>
						<li style="display:none;">
							<?= $form->field($model, 'company', ['inputOptions' => ['placeholder' => '您的单位名称', 'class' => 'info-text']]) ?>
						</li>
						<li style="display:none;">
							<?= $form->field($model, 'position', ['inputOptions' => ['placeholder' => '您的职位', 'class' => 'info-text']]) ?>
						</li>
						<li>
							<?= $form->field($model, 'password', ['inputOptions' => ['placeholder' => '账号密码', 'class' => 'info-text']])->passwordInput() ?>
						</li>
						<li>
							<?= $form->field($model, 'phoneno', ['inputOptions' => ['placeholder' => '您的手机号码', 'class' => 'info-text']]) ?>
						</li>
						<li>
							<?= $form->field($model, 'verifyCode', ['inputOptions' => ['placeholder' => '输入验证码', 'class' => 'info-text info-text2'], 'template' => "{label}\n{input}\n<a href=\"javascript:void(0);\" class=\"getCode-btn\" id='getvcode'>获取验证码</a>\n{hint}\n{error}"]) ?>
						</li>
						<li>
							<!--<?= $form->field($model,'captcha')->widget(yii\captcha\Captcha::className(), ['captchaAction'=>'/common/index/captcha', 'imageOptions'=>['alt'=>'点击换图','title'=>'点击换图', 'style'=>'cursor:pointer']]);?>-->
						</li>
					</ul>
					<?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'submit-btn']) ?>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
			<a href="<? $Path;?>/index.php?r=site/help" title="无法注册？" class="help-link">无法注册？</a>
		</div>
	</div>
	<div class="footer">
		<div class="comWidth clearfix">
			<!-- <div class="quannei-win">圈内觅 Quannei.me</div> -->
			<div class="ewm"><img src="/images/quanneimiewm.gif" alt=""></div>
			<div class="copyright">
				<p>圈内觅 www.quannei.me  |  发现地产圈职业机会</p>
				<p><a href="<?= $Path.'/index.php?r=site/contact'?>">通过微信或者邮箱联系到我们</a></p>
				<p class="copy">浙ICP备 16005246号  |  浙公网安备 33010802005574号</p>
			</div>

			<div class="mail-box">
				<input class="mail-text" id="mail-text" name="mail-text" type="text" value="" placeholder="您的邮件地址" />
				<a class="mail-btn" href="javascript:void(0);" id="J_mail_btn">订阅</a>
				<p class="mail-msg">关注行业动态，把握职业机会，我们不定时发送一些热门岗位到您的邮箱！</p>
			</div>
			<!-- <div class="copyright">&copy;<?= date('Y');?> 浙ICP备16005246号 | <a href="<?= $Path.'/index.php?r=company/contact'?>" title="联系我们">联系我们</a></div> -->
		</div>
	</div>
	<div class="subscribe-pop popup" id="J_Subscribe_Pop">
		<div class="subscribe-title">
			<i></i><strong>订阅每日行业职位机会</strong>
		</div>
		<div class="subscribe-mail">
			<div class="subscribe-mail-input">
				<input type="text" class="email-text j_g_focus" id="email-subscribe" name="email-subscribe" value="" placeholder="输入您的邮箱地址">
				<a href="javascript:void(0)" id="J_subscribe" class="subscribe-btn">确定订阅</a>
			</div>
		</div>
		<div class="subscribe-msg2">
			<i></i>我们会将每日最新的岗位发送到您的邮箱，当然您可以随时取消订阅！
		</div>
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<div class="subscribe-success popup" id="J_SubscribeSuc_Pop">
		<div class="subscribe-success-icon"></div>
		<div class="subscribe-success-msg">订阅成功 ！</div>
		<div class="subscribe-success-btn clearfix">
			<a href="javascript:void(0);" class="sbtn1 J_close_pop">确认</a>
		</div>
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<div class="captcha-pop popup" id="JCaptchaImg">
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
		<div class="captcha-title">
			<i></i><strong>请输入下图中的文字或字母</strong>
		</div>
		<div class="captcha-input">
			<input type="text" class="captcha-text" id="captchaImgTxt" name="captcha" value="" placeholder="请输入验证码">
			<img src="#" alt="点击刷新" title="点击刷新" id="J_resetcaptcha" >
		</div>
		<a href="javascript:;" class="captcha-btn" id="J_GetVcode">确定</a>
	</div>


<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/layer.js"></script>
<script type="text/javascript">$.include(['/css/common/reset.css']);</script>
<script type="text/javascript">
$(function(){
	if($.cookie('_registration_')=='1') {
		$('.Navbtn').removeClass('Navcur');
		$('.Navbtn').eq(1).addClass('Navcur');
		$('#J_RegisterCont_').find('.panel-title').html('招聘用户注册');
		$("#user-company").parents("li:eq(0)").show();
		$("#user-position").parents("li:eq(0)").show();
		$('#user-type').val('1');
		$('.register-box').removeClass('Nav1').addClass('Nav2')
	}
	else {
		$('.register-box').removeClass('Nav2').addClass('Nav1')
	}

	// 注册切换
	$('.Navbtn').on('click', function() {
		var _ = $(this),
			_n = _.data("type"),
			_o = $('#J_RegisterCont_');

		if(_n) {
			_o.find('.panel-title').html('招聘用户注册');
			$('.register-box').removeClass('Nav1').addClass('Nav2')
			$("#user-company").parents("li:eq(0)").show();
			$("#user-position").parents("li:eq(0)").show();
			$.cookie('_registration_', '1', {expires:1,path: '/'})
		}
		else {
			_o.find('.panel-title').html('求职用户注册');
			$('.register-box').removeClass('Nav2').addClass('Nav1')
			$("#user-company").parents("li:eq(0)").hide();
			$("#user-position").parents("li:eq(0)").hide();
			$.cookie('_registration_', '0', {expires:1,path: '/'})
		}

		$('#user-type').val("");
		_o.find('.info-text').val("")
		_o.find('.form-group').addClass('blur-input');
		_o.find('.help-block').hide();
		$('#user-type').val(_n);
		_.addClass('Navcur').siblings().removeClass('Navcur');
	})

	$('.info-text').on('focus', function() {
		$(this).parents('.form-group:eq(0)').removeClass('blur-input');
		$(this).nextAll('.help-block').hide();
	})
	$('.info-text').on('blur', function() {
		$(this).nextAll('.help-block').show();
	})

	// 获取手机验证码
	$("#getvcode").click(function(){
		$('#J_resetcaptcha').attr('src', '<?= $Path;?>/index.php?r=site/captcha')
		var $this = $(this),$n = $("#user-phoneno"),$fg = $n.parents(".form-group:eq(0)");
		$n.blur();
		setTimeout(function(){
			if($fg.is(".has-error") || $this.is(".waitTime")){return}
			$('#captchaImgTxt').val('')
			$('#JCaptchaImg').show();
			$('#J_overlayAll_').show();
		}, 220)

	});

	$('#J_resetcaptcha').on('click', function() {
		$(this).attr('src', '')
		$(this).attr('src', '<?= $Path;?>/index.php?r=site/captcha')
	})

	$('#J_GetVcode').on('click', function() {
		var $this = $('#getvcode'),$n = $("#user-phoneno"),$fg = $n.parents(".form-group:eq(0)");
		setTimeout(function(){
			$.get("<?= Url::to(["/common/index/send-verify-code","phoneno"=>""]) ?>"+$n.val()+"&captcha="+$.trim($('#captchaImgTxt').val())).then(function(data){
				if(data.status != 0) {
					layer.msg('验证码错误，请重新输入', {time: 2000});
					$('#J_resetcaptcha').attr('src', '<?= $Path;?>/index.php?r=site/captcha');
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

	$('#registration-form').on('submit', check)
	function check() {
		$('.help-block').show()
	}
});
</script>
<?php $this->endBlock();  ?>
