<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.article_list.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div class="modal hide fade" id="movetype">
	<div class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3>{t}转移文章至分类{/t}</h3>
	</div>
	<div class="modal-body h300">
		<div class="row-fluid  ecjiaf-tac">
			<div>
				<select class="noselect no_search w200" size="15" name="target_cat">
					<option value="0">{$lang.all_cat}</option>
					<!-- {$cat_select} -->
				</select>
			</div>
			<div>
				<a class="btn btn-gebo m_l5" data-toggle="ecjiabatch" data-name="article_id" data-idClass=".checkbox:checked" data-url="{$form_action}&sel_action=move_to&" data-msg="是否将选中文章转移至分类？" data-noSelectMsg="请先选中要转移的文章！" href="javascript:;" name="move_cat_ture">{t}开始转移{/t}</a>
			</div>
		</div>
	</div>
</div>
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}"  id="sticky_a"><i class="fontello-icon-plus"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>

<!-- 批量操作和搜索 -->
<div class="row-fluid batch" >
	<form method="post" action="{$search_action}" name="searchForm">
		<div class="btn-group f_l m_r5">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="fontello-icon-cog"></i>{t}批量操作{/t}
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li><a class="button_remove" data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{url path='article/admin/batch' args='sel_action=button_remove'}" data-msg="您确定要这么做吗？" data-noSelectMsg="请先选中要删除的文章！" data-name="article_id" href="javascript:;"><i class="fontello-icon-trash"></i>{t}删除文章{/t}</a></li>
				<li><a class="button_hide"   data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{url path='article/admin/batch' args='sel_action=button_hide'}" data-msg="您确定要这么做吗？" data-noSelectMsg="请先选中要隐藏的文章！" data-name="article_id" href="javascript:;"><i class="fontello-icon-eye-off"></i>{t}隐藏{/t}</a></li>
				<li><a class="button_show"   data-toggle="ecjiabatch" data-idClass=".checkbox:checked" data-url="{url path='article/admin/batch' args='sel_action=button_show'}" data-msg="您确定要这么做吗？" data-noSelectMsg="请先选中要显示的文章！" data-name="article_id" href="javascript:;"><i class="fontello-icon-eye"></i>{t}显示{/t}</a></li>
				<li><a class="batch-move-btn" href="javascript:;" data-move="data-operatetype" data-name="move_cat"><i class="fontello-icon-exchange"></i>{t}转移分类{/t}</a></li>
			</ul>
		</div>
		<select class="w220" name="cat_id" id="select-cat">
			<option value="0">{$lang.all_cat}</option>
			<!-- {$cat_select} -->
		</select>
		<a class="btn m_l5 screen-btn">{t}筛选{/t}</a>
		<div class="choose_list f_r" >
			<input type="text" name="keywords" value="{$smarty.get.keywords}" placeholder="请输入文章名称"/>
			<button class="btn search_articles" type="button">{$lang.search_article}</button>
		</div>
	</form>
</div>
	
	<div class="row-fluid">
		<div class="span12">
			<form method="POST" action="{$form_action}" name="listForm" data-pjax-url="{RC_Uri::url('article/admin/init')}">
				<div class="row-fluid">
					<table class="table table-striped smpl_tbl table-hide-edit">
						<thead>
							<tr>
							    <th class="table_checkbox"><input type="checkbox" name="select_rows" data-toggle="selectall" data-children=".checkbox"/></th>
							    <th class="w500">{$lang.title}</th>
							    <th class="w200">{$lang.cat}</th>
							    <th class="w200">{$lang.add_time}</th>
							    <th class="w100">{$lang.is_open}</th>
						  	</tr>
						</thead>
						<tbody>
							<!-- {foreach from=$article_list.arr item=list} -->
							<tr>
							    <td>
							         <span><input type="checkbox" name="checkboxes[]" class="checkbox" value="{$list.article_id}" {if $list.cat_id lte 0 }disabled="disabled"{/if}/></span>
							    </td>
							    <td class="hide-edit-area">
							    	<span class="cursor_pointer" data-text="textarea" data-trigger="editable" data-url="{RC_Uri::url('article/admin/edit_title')}" data-name="{$list.cat_id}" data-pk="{$list.article_id}" data-title="编辑文章名称" >{$list.title}</span>
							    	<div class="edit-list">
										{assign var=view_url value=RC_Uri::url('article/admin/preview',"id={$list.article_id}")}
								      	<a class="data-pjax" href="{$view_url}" title="{$lang.view}">{t}预览{/t}</a>&nbsp;|&nbsp;
								      	{assign var=edit_url value=RC_Uri::url('article/admin/edit',"id={$list.article_id}")}
								      	<a class="data-pjax" href="{$edit_url}" title="{$lang.edit}">{t}编辑{/t}</a>&nbsp;|&nbsp; 
								      	{if $has_goods}
								      	<a class="data-pjax" href='{url path="article/admin/link_goods" args="id={$list.article_id}"}' title="关联商品">关联商品</a>&nbsp;|&nbsp; 
								      	{/if}
								     	<!-- {if $list.cat_id > 0} -->
								     	<a class="ajaxremove ecjiafc-red" data-toggle="ajaxremove" data-msg="{t}您确定要删除文章[{$list.title}]吗？{/t}" href='{RC_Uri::url("article/admin/remove","id={$list.article_id}")}' title="{t}移除{/t}">{t}删除{/t}</a>
								     	<!-- {/if} -->
								     </div>
								</td>
							    <td><span><!-- {if $list.cat_id > 0} -->{$list.cat_name|escape:html}<!-- {else} -->{$lang.reserve}<!-- {/if} --></span></td>
							    <td><span>{$list.date}</span><br><span>{if $list.article_type eq 0}{$lang.common}{else}{$lang.top}{/if}</span></td>
							    <td>
						    	<i class="{if $list.is_open eq '1'}fontello-icon-ok cursor_pointer{else}fontello-icon-cancel cursor_pointer{/if}" data-trigger="toggleState" data-url="{RC_Uri::url('article/admin/toggle_show')}" data-id="{$list.article_id}" ></i>
							    </td>
							</tr>
							<!-- {foreachelse} -->
							   <tr><td class="no-records" colspan="10">{t}没有找到任何记录{/t}</td></tr>
							<!-- {/foreach} -->
			            </tbody>
			         </table>
		         </div>
	         </form>
         </div>
	</div>
	<!-- {$article_list.page} -->
<!-- {/block} -->