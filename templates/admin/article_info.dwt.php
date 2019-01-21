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

<div class="row-fluid ">
	<div class="span12">
		<div class="tabbable">
			{if $action eq 'edit' && $has_goods}
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab1" data-toggle="tab">{t}通用信息{/t}</a></li>
				<!--<li><a href="#tab2" data-toggle="tab">{t}文章内容{/t}</a></li> -->
				<li><a class="data-pjax" href='{url path="article/admin/link_goods" args="id={$smarty.get.id}{if $publishby}&publishby={$publishby}{/if}"}'>{t}关联商品{/t}</a></li>
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
											<input type="text" name="title" class="span10"  value="{$article.title|escape}" placeholder="{t}在此输入文章标题{/t}" />
											<span class="input-must"><span class="require-field" style="color:#FF0000,">*</span></span>
										</div>
									</div>

									<div class="control-group" >
									 	<label>{t}外部链接：{/t}</label>
										<div>
											<input type="text" name="link_url" class="span10" value="{if $article.link neq ''}{$article.link|escape}{/if}" />
											<span class="help-block">{t}若输入外部链接，则该链接优先{/t}</span>
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
 													<label><b>{t}编辑自定义栏目：{/t}</b></label>
													<table class="table smpl_tbl ">
														<thead>
															<tr>
																<td class="span4">{t}名称{/t}</td>
																<td>{t}值{/t}</td>
															</tr>
														</thead>
														<tbody class="term_meta_edit" data-id="{$article.article_id}" data-active="{url path='article/admin/update_term_meta'}">
															<!-- {foreach from=$data_term_meta item=term_meta} -->
															<tr>
																<td>
																	<input class="span12" type="text" name="term_meta_key" value="{$term_meta.meta_key}" />

																	<input type="hidden" name="term_meta_id" value="{$term_meta.meta_id}">
																	<a class="data-pjax btn m_t5" data-toggle="edit_term_meta" href="javascript:;">{t}更新{/t}</a>
																	<a class="ajaxremove btn btn-danger m_t5" data-toggle="ajaxremove" data-msg="{t}您确定要删除此条自定义栏目吗？{/t}" href='{url path="article/admin/remove_term_meta" args="meta_id={$term_meta.meta_id}"}'>{t}删除{/t}</a>

																</td>
																<td><textarea class="span12 h70" name="term_meta_value">{$term_meta.meta_value}</textarea></td>
															</tr>
															<!-- {/foreach} -->
														</tbody>
													</table>
													<!-- {/if} -->

 													<!-- 编辑区域 -->
 													<label><b>{t}添加自定义栏目：{/t}</b></label>

 													<div class="term_meta_add" data-id="{$article.article_id}" data-active="{url path='article/admin/insert_term_meta'}">
														<table class="table smpl_tbl ">
															<thead>
																<tr>
																	<td class="span4">{t}名称{/t}</td>
																	<td>{t}值{/t}</td>
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
																		<div><a data-toggle="add_new_term_meta" href="javascript:;">{t}添加新栏目{/t}</a></div>
 																		<!-- {else} -->
																		<input class="span12" type="text" name="term_meta_key" value="" />
																		<!-- {/if} -->
																		<a class="btn m_t5" data-toggle="add_term_meta" href="javascript:;">{t}添加自定义栏目{/t}</a>
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
														<label class="control-label">{t}作者名称：{/t}</label>
														<div class="span8">
															<input type="text" name="author"  value="{$article.author|escape}"/>
														</div>
													</div>
													<div class="control-group control-group-small" >
														<label class="control-label">{t}作者邮箱：{/t}</label>
														<div class="span8">
															<input type="text" name="author_email"  value="{$article.author_email|escape}"/>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
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
														<label class="control-label">{t}文章分类：{/t}</label>
														<div class="control span8">
															<select name="article_cat">
																<option value="0">{t}请选择...{/t}</option>
																<!-- {foreach from=$cat_select key=key item=val} -->
																<option value="{$val.cat_id}" {if $article.cat_id eq $val.cat_id}selected{/if} {if $val.level}style="padding-left:{$val.level*20}px"{/if}>{$val.cat_name}</option>
																<!-- {/foreach} -->
															</select>
														</div>
													</div>
													<!-- {else} -->
													<input type="hidden" name="article_cat" value="-1" />
													<!-- {/if} -->

													<!-- {if $article.cat_id >= 0} -->
													<div class="control-group control-group-small" >
														<label class="control-label">{t}文章类型：{/t}</label>
														<div class="control span8">
															<select name="article_type">
																<option value="article">{t}请选择...{/t}</option>
																<!-- {foreach from=$article_type key=key item=val} -->
																<option value="{$val.article_type}" {if $article.article_type eq $val.article_type}selected{/if}>{$val.article_type_name}</option>
																<!-- {/foreach} -->
															</select>
														</div>
													</div>
													<div class="control-group control-group-small" >
														<label class="control-label">{t}推荐类型：{/t}</label>
														<div class="span8 chk_radio">
															<input type="radio" class="uni_style" name="suggest_type" value="stickie" {if $article.suggest_type eq 'stickie'}checked{/if}><span>{t}置顶{/t}</span>
															<input type="radio" class="uni_style" name="suggest_type" value="0" {if $article.suggest_type eq '0'}checked{/if}><span>{t}默认{/t}</span>
														</div>
													</div>
													<div class="control-group control-group-small" >
														<label class="control-label">{t}状态审核：{/t}</label>
														<div class="span8 chk_radio">
															<input type="radio" class="uni_style" name="article_approved" value="1" {if $article.article_approved eq 1}checked{/if}><span>{t}通过{/t}</span>
															<input type="radio" class="uni_style" name="article_approved" value="0" {if $article.article_approved eq 0}checked{/if}><span>{t}待审核{/t}</span>
															<input type="radio" class="uni_style" name="article_approved" value="trash" {if $article.article_approved eq 'trash'}checked{/if}><span>{t}回收站{/t}</span>
															<input type="radio" class="uni_style" name="article_approved" value="spam" {if $article.article_approved eq 'spam'}checked{/if}><span>{t}垃圾文章{/t}</span>
														</div>
													</div>
													<!-- {else} -->
													<div style="display:none;">
														<input type="hidden" name="article_type" value="article" />
														<input type="hidden" name="article_approved" value="1" />
													</div>
													<!-- {/if} -->

													<input type="hidden" name="old_title" value="{$article.title}"/>
													<input type="hidden" name="id" value="{$article.article_id}" />
													<input type="hidden" name="publishby" value="{$publishby}" />
													{if $article.article_id eq ''}
													<button class="btn btn-gebo" type="submit">{t}发布{/t}</button>
													{else}
													<button class="btn btn-gebo" type="submit">{t}更新{/t}</button>
													{/if}
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
																	<img class="w300 h300 t_c"  class="img-polaroid" src="{$article.image_url} " />
																</div>
													       		<span class="ecjiaf-db m_t5 m_b5 ecjiaf-wwb">{t}文件地址：{/t}{$article.file_url}</span>
													       	{/if}
															<a class="ajaxremove ecjiafc-red ecjiaf-db" data-toggle="ajaxremove" data-msg="{t}您确定要删除该文章附件吗？{/t}" href='{RC_Uri::url("article/admin/delfile","id={$article.article_id}")}' title="{t}删除文件{/t}">
													        {t}删除文件{/t}
													        </a>
													        <input name="file_name" value="{$article.file_url}" class="hide">
														{else}
														<div>
															<div data-provides="fileupload" class="fileupload fileupload-new m_t10"><input type="hidden" value="" name="">
																<span class="btn btn-file"><span class="fileupload-new">{t}上传文件{/t}</span><span class="fileupload-exists">{t}修改文件{/t}</span><input type="file" name="file"></span>
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
									<!-- 上传文章封面 -->
									<div class="foldable-list move-mod-group" id="article_cover_image_upfile">
										<div class="accordion-group">
											<div class="accordion-heading">
												<a class="accordion-toggle collapsed move-mod-head" data-toggle="collapse" data-target="#article_cover_upfile">
													<strong>{t}文章封面{/t}</strong>
												</a>
											</div>
											<div class="accordion-body in in_visable collapse" id="article_cover_upfile">
												{if !$article.cover_image}
													<div class="accordion-inner" style="padding:16px 15px;">
														<div class="fileupload fileupload-new m_b0" data-provides="fileupload">
															<div class="fileupload-preview fileupload-exists thumbnail" style="width: 100px; height: 100px; line-height: 50px;"></div>
															<span class="btn btn-file">
																<span  class="fileupload-new">{t}浏览{/t}</span>
																<span  class="fileupload-exists">{t}修改{/t}</span>
																<input type='file' name='cover_image' size="35"/>
															</span>
															<a class="btn fileupload-exists" data-dismiss="fileupload" href="#">{t}删除{/t}</a>
														</div>
													</div>
												{else}
												<div class="fileupload fileupload-new" data-provides="fileupload">
												    <div class="t_c">
														<img class="w200 h200"  class="img-polaroid" src="{RC_Upload::upload_url()}/{$article.cover_image}">
													</div>
													<div class="t_c">
														{t}图片地址：{/t} {$article.cover_image}<br><br>
														<div class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px; line-height: 50px;"></div>
														<span class="btn btn-file">
															<span  class="fileupload-new">{t}更换图片{/t}</span>
															<span  class="fileupload-exists">{t}修改{/t}</span>
															<input type='file' name='cover_image' size="35"/>
														</span>
													</div>
												</div>
											   {/if}
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

				<h3 class="heading">{t}文章内容{/t}</h3>
				<div class="row-fluid">
					<div class="span12">
						{ecjia:editor content=$article.content textarea_name='content'}
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- {/block} -->