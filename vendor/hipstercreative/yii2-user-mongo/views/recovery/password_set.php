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

	<div class="header">
		<div class="comWidth clearfix">
			<div class="h-logo">
				<a href="<?= $Path;?>/index.php" class="logo-icon"><img src="<?= $Path;?>/images/top-logo-20160330.png"/></a>
			</div>
			<div class="h-nav">
				<a href="<?= $Path;?>/index.php" class="home-text" id="J_Home_">发现</a>
				<a href="<?= $Path;?>/index.php?r=company/list" class="company-text" id="J_Company_">公司</a>
				<a href="<?= $Path;?>/index.php?r=site/help" class="help-text" id="J_Help_">帮助</a>
			</div>
			<div class="h-menu clearfix">
				<div class="mnav">
					<a href="javascript:void(0)" class="search-text" id="J_Search_Pop">搜索</a>
				</div>
				<?php
				if (!(\Yii::$app->user->isGuest)) {?>
				<div class="mnav">
					<?php if(\Yii::$app->user->identity->type){ ?>
					<a href="<?= $Path;?>/index.php?r=job/publish" class="publish-text" id="J_Release_">发布</a>
					<?php } else { ?>
					<a href="javascript:void(0)" class="publish-text J_dialog" data-dialog="J_denied">发布</a>
					<?php } ?>
				</div>
				<?php }else{ ?>
				<div class="mnav">
					<a href="<?= $Path;?>/index.php?r=job/publish" class="publish-text" id="J_Release_">发布</a>
				</div>
				<?php } ?>
				<div class="mnav">
					<?php if (\Yii::$app->user->isGuest)  {?>
						<div class="login-regist">
							<a href="<?= $Path;?>/index.php?r=user/security/login" class="login-text">登录</a>
							<span>&nbsp;|&nbsp;</span>
							<a href="<?= $Path;?>/index.php?r=user/registration/register" class="regist-text">注册</a>
						</div>
					<?php } else { ?>
						<div class="user-login-info" id="J_user_info">
							<a href="javascript:void(0)" class="user-name"><?= \Yii::$app->user->identity->nickname; ?></a>
							<div class="user-menu" id="J_user_menu">
								<?php if(\Yii::$app->user->identity->type){ ?>
								<ul>
									<li>
										<i class="um-icon um-publish"></i>
										<a href="<?= $Path.'/index.php?r=account/comp-publish'?>">我的发布</a>
									</li>
									<li>
										<i class="um-icon um-list"></i>
										<a href="<?= $Path.'/index.php?r=account/comp-resume-list'?>">已收简历</a>
										<!-- <i class='dot-tips'></i> -->
									</li>
									<li>
										<i class="um-icon um-account"></i>
										<a href="<?= $Path.'/index.php?r=account/comp-account'?>">账号设置</a>
									</li>
									<li>
										<i class="um-icon um-auth"></i>
										<a href="<?= $Path.'/index.php?r=account/comp-auth'?>">账号认证</a>
									</li>
									<li>
										<i class="um-icon um-change-pass"></i>
										<a href="<?= $Path.'/index.php?r=account/comp-password'?>">修改密码</a>
									</li>
									<?php if (in_array(\Yii::$app->user->identity->username, Dictionary::indexMap("AdminUsers"))) {?>
									<li>
										<i class="um-icon um-change-pass"></i>
										<a href="<?= $Path.'/index.php?r=admin'?>">后台管理</a>
									</li>
									<?php } ?>
									<li>
										<form action="<?= $Path;?>/index.php?r=user/security/change-account" method="post">
											<i class="um-icon um-switch-user"></i>
											<a href="javascript:void(0);" id="J_logout"><input type="submit" value="切换账号"></a>
										</form>
									</li>
									<li>
										<form action="<?= $Path;?>/index.php?r=user/security/logout" method="post">
											<i class="um-icon um-logout"></i>
											<a href="javascript:void(0);" id="J_logout"><input type="submit" value="退出账户"></a>
										</form>
									</li>
								</ul>
								<?php } else { ?>
								<ul>
									<li>
										<i class="um-icon um-resume"></i>
										<a href="<?= $Path.'/index.php?r=account/personal-resume'?>">我的简历</a>
									</li>
									<li>
										<i class="um-icon um-gather"></i>
										<a href="<?= $Path.'/index.php?r=account/personal-gather'?>">我的投递</a>
									</li>
									<li>
										<i class="um-icon um-account"></i>
										<a href="<?= $Path.'/index.php?r=account/personal-account'?>">账号设置</a>
									</li>
									<li>
										<i class="um-icon um-change-pass"></i>
										<a href="<?= $Path.'/index.php?r=account/personal-password'?>">修改密码</a>
									</li>
									<?php if (in_array(\Yii::$app->user->identity->username, Dictionary::indexMap("AdminUsers"))) {?>
									<li>
										<i class="um-icon um-change-pass"></i>
										<a href="<?= $Path.'/index.php?r=admin'?>">后台管理</a>
									</li>
									<?php } ?>
									<li>
										<form action="<?= $Path;?>/index.php?r=user/security/change-account" method="post">
											<i class="um-icon um-switch-user"></i>
											<a href="javascript:void(0);" id="J_logout"><input type="submit" value="切换账号"></a>
										</form>
									</li>
									<li>
										<form action="<?= $Path;?>/index.php?r=user/security/logout" method="post">
											<i class="um-icon um-logout"></i>
											<a href="javascript:void(0);" id="J_logout"><input type="submit" value="退出账户"></a>
										</form>
									</li>
								</ul>
								<?php } ?>
								<i class="arrows-top"></i>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="main">
		<div class="password-set-box clearfix">
			<div class="password-tit">找回密码</div>
			<?php $form = ActiveForm::begin([
				'id' => 'pwd-form'
			]); ?>
			<div class="ps-box">
				<div class="ps-panel">
					<?= $form->field($model, 'phoneno', ['inputOptions' => ['class' => 'pass-text', 'placeholder' => '手机号码'], 'template' => "<label class='control-label'>手机号码</label>\n{input}\n{hint}\n{error}"]) ?>
				</div>
				<div class="ps-panel">
					<?= $form->field($model, 'verifyCode', ['inputOptions' => ['class' => 'pass-text', 'style' => 'width:99px', 'placeholder' => '输入验证'], 'template' => "<label class='control-label'>输入验证</label>\n{input}\n<a href='javascript:;'' class='getvcode' id='getvcode'>获取验证码</a>\n{hint}\n{error}"]) ?>
				</div>
				<div class="ps-panel">
					<?= $form->field($model, 'password', ['inputOptions' => ['class' => 'pass-text', 'placeholder' => '新的密码'], 'template' => "<label class='control-label'>新的密码</label>\n{input}\n{hint}\n{error}"])->passwordInput() ?>
				</div>
				<div class="ps-panel">
					<div class="form-group field-recovery-form-password2 required has-error">
						<label class="control-label">确认密码</label>
						<input type="password" id="recovery-form-password2" class="pass-text" name="recovery-form[password2]" placeholder="再次输入密码">					

						<div class="help-block"></div>
					</div>
				</div>
			</div>
			<div class="ps-btn-box">
				<!-- <a class="submit-btn" href="javascript:;" id="J_submit">确认</a> -->
				<?= Html::submitButton(Yii::t('user', '确认'), ['class' => 'submit-btn']) ?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
	<div class="footer">
		<div class="comWidth clearfix">
			<!-- <div class="quannei-win">圈内觅 Quannei.me</div> -->
			<div class="ewm"><img src="/images/quanneimiewm.gif" alt=""></div>
			<div class="copyright">
				<p>圈内觅 www.quannei.me  |  发现地产圈职业机会</p>
				<p><a href="<?= $Path.'/index.php?r=company/contact'?>">通过微信或者邮箱联系到我们</a></p>
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
	<div class="pass-set-suc popup">
		<div class="suc-icon"></div>
		<div class="suc-msg">密码修改成功！</div>
		<div class="suc-btn-box clearfix">
			<!-- <a href="#" class="sbtn1">发布招聘</a> -->
			<a href="<?= $Path;?>/index.php" class="sbtn2">返回首页</a>
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
$(function() {
	$(document).on('submit', '#pwd-form', function(e) {
		var pwd1 = $('#recovery-form-password').val();
		var pwd2 = $('#recovery-form-password2').val();

		if(pwd1 != pwd2) {
			$('#recovery-form-password2').parents(".form-group:eq(0)").removeClass('has-success').addClass('has-error')
			$('#recovery-form-password2').parents(".form-group:eq(0)").find('.help-block').html('两次密码不一致。');
			$('#recovery-form-password2').focus();
			e.preventDefault();
		}
		else {
			$('#recovery-form-password2').parents(".form-group:eq(0)").removeClass('has-error').addClass('has-success')
			$('#recovery-form-password2').parents(".form-group:eq(0)").find('.help-block').html('');
		}
	})

	$("#getvcode").click(function(){
		var $this = $(this),$n = $("#recovery-form-phoneno"),$fg = $n.parents(".form-group:eq(0)");
		$n.blur();
		setTimeout(function(){
			if($fg.is(".has-error") || $this.is(".waitTime")){return}
			$('#captchaImgTxt').val('')
			$('#J_resetcaptcha').attr('src', '<?= $Path;?>/index.php?r=site/captcha')
			$('#JCaptchaImg').show();
			$('#J_overlayAll_').show();
		}, 220)

	});

	$('#J_resetcaptcha').on('click', function() {
		$(this).attr('src', '')
		$(this).attr('src', '<?= $Path;?>/index.php?r=site/captcha')
	})

	$('#J_GetVcode').on('click', function() {
		var $this = $('#getvcode'),$n = $("#recovery-form-phoneno"),$fg = $n.parents(".form-group:eq(0)");
		setTimeout(function(){
			$.get("<?= Url::to(["/common/index/send-verify-code","phoneno"=>""]) ?>"+$n.val()+'&captcha='+$('#captchaImgTxt').val()).then(function(data){
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
})
</script>
<?php $this->endBlock();  ?>
