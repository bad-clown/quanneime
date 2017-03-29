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
		<div class="companyRecruit-box clearfix">
			<div class="companyRecruit-left">
				<div class="companyJob-title">
					<div class="jobName"><?= $job->name;?></div>
					<div class="jobCompany"><?= $job->company;?></div>
					<div class="jobSalary">
						<div class="salary"><?= '￥'.$job->salary.Dictionary::indexKeyValue('SalaryType', $job->salaryType);?></div>
						<!-- <div class="location"><?= $job->city?></div> -->
					</div>
					<div class="jobPay">
						<p id="J_attract">职位诱惑：<?= $job->attract;?></p>

						<?php if (!(\Yii::$app->user->isGuest)) {
							if(\Yii::$app->user->identity->_id == $publisher->_id) {
						?>
						<a href="javascript:;" class="control_edit" data-attr="attract">编辑</a>
						<?php }
						}?>
					</div>
				</div>
				<div class="companyJob-description">
					<div class="jobText-tit clearfix">
						<div class="tit"><i></i>职位描述：</div>
						<div class="pv">
							<div class="location"><?= $job->city?></div>
						</div>
					</div>
					<div class="jobText-content">
						<?php
							$str      =  $job->require;
							$order    = array( "\r\n" ,  "\n" ,  "\r" );
							$replace  =  '<br />' ;
							echo '<p id="J_require">'.str_replace ( $order ,  $replace ,  $str ).'</p>';
						?>
					</div>

					<?php if (!(\Yii::$app->user->isGuest)) {
							if(\Yii::$app->user->identity->_id == $publisher->_id) {
						?>
						<a href="javascript:;" class="control_edit" data-attr="require">编辑</a>
						<?php }
						}?>
				</div>
				<div class="companyJob-time">
					<!-- <strong>发布于</strong>&nbsp;<span><?= date('Y年m月d日', $job->time);?></span> -->
				</div>
				<div class="companyJob-join">
				<?php if (!(\Yii::$app->user->isGuest)) {
					if(\Yii::$app->user->identity->type){ ?>
						<a href="javascript:;" class="deliver-btn J_dialog" data-dialog="J_denied">申请职位</a>
						<?php if($job->privateMeeting){?>
						<a href="javascript:;" class="meet-btn J_dialog" data-dialog="J_denied">私人会面</a>
						<?php }?>
					<?php } else { ?>
						<?php if(!($hasDeliver)){ ?>
						<a href="<?= $Path.'/index.php?r=account/deliver-with-resume&jobId='.$job->_id;?>" class="deliver-btn">申请职位</a>
						<?php }else{ ?>
						<a href="javascript:;" class="deliver-btn posted">已申请</a>
						<?php }; ?>
						<?php if($job->privateMeeting){?>
						<a href="<?= $Path;?>/index.php?r=account/priv-meeting&jobId=<?= $job->_id;?>" class="meet-btn">私人会面</a>
						<?php }?>
					<?php }}else{ ?>
						<a href="<?= $Path.'/index.php?r=account/deliver-with-resume&jobId='.$job->_id;?>" class="deliver-btn">申请职位</a>
						<?php if($job->privateMeeting){?>
						<a href="<?= $Path;?>/index.php?r=account/priv-meeting&jobId=<?= $job->_id;?>" class="meet-btn">私人会面</a>
						<?php } ?>
				<?php } ?>
				</div>
				<div class="share-box">
					<a href="javascript:;" class="weixin-icon" id="weixin_hover"></a>
					<div class="codeBox" id="codeBox">
						<div class="codeImg" id="codeImg"></div>
						<div class="codeMsg">
							<strong>把职位分享给朋友</strong>
							<p>打开微信“扫一扫”，打开网页后点击屏幕右上方分享按钮。</p>
						</div>
					</div>
				</div>
			</div>
			<div class="companyRecruit-right">
				<div class="companyCade-image">
					<img src="<?= $Path.$publisher['avatar'];?>" height="88" width="88" alt="">
				</div>
				<div class="companyCade-name"><?= $publisher->nickname;?></div>
				<div class="companyCade-jobname"><?= $publisher->company;?><?= $publisher->position;?></div>
				<div class="companyCade-proving">
					<?php if (!(\Yii::$app->user->isGuest)) {
						if(\Yii::$app->user->identity->_id == $publisher->_id) {
					?>
						<?php  if($publisher->authStatus == Dictionary::indexKeyValue('AuthStatus', 'Pass')){?>
						<a href="<?= $Path.'/index.php?r=account/comp-auth'?>" class="proved" title="已官方认证">已认证</a>
						<?php }else{ ?>
						<a href="<?= $Path.'/index.php?r=account/comp-auth'?>" title="">未认证</a>
						<?php }?>
					<?php }else{ ?>
						<?php  if($publisher->authStatus == Dictionary::indexKeyValue('AuthStatus', 'Pass')){?>
						<a href="javascript:void(0)" class="proved" title="已官方认证">已认证</a>
						<?php }else{ ?>
						<a href="javascript:void(0)" class="unproved" title="">未认证</a>
						<?php }?>
					<?php }} else{ ?>
						<?php  if($publisher->authStatus == Dictionary::indexKeyValue('AuthStatus', 'Pass')){?>
						<a href="javascript:void(0)" class="proved" title="已官方认证">已认证</a>
						<?php }else{ ?>
						<a href="javascript:void(0)" class="unproved" title="">未认证</a>
						<?php }?>
					<?php }?>
				</div>
				<div class="companyCade-tips">更多 <a href="<?= $Path.'/index.php?r=job/personal-gather&userId='.$publisher->_id;?>" target="_blank">还有<strong><?= $publisher->jobCount;?></strong> 个岗位</a></div>
				<div class="companyCade-map">
					<div class="map-tit clearfix"><span>地点</span> <p id="J_location"><?= $job->location;?></p></div>
					<div class="map-btn"><a href="javascript:;" id="J_map_open">地图</a></div>

					<?php if (!(\Yii::$app->user->isGuest)) {
							if(\Yii::$app->user->identity->_id == $publisher->_id) {
						?>
						<a href="javascript:;" class="control_edit" data-attr="location">编辑</a>
						<?php }
						}?>
				</div>
				<div class="companyCade-intro clearfix">
					<strong>简介</strong>
					<p id="J_compDesc"><?php if(mb_strlen($publisher->compDesc,'utf-8')>200){echo mb_substr($publisher->compDesc,0, 200,'utf-8').'...';}else{echo mb_substr($publisher->compDesc,0, 200,'utf-8');};?></p>

					<?php if (!(\Yii::$app->user->isGuest)) {
						if(\Yii::$app->user->identity->_id == $publisher->_id) {
					?>
					<a href="javascript:;" class="control_edit" data-attr="compDesc">编辑</a>
					<?php }} ?>
				</div>
			</div>
		</div>
		<div class="ensure">
			<ul>
				<li class="zhenshi">
					<strong>真实</strong>
					<p>严格的认证审核过程确保每条招聘信息真实有效，搭建起招聘方与求职方的沟通桥梁。</p>
				</li>
				<li class="jishi">
					<strong>及时</strong>
					<p>只要进行简历投递行为，系统立刻自动触发短信通知功能，告知招聘方及时查阅简历。</p>
				</li>
				<li class="shishi">
					<strong>实时</strong>
					<p>每个使用者均可在简历选项里实时了解简历被查询的状态，以提升招聘和投递效率。</p>
				</li>
			</ul>
		</div>
	</div>
	<!-- 内容 end -->

