<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
// 	ecjia.merchant.article_list.init();
</script>
<!-- {/block} -->
<!-- {block name="home-content"} -->

<div class="page-header">
	<h2 class="pull-left">
	<!-- {if $ur_here}{$ur_here}{/if} -->
	<!-- {if $action_link} -->
	<!-- {/if} -->
	</h2>
	<div class="pull-right">
		<a class="btn btn-primary data-pjax" href="{$action_link.href}" id="sticky_a"><i class="fa fa-plus"></i><i class="fontello-icon-plus"></i> {$action_link.text}</a>
	</div>
	<div class="clearfix">
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel">
			<div class="panel-body panel-body-small">
				<form class="form-inline" method="post" action="{$search_action}" name="searchForm">
					<div class="f_r form-group">
						<input type="text" name="keywords" class="form-control" value="{$smarty.get.keywords}" placeholder="{lang key='article::article.enter_article_title'}"/>
						<a class="btn btn-primary m_l5 search_articles"><i class="fa fa-search"></i> 筛选</a>
					</div>
				</form>
			</div>
			
			<div class="panel-body panel-body-small">
				<table class="table table-striped table-hover table-hide-edit ecjiaf-tlf">
					<thead>
						<tr>
							<th class="table_checkbox check-list w30">
								<div class="check-item">
									<input id="checkall" type="checkbox" name="select_rows" data-toggle="selectall" data-children=".checkbox"/>
									<label for="checkall"></label>
								</div>
							</th>
							<th>
								{lang key='article::article.title'}
							</th>
							<th class="w200">
								{lang key='article::article.cat'}
							</th>
							<th class="w250">
								{lang key='article::article.add_time'}
							</th>
						</tr>
					</thead>
					<tbody>
					<!-- {foreach from=$article_list.arr item=list} -->
						<tr>
							<td class="check-list">
								<div class="check-item">
									<input id="check_{$list.article_id}" class="checkbox" type="checkbox" name="checkboxes[]" value="{$list.article_id}"/>
									<label for="check_{$list.article_id}"></label>
								</div>
							</td>
							<td class="hide-edit-area">
								<span class="cursor_pointer" data-text="textarea" data-trigger="editable" data-url="{RC_Uri::url('article/merchant/edit_title')}" data-name="{$list.cat_id}" data-pk="{$list.article_id}" data-title="{lang key='article::article.edit_article_title'}">{$list.title}</span>
								<div class="edit-list">
									<a class="data-pjax" href='{RC_Uri::url("article/merchant/preview", "id={$list.article_id}")}' title="{lang key='article::article.view'}">{lang key='article::article.view'}</a>&nbsp;|&nbsp;
									<a class="data-pjax" href='{RC_Uri::url("article/merchant/edit", "id={$list.article_id}")}' title="{lang key='system::system.edit'}">{lang key='system::system.edit'}</a>&nbsp;|&nbsp; 
									{if $has_goods}
									<a class="data-pjax" href='{url path="article/merchant/link_goods" args="id={$list.article_id}"}' title="{lang key='article::article.tab_goods'}">{lang key='article::article.tab_goods'}</a>&nbsp;|&nbsp; 
									{/if}
									{if $list.cat_id gt 0}
									<a class="ajaxremove ecjiafc-red" data-toggle="ajaxremove" data-msg="{lang key='article::article.drop_confirm'}" href='{RC_Uri::url("article/merchant/remove", "id={$list.article_id}")}' title="{lang key='system::system.remove'}">{lang key='system::system.drop'}</a>
									{/if}
								</div>
							</td>
							<td>
								<span>{if $list.cat_id gt 0}{$list.cat_name|escape:html}{else}{lang key='article::article.reserve'}{/if}</span>
							</td>
							<td>
								<span>{$list.date}</span>
							</td>
						</tr>
						<!-- {foreachelse} -->
						<tr>
							<td class="no-records" colspan="5">
								{lang key='system::system.no_records'}
							</td>
						</tr>
					<!-- {/foreach} -->
					</tbody>
				</table>
				<!-- {$article_list.page} -->
			</div>
		</div>
	</div>
</div>
<!-- {/block} -->