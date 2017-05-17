<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="home-content"} -->
<div class="page-header">
	<h2 class="pull-left">
		<!-- {if $ur_here}{$ur_here}{/if} -->
	</h2>
	<div class="pull-right">
	   {if $action_link}
		<a class="btn btn-primary data-pjax" href="{$action_link.href}" id="sticky_a" ><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		{/if}
		{if $action_linkedit}
		<a class="btn btn-primary data-pjax" href="{$action_linkedit.href}" id="sticky_a" ><i class="fontello-icon-edit"></i>{$action_linkedit.text}</a>
		{/if}
	</div>	
	<div class="clearfix"></div>
</div>
<div class="row-fluid edit-page">
	<div class="span12">
		<div class="tabbable">
			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<h3 class="text-center">{$article.title}</h3>
					<p  class="text-center">{lang key='article::article.author'}：{$article.author}&nbsp;&nbsp;{lang key='article::article.add_time'}：{$article.add_time}</p>
					{if $article.open_type == 2}
						<p>
						{$article.content}
						<a href="{RC_Upload::upload_url()}/{$article.file_url}">{lang key='article::article.related_download'}</a>
						</p>
					{elseif $article.open_type == 1}
						<a href="{RC_Upload::upload_url()}/{$article.file_url}">{lang key='article::article.related_download'}</a>
					{else}
						{if $article.file_url != ''}
							<img  class="thumbnail" src="{RC_Upload::upload_url()}/{$article.file_url}" style="max-width:800px; margin:0 auto">
						{/if}
						{$article.content}
					{/if}
				</div>
			</div>
		</div>
	</div>
</div>
<!-- {/block} -->