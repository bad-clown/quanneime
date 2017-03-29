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
		<div class="success-deliver">
			<div class="suc-icon"></div>
			<div class="suc-msg">您的简历投递成功，招聘方现已收到，请保持手机畅通。</div>
			<div class="back-tips">[<span id="J_time">3</span>]秒后返回，[<a href="<?= $Path."/index.php?r=job/detail&id=".$jobId;?>" title="返回">或者点击这里直接回返</a>]</div>

		</div>
	</div>
	<!-- 内容 end -->

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript">
$(function() {
	var interval = setInterval(function(){
		var t = parseInt($('#J_time').html(),10);
		t--;
		$('#J_time').html(t);
		if(t==0){
			clearInterval(interval);
			window.location.href = '<?= $Path."/index.php?r=job/detail&id=".$jobId;?>'
		}
	},1000)
})
</script>
<?php $this->endBlock();  ?>
