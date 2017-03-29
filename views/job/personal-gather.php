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
			<div class="personal-title-box clearfix">
				<script type="text/javascript">var $__id = '<?= $publisher->_id;?>';</script>
				<div class="personal-icon"><img src="<?= $Path.$publisher->avatar;?>" height="77" width="77" alt=""></div>
				<div class="personal-name">
					<strong><?= $publisher->nickname;?></strong>
					<span><?= $publisher->company;?><?= $publisher->position;?></span>
					<div class="verify">
						<?php if($publisher->authStatus == Dictionary::indexKeyValue('AuthStatus', 'Pass')){?>
						<a href="javascript:;" class="verified" title="已官方认证">已认证</a>
						<?php }else{ ?>
						<a href="javascript:;" class="unverified">未认证</a>
						<?php }?>
					</div>
				</div>
				<div class="work-count">
					<strong><i><?= $publisher->jobCount;?></i> 个</strong>
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
					<a href="javascript:void(0);" class="more-btn" id="J_Gather_More">加载更多</a>
				</div>
			</div>
		</div>
	</div>
	<!-- 内容 end -->
<?php $this->beginBlock("bottomcode");  ?>
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
	var __page = 1, __data = {userId:$__id, page: __page}
	$('#J_Gather_More').on('click', function() {
		__page++;
		__data = {userId:$__id, page: __page}
		Quannei.gather._GetGatherJob(apiUrl._personalJob, __data)
	})
	Quannei.gather._GetGatherJob(apiUrl._personalJob, __data);
})
</script>
<?php $this->endBlock();  ?>
