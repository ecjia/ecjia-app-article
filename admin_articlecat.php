<?php
/**
 * ECJIA 文章分类管理程序 
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin_articlecat extends ecjia_admin {
	private $db_article;
	private $db_article_cat;
	private $db_nav;
	public function __construct() {
		parent::__construct();
		
		RC_Lang::load('article');
		RC_Loader::load_app_func('article');

		$this->db_nav 		  = RC_Loader::load_model('nav_model');
		$this->db_article     = RC_Loader::load_app_model('article_model');
		$this->db_article_cat = RC_Loader::load_app_model('article_cat_model');
		
		/* 加载全局 js/css */
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('bootstrap-editable.min', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js') );
		RC_Style::enqueue_style('bootstrap-editable', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'));
		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');
		
		RC_Script::enqueue_script('article_cat_info', RC_App::apps_url('statics/js/article_cat_info.js', __FILE__), array(), false, true);
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('文章分类'), RC_Uri::url('article/admin_articlecat/init')));
	}
		
	/**
	 * 分类列表
	 */
	public function init() {
		$this->admin_priv('article_cat_manage', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here', RC_Lang::lang('02_articlecat_list'));
		$this->assign('action_link', array('text' => RC_Lang::lang('articlecat_add'), 'href' => RC_Uri::url('article/admin_articlecat/add')));
		
		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('文章分类')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台文章分类页面，系统中所有的文章分类都会显示在此列表中。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:文章分类" target="_blank">关于文章分类帮助文档</a>') . '</p>'
		);
		
		$articlecat = article_cat_list(0, 0, false);
		if (!empty($articlecat)) {
			foreach ($articlecat as $key => $cat) {
				$articlecat[$key]['type_name'] = RC_Lang::lang('type_name/'.$cat['cat_type']);
			}	
		}
		$this->assign('articlecat', $articlecat);
		$this->assign_lang();
		
		$this->display('articlecat_list.dwt');
	}
	
	/**
	 * 添加文章分类
	 */
	public function add() {
		/* 权限判断 */
		$this->admin_priv('article_cat_manage', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here', RC_Lang::lang('articlecat_add'));
		$this->assign('action_link', array('text' => RC_Lang::lang('back_cat_list'), 'href' => RC_Uri::url('article/admin_articlecat/init')));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('添加文章分类')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台添加文章分类页面，可以在此页面添加文章分类信息。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:文章分类#.E6.B7.BB.E5.8A.A0.E6.96.87.E7.AB.A0.E5.88.86.E7.B1.BB" target="_blank">关于添加文章分类帮助文档</a>') . '</p>'
		);
		
		$this->assign('form_action', RC_Uri::url('article/admin_articlecat/insert'));
		$this->assign('cat_select', article_cat_list(0));
		
		$this->assign_lang();
		$this->display('articlecat_info.dwt');
	}
	
	public function insert() {
		$this->admin_priv('article_cat_manage', ecjia::MSGTYPE_JSON);
		$cat_name = trim($_POST['cat_name']);
		
		/*检查分类名是否重复*/
		if ($this->db_article_cat->where(array('cat_name' => $cat_name))->count() > 0) {
			$this->showmessage(sprintf(RC_Lang::lang('catname_exist'), stripslashes($_POST['cat_name'])), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		$cat_type = 1;
		$parent_id = intval($_POST['parent_id']);
		if ($parent_id > 0) {
			$p_cat_type = $this->db_article_cat->where(array('cat_id' => $parent_id))->get_field('cat_type');
			$p_cat_type = $p_cat_type['cat_type'];
			if ($p_cat_type == 2 || $p_cat_type == 3 || $p_cat_type == 5) {
				$this->showmessage(RC_Lang::lang('not_allow_add'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			} else if ($p_cat_type == 4) {
				$cat_type = 5;
			}
		}
		$show_in_nav = !empty($_POST['show_in_nav']) ? intval($_POST['show_in_nav']) : 0;
		$data = array( 
			'cat_name'	  => $cat_name,
			'cat_type' 	  => $cat_type,
			'cat_desc' 	  => trim($_POST['cat_desc']),
			'keywords' 	  => trim($_POST['keywords']),
			'parent_id'   => $parent_id,
			'sort_order'  => intval($_POST['sort_order']),
			'show_in_nav' => $show_in_nav,
		);
		$id = $this->db_article_cat->insert($data);
		
		if ($show_in_nav == 1) {
			$vieworder = $this->db_nav->where(array('type' => 'middle'))->max('vieworder');
			$vieworder += 2;
			//显示在自定义导航栏中
			$data = array(
				'name' 		=> $cat_name,
				'ctype'   	=> 'a',
				'cid' 		=> $id,
				'ifshow' 	=> '1',
				'vieworder'	=> $vieworder,
				'opennew' 	=> '0',
				'url' 		=>  build_uri('article_cat', array('acid' => $id), $cat_name),
				'type' 		=> 'middle',
			);
			$this->db_nav->insert($data);
		}
		
		ecjia_admin::admin_log($cat_name, 'add', 'articlecat');
		$links[] = array('text' => RC_Lang::lang('back_cat_list'), 'href' => RC_Uri::url('article/admin_articlecat/init'));
		$links[] = array('text' => RC_Lang::lang('continue_add'), 'href' => RC_Uri::url('article/admin_articlecat/add'));
		$this->showmessage($_POST['cat_name']. RC_Lang::lang('catadd_succed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links, 'pjaxurl' => RC_Uri::url('article/admin_articlecat/edit', array('id' => $id))));		
	}
	
	/**
	 * 编辑文章分类
	 */
	public function edit() {
		$this->admin_priv('article_cat_manage', ecjia::MSGTYPE_JSON);
		
		$this->assign('ur_here', RC_Lang::lang('articlecat_edit'));
		$this->assign('action_link', array('text' => RC_Lang::lang('back_cat_list'), 'href' => RC_Uri::url('article/admin_articlecat/init')));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('编辑文章分类')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> __('概述'),
			'content'	=>
			'<p>' . __('欢迎访问ECJia智能后台编辑文章分类页面，可以在此页面编辑相应的文章分类信息。') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . __('更多信息:') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:文章分类#.E7.BC.96.E8.BE.91.E6.96.87.E7.AB.A0.E5.88.86.E7.B1.BB" target="_blank">关于编辑文章分类帮助文档</a>') . '</p>'
		);
		
		$cat = $this->db_article_cat->field('cat_id, cat_name, cat_type, cat_desc, show_in_nav, keywords, parent_id,sort_order')->find( array('cat_id' => $_REQUEST['id']) );
		if ($cat['cat_type'] == 2 || $cat['cat_type'] == 3 || $cat['cat_type'] ==4) {
			$this->assign('disabled', 1);
		}
		$options  = article_cat_list(0, $cat['parent_id'], false);
		$select   = '';
		$selected = $cat['parent_id'];
		$id = intval($_GET['id']);
		
		foreach ($options as $var) {
			if (intval($var['cat_id']) == $id) {
				continue;
			}
			$select .= '<option value="' . $var['cat_id'] . '" ';
			$select .= ' cat_type="' . $var['cat_type'] . '" ';
			$select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
			$select .= '>';
			if ($var['level'] > 0) {
				$select .= str_repeat('&nbsp;', $var['level'] * 4);
			}
			$select .= htmlspecialchars($var['cat_name']) . '</option>';
		}
		unset($options);
		
		$this->assign('cat', $cat);
		$this->assign('cat_select', $select);
		$this->assign('form_action', RC_Uri::url('article/admin_articlecat/update'));
		$this->assign_lang();
		
		$this->display('articlecat_info.dwt');
	}

	public function update() {
		$this->admin_priv('article_cat_manage', ecjia::MSGTYPE_JSON);
		
		$cat_name    = trim($_POST['cat_name']);
		$old_catname = trim($_POST['old_catname']);
		$id          = intval($_POST['id']);
		$parent_id   = intval($_POST['parent_id']);
		$show_in_nav = !empty($_POST['show_in_nav']) ? intval($_POST['show_in_nav']) : 0;
		
		if ($cat_name != $old_catname) {
			if ($this->db_article_cat->where( array('cat_name' => $cat_name))->count() > 0) {
				$this->showmessage(sprintf(RC_Lang::lang('catname_exist'), stripslashes($cat_name)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}
		if (!isset($parent_id)) {
			$parent_id = 0;
		}

		$row = $this->db_article_cat->field('cat_type, parent_id')->find(array('cat_id' => $id));
		$cat_type = $row['cat_type'];
		if ($cat_type == 3 || $cat_type ==4) {
			$parent_id = $row['parent_id'];
		}
		
		/* 检查设定的分类的父分类是否合法 */
		$child_cat = article_cat_list($id, 0, false);
		if (!empty($child_cat)) {
			foreach ($child_cat as $child_data) {
				$catid_array[] = $child_data['cat_id'];
			}
		}
		if (in_array($parent_id, $catid_array)) {
			$this->showmessage(sprintf(RC_Lang::lang('parent_id_err'), stripslashes($cat_name)), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		
		if ($cat_type == 1 || $cat_type == 5) {
			if ($parent_id > 0) {
				$p_cat_type = $this->db_article_cat->where(array('cat_id' => $parent_id))->get_field('cat_type');

				if ($p_cat_type == 4) {
					$cat_type = 5;
				} else {
					$cat_type = 1;
				}
			} else {
				$cat_type = 1;
			}
		}
		
		$dat =	$this->db_article_cat->field('cat_name, show_in_nav')->find(array('cat_id' => $id));
		$data  = array(
			'cat_name'	  => $cat_name,
			'cat_desc'	  => trim($_POST['cat_desc']),
			'keywords'	  => trim($_POST['keywords']),
			'parent_id'	  => $parent_id,
			'cat_type'	  => $cat_type,
			'sort_order'  => intval($_POST['sort_order']),
			'show_in_nav' => $show_in_nav,
		);
		$query = $this->db_article_cat->where(array('cat_id' => $id))->update($data);
		if ($query) {
			if ($cat_name != $dat['cat_name']) {
				//如果分类名称发生了改变
				$data  = array( 'name' => $cat_name);
			    $this->db_article_cat->where(array('ctype' => 'a', 'cid' => $id, 'type' => 'middle'))->update($data);
			}
			if ($show_in_nav != $dat['show_in_nav']) {
				if ($show_in_nav == 1) {
					//显示
					$nid = $this->db_nav->field('id')->find(array('ctype' => 'a', 'cid' => $id, 'type' => 'middle'));
					$nid = $nid['id'];
					if(empty($nid)) {
						$vieworder = $this->db_nav->where('type = "middle"')->max('vieworder');
						$vieworder += 2;
						$uri = build_uri('article_cat', array('acid'=> $id), $cat_name);
						//不存在
						$data = array(
							'name' 		=> $cat_name,
							'ctype'	 	=> 'a',
							'cid' 		=> $id,
							'ifshow' 	=> '1',
							'vieworder' => $vieworder,
							'opennew' 	=> '0',
							'url' 		=> $uri,
							'type' 		=> 'middle',
						);
						$this->db_nav->insert($data);
					} else {
						$data = array( 'ifshow' => 1, );
						$this->db_nav->where(array('ctype' => 'a', 'cid' => $id, 'type' => 'middle'))->update($data);
					}
				} else {
					//去除
					$data = array( 'ifshow' => 0, );
					$this->db_nav->where(array('ctype' => 'a', 'cid' => $id, 'type' => 'middle'))->update($data);
				}
			}
			ecjia_admin::admin_log($cat_name, 'edit', 'articlecat');
			$this->showmessage(sprintf(RC_Lang::lang('catedit_succed'), $cat_name), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_Uri::url('article/admin_articlecat/edit', array('id' => $id))));
		} else {
			die($this->db_article_cat->error());
		}
	}
	
	/**
	 * 编辑文章分类的排序
	 */
	public function edit_sort_order() {
		$this->admin_priv('article_cat_manage', ecjia::MSGTYPE_JSON);
		
		$id    = intval($_POST['pk']);
		$order = intval($_POST['value']);
		
		if (!is_numeric($order)) { //!preg_match("/^[0-9]+$/", $order)
			$this->showmessage(RC_Lang::lang('enter_int'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
			if ($this->db_article_cat->where(array('cat_id' => $id))->update(array('sort_order' => $order))) {
				
				$this->showmessage(RC_Lang::lang('catedit_ok'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl' => RC_uri::url('article/admin_articlecat/init')) );
			} else {
				$this->showmessage('编辑失败', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
		}
	}
	
	/**
	 * 删除文章分类
	 */
	public function remove() {
		$this->admin_priv('article_cat_delete', ecjia::MSGTYPE_JSON);
		$id = intval($_GET['id']);

		/* 还有子分类，不能删除 */
		$count = $this->db_article_cat->where(array('parent_id' => $id))->count();
		if ($count > 0) {
			$this->showmessage(RC_Lang::lang('is_fullcat'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
			/* 非空的分类不允许删除 */
			$query = $this->db_article->where(array('cat_id' => $id))->count();
			if ($query > 0) {
				$this->showmessage(sprintf(RC_Lang::lang('not_emptycat')), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			} else {
				$cat_name = $this->db_article_cat->where(array('cat_id' => $id))->get_field('cat_name');
				
				$this->db_article_cat->where(array('cat_id' => $id))->delete();
				$this->db_nav->where(array('ctype' => 'a', 'cid' => $id, 'type' => 'middle'))->delete();
				
				ecjia_admin::admin_log($cat_name, 'remove', 'articlecat');
				$this->showmessage(RC_Lang::lang('drop_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
			}
		}
	}
	
	/**
	 *快捷编辑改分类是否在导航栏上面显示
	 */
	public function toggle_show_in_nav() {
		$this->admin_priv('article_cat_manage', ecjia::MSGTYPE_JSON);
		
		$id  = intval($_POST['id']);
		$val = intval($_POST['val']);
		
		if ($this->cat_update($id, array('show_in_nav' => $val)) != false) {
			if ($val == 1) {
				$nid = $this->db_nav->field('id')->where(array('ctype' => 'a', 'cid' => $id, 'type' => 'middle'))->select();
				if (empty($nid)) {
					//不存在
					$vieworder = $this->db_nav->where(array('type' => 'middle'))->max('vieworder');
					$vieworder += 2;	
					$catnamearr = $this->db_article_cat->where(array('cat_id' => $id))->field('cat_name')->select(); 
					$catname = $catnamearr[0]['cat_name'];
					$uri  = build_uri('article_cat', array('acid'=> $id), $catname);
					$data = array(
						'name'		=> $catname,
						'ctype'		=> 'a',
						'cid'		=> $id,
						'ifshow'	=> '1',
						'vieworder' => $vieworder,
						'opennew'	=> '0',
						'url'		=> $uri,
						'type'		=> 'middle',
					);
					$this->db_nav->insert($data);
				} else {
					$data = array( 'ifshow' => 1, );
					$this->db_nav->where(array('ctype' => 'a','cid' => $id,'type' => 'middle'))->update($data);
				}
			} else {
				//去除
				$data = array( 'ifshow' => 0, );
				$this->db_nav->where(array('ctype' => 'a', 'cid' => $id, 'type' => 'middle'))->update($data);
			}
			$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => $val));
		} else {
			$this->showmessage($this->db_nav->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}

	private function cat_update($cat_id, $args) {
		if (empty($args) || empty($cat_id)) {
			return false;
		}
		return  $this->db_article_cat->where(array('cat_id' => $cat_id))->update($args);
	}
}

// end