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
	<!-- 内容 end -->

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/x-jquery-tmpl" id="points-list-tmpl">
<li>
	<div class="points-list">
		<a href="javascript:;" class="_time">${time} ${reason}</a>
		<span class="_points">+1</span>
	</div>
</li>
</script>
<script>
$(function() {
	$('#J_PointsNav').addClass('menu-cur')

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
