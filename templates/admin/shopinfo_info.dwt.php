<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.shoparticle_info.init();
</script>

<!-- {/block} -->
<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		{if $action_link} 
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		{/if}
	</h3>
</div>
<div class="row-fluid edit-page">
	<div class="span12">
		<div class="tabbable">
			<form class="form-horizontal" action="{$form_action}" method="post" enctype="multipart/form-data" name="theForm" data-edit-url="{RC_Uri::url('article/admin_shopinfo/edit')}">
				<div class="control-group formSep">
					<div >
						<input type="text" name="title" size="40" maxlength="60" class="span10"  value="{$article.title}"  placeholder="在此输入网店标题"/> <span class="input-must">{$lang.require_field}</span>
					</div>
				</div>
				<div class="control-group formSep">
					<div>{ecjia:editor content=$article.content textarea_name='content'}</div>
				</div>
				<div class="foldable-list move-mod-group">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#collapse001">
								<strong>{t}SEO优化{/t}</strong>
							</a>
						</div>
						<div class="accordion-body collapse" id="collapse001">
							<div class="accordion-inner">
							<div class="control-group control-group-small" >
									<label class="control-label">{t}关键字：{/t}</label>
									<div class="controls">
										<input class="span12" type="text" name="keywords" value="{$article.keywords}" size="40" />
										<br />
										<p class="help-block w280 m_t5">{t}用英文逗号分隔{/t}</p>
									</div>
								</div>
								<div class="control-group control-group-small" >
									<label class="control-label">{t}简单描述：{/t}</label>
									<div class="controls">
										<textarea class="span12 h100" name="description" value="{$article.description}" cols="40" rows="3">{$article.description}</textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<p class="ecjiaf-tac">
					<button class="btn btn-gebo" type="submit">{$lang.button_submit}</button>
					<input type="hidden" name="old_title" value="{$article.title}"/>
					<input type="hidden" name="id" value="{$article.article_id}" />
				</p>
			</form>
		</div>
	</div>
</div>
<!-- {/block} -->