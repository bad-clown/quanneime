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
				<div class="Usercenter-resumelist Usercenter-right">
					<div class="resumelist-content">
						<ul class="resumelist-list clearfix">
						</ul>
						<div class="resumes-load" style="display: none;">
							<a href="javascript:;" class="resumes-loadbtn" id="J_load_re">加载更多</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 内容 end -->

<!-- 确认删除 start -->
<div class="resume-del-pop popup" id="J-resume-del-pop">
	<div class="del-msg">确定要将“<span id="J-resume-name"></span>”投递的简历删除？</div>
	<div class="del-btn">
		<a href="javascript:;" class="dbtn1" id="J_Delete_Resume">删除</a>
		<a href="javascript:;" class="dbtn2 J_close_pop">取消</a>
	</div>
	<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
</div>
<!-- 确认删除 end -->

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/x-jquery-tmpl" id="resume-list-tmpl">
<li>
	{{if status == <?= DictionaryLogic::indexKeyValue('DeleveredResumeStatus', 'Delivered');?>}}
	<div class="newtips">新</div>
	{{/if}}
	<a href="<?= $Path;?>/index.php?r=account/comp-view-resume&delivererId=${delivererId['$id']}&jobId=${jobId['$id']}" class="resumelist-target">
		<img class="resumelist-target-img" src="<?= $Path;?>${avatar}" height="55" width="55" alt="">
		<p class="resumelist-target-name">${nickname}</p>
		<p class="resumelist-target-msg">简历</p>
	</a>
	<div class="resumelist-career clearfix">
		<p class="career">${position}</p>
		<span class="time">${time}</span>
	</div>
	<a href="javascript:;" data-jobid="${_id['$id']}" class="resume-delbtn">删除</a>
</li>
</script>
<script type="text/javascript">
$(function() {
	$('#J_ListNav').addClass('menu-cur')

	$(document).on('mouseenter', '.resumelist-list > li', function() {
		$(this).addClass('cur').find('.resume-delbtn').show();
	})
	$(document).on('mouseleave', '.resumelist-list > li', function() {
		$(this).removeClass('cur').find('.resume-delbtn').hide();
	})

	$(document).on('click', '.resume-delbtn', function() {
		$(".g-delete-pop").show();
		$(".g-overlay").show();
	})

	$(document).on('click', '.resume-delbtn', function() {
		$('#J-resume-name').html($(this).parents('li:eq(0)').find('.resumelist-target-name').html())
		$('#J-resume-del-pop').show()
		$('#J_overlayAll_').show();
		$("#J_Delete_Resume").data('jobid', $(this).data('jobid'));
	})
	$('#J_Delete_Resume').on('click', function() {
		var _id = $(this).data('jobid');
		$.ajax({
			type : 'GET',
			url : '<?= $Path;?>/index.php?r=account/comp-delete-resume',
			data : {
				id : _id
			},
			success : function(data) {
				if(data.code == '0') {
					$('#J-resume-del-pop').hide()
					$('#J_overlayAll_').hide();
					window.location.reload();
				}
			}
		})
	})



	function getResumeList(n) {
		var _n = n || 1;
		var param = {
			type : 'GET',
			url : apiUrl._receivedResume,
			data : {
				page : _n
			},
			callback : function(data) {
				//console.log(data)
				var _drList = data.drList, len = _drList.length, _result = [];

				if(len > 0) {
					_result = Quannei.extend(_drList)
					$('#resume-list-tmpl').tmpl(_result).appendTo('.resumelist-list')
					if(data.currPage == data.pageCount) {
						$('.resumes-load').hide();
					}
					else {
						$('.resumes-load').show();
					}
				}
				else {
					$('.resumelist-list').html('<div class="__loading">目前暂无简历投递，试试发布其他岗位 <a href="'+$_Path+'/index.php?r=job/publish" title="" style="color:#1ba8ed;">立即发布</a></div>')
					$('.resumes-load').hide();
				}
			}
		}
		apiUrl.getJSON(param)
	}
	var _repage = 1;
	$('#J_load_re').on('click', function() {
		_repage++;

		getResumeList(_repage)
	})
	getResumeList()
})
</script>
<?php $this->endBlock();  ?>