<!-- 编辑 start -->
<div class="edit-text-pop popup">
	<div class="edit-title">
		<i></i>您正在编辑 <span id="J_updata_title">岗位描述</span>
	</div>
	<div class="edit-content">
		<textarea name="" id="J_updata_attr"></textarea>
	</div>
	<a href="javascript:void(0)" id="J_edit_submit" data-attr="" class="edit-submit">保存</a>
	<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
</div>
<!-- 编辑 end -->

<div class="publish-suc popup" id="J_publish_suc">
	<div class="suc-msg">恭喜，您的信息已经发布成功！</div>
	<div class="suc-tip">3秒后该层渐出消失,或者用户自己点击关闭</div>
	<a href="javascript:void(0)" title="关闭" class="pop-close" id="J_close_tips"></a>
</div>

<div id="mapBox" class="popup">
	<div id="J_map" class="map-popup">
		<div id="allmap"></div>
	</div>
	<a href="javascript:;" title="关闭" class="close_ditu J_close_pop"></a>
</div>

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.qrcode.min.js"></script>
<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=jN5FNEVlG0i3gdNVoNupoaLu"></script>
<script type="text/javascript">
$(function() {
	document.title = '<?= $job->company;?> 招聘 <?= $job->name;?> <?= $job->city?> 圈内觅-发现地产圈职业机会';
	$('#J_close_tips').on('click', function() {
		$('#J_publish_suc').slideUp('fast')
	})
	setTimeout(function() {
		$('#J_publish_suc').slideUp('fast')
	}, 3000)

	$('.control_edit').on('click', function() {
		var title = {
			attract : '职位诱惑',
			require : '岗位描述',
			location : '办公地点',
			compDesc : '公司简介'
		}
		var attr = $(this).data('attr');
		var val = $('#J_'+attr).html();

		val = val.replace(/\<br\>/g, "\r\n")

		$('#J_updata_title').html(title[attr]);
		$('#J_updata_attr').val(val)

		$('#J_edit_submit').data('attr', attr)
		$('.edit-text-pop').show()
		$('._overlayAll_').show()
	})
	$('#J_edit_submit').on('click', function() {
		var attr = $(this).data('attr'),
			val = $('#J_updata_attr').val(),
			data = {}, url = '';
		if(attr == 'compDesc') {
			url = apiUrl._updateCompDesc
			data['compDesc'] = val;
		}
		else {
			url = apiUrl._updataAttr
			data['id'] = '<?= $job->_id;?>';
			data[attr] = val;
		}

		var param = {
			type : 'GET',
			url : url,
			data : data,
			callback : function(data) {
				if(data.code == '0') {
					window.location.reload();
				}
			}
		}
		apiUrl.getJSON(param)
	})

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
		Quannei.common._map("<?= $job->location;?>", "<?= $job->city;?>")
	})

	$("#codeImg").qrcode({
		render:"table",
		width:74,
		height:74,
		correctLevel:0,
		text:"<?= $Path.'/index.php?r=job/detail&id='.$job->_id;?>"
	});
	$('#weixin_hover').hover(function() {
		$('#codeBox').show()
	}, function() {
		$('#codeBox').hide()
	})
})
</script>
<?php $this->endBlock();  ?>
