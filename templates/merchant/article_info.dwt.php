<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->
<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.merchant.article_info.init();
</script>
<!-- {/block} -->
<!-- {block name="home-content"} -->
<div class="page-header">
	<div class="pull-left">
		<h2>
		<!-- {if $ur_here}{$ur_here}{/if} -->
		</h2>
	</div>
	{if $action_link}
	<div class="pull-right">
		<a class="btn btn-primary data-pjax" href="{$action_link.href}" id="sticky_a"><i class="fa fa-reply"></i><i class="fontello-icon-reply"></i> {$action_link.text}</a>
	</div>
	{/if}
	<div class="clearfix">
	</div>
</div>
<div class="row edit-page">
	<div class="col-lg-12">
		<section class="panel">
			<div class="panel-body panel-body-small">
				{if $action eq 'edit' && $has_goods}
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab1" data-toggle="tab">{lang key='article::article.tab_general'}</a></li>
					<!--<li><a href="#tab2" data-toggle="tab">{lang key='article::article.tab_content'}</a></li>
					 -->
					<li><a class="data-pjax" href='{url path="article/merchant/link_goods" args="id={$smarty.get.id}"}'>{lang key='article::article.tab_goods'}</a></li>
				</ul>
				{/if}
				<form class="form-horizontal" action="{$form_action}" method="post" enctype="multipart/form-data" name="infoForm" data-edit-url="{RC_Uri::url('article/merchant/edit')}">
					<div class="tab-content panel m_b0">
						<div class="tab-pane active panel-body" id="tab1">
							<fieldset>
								<div class="row-fluid edit-page editpage-rightbar">
									<div class="left-bar move-mod">
										<!--左边-->
										<div class="form-group m_t10">
											<div class="controls col-lg-11">
												<input type="text" name="title" class="span10 form-control" value="{$article.title|escape}" placeholder="{lang key='article::article.enter_title_article_here'}"/>
											</div>
											<span class="input-must">{lang key='system::system.require_field'}</span>
										</div>
										<div class="form-group m_b0">
											<label class="controls col-lg-11">{lang key='article::article.external_links'}</label>
											<div class="controls col-lg-11">
												<input type="text" name="link_url" class="span10 form-control" value="{if $article.link neq ''}{$article.link|escape}{else}http://{/if}"/>
												<div class="help-block">{lang key='article::article.links_help_block'}</div>
											</div>
										</div>
										<div class="panel-group" id="goods_info_sort_seo">
											<div class="panel panel-info">
												<div class="panel-heading">
													<a class="accordion-toggle" data-toggle="collapse" data-target="#goods_info_area_seo">
													<span class="glyphicon"></span>
													<h4 class="panel-title">{lang key='article::article.seo_optimization'}</h4>
													</a>
												</div>
												<div class="accordion-body in collapse" id="goods_info_area_seo">
													<div class="panel-body">
														<div class="form-group m_b0">
															<label class="control-label col-lg-2 p_l0">{lang key='article::article.keywords'}</label>
															<div class="col-lg-9 p_l0">
																<input class="span10 form-control" type="text" name="keywords" value="{$article.keywords|escape}" size="40"/>
																<br/>
																<p class="help-block">
																	{lang key='article::article.split'}
																</p>
															</div>
														</div>
														<div class="form-group m_b0">
															<label class="control-label col-lg-2 p_l0">{lang key='article::article.simple_description'}</label>
															<div class="col-lg-9 p_l0">
																<textarea class="form-control" name="description" cols="40" rows="3">{$article.description}</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<!-- {if $action eq 'edit'} -->
										<div class="panel-group" id="goods_info_sort_note">
											<div class="panel panel-info">
												<div class="panel-heading">
													<a class="accordion-toggle" data-toggle="collapse" data-target="#goods_info_term_meta">
													<span class="glyphicon"></span>
													<h4 class="panel-title"><strong>{lang key='article::article.custom_columns_success'}</strong></h4>
													</a>
												</div>
												<div class="accordion-body in" id="goods_info_term_meta">
													<div class="panel-body">
														<!-- 自定义栏目模板区域 START -->
														<!-- {if $data_term_meta} -->
														<label><b>{lang key='article::article.edit_custom_columns_success'}：</b></label>
														<table class="table smpl_tbl ">
														<thead>
														<tr>
															<td class="">
																{lang key='article::article.name'}
															</td>
															<td>
																{lang key='article::article.value'}
															</td>
														</tr>
														</thead>
														<tbody class="term_meta_edit" data-id="{$article.article_id}" data-active="{url path='article/merchant/update_term_meta'}">
														<!-- {foreach from=$data_term_meta item=term_meta} -->
														<tr>
															<td class="col-lg-6">
																<div class="col-lg-12 p_l0">
																	<input class="form-control" type="text" name="term_meta_key" value="{$term_meta.meta_key}"/>
																</div>
																<input type="hidden" name="term_meta_id" value="{$term_meta.meta_id}">
																<div class="clear_both m_t5">
																	<a class="data-pjax btn btn-info m_t5" data-toggle="edit_term_meta" href="javascript:;">{lang key='article::article.update'}</a>
																	<a class="ajaxremove btn btn-danger m_t5" data-toggle="ajaxremove" data-msg="{lang key='article::article.drop_custom_columns_confirm'}" href='{url path="article/merchant/remove_term_meta" args="meta_id={$term_meta.meta_id}"}'>{lang key='system::system.remove'}</a>
																</div>
															</td>
															<td>
																<textarea class="span12 h70 form-control" name="term_meta_value">{$term_meta.meta_value}</textarea>
															</td>
														</tr>
														<!-- {/foreach} -->
														</tbody>
														</table>
														<!-- {/if} -->
														<!-- 编辑区域 -->
														<label class="control-label"><b>{lang key='article::article.add_custom_columns'}：</b></label>
														<div class="term_meta_add" data-id="{$article.article_id}" data-active="{url path='article/merchant/insert_term_meta'}">
															<table class="table smpl_tbl ">
															<thead>
															<tr>
																<td class="col-lg-6">
																	{lang key='article::article.name'}
																</td>
																<td>
																	{lang key='article::article.value'}
																</td>
															</tr>
															</thead>
															<tbody class="term_meta_edit" data-id="{$article.article_id}" data-active="{url path='article/merchant/update_term_meta'}">
															<tr>
																<td>
																	<!-- {if $term_meta_key_list} -->
																	<div class="controls col-lg-12 p_l0 m_b5">
																		<select class="form-control" data-toggle="change_term_meta_key">
																			<!-- {foreach from=$term_meta_key_list item=meta_key} -->
																			<option value="{$meta_key.meta_key}">{$meta_key.meta_key}</option>
																			<!-- {/foreach} -->
																		</select>
																		<input class="form-control hide" type="text" name="term_meta_key" value="{$term_meta_key_list.0.meta_key}"/>
																	</div>
																	<div class="clear_both">
																		<a data-toggle="add_new_term_meta" href="javascript:;">{lang key='article::article.add_new_columns'}</a>
																	</div>
																	<!-- {else} -->
																	<input class="form-control" type="text" name="term_meta_key" value=""/>
																	<!-- {/if} -->
																	<a class="btn btn-primary m_t5" data-toggle="add_term_meta" href="javascript:;">{lang key='article::article.add_custom_columns'}</a>
																</td>
																<td>
																	<textarea class="span12 form-control" name="term_meta_value"></textarea>
																</td>
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
										<div class="panel-group">
											<div class="panel panel-info">
												<div class="panel-heading">
													<a class="accordion-toggle" data-toggle="collapse" data-target="#goods_info_area_cat">
													<span class="glyphicon"></span>
													<h4 class="panel-title"><strong>{lang key='article::article.category_info'}</strong></h4>
													</a>
												</div>
												<div class="panel-collapse collapse in" id="goods_info_area_cat">
													<div class="panel-body">
														<!-- {if $article.cat_id >= 0} -->
														<div class="form-group">
															<label class="control-label col-lg-4">{lang key='article::article.cat_lable'}</label>
															<div class="controls col-lg-7">
																<select class="form-control" name="article_cat">
																	<option value="0">{lang key='article::article.select_plz'}</option>
																	<!-- {foreach from=$cat_select key=key item=val} -->
																	<option value="{$val.cat_id}" {if $article.cat_id eq $val.cat_id}selected{/if} {if $val.level}style="padding-left:{$val.level*20}px"{/if}>{$val.cat_name}</option>
																	<!-- {/foreach} -->
																</select>
															</div>
														</div>
														<!-- {else} -->
														<input type="hidden" name="article_cat" value="-1"/>
														<!-- {/if} -->
														<!-- {if $article.cat_id >= 0} -->
														<div class="form-group">
															<label class="control-label col-lg-4">{lang key='article::article.is_top'}</label>
															<div class="col-lg-7">
																<input type="radio" class="uni_style" name="article_type" value="0" {if $article.article_type eq 0}checked{/if} checked="true"><label for="open">{lang key='article::article.common'}</label>
																<input type="radio" class="uni_style" name="article_type" value="1" {if $article.article_type eq 1}checked{/if}><label for="close">{lang key='article::article.top'}</label>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-lg-4">{lang key='article::article.is_open_lable'}</label>
															<div class="col-lg-7">
																<input type="radio" class="uni_style" name="is_open" value="1" {if $article.is_open eq 1}checked{/if} checked="true"><label for="open">{lang key='article::article.isopen'}</label>
																<input type="radio" class="uni_style" name="is_open" value="0" {if $article.is_open eq 0}checked{/if}><label for="close">{lang key='article::article.isclose'}</label>
															</div>
														</div>
														<!-- {else} -->
														<div style="display:none;">
															<input type="hidden" name="article_type" value="0"/>
															<input type="hidden" name="is_open" value="1"/>
														</div>
														<!-- {/if} -->
														<input type="hidden" name="old_title" value="{$article.title}"/>
														<input type="hidden" name="id" value="{$article.article_id}"/>
														
														<div class="form-group m_b0">
															<label class="control-label col-lg-7">
																{if $article.article_id eq ''}
																<button class="btn btn-info" type="submit">{lang key='article::article.issue'}</button>
																{else}
																<button class="btn btn-info" type="submit">{lang key='article::article.update'}</button>
																{/if}
															</label>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<!-- 作者信息 -->
										<div class="panel-group" id="goods_info_sort_author">
											<div class="panel panel-info">
												<div class="panel-heading">
													<a class="accordion-toggle" data-toggle="collapse" data-target="#goods_info_area_author">
													<span class="glyphicon"></span>
													<h4 class="panel-title"><strong>{lang key='article::article.author_info'}</strong></h4>
													</a>
												</div>
												<div class="accordion-body in in_visable collapse" id="goods_info_area_author">
													<div class="panel-body">
														<div class="form-group">
															<label class="control-label col-lg-4">{lang key='article::article.author_name'}</label>
															<div class="col-lg-7">
																<input type="text" name="author" class="form-control" value="{$article.author|escape}"/>
															</div>
														</div>
														<div class="form-group m_b0">
															<label class="control-label col-lg-4">{lang key='article::article.author_email'}</label>
															<div class="col-lg-7">
																<input type="text" name="author_email" class="form-control" value="{$article.author_email|escape}"/>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<!-- 上传 -->
										<div class="panel-group" id="goods_info_sort_upfile">
											<div class="panel panel-info">
												<div class="panel-heading">
													<a class="accordion-toggle" data-toggle="collapse" data-target="#goods_info_area_upfile">
													<span class="glyphicon"></span>
													<h4 class="panel-title"><strong>{lang key='article::article.upload_file'}</strong></h4>
													</a>
												</div>
												<div class="accordion-body in in_visable collapse" id="goods_info_area_upfile">
													<div class="panel-body">
														{if $article.file_url neq ''}
															{if $article.is_file}
																<div class="t_c">
																	<img class="w100 f_l" src="{$article.image_url} "/>
																</div>
																<div class="h100 ecjiaf-wwb">
																	{lang key='article::article.file_address'}{$article.file_url}
																</div>
														    {else}
																<div class="t_c">
																	<img class="w300 h300 t_c" class="img-polaroid" src="{$article.image_url} "/>
																</div>
																<span class="ecjiaf-db m_t5 m_b5 ecjiaf-wwb">{lang key='article::article.file_address'}{$article.file_url}</span>
														    {/if}
															<a class="ajaxremove ecjiafc-red ecjiaf-db" data-toggle="ajaxremove" data-msg="{lang key='article::article.drop_file_confirm'}" href='{RC_Uri::url("article/merchant/delfile","id={$article.article_id}")}' title="{lang key='article::article.drop_file'}">
														        {lang key='article::article.drop_file'}
															</a>
															<input name="file_name" value="{$article.file_url}" class="hide">
														{else}
														
						 								<div class="fileupload fileupload-new m_b0" data-provides="fileupload">
						                                    <span class="btn btn-primary btn-file btn-sm">
						                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i>浏览</span>
						                                        <span class="fileupload-exists"> 修改</span>
						                                        <input type="file" class="default" name="cover_image" />
						                                    </span>
						                                    <span class="fileupload-preview"></span>
						                                    <a style="float: none" data-dismiss="fileupload" class="close fileupload-exists" href="index.php-uid=1&page=form_extended.html#">&times;</a>
						                                </div>
														{/if}
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div>

					<div class="page-header panel-body m_b0">
						<div class="pull-left">
							<h3>{lang key='article::article.tab_content'}</h3>
						</div>
						<div class="clearfix">
						</div>
					</div>
					<div class="row-fluid panel-body">
						<div class="span12">
							{ecjia:editor content=$article.content textarea_name='content' is_teeny=0}
						</div>
					</div>
				</form>
			</div>
		</section>
	</div>
</div>
<!-- {/block} -->