<?php
/**
 * ECJIA 帮助信息管理程序
 */
defined('IN_ECJIA') or exit('No permission resources.');
RC_Loader::load_sys_class('ecjia_admin', false);

class admin_shophelp extends ecjia_admin 
{
	private $db_article;
	private $db_article_cat;
	public function __construct() 
	{
		parent::__construct();
		
		RC_Lang::load('admin_shophelp');
		RC_Loader::load_app_func('article');
		
		/* 数据模型加载 */
		$this->db_article 	  = RC_Loader::load_app_model('article_model', 'article');
		$this->db_article_cat = RC_Loader::load_app_model('article_cat_model', 'article');
		
		//html
// 		RC_Script::enqueue_script('article-tinymce-script', RC_Uri::vendor_url() . '/tinymce/tinymce.min.js', array(), false, true);
	
		/* 加载全局 js/css */
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		
		RC_Script::enqueue_script('shophelp_list', RC_App::apps_url('statics/js/shophelp_list.js', __FILE__), array(), false, true);
		RC_Script::enqueue_script('bootstrap-editable.min', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js'));
		RC_Style::enqueue_style('bootstrap-editable', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'));
		
		
		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Style::enqueue_style('datepicker', RC_Uri::admin_url('statics/lib/datepicker/datepicker.css'));
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');
		
		//加载分页类
		RC_Loader::load_sys_class('ecjia_page', false);
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('帮助分类'), RC_Uri::url('article/admin_shophelp/init')));
	}

