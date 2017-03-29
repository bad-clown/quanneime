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
	<div class="search-resultall-bg">
		<!-- 内容 start -->
		<div class="main">
			<div class="searchResult-box">
				<div class="search-title clearfix">
					<input type="text" class="search-text" id="J_searchText" name="" value="" placeholder="输入职位或者公司名称">
					<a href="javascript:void(0)"  class="search-btn" id="J_sBtn" title="搜索"></a>
					<div class="search-msg" id="J_searchCount"></div>
				</div>
				<div class="resultWork-box">
					<ul class="resultWork-lists-tit" style="display:none;">
						<li>
							<span class="post">职位</span>
							<span class="comp">单位</span>
							<span class="sala">薪资</span>
							<span class="time">时间</span>
						</li>
					</ul>
					<ul class="resultWork-lists" style="display:none;"></ul>
					<div class="resultWork-more" style="display:none;">
						<a href="javascript:void(0)" class="more-btn" id="J_loadmore">加载更多</a>
					</div>
				</div>
			</div>
		</div>
		<!-- 内容 end -->
	</div>
<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/x-jquery-tmpl" id="job-list-tmpl">
<li>
	<a href="<?= $Path; ?>/index.php?r=job/detail&id=${_id['$id']}" class="work-link" target="_blank">
		<span class="post">${name}</span>
		<span class="comp">${company}</span>
		<span class="sala">￥${salary}${salaryType}</span>
		<span class="time">${showTime}</span>
	</a>
</li>
</script>
<script type="text/javascript">
$(function() {
	function getHash(name){
		var url = window.location.href;
		if(url.indexOf("?")==-1 || url.indexOf(name+'=')==-1){
			return "";
		}
		else {
			function getQueryStringRegExp(name) {
				var reg = new RegExp("(^|\\?|&)"+ name +"=([^&]*)(\\s|&|$)", "i");

				if (reg.test(window.location.href)) {
					return decodeURIComponent(RegExp.$2.replace(/\+/g, " "))
				}

				return "";
			};
			return getQueryStringRegExp(name);
		}
	}
	var hash = getHash("searchTxt"), page = 1;
	if(hash) {
		if(hash == '__All') {
			$('#J_searchText').val("");
		}
		else {
			$('#J_searchText').val(hash);
		}
		searchResult()
	}

	$('#J_sBtn').on('click', function() {
		searchResult()
		$('#J_searchText').focus();
	})

	$('#J_searchText').on('keypress', function(e) {
		if(e.keyCode == '13') {
			searchResult()
		}
	})
	$('#J_loadmore').on('click', function() {
		page++
		searchResult(page, true)
	})

	function searchResult(n, b) {
		var _n = n || 1,
			_b = b || false,
			_key = $('#J_searchText').val();
		if(_key == '' || _key == null) return;

		var param = {
			type : 'GET',
			url : apiUrl._search,
			dataType : 'json',
			data : {
				key : _key,
				page : _n
			},
			callback : function(data) {
				$('#J_searchCount').html('一共有<span>'+data.totalJobCount+'</span>条符合“<span>'+data.key+'</span>”的搜索结果')
				var _jobList = data.jobList, len = _jobList.length, _result = [];

				if(len > 0) {
					_result = Quannei.extend(_jobList)
					$('.resultWork-lists-tit').show();
					$('.resultWork-lists').show();
					if(!_b) {
						$('.resultWork-lists').empty();
					}
					$('#job-list-tmpl').tmpl(_result).appendTo('.resultWork-lists')
					if(data.currPage == data.pageCount) {
						$('.resultWork-more').hide();
					}
					else {
						$('.resultWork-more').show();
					}
				}
				else {
					$('.resultWork-lists-tit').hide();
					$('.resultWork-lists').empty();
				}
			}
		}
		apiUrl.getJSON(param)
	}
})
</script>
<?php $this->endBlock();  ?>
