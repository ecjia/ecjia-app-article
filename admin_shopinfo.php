<?php
/**
 * ECJIA 网店信息管理页面
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin_shopinfo extends ecjia_admin {
	private $db_article;
	private $db_article_cat;
	
	public function __construct() {
		parent::__construct();
		
		RC_Lang::load('admin_shopinfo');
		RC_Loader::load_app_func('article');
	
		/* 数据模型加载 */
		$this->db_article     = RC_Loader::load_app_model('article_model');
		$this->db_article_cat = RC_Loader::load_app_model('article_cat_model');

		/* 加载全局 js/css */
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('bootstrap-editable.min', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js') , array(), false, false);
		RC_Style::enqueue_style('bootstrap-editable', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'), array(), false, false);
		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Style::enqueue_style('datepicker', RC_Uri::admin_url('statics/lib/datepicker/datepicker.css'), array(), false, false);
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');

		RC_Script::enqueue_script('shoparticle_info', RC_App::apps_url('statics/js/shoparticle_info.js', __FILE__), array(), false, true);
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('网店信息'), RC_Uri::url('article/admin_shopinfo/init')));
	}
	
	/**
	 * 网店信息文章列表
	 */
	public function init() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here', '网店信息');
		$this->assign('action_link', array('text' => RC_Lang::lang('shopinfo_add'), 'href'=> RC_Uri::url('article/admin_shopinfo/add')));
		$this->assign('list', $this->shopinfo_article_list(0));

		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('网店信息')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台网店信息页面，系统中所有的网店信息都会显示在此列表中。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:网店信息" target="_blank">关于网店信息帮助文档</a>') . '</p>'
		);
		
		$this->assign_lang();
		$this->display('shopinfo_list.dwt');
	}
	
	/**
	 * 添加新文章
	 */
	public function add() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		
		$article['article_type'] = 0;
		$this->assign('ur_here', RC_Lang::lang('shopinfo_add'));
		$this->assign('action_link', array('text' => '返回网店信息', 'href'=> RC_Uri::url('article/admin_shopinfo/init')));
		$this->assign('form_action', RC_Uri::url('article/admin_shopinfo/insert'));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('添加网店信息')));
		ecjia_screen::get_current_screen()->add_help_tab( array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台添加网店信息页面，可以在此页面添加网店信息。') . '</p>'
		) );
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
		'<p><strong>' . __('更多信息:') . '</strong></p>' .
		'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:网店信息" target="_blank">关于添加网店信息帮助文档</a>') . '</p>'
		);
		
		$this->assign_lang();
		$this->display('shopinfo_info.dwt');
	}
	
	public function insert() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		
		/* 判断是否重名 */
		$is_only = $this->db_article->where(array('title' => $_POST['title'],'cat_id'=>0))->count();
		$title = trim($_POST['title']);
		if ($is_only != 0) {
			$this->showmessage(sprintf(RC_Lang::lang('title_exist'), stripslashes($title)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		/* 插入数据 */
		$add_time = RC_Time::gmtime();
		$data = array(
			'title' 	   	=> $title,
			'cat_id'   		=> 0,
			'content'  		=> trim($_POST['content']),
			'keywords' 	 	=> trim($_POST['keywords']),
			'description'  	=> trim($_POST['description']),
			'add_time' 		=> $add_time,
		);
		$id = $this->db_article->insert($data);

		/* 记录管理员操作 */
		ecjia_admin::admin_log($title, 'add', 'shopinfo');
		$this->showmessage(sprintf(RC_Lang::lang('articleadd_succeed'), stripslashes($title)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('article/admin_shopinfo/edit', array('id' => $id))));
	}
	
	/**
	 * 文章编辑
	 */
	public function edit() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here', RC_Lang::lang('shopinfo_edit'));
		$this->assign('action_link', array('text' => '返回网店信息', 'href'=> RC_Uri::url('article/admin_shopinfo/init')));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('编辑网店信息')));
		ecjia_screen::get_current_screen()->add_help_tab( array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台编辑网店信息页面，可以在此页面编辑相应的网店信息。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:网店信息" target="_blank">关于编辑网店信息帮助文档</a>') . '</p>'
		);
		
		$id = intval($_GET['id']);
		$article = $this->db_article->field('article_id, title, content,keywords,description')->find(array('article_id' => $id));

		$this->assign('article', $article);
		$this->assign('form_action', RC_Uri::url('article/admin_shopinfo/update'));
		$this->assign_lang();
		
		$this->display('shopinfo_info.dwt');
	}
	
	public function update() {
		/* 权限判断 */ 
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		
		$title 		= trim($_POST['title']);
		$old_title 	= trim($_POST['old_title']);
		$id 		= intval($_POST['id']);
		$content 	= trim($_POST['content']);
		$keywords 	= trim($_POST['keywords']);
		$description = trim($_POST['description']);
		/* 检查重名 */
		if ($title != $old_title) {
			$is_only = $this->db_article->where(array('title' => $title, 'cat_id' => 0))->count();
			if ($is_only != 0) {
				$this->showmessage(sprintf(RC_Lang::lang('title_exist'), stripslashes($title)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}
		/* 更新数据 */
		$cur_time = RC_Time::gmtime();
		$data = array(
			'title'       => $title, 
			'content'     => $content, 
			'keywords'    => $keywords,
			'description' => $description,
			'add_time'    => $cur_time
		);
		if ($this->db_article->where(array('article_id' => $id))->update($data)) {
			ecjia_admin::admin_log($title, 'edit', 'shopinfo');
			$links = array('text' => RC_Lang::lang('back_list'), 'href' => RC_Uri::url('article/admin_shopinfo/init'));
			$this->showmessage(sprintf(RC_Lang::lang('articleedit_succeed'), stripslashes($title)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links, 'pjaxurl' => RC_Uri::url('article/admin_shopinfo/edit', array('id' => $id))));
		}
	}
	
	/**
	 * 编辑文章主题
	 */
	public function edit_title() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		$id    = intval($_POST['id']);
		$title = trim($_POST['val']);
		/* 检查文章标题是否有重名 */
		if ($this->db_article->where(array('title' => $title, 'article_id' => array('neq' => $id)))->count() == 0) {
			if($this->db_article->where(array('article_id' => $id))->update(array('title' => $title))) {
				ecjia_admin::admin_log($title, 'edit', 'shopinfo');
				$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content'=>stripslashes($title)));
			}
		} else {
			$this->showmessage(sprintf(RC_Lang::lang('title_exist'), $title), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
		}
	}
	
	/**
	 * 删除文章
	 */
	public function remove() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		$id = intval($_GET['id']);
		
		/* 获得文章主题 */
		$title = $this->db_article->where(array('article_id' => $id))->get_field('title');
		if ($this->db_article->where(array('article_id' => $id))->delete()) {
			ecjia_admin::admin_log(addslashes($title), 'remove', 'shopinfo');
		}
		$this->showmessage(sprintf(RC_Lang::lang('remove_success'), $title), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
	}
	
	/**
	 * 获取网店信息文章数据
	 */
	private	function shopinfo_article_list($cat_id) {
		$db_article = RC_Loader::load_app_model('article_model');
		$data = $db_article->field('article_id, title , add_time')->where(array('cat_id' => $cat_id))->order('article_id asc')->select();
		$list = array();
		if(!empty($data)) {
			foreach ($data as $rows) {
				$rows['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $rows['add_time']);
				$list[] = $rows;
			}
		}
		return $list;
	}
}
// end