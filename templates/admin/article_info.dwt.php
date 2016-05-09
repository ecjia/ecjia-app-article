<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.article_info.init();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		{if $action_link}
		<a class="btn plus_or_reply data-pjax" href="{$action_link.href}" id="sticky_a" ><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		{/if}
	</h3>
</div>

<!-- start goods form -->
<div class="row-fluid ">
	<div class="span12">
		<div class="tabbable">
			{if $action eq 'edit' && $has_goods}
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab1" data-toggle="tab">{$lang.tab_general}</a></li>
				<!--<li><a href="#tab2" data-toggle="tab">{$lang.tab_content}</a></li> -->
				<li><a class="data-pjax" href='{url path="article/admin/link_goods" args="id={$smarty.get.id}"}'>{$lang.tab_goods}</a></li>
			</ul>
			{/if}
			<form class="form-horizontal" action="{$form_action}" method="post" enctype="multipart/form-data" name="infoForm" data-edit-url="{RC_Uri::url('article/admin/edit')}">
				<div class="tab-content">
					<div class="tab-pane active" id="tab1">
					<fieldset>
							<div class="row-fluid edit-page editpage-rightbar">
								<div class="left-bar move-mod">
									<!--左边-->
									<div class="control-group" >
										<div>
											<input type="text" name="title" class="span10"  value="{$article.title|escape}" placeholder="在此输入文章标题" />
											<span class="input-must">{$lang.require_field}</span>
										</div>
									</div>

									<div class="control-group" >
									 	<label>{t}外部链接：{/t}</label>
										<div>
											<input type="text" name="link_url" class="span10" value="{if $article.link neq ''}{$article.link|escape}{else}http://{/if}" />
											<br><span class="help-block">{t}若输入外部链接，则该链接优先{/t}</span>
										</div>
									</div>

									<div class="foldable-list move-mod-group" id="goods_info_sort_seo">
										<div class="accordion-group">
											<div class="accordion-heading">
												<a class="accordion-toggle collapsed move-mod-head acc-in" data-toggle="collapse" data-target="#goods_info_area_seo">
													<strong>{t}SEO优化{/t}</strong>
												</a>
											</div>
											<div class="accordion-body in collapse" id="goods_info_area_seo">
												<div class="accordion-inner">
												<div class="control-group control-group-small" >
														<label class="control-label">{t}关键字：{/t}</label>
														<div class="controls">
															<input class="span12" type="text" name="keywords" value="{$article.keywords|escape}" size="40" />
															<br />
															<p class="help-block w280 m_t5">{t}用英文逗号分隔{/t}</p>
														</div>
													</div>
													<div class="control-group control-group-small" >
														<label class="control-label">{t}简单描述：{/t}</label>
														<div class="controls">
															<textarea class="span12 h100" name="description" cols="40" rows="3">{$article.description}</textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<!-- {if $action eq 'edit'} -->
									<div class="foldable-list move-mod-group" id="goods_info_sort_note">
										<div class="accordion-group">
											<div class="accordion-heading">
												<a class="accordion-toggle collapsed acc-in move-mod-head" data-toggle="collapse" data-target="#goods_info_term_meta">
													<strong>{t}自定义栏目{/t}</strong>
												</a>
											</div>
											<div class="accordion-body in" id="goods_info_term_meta">
												<div class="accordion-inner">

	 												<!-- 自定义栏目模板区域 START -->

	 												<!-- {if $data_term_meta} -->
 													<label><b>编辑自定义栏目：</b></label>
													<table class="table smpl_tbl ">
														<thead>
															<tr>
																<td class="span4">名称</td>
																<td>值</td>
															</tr>
														</thead>
														<tbody class="term_meta_edit" data-id="{$article.article_id}" data-active="{url path='article/admin/update_term_meta'}">
															<!-- {foreach from=$data_term_meta item=term_meta} -->
															<tr>
																<td>
																	<input class="span12" type="text" name="term_meta_key" value="{$term_meta.meta_key}" />

																	<input type="hidden" name="term_meta_id" value="{$term_meta.meta_id}">
																	<a class="data-pjax btn m_t5" data-toggle="edit_term_meta" href="javascript:;">{t}更新{/t}</a>
																	<a class="ajaxremove btn btn-danger m_t5" data-toggle="ajaxremove" data-msg="{t}您确定要删除此条自定义栏目吗？{/t}" href="{url path='article/admin/remove_term_meta' args="meta_id={$term_meta.meta_id}"}">{t}移除{/t}</a>

																</td>
																<td><textarea class="span12 h70" name="term_meta_value">{$term_meta.meta_value}</textarea></td>
															</tr>
															<!-- {/foreach} -->
														</tbody>
													</table>
													<!-- {/if} -->

 													<!-- 编辑区域 -->
 													<label><b>添加自定义栏目：</b></label>

 													<div class="term_meta_add" data-id="{$article.article_id}" data-active="{url path='article/admin/insert_term_meta'}">
														<table class="table smpl_tbl ">
															<thead>
																<tr>
																	<td class="span4">名称</td>
																	<td>值</td>
																</tr>
															</thead>
															<tbody class="term_meta_edit" data-id="{$article.article_id}" data-active="{url path='article/admin/update_term_meta'}">
																<tr>
																	<td>
 																		<!-- {if $term_meta_key_list} -->
																		<select class="span12" data-toggle="change_term_meta_key" >
																			<!-- {foreach from=$term_meta_key_list item=meta_key} -->
																			<option value="{$meta_key.meta_key}">{$meta_key.meta_key}</option>
																			<!-- {/foreach} -->
																		</select>
																		<input class="span12 hide" type="text" name="term_meta_key" value="{$term_meta_key_list.0.meta_key}" />
																		<div><a data-toggle="add_new_term_meta" href="javascript:;">添加新栏目</a></div>
 																		<!-- {else} -->
																		<input class="span12" type="text" name="term_meta_key" value="" />
																		<!-- {/if} -->
																		<a class="btn m_t5" data-toggle="add_term_meta" href="javascript:;">添加自定义栏目</a>
																	</td>
																	<td><textarea class="span12" name="term_meta_value"></textarea></td>
																</tr>
															</tbody>
														</table>
													</div>
	 												<!-- 自定义栏目模板区域 END -->
												</div>
											</div>
										</div>
									</div>
									<!-- {/if} -->
								</div>
								<!-- 右边 -->
								<div class="right-bar move-mod">
								<!-- 分类信息 发布-->
									<div class="foldable-list move-mod-group" id="goods_info_sort_cat">
										<div class="accordion-group">
											<div class="accordion-heading">
												<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#goods_info_area_cat">
													<strong>{t}分类信息{/t}</strong>
												</a>
											</div>
											<div class="accordion-body in in_visable collapse" id="goods_info_area_cat">
												<div class="accordion-inner">
													<!-- {if $article.cat_id >= 0} -->
													<div class="control-group control-group-small" >
														<label class="control-label">{$lang.cat}：</label>
														<div class="control">
															<select name="article_cat" class="span8">
																<option value="0">{$lang.select_plz}</option>
																<!-- {$cat_select} -->
															</select>
														</div>
													</div>
													<!-- {else} -->
													<input type="hidden" name="article_cat" value="-1" />
													<!-- {/if} -->

													<!-- {if $article.cat_id >= 0} -->
													<div class="control-group control-group-small" >
														<label class="control-label">{t}是否置顶{/t}：</label>
														<div class="span8 chk_radio">
															<input type="radio" class="uni_style" name="article_type" value="0" {if $article.article_type eq 0}checked{/if}><span>{$lang.common}</span>
															<input type="radio" class="uni_style" name="article_type" value="1" {if $article.article_type eq 1}checked{/if}><span>{$lang.top}</span>
														</div>
													</div>
													<div class="control-group control-group-small" >
														<label class="control-label">{$lang.is_open}：</label>
														<div class="span8 chk_radio">
															<input type="radio" class="uni_style" name="is_open" value="1" {if $article.is_open eq 1}checked{/if}><span>{$lang.isopen}</span>
															<input type="radio" class="uni_style" name="is_open" value="0" {if $article.is_open eq 0}checked{/if}><span>{$lang.isclose}</span>
														</div>
													</div>
													<!-- {else} -->
													<div style="display:none;">
														<input type="hidden" name="article_type" value="0" />
														<input type="hidden" name="is_open" value="1" />
													</div>
													<!-- {/if} -->

													<input type="hidden" name="old_title" value="{$article.title}"/>
													<input type="hidden" name="id" value="{$article.article_id}" />
													{if $article.article_id eq ''}
													<button class="btn btn-gebo" type="submit">{t}发布{/t}</button>
													{else}
													<button class="btn btn-gebo" type="submit">{t}更新{/t}</button>
													{/if}
												</div>
											</div>
										</div>
									</div>
									<!-- 作者信息 -->
									<div class="foldable-list move-mod-group" id="goods_info_sort_author">
										<div class="accordion-group">
											<div class="accordion-heading">
												<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#goods_info_area_author">
													<strong>{t}作者信息{/t}</strong>
												</a>
											</div>
											<div class="accordion-body in in_visable collapse" id="goods_info_area_author">
												<div class="accordion-inner">
													<div class="control-group control-group-small" >
														<label class="control-label">{t}作者名称{/t}：</label>
														<div class="span8">
															<input type="text" name="author"  value="{$article.author|escape}"/>
														</div>
													</div>
													<div class="control-group control-group-small" >
														<label class="control-label">{t}作者邮箱{/t}：</label>
														<div class="span8">
															<input type="text" name="author_email"  value="{$article.author_email|escape}"/>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- 上传 -->
									<div class="foldable-list move-mod-group" id="goods_info_sort_upfile">
										<div class="accordion-group">
											<div class="accordion-heading">
												<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#goods_info_area_upfile">
													<strong>{t}上传文件{/t}</strong>
												</a>
											</div>
											<div class="accordion-body in in_visable collapse" id="goods_info_area_upfile">
												<div class="accordion-inner">
													<div>
														{if $article.file_url neq ''}
															{if $article.is_file}
																<div class="t_c">
																	<img class="w100 f_l" src="{$article.image_url} " />
																</div>
														    	<div class="h100 ecjiaf-wwb">{t}文件地址：{/t}{$article.file_url}</div>
													       	{else}
													       		<div class="t_c">
																	<img class="w300 h300 t_c "  class="img-polaroid" src="{$article.image_url} " />
																</div>
													       		<span class="ecjiaf-db m_t5 m_b5 ecjiaf-wwb">{t}文件地址：{/t}{$article.file_url}</span>
													       	{/if}
															<a class="ajaxremove ecjiafc-red ecjiaf-db" data-toggle="ajaxremove" data-msg="{t}您确定要删除该文章附件吗？{/t}" href='{RC_Uri::url("article/admin/delfile","id={$article.article_id}")}' title="{t}删除附件{/t}">
													        {t}删除文件{/t}
													        </a>
													        <input name="file_name" value="{$article.file_url}" class="hide">
														{else}
														<div>
															<div data-provides="fileupload" class="fileupload fileupload-new m_t10"><input type="hidden" value="" name="">
																<span class="btn btn-file"><span class="fileupload-new">上传文件</span><span class="fileupload-exists">修改文件</span><input type="file" name="file"></span>
																<span class="fileupload-preview"></span>
																<a style="float: none" data-dismiss="fileupload" class="close fileupload-exists" href="index.php-uid=1&page=form_extended.html#">&times;</a>
															</div>
														</div>
														{/if}
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<div class="row-fluid edit-page">
					<div class="span12 move-mod">
					</div>
				</div>
				<h3 class="heading">
					文章内容
				</h3>
				<div class="row-fluid">
					<div class="span12">
						{ecjia:editor content=$article.content textarea_name='content'}
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- end goods form -->
<!-- {/block} -->