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
			<div class="gather">
				<div class="companyName">
					<div class="clogo"><img src="<?= $Path.$company->logo;?>" /></div>
			  		<div class="cname"><b><?= $company->name;?></b></div>
					<div class="cdesc"><?= $company->description;?></div>
				</div>
				<div class="companyJobList">
					<ul id="companyJobListUl"></ul>
					<div class="companyJobListMore"><a href="javascript:;" id="J_loadList">更多</a></div>
				</div>
				<div class="companyUserBox">
					<div class="companyUserCount">
						<strong>认证员工</strong>
						<span>有 <i id="J_pubCount">0</i> 个员工正在发布岗位</span>
					</div>
					<div class="companyUserList" id="J_verify_personal"></div>
				</div>
				<div class="joblist-gather-companyinfo">
					<div class="company-info-title clearfix">
						<strong>企业信息</strong>
					</div>
					<div class="company-info-box">
						<div class="company-intro clearfix">
							<strong>公司简介</strong>
							<p><?= $company->detail;?></p>
						</div>
						<div class="company-adds clearfix">
							<strong>公司位置</strong>
							<p><?= $company->location;?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /page -->

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/x-jquery-tmpl" id="job-list-tmpl">
<li>
	<a href="<?= $Path; ?>/index.php?r=m/job/detail&id=${_id['$id']}" class="clearfix">
		<div class="g-left">
			<h2>${name}</h2>
			<p>￥${salary}${salaryType}</p>
		</div>
		<div class="g-right">${showTime}</div>
	</a>
</li>
</script>
<script type="text/x-jquery-tmpl" id="verify-list-tmpl">
{{if authStatus == <?= Dictionary::indexKeyValue('AuthStatus', 'Pass');?> }}
<div class="personal-title-box clearfix">
	<div class="personal-icon"><img src="<?= $Path;?>${avatar}" width="100%" alt=""></div>
	<div class="personal-name">
		<strong>${nickname}</strong>
		<span>${position}</span>
		<div class="verify">
			{{if authStatus == <?= Dictionary::indexKeyValue('AuthStatus', 'Pass');?> }}
			<a href="javascript:void(0);" class="verified">已认证</a>
			{{/if}}
		</div>
	</div>
	<div class="work-count">
		<strong><i>${jobCount}</i> 个</strong>
		<span>岗位虚位以待</span>
	</div>
</div>
{{/if}}
</script>
<script type="text/javascript">
$(function() {
	$( "body>[data-role='panel']" ).panel();
	var _page = 1;

	function getData() {
		$.ajax({
			type : 'get',
			url : apiUrl._companyJob,
			data : {
				id : '<?= $company->_id;?>',
				page : _page
			},
			dataType : 'json',
			cache : false,
			beforeSend: function() {
				showLoader();
			},
			complete: function() {
				hideLoader();
			},
			success : function(data) {
				var _jobList = data.jobList, len = _jobList.length, _result = [];

				if(len > 0) {
					_result = Quannei.extend(_jobList)
					$('#job-list-tmpl').tmpl(_result).appendTo('#companyJobListUl')
					if(data.currPage == data.pageCount) {
						$('.companyJobListMore').hide();
					}
					else {
						$('.companyJobListMore').show();
					}
				}
			}
		})
	}
	getData()

	$('#J_loadList').on('click', function() {
		_page++
		getData()
	})

	var cnt = cnt || 0;
	var params = {
		type : "GET",
		url : apiUrl._authPublishers,
		data : {
			id : '<?= $company->_id;?>',
			cnt : 0
		},
		dataType : 'json',
		cache : false,
		callback : function(data) {
			var _publishers = data.publishers;
			$('#J_pubCount').html(data.pubCount);
			if(_publishers != "") {
				$('#J_pubCount').html();
				$('#J_verify_personal').empty();
				$('#verify-list-tmpl').tmpl(_publishers).appendTo('#J_verify_personal');
			}
		}
	}
	apiUrl.getJSON(params);


	function showLoader() {
		//显示加载器
		$.mobile.loading('show', {
			text: '加载中...', //加载器中显示的文字
			textVisible: true, //是否显示文字
			theme: 'a', //加载器主题样式a-e
			textonly: false, //是否只显示文字
			html: "" //要显示的html内容，如图片等，默认使用Theme里的ajaxLoad图片
		});
	}

	function hideLoader() {
		//隐藏加载器
		$.mobile.loading('hide');
	}
})
</script>
<?php $this->endBlock();  ?>