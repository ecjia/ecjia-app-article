// JavaScript Document

;(function(app, $) {
	app.shophelp_list = {
		init : function() {
			/* 帮助信息列表 添加分类form提交*/
			$form = $("form[name='addcatForm']");
			var option = {    
				submitHandler:function(){
					$form
					.bind('form-pre-serialize', function(event, form, options, veto) {
						(typeof(tinyMCE) != "undefined") && tinyMCE.triggerSave();
					})
					.ajaxSubmit({
						dataType:"json",
						success:function(data){
							if (data.state == "success") {
								if(data.pjax) {
									var url = data.pjax;
									ecjia.pjax(url, function(){
										ecjia.admin.showmessage(data);
									});
								} else {
									ecjia.admin.showmessage(data);
								}
							} else {
								ecjia.admin.showmessage(data);
							}	
						}
					});
				}
			}
			var options = $.extend(ecjia.admin.defaultOptions.validate, option);
			$form.validate(options);
		},
		shophelp_article_info : function() {
			$form = $("form[name='theForm']");
			var option = {
				rules : {
					title : { required : true },
				},
				messages : {
					title : { required : "请输入帮助文章标题！" },
				},
				submitHandler : function() {
					$form
					.bind('form-pre-serialize', function(event, form, options, veto) {
						(typeof(tinyMCE) != "undefined") && tinyMCE.triggerSave();
					})
					.ajaxSubmit({
						dataType : "json",
						success : function(data) {
							ecjia.admin.showmessage(data);
						}
					});
				}
			}
			var options = $.extend(ecjia.admin.defaultOptions.validate, option);
			$form.validate(options);
		}
	};
 
})(ecjia.admin, jQuery);

// end
