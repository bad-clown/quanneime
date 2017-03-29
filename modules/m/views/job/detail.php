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
			<h1><img src="/images/mobile/m-logo.png" width="100%" alt=""></h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageSearch" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-search ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">搜索</a>
		</div><!-- /header -->
		<div data-role="content">
			<ul data-role="listview" data-inset="true">
				<li class="publisher">
					<img class="por-icon" src="<?= $Path.$publisher['avatar'];?>">
					<h2><?= $publisher->nickname;?></h2>
					<p> <?= $publisher->company;?><?= $publisher->position;?></p>
					<p>
						<?php  if($publisher->authStatus == Dictionary::indexKeyValue('AuthStatus', 'Pass')){?>
					<a href="javascript:void(0)" class="proved">已认证</a>
					<?php }else{ ?>
					<a href="javascript:void(0)" class="unproved">未认证</a>
					<?php }?>
					</p>

				</li>
				<li>
					<h2><?= $job->company;?></h2>
					<h2><?= $job->name;?></h2>
					<p style="color:#eb6100;font-size: .85em;"><?= '￥'.$job->salary.Dictionary::indexKeyValue('SalaryType', $job->salaryType);?></p>
					<p class="jobcity"><?= $job->city?></p>
				</li>
				<li>
					<p>职位诱惑：<?= $job->attract;?></p>
				</li>
				<li class="ws-no">
					<div style="font-weight: 700;">岗位描述</div><br>
					<div class="jobText-content">
						<?php
							$str      =  $job->require;
							$order    = array( "\r\n" ,  "\n" ,  "\r" );
							$replace  =  '<br />' ;
							echo '<p id="J_require" style="white-space: normal;">'.str_replace ( $order ,  $replace ,  $str ).'</p>';
						?>
					</div>
				</li>
				<li class="ui-grid-a ui-listview-grid">
					<?php if (!(\Yii::$app->user->isGuest)) {
						if(\Yii::$app->user->identity->type){ ?>
							<div class="ui-block-a">
							<a href="#J_denied" class="ui-btn ui-corner-all">投递简历</a>
							</div>
							<div class="ui-block-b">
							<?php if($job->privateMeeting){?>
							<a href="#J_denied" class="ui-btn ui-corner-all">私人会面</a>
							</div>
							<?php }?>
						<?php } else { ?>
							<?php if(!($hasDeliver)){ ?>
							<div class="ui-block-a">
							<a href="<?= $Path.'/index.php?r=m/account/deliver-with-resume&jobId='.$job->_id;?>" class="ui-btn ui-corner-all">投递简历</a>
						</div>
							<?php }else{ ?>
							<a href="javascript:;" class="deliver-btn posted">已投递</a>
							<?php }; ?>
							<?php if($job->privateMeeting){?>
							<div class="ui-block-b">
							<a href="<?= $Path;?>/index.php?r=m/account/priv-meeting&jobId=<?= $job->_id;?>" class="ui-btn ui-corner-all">私人会面</a>
						</div>
							<?php }?>
						<?php }}else{ ?>
						<div class="ui-block-a">
							<a href="<?= $Path.'/index.php?r=m/account/deliver-with-resume&jobId='.$job->_id;?>" class="ui-btn ui-corner-all">投递简历</a>
						</div>
							<?php if($job->privateMeeting){?>
							<div class="ui-block-b">
							<a href="<?= $Path;?>/index.php?r=m/account/priv-meeting&jobId=<?= $job->_id;?>" class="ui-btn ui-corner-all">私人会面</a>
						</div>
							<?php } ?>
					<?php } ?>
				</li>
				<li>
					<h2><a href="<?= $Path.'/index.php?r=m/job/personal-gather&userId='.$publisher->_id;?>">该用户还有<strong><?= $publisher->jobCount;?></strong>个岗位</a></h2>
				</li>
			</ul>
		</div>
	</div>
</div><!-- /page -->

<!-- 修改信息 start -->
<div data-role="page" id="J_denied">
	<div class="ui-panel-wrapper">
		<div data-role="header" data-theme="b">
			<h1><img src="/images/mobile/m-logo.png" width="100%" alt=""></h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageMain" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-delete ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">关闭</a>
		</div><!-- /header -->
		<div data-role="content">

			<ul data-role="listview" data-inset="true">
				<li><p>企业用户不能投递简历，请切换或注册为求职用户</p></li>
				<li>
					<form action="<?= $Path;?>/index.php?r=user/security/change-account" method="post">
						<i class="um-icon"></i>
						<a href="javascript:void(0);"><input type="submit" value="切换账号"></a>
					</form>
				</li>
			</ul>

		</div><!-- /content -->
	</div><!-- /ui-panel-wrapper -->
</div><!-- /page -->
<!-- 修改信息 end -->

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript">
$(function() {
	$( "body>[data-role='panel']" ).panel();

	$('#J_denied').on('click', function() {

	})
})
</script>
<?php $this->endBlock();  ?>
