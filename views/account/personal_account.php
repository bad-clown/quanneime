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
					<?= $this->render('_pnav');?>
				</div>
				<div class="Usercenter-account Usercenter-right">
					<?php $form = ActiveForm::begin([
						'id' => 'account-pernsonal-form'
					]); ?>
					<div class="account-info">
						<h3 class="account-title">基本信息</h3>
						<div class="essential-information">
							<div class="account-panel">
								<label class="account-label">用户名：</label>
								<div class="account-username"><?= $model->username?></div>
							</div>
							<div class="account-panel">
								<?= $form->field($model, 'nickname', ['inputOptions' => ['placeholder' => '输入昵称', 'class' => 'account-text'], 'template' => "<label class='account-label' for='user-nickname'>昵称：</label>\n{input}\n<span class='name-tips'>昵称将对外公开显示</span>\n{hint}\n{error}"]) ?>
							</div>
							<div class="account-panel">
								<?= $form->field($model, 'realName', ['inputOptions' => ['placeholder' => '真实姓名', 'class' => 'account-text'], 'template' => "<label class='account-label' for='user-realName'>真实姓名：</label>\n{input}\n{hint}\n{error}"]) ?>
							</div>
							<div class="account-panel">
								<?= $form->field($model, 'sex', ['inputOptions' => ['class' => 'account-radio'], 'template' => "<label class='account-label' for='user-sex'>性别：</label>\n{input}"])->radioList(['1'=>'男','0'=>'女']) ?>
							</div>
							<div class="account-portrait">
								<div class="portrait-img-box"><img src="<?= $Path.\Yii::$app->user->identity->avatar;?>" id="J_portrait" alt="头像"></div>
								<div class="_overlay"></div>
								<div class="account-portrait-openimg">
									<input type="button" name="" id="J_portrait_filebtn" class="account-portrait-filebtn" value="更新账号头像">
									<input name="pic" type="file" id="J_portrait_file" class="account-portrait-file">
								</div>
								<div class="upload-tips">请上传小于1M大小的正方形图片</div>
							</div>
						</div>
						<div class="account-authentication">
							<div class="clearfix">
								<h3 class="_title">安全设置</h3>
							</div>
							<div class="authentication-details">
								<div class="authentication-panel">
									<table>
										<tbody>
											<tr>
												<td class="auth-left">
													<div class="auth-tit">您的手机</div>
												</td>
												<td class="auth-middle">
													<span class="auth-cont" id="J_phoneno"><?php if($model->phoneno == ""){?>未绑定<?php }else{ echo $model->phoneno; }?></span>
													<!-- <span class="auth-cont">158*****185</span> -->
												</td>
												<td class="auth-right">
													<span class="auth-msg <?php if($model->phoneno == ""){?> auth-not-msg<?php }?>"><?php if($model->phoneno == ""){?>未绑定<?php }else{?>已绑定<?php }?></span>
													<span>|</span>
													<a href="javascript:;" class="auth-btn J_dialog" data-dialog="J_auth_phone"><?php if($model->phoneno == ""){?>绑定<?php }else{?>修改<?php }?></a>
												</td>
											</tr>
											<tr>
												<td class="auth-left">
													<div class="auth-tit">您的邮箱</div>
												</td>
												<td class="auth-middle">
													<span class="auth-cont"><?php if($model->email == ""){?>未绑定<?php }else{ echo $model->email; }?></span>
													<!-- <span class="auth-cont">goodjob@gmail.com</span> -->
												</td>
												<td class="auth-right">
													<span class="auth-msg <?php if($model->email == ""){?> auth-not-msg<?php }?>"><?php if($model->email == ""){?>未绑定<?php }else{?>已绑定<?php }?></span>
													<span>|</span>
													<a href=" javascript:;" class="auth-btn J_dialog" data-dialog="J_auth_email"><?php if($model->email == ""){?>绑定<?php }else{?>修改<?php }?></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="Usercenter-submit">
							<?= Html::submitButton(Yii::t('user', '保存'), ['class' => 'submit-btn']) ?>
						</div>
					</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
	<!-- 内容 end -->

	<!-- 绑定手机 start -->
	<div class="auth-phone-pop popup" id="J_auth_phone">
		<div class="pop-title">
			<i></i><strong>绑定新的手机</strong>
		</div>
		<div class="auth-phone-content">
			<div class="auth-phone-panel">
				<input type="text" class="phone-text" id="J_Phone" name="Phone" value="" placeholder="输入新的手机号码">
			</div>
			<div class="auth-phone-panel">
				<input type="text" class="phone-text phone-verify" id="J_VerifyCode" name="VerifyCode" value="" placeholder="输入验证码">
				<a href="javascript:;" class="getVerifyCode" id="J_phone_code">发送验证</a>
			</div>
			<a href="javascript:;" id="J_phone_submit" class="auth-phone-submit">确定</a>
		</div>

		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<!-- 绑定手机 end -->

	<!-- 绑定邮箱 start -->
	<div class="auth-email-pop popup" id="J_auth_email">
		<div class="pop-title">
			<i></i><strong>绑定您的邮箱</strong>
		</div>
		<div class="auth-email-content">
			<div class="auth-email-panel">
				<input type="text" class="email-text" id="J_Email" name="Email" value="" placeholder="输入邮箱">
				<!-- 发送前 -->
				<a href="javascript:;" class="getVerifyCode" id="J_email_code">发送验证</a>
				<!-- 发送后 -->
				<!-- <a href="#" class="getVerifyCode waitTime">60s 重新发送</a> -->
			</div>
			<!-- <p class="auth-email-msg">我们将会发送一份激活连接到您的邮箱，企业邮箱将一步认证，个人邮箱还需上传公司名片。</p> -->
		</div>

		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<!-- 绑定邮箱 end -->

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
	$('#J_AccountNav').addClass('menu-cur')
	$('#J_portrait_file').on('change', function() {
		var imgPath = $("#J_portrait_file").val();
		if (imgPath == "") {
			alert("请选择上传图片！");
			return;
		}

		var pic = new FormData()
		$.each($("#J_portrait_file")[0].files, function(i, file) {
			pic.append('pic', file)
		})

		//判断上传文件的后缀名
		var strExtension = imgPath.substr(imgPath.lastIndexOf('.') + 1);
		strExtension = strExtension.toLowerCase();
		if (strExtension != 'jpg' && strExtension != 'gif' && strExtension != 'png' && strExtension != 'bmp') {
			alert("请选择图片文件");
			return;
		}

		$.ajax({
			type: "POST",
			url: apiUrl._changeAvatar,
			data: pic,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(data) {
				$('#J_portrait').attr('src', $_Path+data.url)
				alert("上传成功");
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("上传失败，请检查网络后重试");
			}
		});
	})

	/*$("#J_phone_code").click(function(){
		var $this = $(this),$n = $("#J_Phone")//,$fg = $n.parents(".form-group:eq(0)");
		var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
		if(!reg.test($n.val())) {
			alert('请输入正确手机号码')
			return;
		}
		setTimeout(function(){
			if($this.is(".waitTime")){return ;}
			$.get("<?= Url::to(["/common/index/send-verify-code","phoneno"=>""]) ?>"+$n.val()).then(function(){
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
			});
		},220);
	});*/

	$("#J_phone_code").click(function(){
		var $this = $(this),$n = $("#J_Phone")//,$fg = $n.parents(".form-group:eq(0)");
		var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
		if(!reg.test($n.val())) {
			alert('请输入正确手机号码');
			return;
		}
		setTimeout(function(){
			if(!reg.test($n.val()) || $this.is(".waitTime")){return}
			$('#captchaImgTxt').val('')
			$('#J_resetcaptcha').attr('src', '<?= $Path;?>/index.php?r=site/captcha')
			$('#JCaptchaImg').show();
			$('#J_overlayAll_').show();
			$('#J_auth_phone').hide();
		}, 220)
	});

	$('#J_resetcaptcha').on('click', function() {
		$(this).attr('src', '')
		$(this).attr('src', '<?= $Path;?>/index.php?r=site/captcha')
	})

	$('#J_GetVcode').on('click', function() {
		var $this = $('#J_phone_code'),$n = $("#J_Phone")//,$fg = $n.parents(".form-group:eq(0)");
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
					$('#J_auth_phone').show();
					$('#JCaptchaImg').hide();
				}
			});
		},220);
	})

	$('#J_phone_submit').on('click', function() {
		var phone = $("#J_Phone").val(),vcode = $("#J_VerifyCode").val();
		var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
		if(!reg.test(phone)) {
			alert('请输入正确手机号码！')
			return;
		}
		else if(vcode == '') {
			alert('请填写验证码！')
			return;
		}
		if(phone && vcode) {
			$.ajax({
				type : 'GET',
				url : apiUrl._updatePhone,//&phoneno=139xxx&code=yyyy'
				data : {
					phoneno : phone,
					code : vcode
				},
				success : function(data) {
					console.log(data)
					if(data.code == '0') {
						$('#J_phoneno').html(phone)
						$('.J_close_pop').click()
						window.location.reload();
					}
					else {
						alert('请输入正确验证码！')
					}
				}
			})
		}
	})

	$('#J_email_code').on('click', function() {
		var $this = $(this),$n = $("#J_Email")/*,$fg = $n.parents(".form-group:eq(0)");*/
		var reg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!reg.test($n.val())) {
			alert('请输入正确邮箱地址！')
			return;
		}
		setTimeout(function(){
			if($this.is(".waitTime")){return ;}
			$.get("<?= Url::to(["account/bind-email","email"=>""]) ?>"+$n.val()).then(function(){
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
			});
		},220);
	})
})
</script>
<?php $this->endBlock();  ?>
