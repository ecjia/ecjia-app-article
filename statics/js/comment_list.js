// JavaScript Document
;(function (app, $) {
    app.comment_list = {
        init: function () {
            //搜索功能
            $("form[name='searchForm'] .search_articles").on('click', function (e) {
                e.preventDefault();
                var url = $("form[name='searchForm']").attr('action');
                var keywords = $("input[name='keywords']").val();
                if (keywords != '') {
                    url += '&keywords=' + keywords;
                }
                ecjia.pjax(url);
            });
            app.comment_list.review_static();//状态审核
        },
		review_static: function() {
			$('.review_static').each(function() {
				var $this = $(this);
				var oldval = $this.text();
				var url = $this.attr('data-url');
				var name = $this.attr('data-name') || 0;
				var pk = $this.attr('data-pk') || 0;
				var title = $this.attr('data-title');
				var type = $this.attr('data-text') || 'text';
				if (!name || !pk || !url) {
					console.log('editable缺少参数');
					return;
				}
				var pjaxurl = $this.attr('data-pjax-url') || '';
				$this.editable({
					source: [{
						value: 0,
						text: '待审核'
					}, {
						value: 1,
						text: '审核通过'
					}, {
						value: 'trash',
						text: '回收站'
					}, {
						value: 'spam',
						text: '垃圾评论'
					}],
					url: url,
					name: name,
					pk: pk,
					type: type,
					dataType: 'json',
					success: function(data) {
						if (data.state == 'error') return data.message;
						if (pjaxurl != '') {
							ecjia.pjax(pjaxurl, function() {
								ecjia.admin.showmessage(data);
							});
						} else {
							ecjia.admin.showmessage(data);
						}
					}
				});
			}).on('shown', function(e) {
				if ($(".editable-container select option").length) {
					$(".editable-container select").chosen({
						add_class: "down-menu-language",
						no_results_text: "未找到搜索内容!",
						allow_single_deselect: true,
						disable_search_threshold: 8
					});
				}
			});
		},
    }
})(ecjia.admin, jQuery);
 
// end