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
			<ul id="list" data-role="listview" data-inset="true">
			</ul>
			<div class="Usercenter_Box">
				<ul class="deliver-lists"></ul>
				<div class="deliver-more" style="display:none;">
					<a href="javascript:;" class="more-btn" id="J_move-dr">加载更多</a>
				</div>
			</div>

			<!-- <div class="Usercenter-content clearfix">
				<div class="Usercenter-deliver Usercenter-right">
					<ul class="deliver-tit">
						<li>
							<div class="work">投递岗位</div>
							<div class="comp">投递单位</div>
							<div class="time">投递时间</div>
							<div class="stat">状态</div>
						</li>
					</ul>
					<ul class="deliver-lists clearfix"></ul>
					<div class="deliver-more" style="display:none;">
						<a href="javascript:;" class="more-btn" id="J_move-dr">加载更多</a>
					</div>
				</div>
			</div> -->
		</div>
	</div>
</div><!-- /page -->

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/layer.js"></script>
<script type="text/x-jquery-tmpl" id="deliver-list-tmpl">
<li>
	<div>
		<h3><a href="<?= $Path; ?>/index.php?r=m/job/detail&id=${jobId['$id']}" class="work">${position}</a></h3>
		<p class="topic"><strong>${company}</strong></p>
		<p class="time">${time}</p>
		<p class="stat">
			{{if status == <?= DictionaryLogic::indexKeyValue('DeleveredResumeStatus', 'Delivered');?>}}
			<span class="hide">未查看</span>
			{{/if}}
			{{if status == <?= DictionaryLogic::indexKeyValue('DeleveredResumeStatus', 'Viewed');?>}}
			<span class="show">已查看</span>
			{{/if}}
		</p>
	</div>
</li>
</script>
<script>
$(function() {
	$( "body>[data-role='panel']" ).panel();
	var _drpage = 1;

	function getDeliverList(n) {
		var _n = n || 1;
		var param = {
			type : 'GET',
			url : apiUrl._deliveredResume,
			data : {
				page : _n
			},
			callback : function(data) {
				var _drList = data.drList, len = _drList.length, _result = [];

				if(len > 0) {
					_result = Quannei.extend(_drList)
					$('#deliver-list-tmpl').tmpl(_result).appendTo('#list')
					$('#list').listview('refresh');
					if(data.currPage == data.pageCount) {
						$('.deliver-more').hide();
					}
					else {
						$('.deliver-more').show();
					}
				}
				else {
					$('#list').append('<li style="padding:10px 0;text-align:center;">暂未投递！</li>')
				}
			}
		}
		apiUrl.getJSON(param)
	}

	$('#J_move-dr').on('click', function() {
		_drpage++;
		getDeliverList(_drpage)
	})
	getDeliverList()
})
</script>
<?php $this->endBlock();  ?>