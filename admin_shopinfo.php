<?php
/**
 * ECJIA 网店信息管理页面
 *  @author songqian
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin_shopinfo extends ecjia_admin {
	
	private $db_article;
	private $db_article_cat;
	
	public function __construct() {
		parent::__construct();
		
		$this->db_article     = RC_Model::model('article/article_model');
		$this->db_article_cat = RC_Model::model('article/article_cat_model');

		/* 加载全局 js/css */
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('shoparticle_info', RC_App::apps_url('statics/js/shoparticle_info.js', __FILE__), array(), false, true);
		RC_Script::enqueue_script('bootstrap-editable.min', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js') , array(), false, false);
		RC_Style::enqueue_style('bootstrap-editable', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'), array(), false, false);
		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Style::enqueue_style('datepicker', RC_Uri::admin_url('statics/lib/datepicker/datepicker.css'), array(), false, false);
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');

		$js_lang = array(
			'shopinfo_title_required' => RC_Lang::get('article::article.shopinfo_title_required'),
		);
		RC_Script::localize_script('shoparticle_info', 'js_lang', $js_lang);
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('article::shopinfo.shop_information'), RC_Uri::url('article/admin_shopinfo/init')));
	}
	
	/**
	 * 网店信息文章列表
	 */
	public function init() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('article::shopinfo.shop_information')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> RC_Lang::get('article::article.overview'),
			'content'	=> '<p>' . RC_Lang::get('article::article.shopinfo_help') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . RC_Lang::get('article::article.more_info') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:网店信息" target="_blank">'.RC_Lang::get('article::article.about_shopinfo').'</a>') . '</p>'
		);
		
		$this->assign('ur_here', RC_Lang::get('article::shopinfo.shop_information'));
		$this->assign('action_link', array('text' => RC_Lang::get('article::shopinfo.shopinfo_add'), 'href'=> RC_Uri::url('article/admin_shopinfo/add')));
		$this->assign('list', $this->get_shopinfo_article());
		
		$this->display('shopinfo_list.dwt');
	}
	
	/**
	 * 添加网店信息
	 */
	public function add() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('article::shopinfo.shopinfo_add')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> RC_Lang::get('article::article.overview'),
			'content'	=> '<p>' . RC_Lang::get('article::article.add_shopinfo_help') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . RC_Lang::get('article::article.more_info') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:网店信息" target="_blank">'.RC_Lang::get('article::article.about_add_shopinfo').'</a>') . '</p>'
		);
		
		$this->assign('ur_here', RC_Lang::get('article::shopinfo.shopinfo_add'));
		$this->assign('action_link', array('text' => RC_Lang::get('article::shopinfo.shop_information'), 'href'=> RC_Uri::url('article/admin_shopinfo/init')));
		
		$article['article_type'] = 0;
		
		$this->assign('form_action', RC_Uri::url('article/admin_shopinfo/insert'));
		$this->display('shopinfo_info.dwt');
	}
	
	
	public function insert() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		
		$title    = !empty($_POST['title'])       ? trim($_POST['title'])         : '';
		$content  = !empty($_POST['content'])     ? trim($_POST['content'])       : '';
		$keywords = !empty($_POST['keywords'])    ? trim($_POST['keywords'])      : '';
		$desc     = !empty($_POST['description']) ? trim($_POST['description'])   : '';
		
 		$is_only = $this->db_article->article_count(array('title' => $title, 'cat_id' => 0));
		
		if ($is_only != 0) {
			$this->showmessage(sprintf(RC_Lang::get('article::shopinfo.title_exist'), stripslashes($title)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		
		$data = array(
			'title' 	   	=> $title,
			'cat_id'   		=> 0,
			'content'  		=> $content,
			'keywords'  	=> $keywords,
			'description'  	=> $desc,
			'add_time' 		=> RC_Time::gmtime(),
		);
		$id = $this->db_article->article_manage($data);

		ecjia_admin::admin_log($title, 'add', 'shopinfo');
		$this->showmessage(sprintf(RC_Lang::get('article::shopinfo.articleadd_succeed'), stripslashes($title)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('article/admin_shopinfo/edit', array('id' => $id))));
	}
	
	/**
	 * 编辑网店信息
	 */
	public function edit() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
	
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('article::shopinfo.shopinfo_edit')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> RC_Lang::get('article::article.overview'),
			'content'	=> '<p>' . RC_Lang::get('article::article.edit_shopinfo_help') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . RC_Lang::get('article::article.more_info') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:网店信息" target="_blank">'.RC_Lang::get('article::article.about_edit_shopinfo').'</a>') . '</p>'
		);
		
		$this->assign('ur_here', RC_Lang::get('article::shopinfo.shopinfo_edit'));
		$this->assign('action_link', array('text' => RC_Lang::get('article::shopinfo.shop_information'), 'href'=> RC_Uri::url('article/admin_shopinfo/init')));
		
		$id = intval($_GET['id']);
		$article = $this->db_article->article_find($id);
		
		$this->assign('article', $article);
		$this->assign('form_action', RC_Uri::url('article/admin_shopinfo/update'));
		
		$this->display('shopinfo_info.dwt');
	}
	
	public function update() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		
		$title    = !empty($_POST['title'])       ? trim($_POST['title'])         : '';
		$content  = !empty($_POST['content'])     ? trim($_POST['content'])       : '';
		$keywords = !empty($_POST['keywords'])    ? trim($_POST['keywords'])      : '';
		$desc     = !empty($_POST['description']) ? trim($_POST['description'])   : '';
		$old_title= !empty($_POST['old_title'])   ? trim($_POST['old_title'])     : '';
		$id       = !empty($_POST['id'])          ? intval($_POST['id'])          : 0;
		
		if ($title != $old_title) {
			$is_only = $this->db_article->article_count(array('title' => $title, 'cat_id' => 0));
			if ($is_only != 0) {
				$this->showmessage(sprintf(RC_Lang::get('article::shopinfo.title_exist'), stripslashes($title)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}

		$data = array(
		    'article_id'  => $id,
			'title'       => $title,
			'content'     => $content,
			'keywords'    => $keywords,
			'description' => $desc,
			'add_time'    => RC_Time::gmtime()
		);
		if ($this->db_article->article_manage($data)) {
			ecjia_admin::admin_log($title, 'edit', 'shopinfo');
			
			$links = array('text' => RC_Lang::get('article::shopinfo.back_list'), 'href' => RC_Uri::url('article/admin_shopinfo/init'));
			$this->showmessage(sprintf(RC_Lang::get('article::shopinfo.articleedit_succeed'), stripslashes($title)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links, 'pjaxurl' => RC_Uri::url('article/admin_shopinfo/edit', array('id' => $id))));
		}
	}
	
	/**
	 * 删除网店信息
	 */
	public function remove() {
		$this->admin_priv('shopinfo_manage', ecjia::MSGTYPE_JSON);
		
		$id = intval($_GET['id']);
		$title = $this->db_article->article_field(array('article_id' => $id), 'title');
		
		if ($this->db_article->article_delete($id)) {
			ecjia_admin::admin_log(addslashes($title), 'remove', 'shopinfo');
		}
		$this->showmessage(sprintf(RC_Lang::get('article::shopinfo.remove_success'), $title), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
	}
	
	/**
	 * 获取网店信息文章
	 */
	private function get_shopinfo_article($cat_id = 0) {
	    $data = RC_DB::table('article')
	    	->select('article_id', 'title', 'add_time')
	    	->where('cat_id', $cat_id)
	    	->orderby('article_id', 'asc')
	    	->get();
	    
	    $list = array();
	    if (!empty($data)) {
	        foreach ($data as $rows) {
	            $rows['add_time'] = RC_Time::local_date(ecjia::config('time_format'), $rows['add_time']);
	            $list[] = $rows;
	        }
	    }
	    return $list;
	}
}

// end