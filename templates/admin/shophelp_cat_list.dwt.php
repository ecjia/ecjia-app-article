<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.shophelp_list.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h3>
</div>

<div class="row-fluid editpage-leftbar">
	<div class="left-bar">
		<form  class="form-horizontal"  action="{$form_action}" name="addcatForm"  method="post">
            <h4>添加帮助分类：</h4> <br>
            <input type="text" name="cat_name" id="keyword" /><br><br>
            <button class="btn btn-gebo" type="submit">{$lang.cat_add_confirm}</button>
        </form>
	</div>
	<div class="right-bar">
			<table class="table dataTable table-hide-edit">
			<thead>
				<tr>
					<th class="w350">{$lang.cat_name}</th>
					<th class="w50">{$lang.sort}</th>
					<th>{$lang.handler}</th>
				</tr>
			</thead>
			<tbody>
				<!-- {foreach from=$list  item=item} -->
				<tr>
					<td>
						<span class="article_edit_catname cursor_pointer"  data-trigger="editable" data-url="{RC_Uri::url('article/admin_shophelp/edit_catname')}" data-name="title" data-pk="{$item.cat_id}"  data-title="编辑帮助分类名称">{$item.cat_name|escape:html}</span>
					</td>
					<td align="left"><span class="article_edit_catorder cursor_pointer" data-trigger="editable" data-url="{RC_Uri::url('article/admin_shophelp/edit_cat_order')}" data-name="title" data-pk="{$item.cat_id}"  data-title="编辑帮助分类排序">{$item.sort_order}</span></td>
					<td align="center">
						<span>
							{assign var=view_url value=RC_Uri::url('article/admin_shophelp/add',"cat_id={$item.cat_id}")}
							<a class="data-pjax no-underline" href="{$view_url}" target="_blank" title="{$lang.lang_article_add}"><i class="fontello-icon-pencil-squared"></i></a>&nbsp;
							{assign var=articlelist_url value=RC_Uri::url('article/admin_shophelp/list_article',"cat_id={$item.cat_id}")}
							<a class="data-pjax no-underline" href="{$articlelist_url}" title="{$lang.article_list}"><i class=" fontello-icon-doc-text-inv"></i></a>&nbsp;
							<a class="ajaxremove no-underline" data-toggle="ajaxremove" data-msg="{t}您确定要删除帮助分类[{$item.cat_name}]吗？{/t}" href='{RC_Uri::url("article/admin_shophelp/remove","cat_id={$item.cat_id}")}' title="{t}移除{/t}"><i class="fontello-icon-trash"></i></a>
						</span>
					</td>
				</tr>
				<!-- {foreachelse} -->
				<tr><td class="no-records" colspan="10">{$lang.no_article}</td></tr>
				<!-- {/foreach} -->
			</tbody>
		</table>
	</div>
</div>
<!-- {$article_list.page} -->
<!-- {/block} -->