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

<div data-role="page" data-quicklinks="true" id="pageMain">
	<div class="ui-panel-wrapper">
		<div data-role="header" data-theme="b">
			<h1>热门公司</h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageSearch" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-search ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">搜索</a>
		</div><!-- /header -->
		<div data-role="content">
			<ul class="companylist" data-role="listview" data-inset="true" data-icon="false">
				<?php for($i=0;$i<count($compList);$i++) {?>
				<li>
					<a href="<?= $Path.'/index.php?r=m/company/gather&id='.$compList[$i]->_id;?>">
						<img src="<?= $Path.$compList[$i]->logo;?>">
						<h2><?= $compList[$i]->name;?></h2>
						<p><?= $compList[$i]->description;?></p>
					</a>
				</li>
				<?php }?>
			</ul>
		</div>
	</div>
</div><!-- /page -->

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript">
$(function() {
	$( "body>[data-role='panel']" ).panel();
})
</script>
<?php $this->endBlock();  ?>