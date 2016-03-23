// JavaScript Document
;
(function(app, $) {
	app.article_cat_info = {
		init : function() {
			/* 给catinfo表单加入submit事件 */
			var $form = $("form[name='theCatInfoForm']");
			var option = {
				rules : {
					cat_name : {
						required : true
					},
				},
				messages : {
					cat_name : {
						required : "请输入文章分类名称！"
					},
				},
				submitHandler : function() {
					$form.ajaxSubmit({
						dataType : "json",
						success : function(data) {
							ecjia.admin.showmessage(data);
						}
					});
				}
			}
			var options = $.extend(ecjia.admin.defaultOptions.validate, option);
			$form.validate(options);
		},
		
	};
	app.article_cat_list = {
			
		/* 折叠分类列表 */
		rowClicked : function (obj) {
		  	// 当前图像
		  	img = obj;
		  	// 取得上二级tr>td>img对象
		  	obj = obj.parentNode.parentNode;
		  	// 整个分类列表表格
		  	var tbl = document.getElementById("list-table");
		  	// 当前分类级别
		  	var lvl = parseInt(obj.className);
		  	// 是否找到元素
		  	var fnd = false,
		  		has_child = false;
		  	var sub_display = $(obj).find('.open_state').hasClass("is_open") ? 'none' : '';
		  	
		  	// 遍历所有的分类
		  	for (i = 0; i < tbl.rows.length; i++) {
		        var row = tbl.rows[i];
		      	if (row == obj) {
		          	// 找到当前行
		          	fnd = true;
				} else{
		          	if (fnd == true) {
		              	var cur = parseInt(row.className);
		              	var icon = 'icon_' + row.id;
		              	if (cur > lvl) {
			          		has_child = true;
		                  	row.style.display = sub_display;
		                  	if (sub_display != 'none') {
		                      	$(obj).find('.open_state').attr('class', 'open_state is_open');
		                  	} else {
		                  		$(obj).find('.open_state').attr('class', 'open_state is_close');
		                  	}
		              	} else {
		                  	fnd = false;
		                  	break;
		              	}
		          	}
		      	}
		  	}
		  	
		  	if (!has_child) {
		  		$(obj).find('.open_state').hasClass('is_open') ? $(obj).find('.open_state').attr('class', 'open_state is_close m_r10') : $(obj).find('.open_state').attr('class', 'open_state is_open m_r10');
		  	}

		}
	};
})(ecjia.admin, jQuery);
// end
