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
				<div class="Usercenter-released Usercenter-right">
					<ul class="released-tit">
						<li>
							<div class="work">岗位</div>
							<div class="time">时间</div>
							<div class="pass">状态</div>
							<div class="edit">操作</div>
						</li>
					</ul>
					<ul class="released-lists clearfix"></ul>
					<div class="released-more" style="display:none;">
						<a href="javascript:void(0)" class="more-btn" id="J_LoadMore">加载更多</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 内容 end -->

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
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/x-jquery-tmpl" id="released-list-tmpl">
<li>
	<div class="work"><a href="<?= $Path; ?>/index.php?r=job/detail&id=${_id['$id']}" target="_blank" class="_position">${name}</a></div>
	<div class="time">${showTime}</div>
	<div class="pass">
		{{if status==0}}
		<div class="passed">显示中</div>
		{{/if}}
		{{if status==1}}
		<div class="passnot">
			<span class="passnot-tips-font">未通过</span>
			<i class="passnot-tips-icon"></i>
			<p class="passnot-tips-cont">${rejectMsg}<i class="arrows-left"></i></p>
		</div>
		{{/if}}
		{{if status==2}}
		<div class="passin">审核中</div>
		{{/if}}
	</div>
	<div class="edit">
		<a href="javascript:void(0);" data-jobid="${_id['$id']}" class="del-btn">删除</a>
		<a href="javascript:void(0);" data-jobid="${_id['$id']}" class="ref-btn">刷新</a>
		<a href="<?= $Path; ?>/index.php?r=job/update&id=${_id['$id']}" target="_blank" class="edt-btn">编辑</a>
	</div>
</li>
</script>
<script>
$(function() {
	var __page = 1;
	$('#J_PublishNav').addClass('menu-cur')
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
					$('#released-list-tmpl').tmpl(_result).appendTo('.released-lists')
					if(data.currPage == data.pageCount) {
						$('.released-more').hide()
					}
					else {
						$('.released-more').show()
					}
				}
				else {
					$('.released-lists').html('<div class="__loading">目前暂无发布岗位，试试发布其他岗位 <a href="'+$_Path+'/index.php?r=job/publish" title="" style="color:#1ba8ed;">立即发布</a></div>')
					$('.released-tit').hide();
					$('.released-more').hide();
				}
			}
		})
	}
	getPublishedJobs()

	$(document).on('click', '.del-btn', function() {
		$('#J_position').html($(this).parents('li:eq(0)').find('.work>a').html())
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
