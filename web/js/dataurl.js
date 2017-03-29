function uaredirect(f) {
	try {
		if (document.getElementById("bdmark") != null) {
			return
		}
		var b = false;
		if (arguments[1]) {
			var e = window.location.host;
			var a = window.location.href;
			if (isSubdomain(arguments[1], e) == 1) {
				f = f + "/#m/" + a;
				b = true
			} else {
				if (isSubdomain(arguments[1], e) == 2) {
					f = f + "/#m/" + a;
					b = true
				} else {
					f = a;
					b = false
				}
			}
		} else {
			b = true
		}
		if (b) {
			var c = window.location.hash;
			if (!c.match("fromapp")) {
				if ((navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i))) {
					//location.replace(f)
					return 'm/'
				}
			}
		}
	} catch (d) {}
}

function isSubdomain(c, d) {
	this.getdomain = function(f) {
		var e = f.indexOf("://");
		if (e > 0) {
			var h = f.substr(e + 3)
		} else {
			var h = f
		}
		var g = /^www\./;
		if (g.test(h)) {
			h = h.substr(4)
		}
		return h
	};
	if (c == d) {
		return 1
	} else {
		var c = this.getdomain(c);
		var b = this.getdomain(d);
		if (c == b) {
			return 1
		} else {
			c = c.replace(".", "\\.");
			var a = new RegExp("\\." + c + "$");
			if (b.match(a)) {
				return 2
			} else {
				return 0
			}
		}
	}
};
var test = uaredirect($_Path) || '';
var _Path = $_Path + '/index.php?r='+test;
var apiUrl = {
	// 登录接口
	_login : _Path+'user/security/login-json',
	// 注册接口
	_register : _Path+'user/registration/register',
	// 登出接口
	_logout : _Path+'user/security/logout',
	// 修改密码
	_changePwd : _Path+'user/settings/password',
	// 修改账号信息
	_changeUserInfo : _Path+'user/admin/update',
	// 搜索接口
	_search : _Path+'job/search-job',
	// 公司集合岗位接口
	_companyJob : _Path+'company/jobs',
	// 个人集合岗位接口
	_personalJob : _Path+'job/personal-jobs',
	// 公司集合认证接口
	_authPublishers : _Path+'company/auth-publishers',
	// 订阅接口
	_subscribe : _Path+'job/subscribe',
	// 编辑更新接口
	_updataAttr : _Path+'account/update-attr',
	// 编辑公司简介接口
	_updateCompDesc :  _Path+'account/update-comp-desc',
	// 上传个人头像接口
	_changeAvatar :  _Path+'account/change-avatar',
	// 积分接口
	_pointsRecord :  _Path+'account/points-record',
	// 已发布的列表
	_publishedJobs :  _Path+'account/comp-published-jobs',
	// 删除岗位
	_deleteJob :  _Path+'account/delete-job',
	// 更新岗位
	_updateTime :  _Path+'account/update-show-time',
	// 修改密码接口
	_setPassword :  _Path+'user/settings/password',
	// 认证公司和职位接口
	_updatePositionComp :  _Path+'account/update-position-and-comp',
	// 名片图片上传接口
	_uploadCardImg :  _Path+'image/image/upload-card',
	// 名片认证接口
	_uploadCard :  _Path+'account/upload-card',
	// 手机认证接口
	_updatePhone :  _Path+'account/change-phone',
	// 上传简历头像接口
	_updateResumeImg :  _Path+'account/upload-resume-avatar',
	// 上传简历附件接口
	_updateResumeAttachment:  _Path+'account/upload-resume-attachment',
	// 简历投递接口
	_deliverResume:  _Path+'account/deliver',
	// 已收简历接口
	_receivedResume:  _Path+'account/comp-received-resume',
	// 我的投递接口
	_deliveredResume:  _Path+'account/personal-delivered-resume',


	// AJAX请求
	getJSON : function(param) {
		var ajaxConf = {}
		ajaxConf = {
			type : param.type,
			url : param.url,
			dataType : param.dataType,
			data : param.data,
			async : true,
			timeout : 20000,
			success:function(data){
				if(typeof param.callback=='function'){
					param.callback(data);
				}
				//默认处理回调
			},
			error: function(r){
				if(typeof param.errorCallback=='function'){
					param.errorCallback(r);
				}
				//默认处理回调
			}
		}

		return $.ajax(ajaxConf)
	}
}