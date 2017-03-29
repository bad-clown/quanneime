<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */


use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Dictionary;

$Path = \Yii::$app->request->hostInfo;

/**
 * @var yii\web\View $this
 * @var hipstercreative\user\models\User $model
 */

$this->title = Yii::t('user', 'Update user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<script>var $_Path="<?= $Path?>";</script>
<script>var $id="<?= (string)$model->_id?>";</script>
<div class="account-portrait">
    <div class="portrait-img-box"><img src="<?= $Path.$model->avatar;?>" id="J_portrait" alt="头像"></div>
    <div class="_overlay"></div>
    <div class="account-portrait-openimg">
        <input name="pic" type="file" id="J_portrait_file" class="account-portrait-file">
    </div>
    <div class="upload-tips">请上传小于1M大小的正方形图片</div>
</div>

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/layer.js"></script>
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
        data.append('id', $id)

		//判断上传文件的后缀名
		var strExtension = imgPath.substr(imgPath.lastIndexOf('.') + 1);
		strExtension = strExtension.toLowerCase();
		if (strExtension != 'jpg' && strExtension != 'gif' && strExtension != 'png' && strExtension != 'bmp') {
			alert("请选择图片文件");
			return;
		}

		$.ajax({
			type: "POST",
			url: "index.php?r=user/admin/change-avatar",
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
