<?php require_once('../../web/include/messagepopup.php') ?>
<!-- 头部 start -->
<div class="header">
	<div class="comWidth clearfix">
		<div class="h-logo">
			<a href="<?= $Path;?>/index.php" class="logo-icon"><img src="/quandi/images/title_logo.png"/></a>
		</div>
		<div class="h-nav">
			<a href="<?= $Path;?>/index.php" class="home-text" id="J_Home_">首页</a>
			<a href="<?= $Path;?>/index.php?r=company/list" class="company-text" id="J_Company_">公司</a>
		</div>
		<div class="h-menu clearfix">
			<div class="mnav">
				<a href="javascript:void(0)" class="search-text" id="J_Search_Pop">搜索</a>
			</div>
			<?php
			if (!(\Yii::$app->user->isGuest)) {?>
			<div class="mnav">
				<?php if(\Yii::$app->user->identity->type){ ?>
				<a href="<?= $Path;?>/index.php?r=admin/job/publish" class="publish-text" id="J_Release_">发布</a>
				<?php } else { ?>
				<a href="javascript:void(0)" data-usertype="<?= \Yii::$app->user->identity->type;?>" class="publish-text" id="J_Release_">发布</a>
				<?php } ?>
			</div>
			<?php }else{ ?>
			<div class="mnav">
				<a href="javascript:void(0)" class="publish-text" id="J_Release_Guest">发布</a>
			</div>
			<?php } ?>
			<div class="mnav">
				<?php if (\Yii::$app->user->isGuest)  {?>
					<div class="login-regist">
						<a href="javascript:void(0)" class="login-text" id="J_UserLogin_Pop">登录</a>
						<span>&nbsp;|&nbsp;</span>
						<a href="<?= $Path;?>/index.php?r=user/registration/register" class="regist-text">注册</a>
					</div>
				<?php } else { ?>
					<div class="user-login-info" id="J_user_info">
						<a href="javascript:void(0)" class="user-name"><?= \Yii::$app->user->identity->username; ?></a>
						<div class="user-menu" id="J_user_menu">
							<ul>
								<li>
									<i class="um-icon um-publish"></i>
									<a href="user_mypublish.html">我的发布</a>
								</li>
								<li>
									<i class="um-icon um-change-pass"></i>
									<a href="user_passowrd.html">修改密码</a>
								</li>
								<li>
									<i class="um-icon um-user-set"></i>
									<a href="user_account.html">账号设置</a>
								</li>
								<li>
									<i class="um-icon um-portrait-set"></i>
									<a href="user_portrait.html">头像设置</a>
								</li>
								<li>
									<i class="um-icon um-logout"></i>
									<a href="javascript:void(0);">退出账号</a>
								</li>
							</ul>
							<i class="arrows-top"></i>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<!-- 头部 end -->
