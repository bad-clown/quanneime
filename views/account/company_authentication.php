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
$Noauth = Dictionary::indexKeyValue('AuthStatus', 'NoAuth');
$Pending = Dictionary::indexKeyValue('AuthStatus', 'Pending');
$Pass = Dictionary::indexKeyValue('AuthStatus', 'Pass');
$Reject = Dictionary::indexKeyValue('AuthStatus', 'Reject');
?>
	<!-- 内容 start -->
	<div class="main">
		<div class="Usercenter_Box">
			<div class="UserCenter-title">管理中心</div>
			<div class="Usercenter-content clearfix">
				<div class="Usercenter-menu">
					<?= $this->render('_cnav') ?>
				</div>
				<div class="Usercenter-authentication Usercenter-right">
					<div class="account-authentication">
						<div class="clearfix">
							<h3 class="_title">认证情况</h3>
							<?php if($model->authStatus == $Noauth){?>
							<span class="unauthenticated">未认证</span>
							<?php }elseif($model->authStatus == $Pending){?>
							<span class="unauthenticated">审核中</span>
							<?php }elseif($model->authStatus == $Pass){?>
							<span class="authenticated">已认证</span>
							<?php }elseif($model->authStatus == $Reject){?>
							<span class="authfailure">认证失败</span>
							<div class="authfailure-tips"><?= $model->authFailMsg;?></div>
							<?php }?>
						</div>
						<div class="authentication-details">
							<div class="authentication-panel">
								<table>
									<tbody>
										<tr <?php if($model->authStatus == $Pending || $model->authStatus == $Reject){?>class="auth-change"<?php }?>>
											<td class="auth-left">
												<div class="auth-tit">您的单位</div>
											</td>
											<td class="auth-middle">
												<span class="auth-cont" id="J_auth_comp"><?php if($model->company == ""){?>未设置<?php }else{ echo $model->company; }?></span>
											</td>
											<td class="auth-right">
												<span class="auth-msg <?php if($model->company == ""){?> auth-not-msg<?php }?>"><?php if($model->company == ""){?>未设置<?php }else{?>已设置<?php }?></span>
												<span>|</span>
												<a href="javascript:;" class="auth-btn J_dialog" data-dialog="J_auth_identity">修改</a>
											</td>
										</tr>
										<tr <?php if($model->authStatus == $Pending || $model->authStatus == $Reject){?>class="auth-change"<?php }?>>
											<td class="auth-left">
												<div class="auth-tit">您的职务</div>
											</td>
											<td class="auth-middle">
												<span class="auth-cont" id="J_auth_post"><?php if($model->position == ""){?>未设置<?php }else{ echo $model->position; }?></span>
											</td>
											<td class="auth-right">
												<span class="auth-msg <?php if($model->position == ""){?> auth-not-msg<?php }?>"><?php if($model->position == ""){?>未设置<?php }else{?>已设置<?php }?></span>
												<span>|</span>
												<a href="javascript:;" class="auth-btn J_dialog" data-dialog="J_auth_identity">修改</a>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="authentication-tips">以上信息需与公司名片信息一致</div>
							</div>
							<div class="authentication-panel">
								<table>
									<tbody>
										<tr <?php if($model->authStatus == $Pending || $model->authStatus == $Reject){?>class="auth-change"<?php }?>>
											<td class="auth-left">
												<div class="auth-tit">认证资料</div>
											</td>
											<td class="auth-middle">
												<a href="<?php if($model->card == ""){?>javascript:void(0);<?php }else{ echo $Path.$model->card; }?>" id="J_card_img" class="auth-cont auth-card" target="_blank"><?php if($model->card == ""){?>未上传<?php }else{ echo str_replace("/data/card/", "", $model->card); }?></a>
											</td>
											<td class="auth-right">
												<span class="auth-msg <?php if($model->card == ""){?> auth-not-msg<?php }?>"><?php if($model->card == ""){?>未上传<?php }else{?>已上传<?php }?></span>
												<span>|</span>
												<a href="javascript:;" class="auth-btn J_dialog" data-dialog="J_auth_card"><?php if($model->card == ""){?>上传<?php }else{?>修改<?php }?></a>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="authentication-tips">请上传工牌、名片、公司内网等能证明身份的图片。<a href="javascript:void(0)" class="J_dialog" data-dialog="J_card_Pop" style="display:none;">查看范例</a></div>
							</div>
							<div class="authentication-panel">
								<table>
									<tbody>
										<tr <?php if($model->authStatus == $Pending || $model->authStatus == $Reject){?>class="auth-change"<?php }?>>
											<td class="auth-left">
												<div class="auth-tit">您的手机</div>
											</td>
											<td class="auth-middle">
												<span class="auth-cont" id="J_phoneno"><?php if($model->phoneno == ""){?>未绑定<?php }else{ echo $model->phoneno; }?></span>
											</td>
											<td class="auth-right">
												<span class="auth-msg <?php if($model->phoneno == ""){?> auth-not-msg<?php }?>"><?php if($model->phoneno == ""){?>未绑定<?php }else{?>已绑定<?php }?></span>
												<span>|</span>
												<a href="javascript:;" class="auth-btn J_dialog" data-dialog="J_auth_phone"><?php if($model->phoneno == ""){?>绑定<?php }else{?>修改<?php }?></a>
											</td>
										</tr>
										<tr <?php if($model->authStatus == $Pending || $model->authStatus == $Reject){?>class="auth-change"<?php }?>>
											<td class="auth-left">
												<div class="auth-tit">您的邮箱</div>
											</td>
											<td class="auth-middle">
												<span class="auth-cont"><?php if($model->email == ""){?>未绑定<?php }else{ echo $model->email; }?></span>
											</td>
											<td class="auth-right">
												<span class="auth-msg <?php if($model->email == ""){?> auth-not-msg<?php }?>"><?php if($model->email == ""){?>未绑定<?php }else{?>已绑定<?php }?></span>
												<span>|</span>
												<a href=" javascript:;" class="auth-btn J_dialog" data-dialog="J_auth_email"><?php if($model->email == ""){?>绑定<?php }else{?>修改<?php }?></a>
											</td>
										</tr>
									</tbody>
								</table>
								<div class="authentication-tips">绑定公司邮箱更容易通过认证，当然您也可以绑定常用邮箱！</div>
							</div>
						</div>
					</div>
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
				<input type="text" class="email-text" id="J_Email" name="Email" value="" placeholder="输入您的公司或个人邮箱">
				<!-- 发送前 -->
				<a href="javascript:;" class="getVerifyCode" id="J_email_code">发送验证</a>
				<!-- 发送后 -->
				<!-- <a href="#" class="getVerifyCode waitTime">60s 重新发送</a> -->
			</div>
			<p class="auth-email-msg">我们将会发送一份激活连接到您的邮箱，企业邮箱将一步认证，个人邮箱还需上传公司名片。</p>
		</div>

		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<!-- 绑定邮箱 end -->

	<!-- 上传名片 start -->
	<div class="auth-card-pop popup" id="J_auth_card">
		<div class="pop-title">
			<i></i><strong>上传认证资料</strong>
		</div>
		<div class="auth-card-box">
			<div class="auth-card-panel clearfix">
				<!-- 上传前 -->
				<a href="javascript:;" id="J_card_link" class="auth-card-cont" target="_blank">还未上传</a>
				<!-- 上传后 -->
				<!-- <a href="#" class="auth-card-cont">minpian.jpg</a> -->
				<a href="javascript:;" class="auth-card-fileBox">
					<input type="button" name="" class="auth-card-filebtn" value="上传">
					<input name="card" type="file" class="auth-card-file" id="J_card_file">
				</a>
			</div>
			<a href="javascript:;" id="J_card_submit" data-url="" class="auth-card-submit">确定</a>
		</div>

		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<!-- 上传名片 end -->

	<!-- 修改信息 start -->
	<div class="auth-identity-pop popup" id="J_auth_identity">
		<div class="pop-title">
			<i></i><strong>认证信息</strong>
		</div>
		<div class="auth-identity-content">
			<div class="auth-identity-left">
				<ul>
					<li>
						<label class="label-name">公司名称</label>
						<input class="identity-text" id="J_identity_comp" type="text" name="" value="<?= $model->company?>" placeholder="" />
					</li>
					<li>
						<label class="label-name">单位职务</label>
						<input class="identity-text" id="J_identity_post" type="text" name="" value="<?= $model->position?>" placeholder="" />
					</li>
				</ul>
				<a href="javascript:void(0)" class="identity-submit" id="J_identity_submit">确认</a>
				<a href="javascript:void(0)" class="identity-cancel J_close_pop">取消</a>
			</div>
			<div class="auth-identity-right">
				<ul>
					<li>
						<strong>公司名称</strong>
						<p>公司的昵称,如”欧孚科技圈内网”。</p>
					</li>
					<li>
						<strong>单位职务</strong>
						<p>您的职务，如“人力资源主管”。</p>
					</li>
					<li>
						<strong class="Tips">提示！</strong>
						<p>修改公司名称、单位职务将会重新审核您的身份信息。</p>
					</li>
				</ul>
			</div>
		</div>

		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<!-- 修改信息 end -->

	<!-- 认证提交成功 start -->
	<div class="auth-suc-pop popup" id="J_AuthSuc_Pop">
		<div class="auth-suc-icon"></div>
		<div class="auth-suc-msg">您的认证申请提交成功，预计3个工作日审核完成，<br>认证过程中您可以继续发布招聘。</div>
		<div class="auth-suc-btn clearfix">
			<a href="#" class="sbtn1">发布岗位</a>
			<a href="javascript:void(0);" class="sbtn2 J_close_pop">确认</a>
		</div>
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<!-- 认证提交成功 end -->

	<!-- 认证修改 start -->
	<div class="auth-change-pop popup" id="J_AuthChange_Pop">
		<div class="auth-change-icon"></div>
		<div class="auth-change-msg">修改 <span>公司、职务、认证图片</span> 等信息时，<br>将会导致重新认证，预计3个工作日完成。</div>
		<div class="auth-change-btn clearfix">
			<a href="javascript:void(0);" class="sbtn1 J_close_pop">取消</a>
			<a href="javascript:void(0);" class="sbtn2">继续</a>
		</div>
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<!-- 认证修改 end -->

	<!-- 名片示例 start -->
	<div class="card-pop popup" id="J_card_Pop">
		<div class="card-title">正确的名片范例</div>
		<div class="card-img"><img src="<?= $Path;?>/images/card.jpg" height="339" width="540" alt=""></div>
		<div class="card-msg">您上传的名片必须真实有效，画面清晰可见，如冒充或伪造将得不到认证，该账号将被删除。</div>
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<!-- 名片示例 end -->

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/layer.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script>
$(function() {
	$('#J_AuthNav').addClass('menu-cur');
	$('#J_identity_submit').on('click', function() {
		var v1 = $('#J_identity_comp').val()
		var v2 = $('#J_identity_post').val()


		$.ajax({
			type : "GET",
			url : apiUrl._updatePositionComp,//&comp=xxx&position=yyy'
			data : {
				comp : v1,
				position : v2
			},
			success : function(data) {
				$('#J_auth_comp').html(v1)
				$('#J_auth_post').html(v2)
				$('.J_close_pop').click()

				alert('修改成功');
				window.location.reload();
			}
		})
	})

	$('#J_card_file').on('change', function() {
		var imgPath = $("#J_card_file").val();
		if (imgPath == "") {
			alert("请选择上传图片！");
			return;
		}

		var data = new FormData()
		$.each($("#J_card_file")[0].files, function(i, file) {
			data.append('card', file)
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
			url: apiUrl._uploadCardImg,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(data) {
				$('#J_card_submit').data('t', true)
				$('#J_card_submit').data('url', data.url)
				$('#J_card_link').attr('href', $_Path+data.url).html(data.url.replace('\/data\/card\/', ''))
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("上传失败，请检查网络后重试");
			}
		});
	})

	$('#J_card_submit').on('click', function() {
		var _url = $(this).data('url');

		if($(this).data('t')) {
			$.ajax({
				type: "GET",
				url: apiUrl._uploadCard,
				data: 'url='+_url,
				cache: false,
				dataType: 'json',
				success: function(data) {
					if(data.code == '0') {
						$('#J_card_img').html(_url.replace('\/data\/card\/', ''))
						$('.J_close_pop').click()
						alert("上传成功");
						window.location.reload();
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("上传失败，请检查网络后重试");
				}
			});
		}
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