	/**
	 * 网店帮助分类
	 */
	public function init () 
	{
		$this->admin_priv('shophelp_manage',ecjia::MSGTYPE_JSON);
		
		$this->assign('action_link', array('text' => RC_Lang::lang('article_add'), 'href' => RC_Uri::url('article/admin_shophelp/add/')));
		$this->assign('ur_hereadd',  RC_Lang::lang('cat_add'));
		$this->assign('ur_here',     '帮助分类');
		$this->assign('full_page',   1);
		$this->assign('list',        $this->get_shophelp_list());
		$this->assign('form_action', RC_Uri::url('article/admin_shophelp/add_catname'));

		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('帮助分类')));
		ecjia_screen::get_current_screen()->add_help_tab( array(
		'id'		=> 'overview',
		'title'		=> __('概述'),
		'content'	=>
		'<p>' . __('欢迎访问ECJia智能后台网店帮助列表页面，系统中所有的网店帮助都会显示在此列表中。') . '</p>'
		) );
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
		'<p><strong>' . __('更多信息:') . '</strong></p>' .
		'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:网店帮助" target="_blank">关于网店帮助列表帮助文档</a>') . '</p>'
		);
		
		$this->assign_lang();
		$this->display('shophelp_cat_list.dwt');
	}
	

	/**
	 * 分类下的文章
	 */
	public function list_article() 
	{
		$this->admin_priv('shophelp_manage',ecjia::MSGTYPE_JSON);
		
		//获取分类
		$cat_id = intval($_GET['cat_id']);
		$cat_name   = $this->db_article_cat->where(array('cat_id' => $cat_id))->get_field('cat_name');
		
		$this->assign('ur_here',     $cat_name.'列表');
		
		$this->assign('action_linkadd', array('text' => '添加帮助文章', 'href' => RC_Uri::url('article/admin_shophelp/add', array('cat_id' => $cat_id))));
		$this->assign('back_helpcat', array('text' => '返回帮助分类', 'href' => RC_Uri::url('article/admin_shophelp/init')));
	    
		$this->assign('full_page',   1);
		$this->assign('cat',         article_cat_list($cat_id, true, 'cat_id', 0, "onchange=\"location.href='?act=list_article&cat_id='+this.value\""));
		$this->assign('list',        $this->shophelp_article_list($cat_id));
		$this->assign('cat_id',      $cat_id);

		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($cat_name));
		ecjia_screen::get_current_screen()->add_help_tab( array(
		'id'		=> 'overview',
		'title'		=> __('概述'),
		'content'	=>
		'<p>' . __('欢迎访问ECJia智能后台网店帮助文章列表页面，系统中指定分类下的网店帮助文章都会显示在此列表中。') . '</p>'
		) );
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
		'<p><strong>' . __('更多信息:') . '</strong></p>' .
		'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:网店帮助#.E6.9F.A5.E7.9C.8B.E5.B8.AE.E5.8A.A9.E6.96.87.E7.AB.A0" target="_blank">关于网店帮助文章帮助文档</a>') . '</p>'
		);
		
		$this->assign_lang();
		$this->display('shophelp_article_list.dwt');
	}
	
	/**
	 * 添加文章
	 */
	public function add() 
	{
		$this->admin_priv('shophelp_manage',ecjia::MSGTYPE_JSON);
		
		$cat_id = intval($_GET['cat_id']);
		$cat_name   = $this->db_article_cat->where(array('cat_id' => $cat_id))->get_field('cat_name');
		$this->assign('cat_name',      $cat_name);
		$this->assign('cat_id',      $cat_id);
		$this->assign('ur_here',    '添加帮助文章');
		$this->assign('action_link', array('text' => '帮助文章列表', 'href' => RC_Uri::url('article/admin_shophelp/list_article', array(cat_id => $cat_id))));
		$this->assign('form_action', RC_Uri::url('article/admin_shophelp/insert'));
		
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($cat_name, RC_Uri::url('article/admin_shophelp/list_article', array(cat_id => $cat_id))));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('添加帮助文章')));
		ecjia_screen::get_current_screen()->add_help_tab( array(
		'id'		=> 'overview',
		'title'		=> __('概述'),
		'content'	=>
		'<p>' . __('欢迎访问ECJia智能后台添加帮助文章页面，可以在此页面添加帮助文章信息。') . '</p>'
		) );
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
		'<p><strong>' . __('更多信息:') . '</strong></p>' .
		'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:网店帮助#.E6.B7.BB.E5.8A.A0.E5.B8.AE.E5.8A.A9.E6.96.87.E7.AB.A0" target="_blank">关于添加帮助文章帮助文档</a>') . '</p>'
		);
		
		$this->assign_lang();
		$this->display('shophelp_info.dwt');
	}
	
	public function insert() 
	{
		$this->admin_priv('shophelp_manage', ecjia::MSGTYPE_JSON);
		$title = trim($_POST['title']);
		$cat_id = trim($_POST['cat_id']);

		/* 判断是否重名 */
		$is_only = $this->db_article->where(array('title' => $title,'cat_id'=>$cat_id))->count();
		if ($is_only != 0) {
			$this->showmessage(sprintf(RC_Lang::lang('title_exist'), stripslashes($title)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		/* 插入数据 */
		$add_time = RC_Time::gmtime();
		$cat_id = intval($_POST['cat_id']);
		$data = array(
				'title'		   => $title,
				'cat_id'		=> $cat_id,
				'article_type' => intval($_POST['article_type']),
				'content'	   => trim($_POST['content']),
				'keywords'	   => trim($_POST['keywords']),
				'description'	   => trim($_POST['description']),
				'add_time'	   => $add_time,
				'author'		   => '_SHOPHELP',
		);
		$id=$this->db_article->insert($data);
		
		$cat_name = $this->db_article_cat->where(array('cat_id'=>$cat_id))->get_field('cat_name');
		
		ecjia_admin::admin_log($title.'，'.'所属帮助分类是 '.$cat_name, 'add', 'shophelp');
		
		$links[] = array('text' => RC_Lang::lang('back_article_list'), 'href' => RC_Uri::url('article/admin_shophelp/list_article', array('cat_id' => $cat_id)));
		
		$this->showmessage(RC_Lang::lang('articleadd_succeed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links, 'pjaxurl' => RC_Uri::url('article/admin_shophelp/edit', array(id => $id, 'cat_id' => $_POST['cat_id']))));
	}
	
	/**
	 * 编辑文章
	 */
	public function edit() 
	{
		$this->admin_priv('shophelp_manage',ecjia::MSGTYPE_JSON);
		
		$article_id = intval($_GET['id']);
		$cat_id = intval($_GET['cat_id']);
		$cat_name   = $this->db_article_cat->where(array('cat_id' => $cat_id))->get_field('cat_name');
		/* 取文章数据 */
		$article = $this->db_article->field('article_id, title, cat_id, article_type, is_open, author, author_email, keywords,description, content')->find(array('article_id' => $article_id));
		$this->assign('cat_id',      $article['cat_id']);
		$this->assign('ur_here',     RC_Lang::lang('article_edit'));
		$this->assign('action_link', array('text' => '帮助文章列表', 'href'=> RC_Uri::url('article/admin_shophelp/list_article',array('cat_id'=>$cat_id))));
		$this->assign('article',     $article);
		$this->assign('form_action', RC_Uri::url('article/admin_shophelp/update'));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($cat_name, RC_Uri::url('article/admin_shophelp/list_article', "cat_id={$article['cat_id']}")));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('编辑帮助文章')));
		ecjia_screen::get_current_screen()->add_help_tab( array(
		'id'		=> 'overview',
		'title'		=> __('概述'),
		'content'	=>
		'<p>' . __('欢迎访问ECJia智能后台编辑帮助文章页面，可以在此页面编辑相应的帮助文章信息。') . '</p>'
		) );
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
		'<p><strong>' . __('更多信息:') . '</strong></p>' .
		'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:网店帮助#.E7.BC.96.E8.BE.91.E5.B8.AE.E5.8A.A9.E6.96.87.E7.AB.A0" target="_blank">关于编辑帮助文章帮助文档</a>') . '</p>'
		);
		
		$this->assign_lang();
		$this->display('shophelp_info.dwt');
	}
	
	
	public function update() 
	{
		/* 权限判断 */ 
		$this->admin_priv('shophelp_manage', ecjia::MSGTYPE_JSON);
		$cat_id    = intval($_POST['cat_id']);
		$id        = intval($_POST['id']);
		$title     = trim($_POST['title']);
		$old_title = trim($_POST['old_title']);
		/* 检查重名 */
		if ($title != $old_title) {
			$is_only = $this->db_article->where(array('title' => $title,'cat_id'=>$cat_id))->count();
			if ($is_only != 0) {
				$this->showmessage(sprintf(RC_Lang::lang('title_exist'), stripslashes($title)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}
		/* 更新 */
		$data = array(
				'title'        => $title,
				'cat_id'       => $cat_id,
				'article_type' => intval($_POST['article_type']),
				'content'      => trim($_POST['content']),
				'keywords'      => trim($_POST['keywords']),
				'description'   => trim($_POST['description']),
		);
		if ($this->db_article->where(array('article_id' => $id))->update($data)) {
			$cat_name = $this->db_article_cat->where(array('cat_id'=>$cat_id))->get_field('cat_name');
		
			ecjia_admin::admin_log($title.'，'.'所属帮助分类是 '.$cat_name, 'edit', 'shophelp');
			
			$links[] = array('text' => RC_Lang::lang('back_article_list'), 'href' => RC_Uri::url('article/admin_shophelp/list_article', array('cat_id' => $cat_id)));
			$this->showmessage(sprintf(RC_Lang::lang('articleedit_succeed'), $title), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links, 'pjaxurl' => RC_Uri::url('article/admin_shophelp/edit', array(id => $id, 'cat_id' => $cat_id))));
		}
	}
	
	/**
	 * 编辑分类的名称
	 */
	public function edit_catname() 
	{
		$this->admin_priv('shophelp_manage', ecjia::MSGTYPE_JSON);
		
		$id       = intval($_POST['pk']);
		$cat_name = trim($_POST['value']);
		
		if (empty($cat_name)) {
			$this->showmessage('请输入分类名',ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		
		/* 检查分类名称是否重复 */
		if ($this->db_article_cat->where(array('cat_name' => $cat_name, 'cat_id' => array('neq' => $id)))->count()) {
			$this->showmessage(RC_Lang::lang('catname_exist'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
			if ($this->db_article_cat->where(array('cat_id' => $id))->update(array('cat_name' => $cat_name ))) {
				
				ecjia_admin::admin_log($cat_name, 'edit', 'shophelpcat');
				$this->showmessage(RC_Lang::lang('catedit_ok'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => stripslashes($cat_name)));
			} else {
				$this->showmessage(RC_Lang::lang('catedit_fail'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}
	}
	
	
	/**
	 * 编辑分类的排序
	 */
	public function edit_cat_order() 
	{
		$this->admin_priv('shophelp_manage', ecjia::MSGTYPE_JSON);
		
		$id    = intval($_POST['pk']);
		$order = trim($_POST['value']);
		
		/* 检查输入的值是否合法 */
		if (!is_numeric($order)) { //!preg_match("/^[0-9]+$/", $order)
			$this->showmessage(RC_Lang::lang('enter_int'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
			if ($this->db_article_cat->where(array('cat_id' => $id))->update(array('sort_order' => $order))) {
				
				$this->showmessage(RC_Lang::lang('catedit_ok'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_uri::url('article/admin_shophelp/init')) );
			}
		}
	}
	
	/**
	 * 删除分类
	 */
	public function remove() 
	{
		$this->admin_priv('shophelp_manage', ecjia::MSGTYPE_JSON);
		$id = intval($_GET['cat_id']);
		/* 非空的分类不允许删除 */
		if ($this->db_article->where(array('cat_id' => $id))->count() != 0) {
			$this->showmessage(RC_Lang::lang('not_emptycat'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
			$cat_name = $this->db_article_cat->where(array('cat_id' => $id))->get_field('cat_name');
			
			ecjia_admin::admin_log($cat_name, 'remove', 'shophelpcat');
			$this->db_article_cat->where(array('cat_id' => $id))->delete();
			$this->showmessage(RC_Lang::lang('del_succeed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
		}
	}
	
	/**
	 * 删除分类下的某文章
	 */
	public function remove_art() 
	{
		$this->admin_priv('shophelp_manage', ecjia::MSGTYPE_JSON);
		$id = intval($_GET['id']);
		$info = $this->db_article->where(array('article_id' => $id))->find();
		
		if ($this->db_article->where(array('article_id' => $id))->delete()) {
			
			$cat_name = $this->db_article_cat->where(array('cat_id'=>$info['cat_id']))->get_field('cat_name');
		
			ecjia_admin::admin_log($info['title'].'，'.'所属帮助分类是 '.$cat_name, 'remove', 'shophelp');
			
			$this->showmessage(RC_Lang::lang('remove_article_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
		} else {
			$this->showmessage(sprintf(RC_Lang::lang('remove_fail')), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	/**
	 * 添加一个新分类
	 */
	public function add_catname() 
	{
		$this->admin_priv('shophelp_manage', ecjia::MSGTYPE_JSON);
		$cat_name = trim($_POST['cat_name']);
		if (!empty($cat_name)) {
			if ($this->db_article_cat->where(array('cat_name' => $cat_name))->count() != 0) {
				$this->showmessage(RC_Lang::lang('catname_exist'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			} else {
				$data = array(
						'cat_name'  => $cat_name,
						'cat_type'  => 5,
						'parent_id' => 3,
				);
				$this->db_article_cat->insert($data);
				ecjia_admin::admin_log($cat_name, 'add', 'shophelpcat');				
				$this->showmessage(sprintf(RC_Lang::lang('catadd_succeed'), $cat_name), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjax' => RC_Uri::url('article/admin_shophelp/init')));
			}
		} else {
			$this->showmessage(RC_Lang::lang('js_languages/no_catname'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	
	/**
	 * 编辑文章标题
	 */
	public function edit_title() 
	{
		$this->admin_priv('shophelp_manage', ecjia::MSGTYPE_JSON);
		
		$id    = intval($_POST['id']);
		$title = trim($_POST['val']);
		
		/* 检查文章标题是否有重名 */
		if ($this->db_article->where(array('title' => $title,'article_id' => array('neq' => $id)))->count() == 0) {
			if ($this->db_article->where(array('article_id' => $id))->update(array('title' => $title))) {
				//clear_cache_files();
				ecjia_admin::admin_log($title, 'edit', 'shophelp');
				$this->showmessage(RC_Lang::lang('catedit_ok'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => stripslashes($title)));
			}
		} else {
			$this->showmessage(sprintf(RC_Lang::lang('articlename_exist'), $title), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
    }
    
    
	/**
	 * 获得网店帮助文章分类
	 * @return array
	 */
	private	function get_shophelp_list() 
	{
		$list = array();
	    $data = $this->db_article_cat->field(array('cat_id', 'cat_name', 'sort_order'))->where(array('cat_type' => 5, 'parent_id' => 3))->order('sort_order asc')->select();
		if (!empty($data)) {
		    foreach ($data as $rows) {
// 				$count       = $this->db_article->where(array('cat_id' => $rows[cat_id]))->count();
// 		    	$rows['num'] = $count;
		        $list[]      = $rows;
		    }
		}
		return $list;
	}
	
	/**
	 * 获得网店帮助某分类下的文章
	 * @param int $cat_id
	 * @return array
	 */
	private function shophelp_article_list($cat_id) 
	{
		$list=array();
		$data = $this->db_article->field(array('article_id', 'title', 'article_type' , 'add_time'))->where(array('cat_id' => $cat_id))->order('article_type DESC')->select();
		if (!empty($data)) {
			foreach ($data as $rows) {
				$rows['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $rows['add_time']);
				$list['row'][] = $rows;
			}
		}
		$list['num'] = count($data);
		return $list;
	}
}

// end