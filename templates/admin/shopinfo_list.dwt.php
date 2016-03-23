<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		{if $action_link}
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a"><i class="fontello-icon-plus"></i>{$action_link.text}</a>
		{/if}
	</h3>
</div>
<div>
	<table class="table table-striped" id="smpl_tbl">
		<thead>
			<tr>
				<th>{$lang.id}</th>
				<th>{$lang.title}</th>
				<th>{$lang.add_time}</th>
				<th>{$lang.handler}</th>
			</tr>
		</thead>
		<tbody>
			<!-- {foreach from=$list item=item}-->
			<tr>
				<td align="center"><span>{$item.article_id}</span></td>
				<td>
					<input type="hidden" value="{$item.article_id}" />
					<span class="article_info_name">{$item.title|escape:html}</span>
				</td>
				<td align="right"><span>{$item.add_time}</span></td>
				<td align="right">
					<span>
						{assign var=edit_url value=RC_Uri::url('article/admin_shopinfo/edit',"id={$item.article_id}")}
						<a class="data-pjax no-underline" href="{$edit_url}" title="{$lang.edit}"><i class="fontello-icon-edit"></i></a>
						<a class="ajaxremove no-underline" data-toggle="ajaxremove" data-msg="{t}您确定要删除网店信息[{$item.title}]吗？{/t}" href="{RC_Uri::url('article/admin_shopinfo/remove',"id={$item.article_id}")}" title="{t}移除{/t}"><i class="fontello-icon-trash"></i></a>
					</span>
				</td>
			</tr>
			<!-- {foreachelse} -->
			<tr><td class="no-records" colspan="10">{$lang.no_records}</td></tr>
			<!-- {/foreach} -->
		</tbody>
	</table>
	<!-- {$ads_list.page} -->
</div>
<!-- {/block} -->