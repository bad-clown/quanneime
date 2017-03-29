<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\invite-code */
/* @var $form yii\widgets\ActiveForm */
//$Path = DictionaryLogic::indexKeyValue('App', 'Host', false);
$Path = \Yii::$app->request->hostInfo;
?>

<div class="company-form">

	<div class="form-group field-pic required">
		<form id="uploadForm">
			<label class="control-label" for="pic">LOGO</label>
			<input type="file" id="pic" class="form-control" name="pic">
			<div id="img"></div>
		</form>
	</div>

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->	field($model, '_id')->hiddenInput()->label("") ?>
	<?= $form->	field($model, 'logo')->hiddenInput()->label("") ?>
	<?= $form->	field($model, 'name')->textInput(['maxlength' => 10]) ?>
	<?= $form->	field($model, 'description')->textInput(['maxlength' => 20]) ?>
	<?= $form->	field($model, 'keys')->textInput(['maxlength' => 50]) ?>
	<?= $form->	field($model, 'detail')->textInput() ?>
	<?= $form->	field($model, 'location')->textInput(['maxlength' => 50]) ?>
	<div class="form-group">
		<?= Html::a(I18n::text('Cancel'), ['index'], ['class' =>
		'btn btn-default']) ?>
		<?= Html::submitButton($model->
		isNewRecord ? I18n::text('Create') : I18n::text('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$('#pic').on('change', function() {
	
	var imgPath = $("#pic").val();
	if (imgPath == "") {
		alert("请选择上传图片！");
		return;
	}
	//判断上传文件的后缀名
	var strExtension = imgPath.substr(imgPath.lastIndexOf('.') + 1);
	strExtension = strExtension.toLowerCase();
	if (strExtension != 'jpg' && strExtension != 'gif' && strExtension != 'png' && strExtension != 'bmp') {
		alert("请选择图片文件");
		return;
	}
	var formData = new FormData($( "#uploadForm" )[0]);  

	$.ajax({
		type: "POST",
		url: "<?php echo $Path.'/index.php?r=image/image/upload-comp-logo';?>",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		dataType: 'json',
		success: function(data) {
			alert("上传成功");
			$('#pic').hide()
			$('#btnUpload').hide()
			$('#img').html('<img src="<?php echo $Path;?>'+data.url+'">')
			$('#company-logo').val(data.url)
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert("上传失败，请检查网络后重试");
		}
	});
})
</script>
<?php $this->endBlock();  ?>