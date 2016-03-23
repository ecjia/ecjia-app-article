// JavaScript Document
;
(function(app, $) {
	app.shoparticle_info = {
		init : function() {
			$form = $('form[name="theForm"]');
			
			var option = {
				rules : { title : { required : true }, },
				messages : {
					title : { required : "请输入网店标题！" },
				},
				submitHandler : function() {
					$("form[name='theForm']")
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
			$("form[name='theForm']").validate(options);
		}
	};
	//$(app.shoparticle_info.init).on('pjax.end', '.main_content', app.shoparticle_info.init);	
})(ecjia.admin, jQuery);
// end
