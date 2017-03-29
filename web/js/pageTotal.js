$(function() {
	if(window.pageTotal) {return}
	window.pageTotal = {};
	var _type = ["",""];
	function getDataList(_arr, _page) {
		$('#J_jobList').html('<div class="__loading"><img src="'+$_Path+'/images/loader.gif" alt=""></div>');
		var params = {
			type : 'GET',
			url : apiUrl._search,
			dataType : 'json',
			data : {
				comType : _arr[0],
				positionType : _arr[1],
				page : _page
			},
			callback : function(data) {
				var _jobList = data.jobList, len = _jobList.length, _result = [];

				if(len > 0) {
					_result = Quannei.extend(_jobList)

					$.each(_result, function(i, o) {
						for(var n in o) {
							switch (n) {
								case 'publisherName' :
									o[n] = Quannei.common._cutstr(o[n], 16);
									break;
							}
						}
					})


					if(!_page) {
						pageTotal.init(data);
					}
					$(".tw-text").html(data.totalJobCount +'<span>个机会</span>')
					$('#J_jobList').empty();
					$('#job-list-tmpl').tmpl(_result).appendTo('#J_jobList');
				}
				else {
					$(".tw-text").html(data.totalJobCount +'<span>个机会</span>')
					$("#J_pages").empty();
					$('#J_jobList').html('<div style="text-align:center;font-size:18px;color:#4d586e;">未查找到相应岗位</div>')
				}
			}
		};
		apiUrl.getJSON(params)
	}
	getDataList(_type)

	pageTotal = {
		init : function(data) {
			pageTotal.current = data.currPage, //当前页
			pageTotal.pageCount = 10, //每页显示的数据量
			pageTotal.total = data.pageCount, //总共的页码
			pageTotal.first = 1, //首页
			pageTotal.last = 0, //尾页
			pageTotal.pre = 0, //上一页
			pageTotal.next = 0, //下一页
			pageTotal.getDate(1,0)
		},
		getPages: function() {
			pageTotal.last = pageTotal.total;
			pageTotal.pre = pageTotal.current - 1 <= 0 ? 1 : (pageTotal.current - 1);
			pageTotal.next = pageTotal.current + 1 >= pageTotal.total ? pageTotal.total : (pageTotal.current + 1);
		},
		//获取数据
		getDate: function(pageno, type) {

			//清除content所有数据和元素
			$("#J_pages").empty();
			if (pageno == null) {
				pageno = 1;
			}
			//设置当前页
			pageTotal.current = pageno;

			if(type) {
				getDataList(_type, pageTotal.current)
				$('body,html').animate({scrollTop:0},200);
			}

			//获取分页样式
			pageTotal.page(type); //type表示分页栏样式
		},
		page: function(type) {
			//清除分页栏元素
			$("#J_pages").empty();
			//填充分页样式欠要加载分页数据
			pageTotal.getPages();
			//if (type == 1) {
			var type = 1
				var x = 4;
				//设置上下页

				if(pageTotal.total > x) {
					var index = pageTotal.current <= Math.ceil(x / 2) ? 1 : (pageTotal.current) >= pageTotal.total - Math.ceil(x / 2) ? pageTotal.total - x : (pageTotal.current - Math.ceil(x / 2));

					var end = pageTotal.current <= Math.ceil(x / 2) ? (x + 1) : (pageTotal.current + Math.ceil(x / 2)) >= pageTotal.total ? pageTotal.total : (pageTotal.current + Math.ceil(x / 2));
				}
				else {
					var index = 1;

					var end = pageTotal.total;
				}
				if (pageTotal.current > 1) {
					$("#J_pages").append("<li><a href='javascript:pageTotal.getDate(" + (pageTotal.current - 1) + "," + type + ");'>&lt;</a></li>");
				}

				console.log(index + '////'+end)

				for (var i = index; i <= end; i++) {
					if (i == pageTotal.current) {
						$("#J_pages").append("<li><a href='javascript:pageTotal.getDate(" + pageTotal.current + "," + type + ");' class='on'>" + i + "</a></li>");
					} else {
						$("#J_pages").append("<li><a href='javascript:pageTotal.getDate(" + i + "," + type + ");'>" + i + "</a></li>");
					}
				}

				if (end != pageTotal.total) {
					$("#J_pages").append("<li class='pt'><a href='javascript:;'>...</a></li>");
					$("#J_pages").append("<li><a href='javascript:pageTotal.getDate(" + pageTotal.total + "," + type + ");'>" + pageTotal.total + "</a></li>");
				}

				if (pageTotal.current < end) {
					$("#J_pages").append("<li><a href='javascript:pageTotal.getDate(" + (pageTotal.current + 1) + "," + type + ");'>&gt;</a></li>");
				}
			//}
		}
	};

	$('#J_companyType').find('.regular-radio').on('click', function() {
		_type = [];
		_type.push($(this).data('type'));
		_type.push("");
		$('body,html').animate({scrollTop:0},200);
		getDataList(_type)
	})
	$('#J_positionType').find('.regular-radio').on('click', function() {
		_type = [];
		_type.push("");
		_type.push($(this).data('type'));
		$('body,html').animate({scrollTop:0},200);
		getDataList(_type)
	})

})
