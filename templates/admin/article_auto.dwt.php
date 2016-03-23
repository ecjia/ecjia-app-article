<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
ecjia.admin.article_auto.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div class="admin_article article_article_auto">
	<div>
		<h3 class="heading">
			<!-- {if $ur_here}{$ur_here}{/if} -->
			{if $action_link}
			<a href="{$action_link.href}" class="btn plus_or_reply" id="sticky_a"><i class="fontello-icon-plus"></i>{$action_link.text}</a>
			{/if}
		</h3>
	</div>
	
	<div class="form-div">
		{if !$crons_enable}
		<ul style="list-style-type: none;margin:0px;">
			<li class="alert" >{$lang.enable_notice}</li>
		</ul>
		{/if}
	</div>
	
	<div class="row-fluid">
		<div class="choose_list span12">
			<form method="post" action="" name="listForm">
				<div class="controls promote_date">
					<div class="input-append date">
						<input name="use_start_date" type="text" class="date" id="use_start_date" size="22" value='{$bonus_arr.use_start_date}' readonly="readonly" />
						<span class="add-on"><i class="fontello-icon-calendar"></i></span>
					</div>
				</div>  
				<input type="button" class="btnSubmit btn" value="{$lang.button_start}" disabled="disabled" onClick="return validate('batch_start')" />
				<input type="button" class="btnSubmit btn" value="{$lang.button_end}" disabled="disabled"  onClick="return validate('batch_end')" />
			</form>
			<form action="{$searcharticle}" method="post" class="f_r" name="searchForm">
				<span>{$lang.article_name}</span>
				<input type="hidden" name="act" value="init" />
				<input name="goods_name" type="text" size="25" class="keyword"/> 
				<input type="submit" value="{$lang.button_search}" class="btn" />
			</form>
		</div>
	</div>
	<div class="row-fluid">

		
		<table class="table table-striped smpl_tbl">
			<thead>
				<tr>
					<th width="5%"><input type="checkbox" class="checkbox"/></th>
					<th>{$lang.id}</th>
					<th>{$lang.articleatolist_name}</th>
					<th width="25%">{$lang.starttime}</th>
					<th width="25%">{$lang.endtime}</th>
					<th width="10%">{$lang.handler}</th>
				</tr>
			</thead>
			<tbody>
				<!-- {foreach from=$goodsdb.goodsdb item=val} -->
				<tr>
					<td><input name="checkboxes[]" type="checkbox" value="{$val.goods_id}" class="uni_style checkbox"/></td>
					<td>{$val.goods_id}</td>
					<td>{$val.goods_name}</td>
					<td class="first-cell">
						<input type="hidden" value="{$val.goods_id}" />
						<span class="auto_time_start">
							<!-- {if $val.starttime} -->
							<span>{$val.starttime}</span>
							<!-- {else} -->
							<span>0000-00-00</span>
							<!-- {/if} -->
						</span>
					</td>
					<td align="center">
						<span class="auto_time_end">
							<!-- {if $val.endtime} -->{$val.endtime}<!-- {else} -->0000-00-00<!-- {/if} -->
						</span>
					</td>
					<td align="center">
						<span id="del{$val.goods_id}">
							{if $val.endtime || $val.starttime}
							<a href="{$thisfile}&goods_id={$val.goods_id}&a=del">{$lang.delete}</a>
							{else}
							-
							{/if}
						</span>
					</td>
				</tr>
				<!-- {foreachelse} -->
				<tr><td class="no-records" colspan="10">{$lang.no_records}</td></tr>
				<!-- {/foreach} -->
			</tbody>
		</table>
	</div>
	<!-- {$goodsdb.page} -->
</div>
<!-- {/block} -->