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
					<?= $this->render('_pnav');?>
				</div>
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
			</div>
		</div>
	</div>
	<!-- 内容 end -->

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/x-jquery-tmpl" id="deliver-list-tmpl">
<li>
	<div class="work"><a href="<?= $Path; ?>/index.php?r=job/detail&id=${jobId['$id']}" target="_blank">${position}</a></div>
	<div class="comp">${company}</div>
	<div class="time">${time}</div>
	<div class="stat">
		{{if status == <?= DictionaryLogic::indexKeyValue('DeleveredResumeStatus', 'Delivered');?>}}
		<div class="hide">未查看</div>
		{{/if}}
		{{if status == <?= DictionaryLogic::indexKeyValue('DeleveredResumeStatus', 'Viewed');?>}}
		<div class="show">已查看</div>
		{{/if}}
	</div>
</li>
</script>
<script>
$(function() {
	$('#J_GatherNav').addClass('menu-cur')
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
					$('#deliver-list-tmpl').tmpl(_result).appendTo('.deliver-lists')
					if(data.currPage == data.pageCount) {
						$('.deliver-more').hide();
					}
					else {
						$('.deliver-more').show();
					}
				}
				else {
					$('.deliver-more').hide();
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
