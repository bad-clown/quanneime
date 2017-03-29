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
			<h1>管理中心</h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageSearch" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-search ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">搜索</a>
		</div><!-- /header -->
		<div data-role="content">
			<div class="Usercenter_Box">
				<div class="Usercenter-content clearfix">
					<div class="Usercenter-mypoints Usercenter-right">
						<div class="myscore-list clearfix">
							<div class="mylist"><ul id="J_points"></ul></div>
							<div class="myscore">
								<strong><?= \Yii::$app->user->identity->points?></strong>
								<span>总积分</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /page -->

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/x-jquery-tmpl" id="points-list-tmpl">
<li>
	<div class="points-list">
		<a href="javascript:;" class="_time">${time} ${reason}</a>
		<span class="_points">+1</span>
	</div>
</li>
</script>
<script type="text/javascript">
$(function() {
	$( "body>[data-role='panel']" ).panel();
	var page = 1;
	$.ajax({
		type : 'GET',
		url : apiUrl._pointsRecord,
		data : {
			page : page
		},
		dataType : 'json',
		success : function(data) {
			var _records = data.records, len = _records.length, _result = [];

			if(len > 0) {
				_result = Quannei.extend(_records)
				$('#points-list-tmpl').tmpl(_result).appendTo('#J_points');
			}
		}
	})
})
</script>
<?php $this->endBlock();  ?>