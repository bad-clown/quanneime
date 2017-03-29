<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
//$Path = DictionaryLogic::indexKeyValue('App', 'Host', false);
$Path = \Yii::$app->request->hostInfo;
if(!ereg('/https/', $Path)) {
	$Path = preg_replace('/http/', 'https', $Path);
}
?>

<div data-role="page" data-quicklinks="true" id="pageMain">
	<div class="ui-panel-wrapper">
		<div data-role="header" data-theme="b">
			<h1>关于我们</h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageSearch" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-search ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">搜索</a>
		</div><!-- /header --> 
		<div data-role="content">
			<div class="contact">
				<div class="about">
					<h3>关于我们</h3>
					<p>圈内觅 Quannei.me 是一个专注于地产圈职业机会的平台</p>
				</div>
				<div class="us">
					<h3>联系我们</h3>
					<p>合作、友情链接、问题反馈发送至</p>
					<p style="font-size: 24px;">quannei[at]quannei.me ( [at]替换成@ )</p>
				</div>
				<div class="line-or"><span>或</span></div>
				<div class="ewm">
					<div style="text-align: center;"><img src="/images/qnmgzhewm.jpg" width="164" alt=""></div>
					<p>微信扫码，关注公众号联系到我们</p>
				</div>
			</div>
		</div>
	</div>
</div><!-- /page -->


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript">
$(function(){
	$( "body>[data-role='panel']" ).panel();
})
</script>
<?php $this->endBlock();  ?>