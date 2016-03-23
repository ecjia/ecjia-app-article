// JavaScript Document
;(function(app, $) {
	app.article_auto = {
		init : function() {
			/* 加载日期控件 */
			$(".date").datepicker({
				format : "yyyy-mm-dd"
			});
			var url1 = $(".starttime").attr("data-name");
			$('.starttime').editable({
				name : 'starttime',
				type : 'text',
				dataType : 'json',
				pk : function() {
					return $(this).prev().attr('value');
				},
				url : url1,
				title : '编辑发布时间',
				success : function(data) {
					var pjaxurl = $(this).attr("data-pjax-url");
					if (data.state == "success") {
						ecjia.pjax(pjaxurl, function() {
							ecjia.admin.showmessage(data);
						});
					} else {
						var old_name = $(this).attr('data-name-value');
						ecjia.pjax(pjaxurl, function() {
							$(this).attr("value", old_name);
							ecjia.admin.showmessage(data);
						});
					}
				}
			});
		},
	};
})(ecjia.admin, jQuery);

// end