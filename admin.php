<?php
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * ECJIA 管理中心文章处理程序文件
 */

class admin extends ecjia_admin {
	private $db_article;
	private $db_goods_article;
	private $db_comment;
	private $db_article_cat;
	private $db_term_meta;
	
	public function __construct() {
		parent::__construct();
		
		RC_Lang::load('article');
		RC_Loader::load_app_func('article');
		RC_Loader::load_app_func('system_goods');
		RC_Loader::load_app_func('category', 'goods');
		RC_Loader::load_app_func('common', 'goods');

		RC_Loader::load_app_func('global');
		assign_adminlog_contents();
		
		/* 数据模型加载 */
		$this->db_goods_article = RC_Loader::load_app_model('goods_article_model', 'goods');
		$this->db_article       = RC_Loader::load_app_model('article_model');
		$this->db_comment       = RC_Loader::load_app_model('comment_model', 'comment');
		$this->db_article_cat   = RC_Loader::load_app_model('article_cat_model');
		$this->db_term_meta     = RC_Loader::load_sys_model('term_meta_model');

		/* 加载所需js */
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');
// 		RC_Script::enqueue_script('media-editor', RC_Uri::vendor_url('tinymce/tinymce.min.js'), array(), false, true);

		RC_Script::enqueue_script('article_list', RC_App::apps_url('statics/js/article_list.js', __FILE__));

		/* 页面所需CSS加载 */
		RC_Style::enqueue_style('uniform-aristo');
		RC_Style::enqueue_style('chosen');

		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('文章列表'), RC_Uri::url('article/admin/init')));
		RC_Script::enqueue_script('bootstrap-placeholder', RC_Uri::admin_url('statics/lib/dropper-upload/bootstrap-placeholder.js'), array(), false, true);

		RC_Style::enqueue_style('bootstrap-editable', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'));
		RC_Script::enqueue_script('bootstrap-editable.min', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js'));
	}

	/**
	 * 文章列表
	 */
	public function init() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here', RC_Lang::lang('03_article_list'));
		$this->assign('action_link', array('text' => RC_Lang::lang('article_add'), 'href' => RC_Uri::url('article/admin/add')));

		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('文章列表')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台文章列表页面，系统中所有的文章都会显示在此列表中。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:文章列表" target="_blank">关于文章列表帮助文档</a>') . '</p>'
		);
		
		$result = ecjia_app::validate_application('goods');
		if (!is_ecjia_error($result)) {
			$this->assign('has_goods', 'has_goods');
		}

		/* 文章筛选时保留筛选的分类cat_id */
		$cat_id = !empty($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
		$this->assign('cat_select', article_cat_list(0, $cat_id));
		
		$article_list = $this->get_articleslist();
		$this->assign('article_list', $article_list);
		$this->assign('form_action', RC_Uri::url('article/admin/batch'));
		$this->assign('search_action', RC_Uri::url('article/admin/init'));

		$this->assign_lang();
		$this->display('article_list.dwt');
	}

	/**
	 * 添加文章页面
	 */
	public function add() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);
		RC_Script::enqueue_script('dropper-jq', RC_Uri::admin_url('statics/lib/dropper-upload/jquery.fs.dropper.js'), array(), false, true);

		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('添加新文章')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台添加文章页面，可以在此页面添加文章信息。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:添加文章" target="_blank">关于添加文章帮助文档</a>') . '</p>'
		);
		
		$this->assign('ur_here', RC_Lang::lang('article_add'));
		$this->assign('action_link', array('text' => RC_Lang::lang('back_article_list'), 'href' => RC_Uri::url('article/admin/init')));

		$article = array();
		$article['is_open'] = 1;

		$this->assign('article', $article);
		$this->assign('cat_select', article_cat_list(0));
		$this->assign('form_action', RC_Uri::url('article/admin/insert'));
		$this->assign_lang();
		
		$this->display('article_info.dwt');
	}

	/**
	 * 添加文章
	 */
	public function insert() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

		$title = trim($_POST['title']);
		$cat_id = intval($_POST['article_cat']);
		
		$is_only = $this->db_article->where(array('title' => $title))->count();
		if ($is_only > 0) {
			$this->showmessage(sprintf(RC_Lang::lang('title_exist'), stripslashes($title)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR );
		} else {
			$file = !empty($_FILES['file']) ? $_FILES['file'] : '';
			if (!empty($file)&&((isset($file['error']) && $file['error'] == 0) || (!isset($file['error']) && $file['tmp_name'] != 'none'))) {
				
				$extname = strtolower(substr($file['name'], strrpos($file['name'], '.') + 1));
				$dir = date("Ym", time());
				
				if (strrpos(RC_Config::load_config('system', 'UPLOAD_FILE_EXT'), $extname)){
					$upload = RC_Upload::uploader('file', array('save_path' => 'data/article', 'auto_sub_dirs' => true));
					$image_info = $upload->upload($file);
					
					if (!empty($image_info)) {
						$file_name = $upload->get_position($image_info);
					} else {
						$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
					}
				} else {
					$upload = RC_Upload::uploader('image', array('save_path' => 'data/article', 'auto_sub_dirs' => true));
					$image_info = $upload->upload($file);
					
					if (!empty($image_info)) {
						$file_name = $upload->get_position($image_info);
					} else {
						$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
					}
				}
			} else {
				$file_name = '';
			}

			$extname = strtolower(substr($file_name, strrpos($file_name, '.') + 1));
			if (strrpos(RC_Config::load_config('system', 'UPLOAD_FILE_EXT'),$extname) && empty($_POST['content'])) {
				$open_type = 1;
			} elseif (strrpos(RC_Config::load_config('system', 'UPLOAD_FILE_EXT'),$extname) && !empty($_POST['content'])) {
				$open_type = 2;
			} else {
				$open_type = 0;
			}

			$add_time = RC_Time::gmtime();
			$data = array(
				'title'        => $title,
				'cat_id'  	   => $cat_id,
				'article_type' => intval($_POST['article_type']),
				'is_open'  	   => intval($_POST['is_open']),
				'author'  	   => trim($_POST['author']),
				'author_email' => trim($_POST['author_email']),
				'keywords'     => trim($_POST['keywords']),
				'content'      => trim($_POST['content']),
				'add_time'     => $add_time,
				'file_url'     => $file_name,
				'open_type'    => $open_type,
				'link'         => trim($_POST['link_url']),
				'description'  => trim($_POST['description']),
			);
			$article_id = $this->db_article->insert($data);

			ecjia_admin::admin_log($title, 'add', 'article');
			$links[] = array('text' => RC_Lang::lang('back_article_list'), 'href'=> RC_Uri::url('article/admin/init'));
			$links[] = array('text' => RC_Lang::lang('continue_article_add'), 'href'=> RC_Uri::url('article/admin/add'));
			$this->showmessage(RC_Lang::lang('articleadd_succeed'),  ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links , 'pjaxurl' => RC_Uri::url('article/admin/edit', array('id' => $article_id))));
		}
	}

	/**
	 * 添加自定义栏目
	 */
	public function insert_term_meta() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

		$article_id = intval($_POST['article_id']);

		$key = htmlspecialchars(trim($_POST['key']));
		$value = htmlspecialchars(trim($_POST['value']));

		/* 商品信息 */
		$article = $this->db_article->where(array('article_id' => $article_id))->find();

		$data = array(
			'object_id'		=> $article['article_id'],
			'object_type'	=> 'ecjia.article',
			'object_group'	=> 'article',
			'meta_key'		=> $key,
			'meta_value'	=> $value,
		);

		$this->db_term_meta->insert($data);
		$res = array(
			'key'		=> $key,
			'value'		=> $value,
			'pjaxurl'	=> RC_Uri::url('article/admin/edit', array('id' => $article['article_id']))
		);

		$this->showmessage(__('添加自定义栏目成功'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, $res);
	}

	/**
	 * 更新自定义栏目
	 */
	public function update_term_meta() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

	    $article_id = intval($_POST['article_id']);

		$meta_id = intval($_POST['meta_id']);

		if (empty($meta_id)) {
			$this->showmessage('缺少关键参数，更新失败！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}

		$key = htmlspecialchars(trim($_POST['key']));
		$value = htmlspecialchars(trim($_POST['value']));

		/* 商品信息 */
		$article = $this->db_article->where(array('article_id' => $article_id))->find();

		$data = array(
			'object_id'		=> $article['article_id'],
			'object_type'	=> 'ecjia.article',
			'object_group'	=> 'article',
			'meta_key'		=> $key,
			'meta_value'	=> $value,
		);

		$this->db_term_meta->where(array('meta_id' => $meta_id))->update($data);

		$res = array(
			'key'		=> $key,
			'value'		=> $value,
			'pjaxurl'	=> RC_Uri::url('article/admin/edit', array('id'=>$article_id))
		);
		$this->showmessage(__('添加自定义栏目成功'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, $res);
	}


	/**
	 * 删除自定义栏目
	 */
	public function remove_term_meta() {
		$meta_id = intval($_GET['meta_id']);
		
		$this->db_term_meta->where(array('meta_id'=>$meta_id))->delete();
		$this->showmessage(__('删除自定义栏目成功'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
	}

	/**
	 * 编辑
	 */
	public function edit() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

		$result = ecjia_app::validate_application('goods');
		if (!is_ecjia_error($result)) {
			$this->assign('has_goods', 'has_goods');
		}

		$this->assign('ur_here',     RC_Lang::lang('article_edit'));
		$this->assign('action_link', array('text' => RC_Lang::lang('back_article_list'), 'href' => RC_Uri::url('article/admin/init')));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('编辑文章内容')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台编辑文章页面，可以在此页面编辑相应的文章信息。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:文章列表#.E6.96.87.E7.AB.A0.E7.BC.96.E8.BE.91" target="_blank">关于编辑文章帮助文档</a>') . '</p>'
		);

		$id = intval($_GET['id']);
		$article = $this->db_article->find(array('article_id' => $id));

		$extname = strtolower(substr($article['file_url'], strrpos($article['file_url'], '.') + 1));
		if ($article['file_url']) {
			if (strrpos(RC_Config::load_config('system', 'UPLOAD_FILE_EXT'),$extname)) {
				$article['image_url'] = RC_Uri::admin_url('statics/images/ecjiafile.png');
				$article['is_file']=true;
			} else {
				$article['image_url'] = RC_Upload::upload_url($article['file_url']);

			}
		}

		$data_term_meta = $this->db_term_meta->field('meta_id, meta_key, meta_value')->where(array('object_id' => $id, 'object_type' => 'ecjia.article', 'object_group'	=> 'article'))->select();
		$term_meta_key_list = $this->db_term_meta->field('meta_key')->where(array('object_id' => $id, 'object_type' => 'ecjia.article', 'object_group'	=> 'article'))->group('meta_key')->select();

		$this->assign('data_term_meta', $data_term_meta);
        $this->assign('term_meta_key_list', $term_meta_key_list);
		$this->assign('action',	'edit');
		$this->assign('article', $article);
		$this->assign('cat_select', article_cat_list(0, $article['cat_id']));
		$this->assign('form_action', RC_Uri::url('article/admin/update'));
		$this->assign_lang();
		
		$this->display('article_info.dwt');
	}

	/**
	 * 预览
	 */
	public function preview() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

		$id = intval($_GET['id']);

		$this->assign('ur_here', RC_Lang::lang('preview_article'));
		$this->assign('action_linkedit', array('text' => RC_Lang::lang('article_editbtn'), 'href' => RC_Uri::url('article/admin/edit', array('id' => $id))));
		$this->assign('action_link', array('text' => RC_Lang::lang('back_article_list'), 'href' => RC_Uri::url('article/admin/init')));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('文章预览')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台预览文章页面，可以在此预览相应的文章信息。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:文章列表#.E9.A2.84.E8.A7.88.E6.96.87.E7.AB.A0" target="_blank">关于预览文章帮助文档</a>') . '</p>'
		);
		$article = $this->db_article->find(array('article_id' => $id));
		$article['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $article['add_time']);

		$this->assign('article',  $article);
		if (isset($article['cat_id'])) {
			$this->assign('cat_select',  article_cat_list(0, $article['cat_id']));
		}
		$this->assign_lang();
		$this->display('preview.dwt');
	}


	public function update() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

		$title = !empty($_POST['title']) ? trim($_POST['title']) : '';
		$id = intval($_POST['id']);
		$cat_id = intval($_POST['article_cat']);
		$is_only = $this->db_article->where(array('title' => $title, 'article_id' => array('neq' => $id)))->count();
		if ($is_only != 0) {
			$this->showmessage(sprintf(RC_Lang::lang('title_exist'), stripslashes($_POST['title'])), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
			$file_name = !empty($_POST['file_name']) ? $_POST['file_name'] : '';
			
			if (empty($file_name)) {
				$file = !empty($_FILES['file']) ? $_FILES['file'] : '';
				
				if (!empty($file)&&((isset($file['error']) && $file['error'] == 0) || (!isset($file['error']) && $file['tmp_name'] != 'none'))) {
					$extname = strtolower(substr($file['name'], strrpos($file['name'], '.') + 1));
					$dir = date("Ym", time());
					
					if(strrpos(RC_Config::load_config('system', 'UPLOAD_FILE_EXT'),$extname)){
						$upload = RC_Upload::uploader('file', array('save_path' => 'data/article', 'auto_sub_dirs' => true));
						$image_info = $upload->upload($file);
						
						if (!empty($image_info)) {
							$file_name = $upload->get_position($image_info);
						} else {
							$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
						}
					}else {
						$upload = RC_Upload::uploader('file', array('save_path' => 'data/article', 'auto_sub_dirs' => true));
						$image_info = $upload->upload($file);
						
						if (!empty($image_info)) {
							$file_name = $upload->get_position($image_info);
						} else {
							$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
						}
					}
				}
			}

			$extname = strtolower(substr($file_name, strrpos($file_name, '.') + 1));
			if (strrpos(RC_Config::load_config('system', 'UPLOAD_FILE_EXT'),$extname) && empty($_POST['content'])) {
				$open_type = 1;
			} elseif (strrpos(RC_Config::load_config('system', 'UPLOAD_FILE_EXT'),$extname) && !empty($_POST['content'])) {
				$open_type = 2;
			} else {
				$open_type = 0;
			}

			$data = array(
				'title'        => $title,
				'cat_id'  	   => $cat_id,
				'article_type' => intval($_POST['article_type']),
				'is_open'  	   => intval($_POST['is_open']),
				'author'  	   => trim($_POST['author']),
				'author_email' => trim($_POST['author_email']),
				'keywords'     => trim($_POST['keywords']),
				'content'      => trim($_POST['content']),
				'file_url'     => $file_name,
				'open_type'    => $open_type,
				'link'         => trim($_POST['link_url']),
				'description'  => trim($_POST['description']),
			);
			$query = $this->db_article->where(array('article_id' => $id))->update($data);
			if ($query) {
				$note = sprintf(RC_Lang::lang('articleedit_succeed'), stripslashes($_POST['title']));
				ecjia_admin::admin_log($title, 'edit', 'article');
				$this->showmessage($note, ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array( 'pjaxurl' => RC_Uri::url('article/admin/edit', array('id' => $id))));
			} else {
				$this->showmessage(RC_Lang::lang('articleedit_fail'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}
	}

	/**
	 * 关联商品
	 */
	public function link_goods() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

		$this->assign('ur_here', __('编辑关联商品'));
		$this->assign('action_link', array('href' => RC_Uri::url('article/admin/init'), 'text' => '文章列表'));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('编辑关联商品')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台关联商品页面，可以在此页面编辑相应的关联商品信息。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:文章列表#.E5.85.B3.E8.81.94.E5.95.86.E5.93.81" target="_blank">关于关联商品帮助文档</a>') . '</p>'
		);

		$article_id = $_GET['id'];
		$linked_goods = get_article_goods($article_id);
		$this->assign('link_goods_list', $linked_goods);
		$this->assign('cat_list', cat_list());
		$this->assign('brand_list', get_brand_list());
		
		$this->assign_lang();
		$this->display('link_goods.dwt');
	}

	/**
	 * 添加商品关联
	 */
	public function insert_link_goods() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

		$article_id		= $_GET['id'];
		$linked_array 	= !empty($_GET['linked_array']) ? $_GET['linked_array'] : '';

		$this->db_goods_article->where(array('article_id' => $article_id))->delete();

		$data = array();
		if (!empty($linked_array)) {
			foreach ($linked_array AS $val) {
				$data[] = array(
					'article_id' 	=> $article_id,
					'goods_id' 		=> $val['goods_id'],
					'admin_id' 		=> $_SESSION['admin_id'],
				);
			}
		}
		
		if (!empty($data)) {
			$this->db_goods_article->batch_insert($data);
		}

		$title = $this->db_article->where(array('article_id' => $article_id))->get_field('title');

		ecjia_admin::admin_log('关联商品，'.'文章标题是 '.$title, 'setup', 'article');
		$this->showmessage('成功修改关联商品', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('article/admin/link_goods', array('id' => $article_id))));
	}

	/**
	 * 编辑文章主题
	 */
	public function edit_title() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

		$id    = intval($_POST['pk']);
		$title = trim($_POST['value']);
		$cat_id = intval($_POST['name']);

		if (empty($title)) {
			$this->showmessage('文章标题不能为空', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		if ($this->db_article->where(array('title' => $title, 'cat_id' => $cat_id))->count() != 0) {
			$this->showmessage(sprintf(RC_Lang::lang('title_exist'), $title), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
			$query = $this->db_article->where(array('article_id' => $id))->update( array('title' => $title) );
			if ($query) {
				ecjia_admin::admin_log($title, 'edit', 'article');
				$this->showmessage(sprintf(RC_Lang::lang('edit_title_success'), $title), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => stripslashes($title)));
			} else {
				$this->showmessage(RC_Lang::lang('articleedit_fail'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}
	}

	/**
	 * 切换是否显示
	 */
	public function toggle_show() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

		$id     = intval($_POST['id']);
		$val    = intval($_POST['val']);
		$this->db_article->where(array('article_id' => $id))->update(array('is_open' => $val));

		$title = $this->db_article->where(array('article_id' => $id))->get_field('title');

		if ($val == 1) {
			ecjia_admin::admin_log('显示文章，文章标题是 '.$title, 'setup', 'article');
		} else {
			ecjia_admin::admin_log('隐藏文章，文章标题是 '.$title, 'setup', 'article');
		}
		$this->showmessage(RC_Lang::lang('edit_ok'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $val));
	}

	/**
	 * 切换文章重要性
	 */
	public function toggle_type() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

		$id     = intval($_POST['id']);
		$val    = intval($_POST['val']);
		$data = array( 'article_type' => $val,);
		$this->db_article->where(array('article_id' => $id))->update($data);

		$this->showmessage(RC_Lang::lang('edit_ok'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $val));
	}

	/**
	 * 删除文章
	 */
	public function remove() {
		$this->admin_priv('article_delete', ecjia::MSGTYPE_JSON);

		$id = intval($_GET['id']);
		$old_url = $this->db_article->where(array('article_id' => $id))->get_field('file_url');

		if ( !empty($old_url) && strpos($old_url, 'http://') === false && strpos($old_url, 'https://') === false) {
// 			@unlink(ROOT_PATH . $old_url);
			$disk = RC_Filesystem::disk();
			$disk->delete(RC_Upload::upload_path() . $old_url);
		}

		$name = $this->db_article->where(array('article_id' =>$id))->get_field('title');
		if ($this->db_article->where(array('article_id' => $id))->delete()) {
			$this->db_comment->where(array('comment_type' => 1, 'id_value' => $id))->delete();
			ecjia_admin::admin_log(addslashes($name), 'remove', 'article');
			$this->showmessage(RC_Lang::lang('drop_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
		} else {
			$this->showmessage(RC_Lang::lang('edit_error'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}

	/**
	 * 删除附件
	 */
	public function delfile() {
		$this->admin_priv('article_delete', ecjia::MSGTYPE_JSON);

		$id = intval($_GET['id']);
		$old_url = $this->db_article->where(array('article_id' => $id))->get_field('file_url');
		$disk = RC_Filesystem::disk();
		$disk->delete(RC_Upload::upload_path() . $old_url);
		
		$this->db_article->where(array('article_id' => $id))->update(array('file_url' => '','open_type'=>0));

		$this->showmessage('删除附件成功', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('article/admin/edit', array('id' => $id))));

	}

	/**
	 * 将商品删除关联
	 */
	public function drop_link_goods() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

 		$drop_goods     = $_GET['drop_ids'];
		$arguments      = $_GET['JSON'];
		$arguments or $arguments = array();
		$article_id = $arguments[0]? $arguments[0]: intval($_GET['id']);

		if ($article_id == 0) {
			$article_id = $this->db_article->max('(article_id+1)|article_id');
		}

		$this->db_goods_article->where(array('article_id' => $article_id))->in(array('goods_id' => $drop_goods))->delete()
			or $this->showmessage($this->db_goods_article->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		/* 重新载入 */
		$arrs = get_article_goods($article_id);
		$opt = array();
		$arrs or $arrs = array();
		if (!empty($arrs)) {
			foreach ($arrs AS $key => $val) {
				$opt[] = array(
					'value' => $val['goods_id'],
					'text'  => $val['goods_name'],
					'data'  => '',
				);
			}
		}
		
		$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $opt));
	}

	/**
	 * 搜索商品  ajax-get
	 */
	public function get_goods_list() {
		$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);

		$keyword  = !empty($_GET['keywords']) ? trim($_GET['keywords']) : '';
		$cat_id   = !empty($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
		$brand_id = !empty($_GET['brand_id']) ? intval($_GET['brand_id']) : 0;
		$db_view  = RC_Loader::load_app_model('goods_auto_viewmodel', 'goods');
		$where = " 1 ";
		if (!empty($cat_id)) {
			$where  .= " and ".get_children($cat_id) ;
		}
		if (!empty($brand_id)) {
			$where  .= " and brand_id = " .$brand_id;
		}
		if (!empty($keyword)) {
			$where .=" and goods_name LIKE '%" . mysql_like_quote($keyword) . "%'
        		 OR goods_sn LIKE '%" . mysql_like_quote($keyword) . "%'
        		 OR goods_id LIKE '%" . mysql_like_quote($keyword) . "%'";
		}
		$arrs = $db_view->join(null)->field('goods_id, goods_name, shop_price')->where($where)->limit(50)->select();
		$arrs or $arrs = array();
		$opt = array();
		if (!empty($arrs)) {
			foreach ($arrs AS $key => $val) {
				$opt[] = array(
					'value'  => $val['goods_id'],
					'text'  => $val['goods_name'],
					'data'  => $val['shop_price']
				);
			}
		}

		$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $opt));
	}

	/**
	 * 批量操作
	 */
	public function batch() {
		$sel_action = trim($_GET['sel_action']);//批量操作行为
		$action = !empty($sel_action) ? $sel_action : 'move_to';
		$article_ids = $_POST['article_id'];

		if ($action == 'button_remove') {
			$this->admin_priv('article_delete', ecjia::MSGTYPE_JSON);
		} else {
			$this->admin_priv('article_manage', ecjia::MSGTYPE_JSON);
		}

		$info = $this->db_article->in(array('article_id' => $article_ids))->select();
		
		if (!empty($article_ids)) {
			switch ($action) {
				//批量删除
				case 'button_remove':
					$this->db_article->in(array('article_id' => $article_ids))->delete();

					foreach ($info as $v) {
						if (!empty($v['file_url']) && strpos($v['file_url'], 'http://') === false && strpos($v['file_url'], 'https://') === false) {
							$disk = RC_Filesystem::disk();
							$disk->delete(RC_Upload::upload_path() . $v['file_url']);
						}
						ecjia_admin::admin_log($v['title'], 'batch_remove', 'article');
					}

					$this->showmessage(RC_Lang::lang('batch_handle_ok_del'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('article/admin/init')));
					break;

				//批量隐藏
				case 'button_hide' :
					$data = array( 'is_open' => '0', );
					$this->db_article->in(array('article_id' => $article_ids))->update($data);

					foreach ($info as $v) {
						ecjia_admin::admin_log('隐藏文章，文章标题是 '.$v['title'], 'batch_setup', 'article');
					}
					$this->showmessage(RC_Lang::lang('batch_handle_ok_hide'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('article/admin/init')));
					break;


				//批量显示
				case 'button_show' :
					$data = array( 'is_open' => '1', );
					$this->db_article->in(array('article_id' => $article_ids))->update($data);

					foreach ($info as $v) {
						ecjia_admin::admin_log('显示文章，文章标题是 '.$v['title'], 'batch_setup', 'article');
					}
					$this->showmessage(RC_Lang::lang('batch_handle_ok_show'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('article/admin/init')));
					break;

				//批量转移分类
				case 'move_to' :
					$target_cat = intval($_GET['target_cat']);
					if($target_cat <= 0) {
						$this->showmessage(RC_Lang::lang('no_select_act'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
					}
					if(!is_array($article_ids)){
						$article_ids = explode(',', $article_ids);
					}
					foreach ($article_ids AS $key => $id) {
						$data = array( 'cat_id' => $target_cat );
						$this->db_article->where(array('article_id' => $id))->update($data);
					}
					$cat_name = $this->db_article_cat->where(array('cat_id' => $target_cat))->get_field('cat_name');

					foreach ($info as $v) {
						ecjia_admin::admin_log('转移文章 '.$v['title'].' 至分类 '.$cat_name, 'batch_setup', 'article');
					}

					$this->showmessage(RC_Lang::lang('batch_handle_ok_move'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('article/admin/init')));
					break;

				default :
					break;
			}
		}
	}

	/**
	 * 获取文章列表
	 * @return multitype:NULL string multitype:Ambigous <string, mixed>
	 */
	private	function get_articleslist() {
		$dbview = RC_Loader::load_app_model('article_viewmodel');

		$filter = array();
		$filter['keywords'] = empty($_GET['keywords']) ? '' : trim($_GET['keywords']);
		if (isset($_GET['is_ajax']) && $_GET['is_ajax'] == 1) {
			$filter['keywords'] = $filter['keywords'];
		}
		$filter['cat_id']     = empty($_GET['cat_id']) ? 0 : intval($_GET['cat_id']);
		$filter['sort_by']    = empty($_GET['sort_by']) ? 'a.article_id' : trim($_GET['sort_by']);
		$filter['sort_order'] = empty($_GET['sort_order']) ? 'DESC' : trim($_GET['sort_order']);
		//不获取系统帮助文章的过滤
		$where = 'a.cat_id <> 0 AND ac.cat_type <> 5';

		if (!empty($filter['keywords'])) {
			$where .= " AND a.title LIKE '%" . mysql_like_quote($filter['keywords']) . "%'";
		}
		if ($filter['cat_id'] && ($filter['cat_id'] > 0)) {
			$where .= " AND a." . get_article_children($filter['cat_id']);
		}

		$count = $dbview->join('article_cat')->where($where)->count('article_id');
		$page = new ecjia_page($count, 15, 5);

		$result = $dbview->join('article_cat')->field('a.is_open, a.article_type, ac.cat_type')->where($where)->order(array($filter['sort_by'] => $filter['sort_order']))->limit($page->limit())->select();
		$arr = array();
		if (!empty($result)) {
			foreach ($result as $rows) {
				$rows['date'] = RC_Time::local_date(ecjia::config('time_format'), $rows['add_time']);
				$arr[] = $rows;
			}
		}
		return array('arr' => $arr, 'page' => $page->show(15), 'desc' => $page->page_desc());
	}
}

// end