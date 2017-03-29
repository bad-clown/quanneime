$(function() {
	if(!$.cookie('mark')) {
		$('#J_News_').addClass('news-text');
	}
	$('#J_News_').on('click', function() {
		$.cookie('mark', true, {expires: 1, path: '/'})
	})

	if(window.Quannei) {return}
	window.Quannei = {};
	Quannei.namespace = function(str) {
		var arr = str.split('.'),
			o = Quannei;
		for (i = (arr[0] == 'Quannei') ? 1 : 0; i < arr.length; i++) {
			o[arr[i]] = o[arr[i]] || {};
			o = o[arr[i]];
		}
	}

	Quannei.namespace('common');
	Quannei.common = {
		_cutstr : function(str, len) {
			var temp,
				icount = 0,
				patrn = /[^\x00-\xff]/,
				strre = "";
			for(var i=0; i<str.length; i++) {
				if(icount<len-1) {
					temp = str.substr(i, 1);
					if(patrn.exec(temp) == null) {
						icount++;
					}
					else {
						icount  += 2;
					}
					strre += temp;
				}
				else {
					break;
				}
			}
			if(str.length <= len) {
				return strre
			}else {
				return strre + "...";
			}
		},
		_map : function(location, city) {
			var map = new BMap.Map("allmap");	// 创建Map实例
			// map.centerAndZoom(city, 12);
			map.enableScrollWheelZoom(true);	// 开启鼠标滚轮缩放
			var myCity = new BMap.LocalCity();
			myCity.get(function(result) {
				var cityName = city || result.name;
				//console.log(cityName)
				map.centerAndZoom(cityName, 12);	// 初始地图中心位置
			});

			var local = new BMap.LocalSearch(map, {
				renderOptions:{map: map}
			});
			local.search(location);
		}
	}
	Quannei.format = function(nS) {
		var c = $_Time;
		var n = nS;
		var d = c*1000 - n*1000;
		var h = Math.floor(d/1000/60/60)
		var s = Math.ceil(d/1000/60)

		if(h < 1) {
			if(s < 1) {
				s = 1;
			}
			return s +'分钟前';
		}
		else if(h < 24) {
			return h +'小时前';
		}
		else if(h >= 24 && h < 48) {
			return '昨天';
		}
		else if(h >= 48) {
			var nS= new Date(parseInt(nS) * 1000);
			// return new Date(parseInt(nS) * 1000).toLocaleString().replace(/\s\S{1,2}\d{1,2}:\d{1,2}:\d{1,2}/g, ' ')
			var year=nS.getFullYear();
			var month=nS.getMonth()+1;
			var date=nS.getDate();
			return year+"/"+month+"/"+date;
		}
		else {
			var nS= new Date(parseInt(nS) * 1000);
			// return new Date(parseInt(nS) * 1000).toLocaleString().replace(/\s\S{1,2}\d{1,2}:\d{1,2}:\d{1,2}/g, ' ')
			var year=nS.getFullYear();
			var month=nS.getMonth()+1;
			var date=nS.getDate();
			return year+"/"+month+"/"+date;
		}
	}

	Quannei.extend = function(a) {
		var b = [],sArr = [' ','元/月', '万/年', '元/日'];
		b = $.extend(true, b, a);
		$.each(b, function(i, o) {
			for(var n in o) {
				switch (n) {
					case 'showTime' :
						o['showTime'] = Quannei.format(o['showTime']);
						break;
					case 'time' :
						o['time'] = Quannei.format(o['time']);
						break;
					case 'salaryType' :
						o['salaryType'] = sArr[o['salaryType']];
						break;
				}
			}
		})
		return b
	}

	Quannei.namespace('login');
	Quannei.login = {
		_Init : function() {
			var _this = this;
			this._username = $("#login-username")
			this._password = $('#login-password')
			this._loginbtn = $('#J_UserLogin_')
			this._popupbtn = $('#J_UserLogin_Pop')

			this._loginbtn.on('click', function() {
				_this._UserLogin()
			})
			this._username.on('focus', function() {
				_this._InputFocus($(this))
			})
			this._password.on('focus', function() {
				_this._InputFocus($(this))
			})
			this._popupbtn.on('click', function() {
				_this._OpenPopup();
			})
			$('.J_Guest').on('click', function() {
				_this._OpenPopup();
			})
			$('#login-username,#login-password').on('keypress', function(e) {
				if(e.keyCode == 13) {
					_this._UserLogin();
				}
			});
			$('#J_logout').on('click', function(e) {
				_this._UserLogout();
			});
		},
		_UserLogin : function() {
			var _this = this;
			var param = {
				type : 'POST',
				url : apiUrl._login,
				dataType : 'json',
				data : {
					login : _this._username.val(),
					password : _this._password.val()
				},
				callback : function(data) {
					if(!data.code) {
						if(location.href.indexOf('register')>-1) {
							location.href = $_Path+'/index.php';
							return;
						}
						else {
							location.href = location.href;
							return;
						}
					}
					else {
						if(data.error.login) {
							_this._username.addClass('has-error');
							_this._username.next('.help-block').html(data.error.login);
							return;
						}
						else if(data.error.password) {
							_this._password.addClass('has-error');
							_this._password.next('.help-block').html(data.error.password);
							return;
						}
					}
				}
			};
			apiUrl.getJSON(param)
		},
		_OpenPopup : function() {
			$('#J_Login_pop').show()
			$('._overlayAll_').show();
			this._username.focus();
		},
		_InputFocus : function(that) {
			var _that = that;
			var _help = _that.next('.help-block');
			_help.html("");
			_that.removeClass('has-error')
		},
		_UserLogout : function() {
			var param = {
				type : "POST",
				url : $_Path+'/index.php?r=user/security/logout',
				success : function(data) {
					// console.log(data);
					//window.location.reload();
				}
			}
			apiUrl.getJSON(param)
		}
	}

	Quannei.namespace('search');
	Quannei.search = {
		_Init : function() {
			var _this = this;
			$('#J_Search_Pop').on('click', function() {
				$('#J_Search_box').show()
				// $('#J_Search_box').removeClass('search-unselected').addClass('search-selected');
			});
			$('#J_Search_close').on('click', function() {
				$('#J_Search_box').hide()
				// $('#J_Search_box').removeClass('search-selected').addClass('search-unselected');
			})
			$('#J_Searchlay').on('click', function() {
				$('#J_Search_close').click()
			})
			$('#J_searchText_pop').on('keypress', function(e) {
				if(e.keyCode == '13') {
					_this._Result($(this).val())
				}
			})
			$('#J_search_btn').on('click', function() {
				_this._Result($('#J_searchText_pop').val())
				$('#J_searchText_pop').focus();
			})
		},
		_Result : function(_k) {
			if(_k == '' || _k == null) return;
			var _key = _k;

			var param = {
				type : 'GET',
				url : apiUrl._search,
				dataType : 'json',
				data : {
					key : _key
				},
				callback : function(data) {
					var _jobList = data.jobList, k = '', l = 0, len = _jobList.length, _result = [];

					if(len > 0) {
						var __jobList = [];
						if(len > 4) {
							l = 4;
							if(_key == "") {
								k = "__All";
							}
							else{
								k = _key;
							}
							$('#j-sr-result-all').show();
							$('#j-sr-result-all').find('a').attr('href', $_Path+'/index.php?r=job/search&searchTxt='+encodeURIComponent(k))
						}
						else {
							$('#j-sr-result-all').hide();
							l = _jobList.length;
						}
						_result = Quannei.extend(_jobList)
						$('#j-sr-list-tit').show();
						$('#j-sr-list').empty().show();
						$('#search-list-tmpl').tmpl(_result.slice(0, 4)).appendTo('#j-sr-list')
					}
					else{
						$('#j-sr-list-tit').hide();
						$('#j-sr-result-all').hide();
						$('#j-sr-list').show().html('<div style="text-align:center;font-size:16px;color:#4d586e;">未搜索到相关岗位</div>');
					}
				}
			}
			apiUrl.getJSON(param)
		}
	}

	Quannei.namespace('subscribe');
	Quannei.subscribe = {
		_Init : function() {
			var _this = this;
			$('#J_mail_btn').on('click', function() {
				var v = $('#mail-text').val();
				_this._Check(v)
			})
			$('#J_subscribe').on('click', function() {
				var v = $('#email-subscribe').val();
				var _call = function() {
					var param = {
						type : 'GET',
						url : apiUrl._subscribe,
						data : {
							email : v
						},
						dataType : 'json',
						callback : function(data) {
							if(data.code == 0) {
								$('#email-subscribe').val("");
								$('#J_Subscribe_Pop').hide();
								$('#J_SubscribeSuc_Pop').show();
							}
						}
					}
					apiUrl.getJSON(param);
				}
				_this._Check(v, _call)
			})
			$('#mail-text').on('keypress', function(e) {
				if(e.keyCode == '13') {
					_this._Check($(this).val())
				}
			})
		},
		_Check : function(_v, _callback) {
			var reg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if(!(_v == "") && reg.test(_v)) {
				if(typeof _callback == 'function') {
					_callback()
				}
				else {
					$('#email-subscribe').val(_v);
					$('#J_Subscribe_Pop').show();
					$('#J_overlayAll_').show();
				}
			}
			else {
				alert('请输入正确邮箱地址');
			}
		}
	}

	Quannei.namespace('dialog');
	Quannei.dailog = {
		_Init : function() {
			var _this = this;
			$(document).on('click', '.J_dialog', function() {
				var dialog = $(this).data('dialog');
				if(dialog) {
					$('#'+dialog).show();
					$('#J_overlayAll_').show();
				}
			})
			$(document).on('click', '.J_close_pop', function() {
				$(this).parents('.popup').hide();
				$('#J_overlayAll_').hide();
			})
			$(document).on('click', '#J_overlayAll_', function() {
				$('.J_close_pop').click()
			})
		}
	}


	Quannei.namespace('gather');
	Quannei.gather = {
		_Init : function(_id) {
			var _this = this;
			this._page = 1;
			this._id = _id
			this._url = apiUrl._companyJob;
			this._GetGatherJob(_this.page)
			this._VerifyPersonal(4)

			$('#J_Gather_More').on('click', function() {
				_this._page++;
				_this._GetGatherJob(_this._url)
			})
			$('#J_verifyall').on('click', function() {
				_this._VerifyPersonal(0)
			})
		},
		_GetGatherJob : function(url, data) {
			var _this = this,
				url = url || this._url,
				data = data || {id:_this._id,page:_this._page},
				param = {
					type : "GET",
					url : url,
					data : data,
					dataType : 'json',
					cache : false,
					callback : function(data) {
						var _jobList = data.jobList, len = _jobList.length, _result = [];

						if(len > 0) {
							_result = Quannei.extend(_jobList)
							$('.gwork-lists-tit').show();
							$('.gwork-lists').show();
							$('.gwork-more').hide();
							$('#job-list-tmpl').tmpl(_result).appendTo('.gwork-lists')
							if(data.currPage == data.pageCount) {
								$('.gwork-more').hide();
							}
							else {
								$('.gwork-more').show();
							}
						}
						else {
							$('.gwork-lists-tit').hide();
							$('.gwork-lists').hide();
							$('.gwork-more').hide();
						}
					}
				}
			apiUrl.getJSON(param);
		},
		_VerifyPersonal : function(cnt) {
			var _this = this;
			var cnt = cnt || 0;
			var params = {
				type : "GET",
				url : apiUrl._authPublishers,
				data : {
					id : _this._id,
					cnt : cnt
				},
				dataType : 'json',
				cache : false,
				callback : function(data) {
					var _publishers = data.publishers;
					$('#J_pubCount').html(data.pubCount);
					if(_publishers != "") {
						$('#J_pubCount').html();
						$('#J_verify_personal').empty();
						$('#verify-list-tmpl').tmpl(_publishers).appendTo('#J_verify_personal')
					}
				}
			}
			apiUrl.getJSON(params);
		}
	}

	Quannei.namespace('retrieve');
	Quannei.retrieve = {
		_Init : function() {
			$('#J_retrieve_btn').on('click', function() {
				$('.J_close_pop').click();
				$('#J_retrieve_popup').show();
				$('#J_overlayAll_').show();
			})
			$('.retrieve-pwd-nav > .navbtn').on('click', function() {
				$(this).siblings().removeClass('act').end().addClass('act');
				$('.retrieve-pwd-panel').hide();
				$('.retrieve-pwd-panel').eq($(this).index()).show();
			})
		}
	}

	Quannei.MenuShow = function() {
		var timer = null,
			$menu = $('#J_user_menu');
		if(!$menu) return;
		$('#J_user_info').on('click', function(e) {
			e.stopPropagation();
			if(!$(this).data('tag')) {
				$(this).data('tag','1');
				$menu.css({
					'opacity' : '1',
					'transform' : 'scale(1)',
					'transition' : 'all 300ms',
					'transform-origin' : '50% 0'
				});
			}
			else {
				$(this).removeData('tag');
				$menu.css({
					'opacity' : '0',
					'transform' : 'scale(0)',
					'transition' : 'all 300ms',
					'transform-origin' : '50% 0'
				});
			}
		})
		$(document).on('click', function() {
			$('#J_user_info').removeData('tag');
			$menu.css({
				'opacity' : '0',
				'transform' : 'scale(0)',
				'transition' : 'all 300ms',
				'transform-origin' : '50% 0'
			});
		});
		$('#hasNewResume').on('click', function() {
			$.ajax({
				type : "GET",
				url : $_Path+'/index.php?r=site/clear-new-resume-state',
				success : function(data) {
					if(!data.code) {
						$('#jdot-tips').remove();
						$('#hasNewResume').removeAttr('id')
					}
				},
				error : function() {

				}
			})
		})
	}


	//初始化
	Quannei.init = function() {
		//Quannei.login._Init();
		Quannei.search._Init();
		Quannei.subscribe._Init();
		Quannei.dailog._Init();
		Quannei.retrieve._Init();
		Quannei.MenuShow()
	}

	Quannei.init()
})

