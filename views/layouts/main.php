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
$this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/css/common/reset.css',]);
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
	<div class="search-box" id="J_Search_box">
		<div class="s-overlay" id="J_Searchlay"></div>
		<div class="search-pop">
			<div class="search-text-box">
				<input type="text" class="search-text" id="J_searchText_pop" name="" value="" placeholder="输入职位或者公司名称">
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
	<div class="header">
		<div class="comWidth clearfix">
			<div class="h-logo">
				<a href="<?= $Path;?>/index.php" class="logo-icon"><img src="<?= $Path;?>/images/top-logo-20160330.png" width="172" /></a>
			</div>
			<div class="h-nav">
				<a href="<?= $Path;?>/index.php" class="home-text" id="J_Home_">招聘</a>
				<a href="<?= $Path;?>/index.php?r=site/news" class="" id="J_News_">快讯<i></i></a>
				<a href="<?= $Path;?>/index.php?r=company/list" class="company-text" id="J_Company_">公司</a>
				<a href="<?= $Path;?>/index.php?r=site/help" class="help-text" id="J_Help_">帮助</a>
			</div>
			<div class="h-menu clearfix">
				<div class="mnav">
					<a href="javascript:void(0)" class="search-text" id="J_Search_Pop">搜索</a>
				</div>
				<?php
				if (!(\Yii::$app->user->isGuest)) {?>
				<div class="mnav">
					<?php if(\Yii::$app->user->identity->type){ ?>
					<a href="<?= $Path;?>/index.php?r=job/publish" class="publish-text" id="J_Release_">发布职位</a>
					<?php } else { ?>
					<a href="javascript:void(0)" class="publish-text J_dialog" data-dialog="J_denied">发布职位</a>
					<?php } ?>
				</div>
				<?php }else{ ?>
				<div class="mnav">
					<a href="<?= $Path;?>/index.php?r=job/publish" class="publish-text" id="J_Release_">发布职位</a>
				</div>
				<?php } ?>
				<div class="mnav">
					<?php if (\Yii::$app->user->isGuest)  {?>
						<div class="login-regist">
							<a href="<?= $Path;?>/index.php?r=user/security/login" class="login-text">登录</a>
							<span>&nbsp;|&nbsp;</span>
							<a href="<?= $Path;?>/index.php?r=user/registration/register" class="regist-text">注册</a>
						</div>
					<?php } else { ?>
						<div class="user-login-info" id="J_user_info">
							<img src="<?= \Yii::$app->user->identity->avatar;?>" class="user-img" width="20" height="20" alt="">
							<?php if(\Yii::$app->user->identity->type){ ?>
							<a href="javascript:void(0)" <?php if(\Yii::$app->user->identity->hasNewResume){ ?>id="hasNewResume"<?php }; ?> class="user-name user-name-comp"><?= \Yii::$app->user->identity->nickname; ?></a>
							<?php } else { ?>
							<a href="javascript:void(0)" class="user-name"><?= \Yii::$app->user->identity->nickname; ?></a>
							<?php } ?>
							<?php if(\Yii::$app->user->identity->hasNewResume){ ?>
							<i class="dot-tips" id="jdot-tips"></i>
							<?php }; ?>
							<div class="user-menu" id="J_user_menu">
								<?php if(\Yii::$app->user->identity->type){ ?>
								<ul>
									<li>
										<i class="um-icon um-publish"></i>
										<a href="<?= $Path.'/index.php?r=account/comp-publish'?>">我的发布</a>
									</li>
									<li>
										<i class="um-icon um-list"></i>
										<a href="<?= $Path.'/index.php?r=account/comp-resume-list'?>">已收简历</a>
										<i class='num-tips'><?php if(\Yii::$app->user->identity->newResumeCount != 0){echo \Yii::$app->user->identity->newResumeCount;}; ?></i>
									</li>
									<li>
										<i class="um-icon um-account"></i>
										<a href="<?= $Path.'/index.php?r=account/comp-account'?>">账号设置</a>
									</li>
									<li>
										<i class="um-icon um-auth"></i>
										<a href="<?= $Path.'/index.php?r=account/comp-auth'?>">账号认证</a>
									</li>
									<li>
										<i class="um-icon um-change-pass"></i>
										<a href="<?= $Path.'/index.php?r=account/comp-password'?>">修改密码</a>
									</li>
									<?php if (in_array(\Yii::$app->user->identity->username, Dictionary::indexMap("AdminUsers"))) {?>
									<li>
										<i class="um-icon um-change-pass"></i>
										<a href="<?= $Path.'/index.php?r=admin'?>">后台管理</a>
									</li>
									<?php } ?>
									<li>
										<form action="<?= $Path;?>/index.php?r=user/security/change-account" method="post">
											<i class="um-icon um-switch-user"></i>
											<a href="javascript:void(0);" class="logoutbtn"><input type="submit" value="切换账号"></a>
										</form>
									</li>
									<li>
										<form action="<?= $Path;?>/index.php?r=user/security/logout" method="post">
											<i class="um-icon um-logout"></i>
											<a href="javascript:void(0);" class="logoutbtn"><input type="submit" value="退出账户"></a>
										</form>
									</li>
								</ul>
								<?php } else { ?>
								<ul>
									<li>
										<i class="um-icon um-resume"></i>
										<a href="<?= $Path.'/index.php?r=account/personal-resume'?>">我的简历</a>
									</li>
									<li>
										<i class="um-icon um-gather"></i>
										<a href="<?= $Path.'/index.php?r=account/personal-gather'?>">我的投递</a>
									</li>
									<li>
										<i class="um-icon um-account"></i>
										<a href="<?= $Path.'/index.php?r=account/personal-account'?>">账号设置</a>
									</li>
									<li>
										<i class="um-icon um-change-pass"></i>
										<a href="<?= $Path.'/index.php?r=account/personal-password'?>">修改密码</a>
									</li>
									<?php if (in_array(\Yii::$app->user->identity->username, Dictionary::indexMap("AdminUsers"))) {?>
									<li>
										<i class="um-icon um-change-pass"></i>
										<a href="<?= $Path.'/index.php?r=admin'?>">后台管理</a>
									</li>
									<?php } ?>
									<li>
										<form action="<?= $Path;?>/index.php?r=user/security/change-account" method="post">
											<i class="um-icon um-switch-user"></i>
											<a href="javascript:void(0);" class="logoutbtn"><input type="submit" value="切换账号"></a>
										</form>
									</li>
									<li>
										<form action="<?= $Path;?>/index.php?r=user/security/logout" method="post">
											<i class="um-icon um-logout"></i>
											<a href="javascript:void(0);" class="logoutbtn"><input type="submit" value="退出账户"></a>
										</form>
									</li>
								</ul>
								<?php } ?>
								<i class="arrows-top"></i>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php if (\Yii::$app->user->isGuest)  {?>
	<div class="tips-top">
		<div class="slogan">
			<span><!-- 发现地产圈职业机会&nbsp;&nbsp;</span> |  --><span>&nbsp;&nbsp; 发布职位，找到机会。</span>
			<a href="<?= $Path;?>/index.php?r=user/registration/register" class="btn-create" title="创建账户">创建账户</a>
		</div>
	</div>
	<?php } ?>

<?= $content ?>

	<div class="footer">
		<div class="comWidth clearfix">
			<!-- <div class="quannei-win">圈内觅 Quannei.me</div> -->
			<div class="ewm"><img src="/images/quanneimiewm.gif" alt=""></div>
			<div class="copyright">
				<p>圈内觅 www.quannei.me  |  发现地产圈职业机会</p>
				<p><a href="<?= $Path.'/index.php?r=site/contact'?>">通过微信或者邮箱联系到我们</a></p>
				<p class="copy">浙ICP备 16005246号  |  浙公网安备 33010802005574号</p>
			</div>

			<div class="mail-box">
				<input class="mail-text" id="mail-text" name="mail-text" type="text" value="" placeholder="您的邮件地址" />
				<a class="mail-btn" href="javascript:void(0);" id="J_mail_btn">订阅</a>
				<p class="mail-msg">关注行业动态，把握职业机会，我们不定时发送一些热门岗位到您的邮箱！</p>
			</div>
			<!-- <div class="copyright">&copy;<?= date('Y');?> 浙ICP备16005246号 | <a href="<?= $Path.'/index.php?r=company/contact'?>" title="联系我们">联系我们</a></div> -->
		</div>
	</div>

	<div class="subscribe-pop popup" id="J_Subscribe_Pop">
		<div class="subscribe-title">
			<i></i><strong>订阅每日行业职位机会</strong>
		</div>
		<div class="subscribe-mail">
			<div class="subscribe-mail-input">
				<input type="text" class="email-text" id="email-subscribe" name="email-subscribe" value="" placeholder="输入您的邮箱地址">
				<a href="javascript:void(0)" id="J_subscribe" class="subscribe-btn">确定订阅</a>
			</div>
		</div>
		<div class="subscribe-msg2">
			<i></i>我们会将每日最新的岗位发送到您的邮箱，当然您可以随时取消订阅！
		</div>
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>

	<div class="subscribe-success popup" id="J_SubscribeSuc_Pop">
		<div class="subscribe-success-icon"></div>
		<div class="subscribe-success-msg">订阅成功 ！</div>
		<div class="subscribe-success-btn clearfix">
			<a href="javascript:void(0);" class="sbtn1 J_close_pop">确认</a>
		</div>
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<div class="retrieve-pwd-pop popup" id="J_retrieve_popup">
		<div class="retrieve-pwd-nav clearfix">
			<div class="navbtn act">通过手机找回</div>
			<div class="navbtn">通过邮箱找回</div>
		</div>
		<div class="retrieve-pwd-select">
			<div class="retrieve-pwd-panel">
				<ul>
					<li>
						<label class="label-name">手机号码</label>
						<input class="phone-text" type="text" name="" value="" placeholder="输入手机号码" />
						<a href="javascript:void(0);" class="getvcode-btn">发送验证</a>
					</li>
					<li>
						<label class="label-name">填写验证</label>
						<input class="verifycode-text" type="text" name="" value="" placeholder="输入验证码" />
						<span class="has-success"></span>
						<span class="has-error">验证不正确请重新输入</span>
					</li>
				</ul>
				<a href="#" class="submit-btn">设置新密码</a>
			</div>
			<div class="retrieve-pwd-panel" style="display:none;">
				<ul>
					<li>
						<label class="label-name">邮箱地址</label>
						<input class="phone-text" type="text" name="" value="" placeholder="输入邮箱地址" />
						<a href="javascript:void(0);" class="getvcode-btn">发送验证</a>
					</li>
					<li>
						<label class="label-name">填写验证</label>
						<input class="verifycode-text" type="text" name="" value="" placeholder="输入验证码" />
						<span class="has-success"></span>
						<span class="has-error">验证不正确请重新输入</span>
					</li>
				</ul>
				<a href="#" class="submit-btn">设置新密码</a>
			</div>
		</div>
		<div class="know-pwd">
			<a href="javascript:void(0);" class="J_close_pop" title="密码想起来了>>">密码想起来了&gt;&gt;</a>
		</div>
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>

	<div class="captcha-pop popup" id="JCaptchaImg">
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
		<div class="captcha-title">
			<i></i><strong>请输入下图中的文字或字母</strong>
		</div>
		<div class="captcha-input">
			<input type="text" class="captcha-text" id="captchaImgTxt" name="captcha" value="" placeholder="请输入验证码">
			<img src="#" alt="点击刷新" title="点击刷新" id="J_resetcaptcha" >
		</div>
		<a href="javascript:;" class="captcha-btn" id="J_GetVcode">确定</a>
	</div>


	<?php if (!(\Yii::$app->user->isGuest)) {
	if(\Yii::$app->user->identity->type){ ?>
	<div class="user-prompts popup" id="J_denied">
		<div class="icon"></div>
		<div class="msg">招聘用户不能投递简历，请切换或注册求职用户！</div>
		<div class="btn-box">
			<a href="javascript:void(0);" class="btn1 J_close_pop">知道了</a>
		</div>
		<div class="btn2-box">
			<form action="<?= $Path;?>/index.php?r=user/security/change-account" method="post">
				<!-- <a href="javascript:;">注册求职用户</a> |  --><a href="javascript:void(0);"><input type="submit" value="切换账号"></a>
			</form>
		</div>
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<?php } else { ?>
	<div class="user-prompts popup" id="J_denied">
		<div class="icon"></div>
		<div class="msg">求职用户不能发布招聘信息，请切换或注册招聘用户！</div>
		<div class="btn-box">
			<a href="javascript:void(0);" class="btn1 J_close_pop">知道了</a>
		</div>
		<div class="btn2-box">
			<form action="<?= $Path;?>/index.php?r=user/security/change-account" method="post">
				<!-- <a href="javascript:;">注册招聘用户</a> |  --><a href="javascript:void(0);"><input type="submit" value="切换账号"></a>
			</form>
		</div>
		<a href="javascript:void(0);" class="close-btn J_close_pop" title="关闭"></a>
	</div>
	<?php }} ?>
</div>


<?php $this->endBody() ?>
<?php if (isset($this->blocks['bottomcode'])): ?>
<?= $this->blocks['bottomcode'] ?>
<?php  endif; ?>
</body>
</html>
<?php $this->endPage() ?>

