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
			<h1>我的发布</h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageSearch" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-search ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">搜索</a>
		</div><!-- /header -->
		<div data-role="content">
			<ul id="list" data-role="listview" data-inset="true">
			</ul>
			<div class="Usercenter_Box">
				<ul class="released-lists"></ul>
				<div class="released-more" style="display:none;">
					<a href="javascript:void(0)" class="more-btn" id="J_LoadMore">加载更多</a>
				</div>
			</div>
		</div>
	</div>
</div><!-- /page -->
<div class="_overlayAll_" id="J_overlayAll_"></div>
<!-- 确认删除 start -->
<div class="releas-del-pop popup" id="J_Delete_Pop">
	<div class="pop-msg">确定要将“<span id="J_position"></span>”岗位删除？</div>
	<div class="pop-btn">
		<a href="javascript:;" id="J_Delete_Job" data-jobid="" class="dbtn1">删除</a>
		<a href="javascript:;" class="dbtn2 J_close_pop" title="关闭">取消</a>
	</div>
	<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
</div>
<!-- 确认删除 end -->


<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/x-jquery-tmpl" id="released-list-tmpl">
<li>
	<div>
		<h3><a href="<?= $Path; ?>/index.php?r=m/job/detail&id=${_id['$id']}" {{if status==1}} style="color:#eb6100;"{{/if}} class="work">${name}</a></h3>
		<p class="topic"><strong>${showTime}</strong></p>
		<p>
			{{if status==0}}
				<span class="passed">显示中</span>
			{{/if}}
			{{if status==1}}
				<span class="passnot" style="color:#eb6100;">未通过</span>
			{{/if}}
			{{if status==2}}
				<span class="passin">审核中</span>
			{{/if}}
		</p>
		<p>
			<a href="javascript:void(0);" data-jobid="${_id['$id']}" class="del-btn">删除</a>
			<a href="javascript:void(0);" data-jobid="${_id['$id']}" class="ref-btn">刷新</a>
			<a href="<?= $Path; ?>/index.php?r=m/job/update&id=${_id['$id']}" class="edt-btn">编辑</a>
		</p>
	</div>
</li>
</script>
<script>
$(function() {
	$( "body>[data-role='panel']" ).panel();
	var __page = 1;
	$(document).on('mouseenter', '.passnot-tips-icon', function() {
		$(this).next('.passnot-tips-cont').show();
	})
	$(document).on('mouseleave', '.passnot-tips-icon', function() {
		$(this).next('.passnot-tips-cont').hide();
	})
	$('#J_LoadMore').on('click', function() {
		__page++
		getPublishedJobs(__page)
	})

	function getPublishedJobs(page) {
		var _page = page || 1;
		$.ajax({
			type : 'GET',
			url : apiUrl._publishedJobs,
			data : {
				page : _page
			},
			success : function(data) {
				var _jobList = data.jobList, len = _jobList.length, _result = [];

				if(len > 0) {
					_result = Quannei.extend(_jobList)
					$('#released-list-tmpl').tmpl(_result).appendTo('#list')
					$('#list').listview('refresh');
					if(data.currPage == data.pageCount) {
						$('.released-more').hide()
					}
					else {
						$('.released-more').show()
					}
				}
				else {
					$('#list').append('<li style="padding:10px 0;text-align:center;">暂未发布！</li>')
				}
			}
		})
	}
	getPublishedJobs()

	$(document).on('click', '.del-btn', function() {
		$('#J_position').html($(this).parents('li:eq(0)').find('.work').html())
		$('#J_Delete_Pop').show();
		$('#J_overlayAll_').show();
		$("#J_Delete_Job").data('jobid', $(this).data('jobid'));
	})
	$('#J_Delete_Job').on('click', function() {
		var _id = $(this).data('jobid');
		$.ajax({
			type : 'GET',
			url : apiUrl._deleteJob,
			data : {
				id : _id
			},
			success : function(data) {
				if(data.code == '0') {
					window.location.reload();
				}
			}
		})
	})

	$(document).on('click', '.ref-btn', function() {
		var _id = $(this).data('jobid');
		console.log(_id)
		$.ajax({
			type : 'GET',
			url : apiUrl._updateTime,
			data : {
				id : _id
			},
			success : function(data) {
				console.log(data)
				if(data.code == '0') {
					alert('刷新成功')
					// window.location.reload();
				}
			}
		})
	})
})
</script>
<?php $this->endBlock();  ?>