<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\admin\models\Dictionary;
use yii\bootstrap\BootstrapPluginAsset;
use app\modules\admin\logic\DictionaryLogic;
//$Path = DictionaryLogic::indexKeyValue('App', 'Host', false);
$Path = \Yii::$app->request->hostInfo;
if($this->context->module->id=='user'){
    BootstrapPluginAsset::register($this);
}
/*if(!ereg('/https/', $Path)) {
	$Path = preg_replace('/http/', 'https', $Path);
}*/
$language = \Yii::$app->language;

?><?php $this->beginPage() ?><!DOCTYPE html>
<head>
<meta charset="<?= Yii::$app->charset ?>"/>
<link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="renderer" content="webkit">
<meta name="description" content="圈内觅是精准、有效的地产圈职业机会平台，这里更是地产圈职业机会内部推荐的平台，我们帮助您招到更好的人，让求职者发现更好的职业机会，Quannei.me"/>
<?= Html::csrfMetaTags() ?>
<title><?= Dictionary::indexKeyValue("App","SystemName") ?><?= (isset($this->context->title) && (!empty($this->context->title)))?"-".$this->context->title:"" ?></title>
<?php
$this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/css/common/common.css',]);
$this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/css/page/main.css',]);
$this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/css/page/popup.css',]);
?>
<script>var $_Path="<?= $Path?>",$_Time=<?= time();?>,_hmt = _hmt || [];(function() {var hm = document.createElement("script");hm.src = "//hm.baidu.com/hm.js?c28ec36b094b3f633a9a82dc4ffcc28d";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(hm, s);})();</script>
<?php $this->head() ?>
</head>
<body  data-baseurl="<?=Url::to(["/"])?>">

<?php $this->beginBody() ?>

<?php if (isset($this->blocks['topcode'])): ?>
<?= $this->blocks['topcode'] ?>
<?php  endif; ?>

<div class="web-container wrapper clearfix">
	<div class="_overlayAll_" id="J_overlayAll_"></div>
	<!-- <div class="user-login-pop popup" id="J_Login_pop">
		<div class="user-login-left">
			<div class="login-panel">
				<span class="login-user">登录账号</span>
				<input class="user-text pop-input" type="text" id="login-username" name="login-form[login]" value="" placeholder="用户名/邮箱/手机">	
				<div class="help-block"></div>
			</div>
			<div class="login-panel">
				<span class="login-pwd">账号密码</span>
				<input class="pwd-text pop-input" type="password" id="login-password" name="login-form[password]" value="" placeholder="输入密码">	
				<div class="help-block"></div>
			</div>
			<div class="login-btn-box">
				<button type="submit" class="login-btn" id="J_UserLogin_">登录</button>
			</div>
		</div>
		<div class="user-login-right">
			<div>
				<a href="<?= $Path;?>/index.php?r=user/recovery/reset">密码找回 ？</a>
			</div>
			<div>
				<a href="<?= $Path;?>/index.php?r=user/registration/register">注册账号 ？</a>
			</div>
		</div>
		<a href="javascript:void(0);" class="pop-close-btn J_close_pop" title="关闭"></a>
	</div> -->
	<div class="search-box" id="J_Search_box">
		<div class="s-overlay" id="J_Searchlay"></div>
		<div class="search-pop">
			<div class="search-text-box">
				<input type="text" class="search-text" id="J_searchText_pop" name="" value="" placeholder="输入职位或者公司名称即可">
				<a href="javascript:void(0)" class="search-btn" id="J_search_btn" title="搜索"></a>
			</div>
			<div class="search-result">
				<ul class="sr-list-tit" id="j-sr-list-tit" style="display:none;">
					<li>
						<span class="post">职位</span>
						<span class="comp">单位</span>
						<span class="sala">薪资</span>
						<span class="time">时间</span>
					</li>
				</ul>
				<ul class="sr-list" id="j-sr-list" style="display:none;">
				</ul>
				<div class="result-all" id="j-sr-result-all" style="display:none;">
					<a href="javascript:void(0)" class="all-btn" target="_blank">查看全部结果</a>
				</div>
			</div>
			<a href="javascript:void(0);" class="s-close-btn" id="J_Search_close" title="关闭"></a>
		</div>
		<script type="text/x-jquery-tmpl" id="search-list-tmpl">
		<li>
			<a href="<?= $Path; ?>/index.php?r=job/detail&id=${_id['$id']}" class="work-link" target="_blank">
				<span class="post">${name}</span>
				<span class="comp">${company}</span>
				<span class="sala">￥${salary}${salaryType}</span>
				<span class="time">${showTime}</span>
			</a>
		</li>
		</script>
	</div>
<?= $content ?>
</div>


<?php $this->endBody() ?>
<?php if (isset($this->blocks['bottomcode'])): ?>
<?= $this->blocks['bottomcode'] ?>
<?php  endif; ?>
</body>
</html>
<?php $this->endPage() ?>

