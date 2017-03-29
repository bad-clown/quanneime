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

<div data-role="page" class="jqm-demos" data-quicklinks="true" id="pageMain">
	<div class="ui-panel-wrapper">
		<div data-role="header" data-theme="b">
			<h1><img src="/images/mobile/m-logo.png" width="100%" alt=""></h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageSearch" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-search ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">搜索</a>
		</div><!-- /header -->
		<div role="main" class="ui-content jqm-content jqm-fullwidth">
			<img src="/images/mobile/home-jpg.jpg" width="100%" alt="">

			<div data-role="navbar" data-iconpos="right" class="ui-alt-icon ui-nodisc-icon">
				<ul>
					<li>
						<a href="#pageComp" data-icon="carat-d">公司类型</a>
					</li>
					<li>
						<a href="#pagePost" data-icon="carat-d">岗位类型</a>
					</li>
				</ul>
			</div><!-- /navbar -->
			<div data-role="content">
				<div style="display: none;">
					<span id="jobTotal"></span>
					个机会
				</div>
				<ul id="jobList" data-role="listview" data-inset="true"></ul>
				<div id="moreBox" class="moreBox">
					<a id="moreBtn" href="javascript:;" title="加载更多">加载更多</a>
				</div>
			</div><!-- /content -->
		</div>
	</div>
</div><!-- /page -->

<div data-role="page" id="pageComp">
	<div class="ui-panel-wrapper">
		<div data-role="header" data-theme="b">
			<h1>公司类型</h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageMain" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-delete ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">关闭</a>
		</div><!-- /header -->
		<div data-role="content">
			<form>
				<fieldset data-role="controlgroup" data-iconpos="right" id="comTypeList">
					<input name="radio-choice-w" id="radio-choice-w-0" value="0" checked="checked" type="radio" data-type="">
					<label for="radio-choice-w-0">全部类型</label>
					<?php ksort($comTypeList);foreach($comTypeList as $key => $value) { ?>
					<input name="radio-choice-w" id="radio-choice-w-<?=$key?>" value="" type="radio" data-type="<?=$key?>">
					<label for="radio-choice-w-<?=$key?>"><?=$value?></label>
					<?php } ?>
				</fieldset>
			</form>
		</div><!-- /content -->
	</div><!-- /ui-panel-wrapper -->
</div><!-- /page -->

<div data-role="page" id="pagePost">
	<div class="ui-panel-wrapper">
		<div data-role="header" data-theme="b">
			<h1>岗位类型类型</h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageMain" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-delete ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">关闭</a>
		</div><!-- /header -->
		<div data-role="content">
			<form>
				<fieldset data-role="controlgroup" data-iconpos="right" id="positionTypeList">
					<input name="radio-choice-w-2" id="radio-choice-w-2-0" value="0" checked="checked" type="radio" data-type="">
					<label for="radio-choice-w-2-0">全部类型</label>
					<?php ksort($positionTypeList);foreach($positionTypeList as $key => $value) { ?>
					<input name="radio-choice-w-2" id="radio-choice-w-2-<?=$key?>" value="" type="radio" data-type="<?=$key?>">
					<label for="radio-choice-w-2-<?=$key?>"><?=$value?></label>
					<?php } ?>
				</fieldset>
			</form>
		</div><!-- /content -->
	</div><!-- /ui-panel-wrapper -->
</div><!-- /page -->

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/x-jquery-tmpl" id="job-list-tmpl">
<li>
	<a href="<?= $Path; ?>/index.php?r=m/job/detail&id=${_id['$id']}" data-ajax="false">
		<img src="<?= $Path;?>${publisherAvatar}" class="ptimg">
		<h2>${name}</h2>
		<p>￥${salary}${salaryType}</p>
	</a>
</li>
</script>
<script type="text/javascript">
$(function() {
	$( "body>[data-role='panel']" ).panel();
	$.mobile.changePage("#pageMain", "slideup");

	var finished = false,
		bool = false,
		pageCount = 1,
		type = [];

	$('#moreBtn').on("click", function(event) {
		event.stopPropagation();
		if (finished) {
			bool = false;
			finished = false;
			pageCount++
			GetAjaxData(type, pageCount);
		}
	});

	function GetAjaxData(_type, _page) {
		$.ajax({
			type: 'get',
			url: apiUrl._search,
			data: {
				comType : _type[0],
				positionType : _type[1],
				page: _page
			},
			dataType: 'json',
			beforeSend: function() {
				showLoader();
			},
			complete: function(XMLHttpRequest, textStatus) {
				hideLoader();
				finished = true;
			},
			success: function(data) {
				var _jobList = data.jobList,
					len = _jobList.length,
					_result = [];

				if (len > 0 && _page <= data.pageCount) {
					if(bool) {
						$('#jobList').empty();
					}
					_result = Quannei.extend(_jobList)
					$("#jobTotal").html(data.totalJobCount)
					$('#job-list-tmpl').tmpl(_result).appendTo('#jobList');
					$('#jobList').listview('refresh');
				}
				else {
					$('#jobList').html('<li style="text-align:center;font-size:18px;color:#4d586e;padding:1em 0;">未查找到相应岗位</li>')
				}

				if(_page < data.pageCount) {
					$('#moreBox').show();
				} else {
					$('#moreBox').hide();
				}

				//console.log(type)
			}
		})
	}
	GetAjaxData(type, pageCount);

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

	$('#comTypeList').find('input').on('click', function() {
		bool = true;
		type = [$(this).data('type'), ''];
		pageCount = 1;

		GetAjaxData(type, pageCount);

		$.mobile.changePage("#pageMain", "slideup");
	})

	$('#positionTypeList').find('input').on('click', function() {
		bool = true;
		type = ['', $(this).data('type')];
		pageCount = 1;

		GetAjaxData(type, pageCount);

		$.mobile.changePage("#pageMain", "slideup");
	})

})
</script>
<?php $this->endBlock();  ?>
