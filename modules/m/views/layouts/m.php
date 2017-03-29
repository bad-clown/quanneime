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
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="renderer" content="webkit">
<meta name="description" content="圈内觅是精准、有效的地产圈职业机会平台，这里更是地产圈职业机会内部推荐的平台，我们帮助您招到更好的人，让求职者发现更好的职业机会，Quannei.me"/>
<?= Html::csrfMetaTags() ?>
<title><?= Dictionary::indexKeyValue("App","SystemName") ?><?= (isset($this->context->title) && (!empty($this->context->title)))?"-".$this->context->title:"" ?></title>
<?php
$this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/css/common/reset.css',]);
$this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/js/jquery.mobile/jquery.mobile-1.4.5.min.css',]);
$this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/js/jquery.mobile/demos/_assets/css/jqm-demos.css',]);
$this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/css/m/index.css',]);
?>
<script>var $_Path="<?= $Path?>",$_Time=<?= time();?>,_hmt = _hmt || [];(function() {var hm = document.createElement("script");hm.src = "//hm.baidu.com/hm.js?c28ec36b094b3f633a9a82dc4ffcc28d";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(hm, s);})();</script>
<?php $this->head() ?>
</head>
<body  data-baseurl="<?=Url::to(["/"])?>">

<?php $this->beginBody() ?>

<?php if (isset($this->blocks['topcode'])): ?>
<?= $this->blocks['topcode'] ?>
<?php  endif; ?>


<?= $content ?>

<div class="topTips" id="topTips">
	<div class="tipsCont">PC 访问圈内觅 quannei.me 获取最佳体验</div>
	<a href="javascript:;" class="tipsClosebtn" id="tipsClosebtn">知道了</a>
</div>

<div data-role="page" id="pageSearch">
	<div class="ui-panel-wrapper">
		<div data-role="header" data-theme="b">
			<h1>搜索</h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageMain" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-delete ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">关闭</a>
		</div><!-- /header -->
		<div data-role="content">
			<form class="ui-filterable">
			    <input id="autocomplete-input" data-type="search" placeholder="输入职位或者公司名称">
			</form>
			<div id="totalJobCount" style="text-align:center;"></div>
			<ul id="autocomplete" data-role="listview" data-inset="true" data-filter="true" data-input="#autocomplete-input"></ul>
		</div><!-- /content -->
	</div><!-- /ui-panel-wrapper -->
</div><!-- /page -->

