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
			<h1>已收简历</h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageSearch" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-search ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">搜索</a>
		</div><!-- /header -->
		<!-- <div data-role="content">
			<div class="Usercenter_Box">
				<div class="Usercenter-content clearfix">
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
		</div> -->
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
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/x-jquery-tmpl" id="resume-list-tmpl">

<li>
	<a href="<?= $Path;?>/index.php?r=m/account/comp-view-resume&delivererId=${delivererId['$id']}&jobId=${jobId['$id']}">
		<img src="<?= $Path;?>${avatar}" style="top: .75em;">
		<h2><span>${nickname}</span> 应聘</h2>
		<p>${position}</p>
		<p>${time}</p>
	</a>
</li>
拾叁舍躯

</script>
<script type="text/javascript">
$(function() {
	$( "body>[data-role='panel']" ).panel();

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

				/*if(len > 0) {
					_result = Quannei.extend(_drList)
					$('#resume-list-tmpl').tmpl(_result).appendTo('#list')
					if(data.currPage == data.pageCount) {
						$('.resumes-load').hide();
					}
					else {
						$('.resumes-load').show();
					}
				}
				else {
					$('.resumelist-list').html('<div class="__loading">目前暂无简历投递，试试发布其他岗位 <a href="'+$_Path+'/index.php?r=job/publish" title="">立即发布</a></div>')
					$('.resumes-load').hide();
				}*/

				if(len > 0) {
					_result = Quannei.extend(_drList)
					$('#resume-list-tmpl').tmpl(_result).appendTo('#list')
					$('#list').listview('refresh');
					if(data.currPage == data.pageCount) {
						$('.released-more').hide()
					}
					else {
						$('.released-more').show()
					}
				}
				else {
					$('#list').append('<li style="padding:10px 0;text-align:center;">目前暂无简历投递，试试发布其他岗位 <a href="'+$_Path+'/index.php?r=job/publish" title="">立即发布</a></li>')
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
