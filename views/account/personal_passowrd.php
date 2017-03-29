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
					<?= $this->render('_pnav') ?>
				</div>
				<div class="Usercenter-setpwd Usercenter-right">
					<div class="set-pwd-box">
						<div class="set-pwd-panel old-pwd">
							<span>当前密码</span>
							<input type="hidden" id="J_Current_Password_val" data-bool="false" name="" value="">
							<input class="pwd-text" id="J_Current_Password" type="password" value="" placeholder="输入现在的密码">
							<i id="pwdcur-help"></i>
						</div>
						<div class="set-pwd-panel new-pwd">
							<span>新的密码</span>
							<input type="hidden" id="J_Password_val" data-bool="false" name="" value="">
							<input class="pwd-text" id="J_Password" type="password" value="" placeholder="输入新的密码">
							<i id="pwd-help"></i>
						</div>
						<div class="set-pwd-panel new-pwd">
							<span>确认密码</span>
							<input type="hidden" id="J_Confirm_Password_val" data-bool="false" name="" value="">
							<input class="pwd-text" id="J_Confirm_Password" type="password" value="" placeholder="再次输入新密码">
							<i id="pwdcfm-help"></i>
						</div>
					</div>
					<div class="Usercenter-submit">
						<a href="javascript:void(0)" id="J_submitpwd" class="submit-btn">确定</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 内容 end -->
	<!-- 密码修改成功 start -->
	<div class="pass-set-suc popup" id="J_PwdSuc_Pop">
		<div class="suc-icon"></div>
		<div class="suc-msg">密码修改成功！</div>
		<div class="suc-btn-box clearfix">
			<a href="<?= $Path;?>/index.php?r=job/publish" class="sbtn1" target="_blank">发布招聘</a>
			<a href="<?= $Path;?>/index.php" class="sbtn2" target="_blank">返回首页</a>
		</div>
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<!-- 密码修改成功 end -->
<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script>
$(function() {
	$('#J_PasswordNav').addClass('menu-cur');

	function ChangePwd(param) {
		$.ajax({
			type : 'POST',
			url : apiUrl._setPassword,
			data : param.data,
			dataType : 'json',
			success : function(data) {
				if(typeof param.callback=='function'){
					setTimeout(function() {
						param.callback(data);
					}, 200)
				}
			}
		})
	}

	$('#J_Current_Password').on('blur', function() {
		var param = {
			data : {
				current_password : $(this).val()
			},
			callback : function(data) {
				var pwderror = data.error;
				if(pwderror.current_password) {
					$('#pwdcur-help').removeClass('help-suc').addClass('help-err').html(pwderror.current_password)
					$('#J_Current_Password_val').data('bool', false)
				}
				else {
					$('#pwdcur-help').removeClass('help-err').addClass('help-suc').html("")
					$('#J_Current_Password_val').data('bool', true)
				}
			}
		}
		ChangePwd(param)
	})

	$('#J_Password').on('blur', function() {
		var param = {
			data : {
				password : $(this).val()
			},
			callback : function(data) {
				var pwderror = data.error;
				if(pwderror.password) {
					$('#pwd-help').removeClass('help-suc').addClass('help-err').html(pwderror.password)
					$('#J_Password_val').data('bool', false)
				}
				else {
					$('#pwd-help').removeClass('help-err').addClass('help-suc').html("")
					$('#J_Password_val').data('bool', true)
				}
			}
		}
		ChangePwd(param)
	})

	$('#J_Confirm_Password').on('blur', function() {
		if($('#J_Password').val() != "") {
			if($(this).val() == $('#J_Password').val()) {
				$('#pwdcfm-help').removeClass('help-err').addClass('help-suc').html("")
				$('#J_Confirm_Password_val').data('bool', true)
			}
			else {
				$('#pwdcfm-help').removeClass('help-suc').addClass('help-err').html('密码不一致。')
				$('#J_Confirm_Password_val').data('bool', false)
			}
		}
		else {
			$('#pwdcfm-help').removeClass('help-suc').addClass('help-err').html('密码不能为空。')
			$('#J_Confirm_Password_val').data('bool', false)
		}
	})


	$('#J_submitpwd').on('click', function() {
		if($('#J_Current_Password_val').data('bool') && $('#J_Password_val').data('bool') && $('#J_Confirm_Password_val').data('bool')) {
			var param = {
				data : {
					password : $('#J_Password').val(),
					current_password : $('#J_Current_Password').val()
				},
				callback : function(data) {
					if(data.code == '0') {
						$('#J_Current_Password,#J_Password,#J_Confirm_Password').val('');
						$('#pwdcur-help,#pwd-help,#pwdcfm-help').removeClass('help-suc').removeClass('help-err').html('')
						$('#J_PwdSuc_Pop').show();
						$('._overlayAll_').show();
					}
				}
			}
			ChangePwd(param)
		}
	})
})
</script>
<?php $this->endBlock();  ?>
