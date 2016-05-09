<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} --><small>{t}（共{$list.num}条）{/t}</small>
		{if $action_linkadd}
		<a class="btn plus_or_reply data-pjax" href="{$action_linkadd.href}" id="sticky_a" ><i class="fontello-icon-plus"></i>{$action_linkadd.text}</a>
		{/if}
		
		{if $back_helpcat}
		<a class="btn plus_or_reply data-pjax" href="{$back_helpcat.href}" id="sticky_a"><i class="fontello-icon-reply"></i>{$back_helpcat.text}</a>
		{/if}
	</h3>
</div>
<div>
	<table class="table table-striped" id="smpl_tbl">
		<thead>
			<tr>
				<th>{$lang.title}</th>
				<th>{$lang.add_time}</th>
				<th>{$lang.handler}</th>
			</tr>
		</thead>
		<tbody>
			<!-- {foreach from=$list.item item=item}-->
			<tr>
				<td>
					<input type="hidden" value="{$item.article_id}" />
					<span class="article_info_name">{$item.title|escape:html}</span>
				</td>
				
				<td align="right"><span>{$item.add_time}</span></td>
				<td align="right">
					<span>
						<a class="data-pjax no-underline" href='{url path="article/admin_shophelp/edit" args="cat_id=$cat_id&id={$item.article_id}"}' title="{$lang.edit}"><i class="fontello-icon-edit"></i></a>
						<a class="ajaxremove no-underline" data-toggle="ajaxremove" data-msg="{t}您确定要删除帮助文章[{$item.title}]吗？{/t}" href='{RC_Uri::url("article/admin_shophelp/remove_art", "id={$item.article_id}")}' title="{t}移除{/t}"><i class="fontello-icon-trash"></i></a>
					</span>
				</td>
			</tr>
			<!-- {foreachelse} -->
			<tr><td class="no-records" colspan="10">{$lang.no_records}</td></tr>
			<!-- {/foreach} -->
		</tbody>
	</table>
	<!-- {$list.page} -->
</div>
<!-- {/block} -->