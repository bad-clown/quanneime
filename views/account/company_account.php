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
				<div class="Usercenter-account Usercenter-right">
					<?php $form = ActiveForm::begin([
						'id' => 'account-form'
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
						<div class="company-about">
							<h3 class="account-title">单位简介 <span>招聘详情页将显示单位介绍</span></h3>
							<div class="account-textarea-box">
								<?= $form->field($model, 'compDesc', ['inputOptions' => ['placeholder' => '请输入招聘单位介绍，字数不超过200.', 'class' => 'account-textarea'], 'template' => "{input}\n{hint}\n{error}"])->textarea() ?>
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

		var data = new FormData()
		$.each($("#J_portrait_file")[0].files, function(i, file) {
			data.append('pic', file)
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
			data: data,
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
})
</script>
<?php $this->endBlock();  ?>
