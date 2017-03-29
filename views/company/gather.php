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
		<div class="gather-box">
			<div class="company-title-box clearfix">
				<script type="text/javascript">var $__id = '<?= $company->_id;?>';</script>
				<div class="company-icon"><img src="<?= $Path.$company->logo;?>" height="77" width="202" alt=""></div>
				<div class="company-name">
			  		<strong><?= $company->name;?></strong>
					<span><?= $company->description;?></span>
				</div>
				<div class="work-count">
					<strong><i><?= $company->jobCount;?></i> 个</strong>
					<span>岗位虚位以待</span>
				</div>
			</div>
			<div class="gwork-box">
				<ul class="gwork-lists-tit" style="display:none;">
					<li>
						<span class="post">职位</span>
						<span class="comp">单位</span>
						<span class="sala">薪资</span>
						<span class="time">时间</span>
					</li>
				</ul>
				<ul class="gwork-lists" style="display:none;"></ul>
				<div class="gwork-more" style="display:none;">
					<a href="javascript:void(0);" id="J_Gather_More" class="more-btn">加载更多</a>
				</div>
			</div>
			<div class="gverify-box">
				<div class="gverify-title clearfix">
					<strong>认证员工</strong>
					<span>有 <i id="J_pubCount">0</i> 个员工正在发布热招岗位！</span>
					<a href="javascript:void(0);" id="J_verifyall">+</a>
				</div>
				<div id="J_verify_personal">
				</div>
			</div>
			<div class="joblist-gather-companyinfo">
				<div class="company-info-title clearfix">
					<strong>企业信息</strong>
					<!-- <a href="#">+</a> -->
				</div>
				<div class="company-info-box">
					<div class="company-intro clearfix">
						<strong>公司简介</strong>
						<p><?= $company->detail;?></p>
					</div>
					<div class="company-adds clearfix">
						<strong>公司位置</strong>
						<p><?= $company->location;?> <a href="javascript:;" id="J_map_open">查看地图</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 内容 end -->
	<div id="mapBox" class="popup">
		<div id="J_map" class="map-popup">
			<div id="allmap"></div>
		</div>
		<a href="javascript:;" title="关闭" class="close_ditu J_close_pop"></a>
	</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=jN5FNEVlG0i3gdNVoNupoaLu"></script>
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
<script type="text/x-jquery-tmpl" id="verify-list-tmpl">
{{if authStatus == <?= Dictionary::indexKeyValue('AuthStatus', 'Pass');?> }}
<div class="personal-title-box clearfix">
	<div class="personal-icon"><img src="<?= $Path;?>${avatar}" height="77" width="77" alt=""></div>
	<div class="personal-name">
		<strong>${nickname}</strong>
		<span>${position}</span>
		<div class="verify">
			{{if authStatus == <?= Dictionary::indexKeyValue('AuthStatus', 'Pass');?> }}
			<a href="javascript:void(0);" class="verified" title="已官方认证">已认证</a>
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
	Quannei.gather._Init($__id);

	var iWidth = $(window).innerWidth()*0.8;
	var iHeight = $(window).innerHeight()*0.8;
	$('#J_map').css({
		'width' : iWidth,
		'height' : iHeight,
		'margin-left' : -iWidth/2,
		'margin-top' : -iHeight/2
	})
	$('#J_map_open').on('click', function() {
		$('#J_overlayAll_').show();
		$('#mapBox').show();
		Quannei.common._map("<?= $company->location;?>")
	})
})
</script>
<?php $this->endBlock();  ?>
