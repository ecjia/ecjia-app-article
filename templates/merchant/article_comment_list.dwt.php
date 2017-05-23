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
				<form class="form-inline" method="post" action="{$search_action}" name="searchForm">
					<div class="f_r form-group">
						<input type="text" name="keywords" class="form-control" value="{$smarty.get.keywords}" placeholder="{lang key='article::article.enter_comment_username'}"/>
						<a class="btn btn-primary m_l5 search_articles"><i class="fa fa-search"></i> {lang key='system::system.button_search'}</a>
					</div>
					<input type="hidden" value="{$data.article_id}" name="article_id" />
				</form>
			</div>
			
			<div class="panel-body panel-body-small">
				<table class="table table-striped table-hover table-hide-edit ecjiaf-tlf">
					<thead>
						<tr>
							<th class="w200">
								{lang key='article::article.user_name'}
							</th>
							<th>
								{lang key='article::article.comment_detail'}
							</th>
							<th class="w150">
								{lang key='article::article.label_comment_time'}
							</th>
						</tr>
					</thead>
					<tbody>
					<!-- {foreach from=$data.arr item=list} -->
						<tr>
							<td>{$list.user_name}</td>
							<td>
								<span>
									{$list.title}<br>
									{$list.content}
								</span>
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