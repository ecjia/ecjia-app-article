<?php defined('IN_ECJIA') or exit('No permission resources.');?>

<div class="move-mod-group" id="widget_admin_dashboard_articlestats">
	<div class="heading clearfix move-mod-head">
		<h3 class="pull-left">{$article_title}</h3>
	</div>
	<div class="heading clearfix move-mod-head no-border">
		<h3 class="pull-left">最新发布</h3>
	</div>
	<table class="table table-striped ecjiaf-wwb article_stats_table">
		<tbody>
			<!-- {foreach from=$article item=val} -->
			<tr>
				<td>
					<p class="m_b5">
						<a href="{RC_Uri::url('article/admin/preview')}&id={$val.article_id}" target="_black" title="{$val.title}">{$val.title}</a>
						<span class="ecjiaf-fr">{RC_Time::local_date('Y-m-d H:i:s', $val.add_time)}</span>
					</p>
				</td>
			</tr>
			<!-- {/foreach} -->
		</tbody>
	</table>
	<div class="ecjiaf-tar"><a href="{RC_Uri::url('article/admin/init')}" title="查看更多">查看更多</a></div>
</div>




<style type="text/css">
	.heading.no-border {
		border: none;
		margin-bottom: 10px;
	}
	.table.article_stats_table td {
		border-top: none;
		border-bottom: 1px solid #eee;
	}
	.table.article_stats_table tr:last-child td {
		border-bottom: none;
	}
</style>