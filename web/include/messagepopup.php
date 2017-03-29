<iframe name="hidden_frame" id="hidden_frame"></iframe>
<div class="_overlayAll_"></div>
<div class="user-login-pop popup" id="J_Login_pop">
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
			<a href="#">密码找回 ？</a>
		</div>
		<div>
			<a href="<?= $Path;?>/index.php?r=user/registration/register">注册账号 ？</a>
		</div>
	</div>
	<a href="javascript:void(0);" class="pop-close-btn J_close_pop" title="关闭"></a>
</div>
<div class="search-box" id="J_Search_box">
	<div class="s-overlay"></div>
	<div class="search-pop">
		<div class="search-text-box">
			<input type="text" class="search-text j_g_focus" id="J_searchText_pop" name="" value="" placeholder="输入职位或者公司名称即可">
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
		<a href="javascript:void(0);" class="s-close-btn" title="关闭"></a>
	</div>
	<script type="text/x-jquery-tmpl" id="search-list-tmpl">
	<li>
		<a href="<?= $Path; ?>/index.php?r=job/detail&id=${_id['$id']}" class="work-link" target="_blank">
			<span class="post">${name}</span>
			<span class="comp">${company}</span>
			<span class="sala">￥${salary}</span>
			<span class="time">${time}</span>
		</a>
	</li>
	</script>
</div>