<div  id="left-panel" data-theme="b" data-display="reveal" data-position="left" data-role="panel">
	<div data-role="content">
		<?php if (\Yii::$app->user->isGuest)  {?>
		<div class="login-box">
			<a href="http://www.quannei.me/index.php?r=user/registration/register" class="ui-btn ui-corner-all ui-btn-a" style="background: #1ba8ed;border-color: #1ba8ed;color: #fff;text-shadow: initial;">注册</a>
			<a href="http://www.quannei.me/index.php?r=user/security/login" class="ui-btn ui-corner-all ui-btn-a" style="background: #80c269;border-color: #80c269;color: #fff;text-shadow: initial;">登录</a>
		</div>
		<?php } ?>
		<ul class="panel-menu">
			<li><a href="<?= $Path.'/index.php?r=m/site/index';?>" data-ajax="false">发现机会</a></li>
			<li><a href="<?= $Path.'/index.php?r=m/site/news';?>" data-ajax="false">快讯</a></li>
			<li><a href="<?= $Path.'/index.php?r=m/company/list';?>" data-ajax="false">热门企业</a></li>
			<li><a href="<?= $Path.'/index.php?r=m/company/contact';?>" data-ajax="false">关于我们</a></li>
			<?php
				if (!\Yii::$app->user->isGuest)  {
					if(\Yii::$app->user->identity->type){
			?>
			<li><a href="<?= $Path.'/index.php?r=m/job/publish';?>" class="ui-btn ui-corner-all ui-btn-a" style="background: #80c269;border-color: #80c269;color: #fff;text-shadow: initial;" data-ajax="false">+ 发布职位</a></li>
			 <!-- class="ui-shadow-icon ui-btn ui-shadow ui-corner-all ui-icon-plus ui-btn-icon-left" -->
			<?php }} ?>
		</ul>
		<div class="user-menu">
			<?php if (!\Yii::$app->user->isGuest)  {?>
			<div class="user-name"><?= \Yii::$app->user->identity->nickname; ?> <img src="<?= \Yii::$app->user->identity->avatar;?>" class="user-img" width="20" height="20" style="border-radius: 50%;" alt=""></div>
			<?php if(\Yii::$app->user->identity->type){ ?>
			<ul>
				<li>
					<!-- <i class="um-icon"></i> -->
					<a href="<?= $Path.'/index.php?r=m/account/comp-publish'?>">我的发布</a>
				</li>
				<li>
					<i class="um-icon"></i>
					<a href="<?= $Path.'/index.php?r=m/account/comp-resume-list'?>">已收简历</a>
					<i class='num-tips'><?php if(\Yii::$app->user->identity->newResumeCount != 0){echo \Yii::$app->user->identity->newResumeCount;}; ?></i>
				</li>
				<li>
					<i class="um-icon"></i>
					<a href="<?= $Path.'/index.php?r=m/account/comp-account'?>">账号设置</a>
				</li>
				<li>
					<i class="um-icon"></i>
					<a href="<?= $Path.'/index.php?r=m/account/comp-auth'?>">账号认证</a>
				</li>
				<li>
					<i class="um-icon"></i>
					<a href="<?= $Path.'/index.php?r=m/account/comp-password'?>">修改密码</a>
				</li>
				<li>
					<form action="<?= $Path;?>/index.php?r=user/security/change-account" method="post">
						<i class="um-icon"></i>
						<a href="javascript:void(0);"><input type="submit" value="切换账号"></a>
					</form>
				</li>
				<li>
					<form action="<?= $Path;?>/index.php?r=user/security/logout" method="post">
						<i class="um-icon"></i>
						<a href="javascript:void(0);"><input type="submit" value="退出账户"></a>
					</form>
				</li>
			</ul>
			<?php } else { ?>
			<ul>
				<li>
					<i class="um-icon"></i>
					<a href="<?= $Path.'/index.php?r=m/account/personal-resume'?>">我的简历</a>
				</li>
				<li>
					<i class="um-icon"></i>
					<a href="<?= $Path.'/index.php?r=m/account/personal-gather'?>">我的投递</a>
				</li>
				<li>
					<i class="um-icon"></i>
					<a href="<?= $Path.'/index.php?r=m/account/personal-account'?>">账号设置</a>
				</li>
				<li>
					<i class="um-icon"></i>
					<a href="<?= $Path.'/index.php?r=m/account/personal-password'?>">修改密码</a>
				</li>
				<li>
					<form action="<?= $Path;?>/index.php?r=user/security/change-account" method="post">
						<i class="um-icon"></i>
						<a href="javascript:void(0);"><input type="submit" value="切换账号"></a>
					</form>
				</li>
				<li>
					<form action="<?= $Path;?>/index.php?r=user/security/logout" method="post">
						<i class="um-icon"></i>
						<a href="javascript:void(0);"><input type="submit" value="退出账户"></a>
					</form>
				</li>
			</ul>
			<?php } ?>
			<?php } ?>
		</div>

		<div class="ewm">
			<img src="/images/qnmgzhewm.jpg" width="60%" alt="">
			<p>先关注再谈年薪百万</p>
		</div>
	</div>
</div><!-- /panel -->


<?php $this->endBody() ?>
<?php if (isset($this->blocks['bottomcode'])): ?>
<?= $this->blocks['bottomcode'] ?>
<script type="text/x-jquery-tmpl" id="search-list-tmpl">
<li>
	<a href="<?= $Path; ?>/index.php?r=m/job/detail&id=${_id['$id']}" data-ajax="false">
		<img src="<?= $Path;?>${publisherAvatar}" class="ptimg">
		<h2>${name}</h2>
		<p>￥${salary}${salaryType}</p>
	</a>
</li>
</script>
<script type="text/javascript">
$( "#autocomplete" ).on( "filterablebeforefilter", function ( e, data ) {
    var $ul = $( this ),
        $input = $( data.input ),
        value = $input.val(),
        html = "";
    $ul.html( "" );
    if ( value && value.length > 0 ) {
        $ul.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" );
        $ul.listview( "refresh" );
        $.ajax({
            url: apiUrl._search,
            dataType: "json",
            crossDomain: true,
            data: {
                key: $input.val()
            }
        })
        .then( function (data) {
        	var _jobList = data.jobList, len = _jobList.length, _result = [];

        	if(len>0) {
        		_result = Quannei.extend(_jobList)
        		$('#totalJobCount').html('一共有'+data.totalJobCount+'条符合 “'+$input.val()+'” 的搜索结果');
        		$('#autocomplete').empty()
            	$('#search-list-tmpl').tmpl(_result).appendTo('#autocomplete')
            	$ul.listview( "refresh" );
            	$ul.trigger( "updatelayout");
        	}
        	else {
        		$('#totalJobCount').html('未搜索到相关岗位');
        	}
        });
    }
});
$('#tipsClosebtn').on('click', function() {
	$('#topTips').hide();
})
</script>
<?php  endif; ?>
</body>
</html>
<?php $this->endPage() ?>

