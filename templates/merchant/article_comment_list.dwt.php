<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.merchant.article_list.init();
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
		<a class="btn btn-primary data-pjax" href="{$article_list}" id="sticky_a"><i class="fa fa-reply"></i><i class="fontello-icon-plus"></i> {lang key='article::article.article_list'}</a>
	</div>
	<div class="clearfix">
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel">
			<div class="panel-body panel-body-small">
				<ul class="nav nav-pills pull-left">
					<li class="{if $type eq ''}active{/if}"><a class="data-pjax" href='{url path="article/merchant/article_comment" args="id={$id}&{if $filter.keywords}&keywords={$filter.keywords}{/if}"}'>{lang key='article::article.all'} <span class="badge badge-info">{if $type_count.count}{$type_count.count}{else}0{/if}</span></a></li>
					<li class="{if $type eq 'has_checked'}active{/if}"><a class="data-pjax" href='{url path="article/merchant/article_comment" args="id={$id}&type=has_checked{if $filter.keywords}&keywords={$filter.keywords}{/if}"}'>{lang key='article::article.has_checked'}<span class="badge badge-info">{if $type_count.has_checked}{$type_count.has_checked}{else}0{/if}</span></a></li>
					<li class="{if $type eq 'wait_check'}active{/if}"><a class="data-pjax" href='{url path="article/merchant/article_comment" args="id={$id}&type=wait_check{if $filter.keywords}&keywords={$filter.keywords}{/if}"}'>{lang key='article::article.wait_check'}<span class="badge badge-info">{if $type_count.wait_check}{$type_count.wait_check}{else}0{/if}</span></a></li>
					<li class="{if $type eq 'unpass'}active{/if}"><a class="data-pjax" href='{url path="article/merchant/article_comment" args="id={$id}&type=unpass{if $filter.keywords}&keywords={$filter.keywords}{/if}"}'>{lang key='article::article.unpass'}<span class="badge badge-info">{if $type_count.unpass}{$type_count.unpass}{else}0{/if}</span></a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			
			<div class="panel-body panel-body-small">	
				<form class="form-inline" method="post" action="{$search_action}" name="searchForm">
					<div class="btn-group f_l m_r5">
						<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="fa fa-cogs"></i>
							<i class="fontello-icon-cog"></i>{lang key='article::article.batch'}
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a class="button_remove" data-toggle="ecjiabatch" data-idclass=".checkbox:checked" data-url="{url path='article/merchant/remove_comment'}&type=batch&article_id={$id}" data-msg="{lang key='article::article.confirm_drop'}" data-noselectmsg="{lang key='article::article.select_drop_comment'}" data-name="id" href="javascript:;"><i class="fa fa-trash-o"></i><i class="fontello-icon-trash"></i> {lang key='article::article.drop_comment'}</a></li>
						</ul>
					</div>
					
					<div class="f_r form-group">
						<input type="text" name="keywords" class="form-control" value="{$smarty.get.keywords}" placeholder="{lang key='article::article.enter_comment_username'}"/>
						<a class="btn btn-primary m_l5 search_articles"><i class="fa fa-search"></i> {lang key='system::system.button_search'}</a>
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
							<th class="w200">
								{lang key='article::article.user_name'}
							</th>
							<th>
								{lang key='article::article.comment_detail'}
							</th>
							<th class="w180">
								{lang key='article::article.label_comment_time'}
							</th>
						</tr>
					</thead>
					<tbody>
					<!-- {foreach from=$data.arr item=list} -->
						<tr>
							<td class="check-list">
								<div class="check-item">
									<input id="check_{$list.id}" class="checkbox" type="checkbox" name="checkboxes[]" value="{$list.id}"/>
									<label for="check_{$list.id}"></label>
								</div>
							</td>
							<td>{$list.user_name}</td>
							<td class="hide-edit-area">
								<span>{$list.title}</span>
								<br>
								<span>{$list.content}</span>
								<div class="edit-list">
									<a class="ajaxremove ecjiafc-red" data-toggle="ajaxremove" data-msg="{lang key='article::article.drop_comment_confirm'}" href='{RC_Uri::url("article/merchant/remove_comment", "id={$list.id}&article_id={$list.id_value}")}' title="{lang key='system::system.remove'}">{lang key='system::system.drop'}</a>
								</div>
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
				<!-- {$data.page} -->
			</div>
		</div>
	</div>
</div>
<!-- {/block} -->