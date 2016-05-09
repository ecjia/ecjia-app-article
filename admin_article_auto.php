<?php
/**
 * ECJIA 文章自动发布管理
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin_article_auto extends ecjia_admin 
{
	private $db_crons;
	private $db_auto_manage;
	public function __construct() 
	{
		parent::__construct();
		RC_Lang::load('article');
		/* 数据模型加载 */
		//$this->db_crons       = RC_Loader::load_app_model('crons_model', 'cronjob');
		$this->db_auto_manage = RC_Loader::load_app_model('auto_manage_model', 'article');
		
		/* 加载全局 js/css */
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');

		RC_Script::enqueue_script('bootstrap-editable.min', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js'));
		RC_Style::enqueue_style('bootstrap-editable', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'));

		RC_Style::enqueue_style('chosen');
		RC_Style::enqueue_style('uniform-aristo');
		RC_Style::enqueue_style('datepicker', RC_Uri::admin_url('statics/lib/datepicker/datepicker.css'));
		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Script::enqueue_script('article_auto',RC_App::apps_url('statics/js/article_auto.js', __FILE__), array(), false, true);
		RC_Script::enqueue_script('bootstrap-datepicker', RC_Uri::admin_url('statics/lib/datepicker/bootstrap-datepicker.min.js'));

		RC_Loader::load_sys_class('ecjia_page', false);

	}


	public function init() 
	{
		$this->admin_priv('article_auto_manage', ecjia::MSGTYPE_JSON);
		$goodsdb = $this->get_auto_goods();
		$this->assign('full_page', 	   1);
		$this->assign('ur_here',   	   RC_Lang::lang('article_auto'));
		$this->assign('goodsdb',       $goodsdb);
		$this->assign('searcharticle', RC_Uri::url('article/admin_article_auto/init'));
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('文章自动发布')));
		$this->assign_lang();
		$this->display('article_auto.dwt');
	}
	
	public function del() 
	{
		$this->admin_priv('article_auto_delete', ecjia::MSGTYPE_JSON);
		$goods_id = intval($_GET['goods_id']);
		$links[]  = array('text' => RC_Lang::lang('article_auto'), 'href' => RC_Uri::url('article/article_auto/init'));
		$this->db_auto_manage->where(array('item_id' => $goods_id, 'type' => 'article'))->delete();
		$this->showmessage(RC_Lang::lang('edit_ok'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links));
	}
	
	
	public function edit_starttime() 
	{
		$this->admin_priv('article_auto_update', ecjia::MSGTYPE_JSON);
		$val = trim($_POST['val']);
		if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $val) ) {
			$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		$id   = intval($_POST['id']);
		$time = RC_Time::local_strtotime($val);
		if ($id <= 0 || $_POST['val'] == '0000-00-00' || $time <= 0) {
			$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		$data = array(
			'item_id' 	=> $id,
			'type'    	=> 'article',
			'starttime'	=> $time
		);
		$this->db_auto_manage->where( array('starttime' => strval($time)) )->update($data);
		$this->showmessage(RC_Lang::lang('edit_ok'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => stripslashes($val), 'act' => 'article_auto', 'id' => $id));
	}
	
	public function edit_endtime() 
	{
		$this->admin_priv('article_auto_update', ecjia::MSGTYPE_JSON);
		$val = trim($_POST['val']);
		if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $val) ) {
			$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}

		$id   = intval($_POST['id']);
		$time = RC_Time::local_strtotime($val);
		if ($id <= 0 || $_POST['val'] == '0000-00-00' || $time <= 0) {
			$this->showmessage('', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		$data = array(
			'item_id' => $id,
			'type'    => 'article',
			'endtime' => $time
		);
		
		$this->db_auto_manage->where(array('endtime' => strval($time)))->update($data);
		$this->showmessage(RC_Lang::lang('edit_ok'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('content' => stripslashes($val), 'act' => 'article_auto', 'id' => $id));
	}
	
	/**
	 * 批量发布
	 */
	public function batch_start() 
	{
		$this->admin_priv('article_auto_update', ecjia::MSGTYPE_JSON);

		if (!isset($_POST['checkboxes']) || !is_array($_POST['checkboxes'])) {
			$this->showmessage(RC_Lang::lang('no_select_goods'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
			$date = trim($_POST['date']);
			if ($date == '0000-00-00') {
				$date = 0;
			} else {
				$date = RC_Time::local_strtotime($date);
			}
			foreach($_POST['checkboxes'] as $id) {
				$data = array(
						'item_id' 	=> $id,
						'type'    	=> 'article',
						'starttime' => $date
				);
				$this->db_auto_manage->where(array('starttime' => strval($date)))->update($data);
			}
			
			$links[] = array('text' => RC_Lang::lang('back_list'), 'href' => RC_Uri::url('article/article_auto/init'));
			$this->showmessage(RC_Lang::lang('batch_start_succeed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links));
		}
	}
	
	
	/**
	 * 批量取消发布
	 */
	public function batch_end() 
	{
		$this->admin_priv('article_auto_update', ecjia::MSGTYPE_JSON);
		
		if (!isset($_POST['checkboxes']) || !is_array( $_POST['checkboxes'] )) {
			$this->showmessage(RC_Lang::lang('no_select_goods'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else{
			$date = trim($_POST['date']);
			if ($date == '0000-00-00') {
				$date = 0;
			} else {
				$date = RC_Time::local_strtotime($date);
			}
			
			foreach($_POST['checkboxes'] as $id) {
				$data = array(
						'item_id' => $id,
						'type'    => 'article',
						'endtime' => $date
				);
				$this->db_auto_manage->where(array('endtime' => strval($date)))->update($data);
			}
			
			$links[] = array('text' => RC_Lang::lang('back_list'), 'href' => RC_Uri::url('article/admin_article_auto/init'));
			$this->showmessage(RC_Lang::lang('batch_end_succeed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links));
		}
	}
	
	/**
	 * 获取自动文章
	 * @return array
	 */
	private  function get_auto_goods()
	{
		$dbview = RC_Loader::load_app_model('article_viewmodel', 'article');
		/* 加载分页类 */
		RC_Loader::load_sys_class('ecjia_page', false);
		$goods_name = trim($_POST['goods_name']);
		if (!empty($goods_name)) {
			// 			$where  = "a.title LIKE '%$goods_name%'";
			$where['a.title'] = array('like' => "%". mysql_like_quote($goods_name). "%" );
			$filter['goods_name'] = $goods_name;
		}
		$count = $dbview->join(null)->where($where)->count('*');
		/* 文章总数 */
		$page = new ecjia_page($count, 15, 5);
		$filter['record_count'] = $count;
		$goodsdb = array();
		$dbview->view = array(
			'auto_manage' => array(
				'type'  => Component_Model_View::TYPE_LEFT_JOIN,
				'alias' => 'am',
				'field' => 'a.*,am.starttime,am.endtime',
				'on'    => 'a.article_id  = am.item_id AND am.type = "article"',
			),
		);
		$data = $dbview->where($where)->order( array('a.add_time' => 'DESC'))->limit($page->limit())->select();
		if(!empty($data)) {
			foreach ($data as $rt) {
				if (!empty($rt['starttime'])) {
					$rt['starttime'] = RC_Time::local_date('Y-m-d', $rt['starttime']);
				}
				if (!empty($rt['endtime'])) {
					$rt['endtime'] = RC_Time::local_date('Y-m-d', $rt['endtime']);
				}
				$rt['goods_id']   = $rt['article_id'];
				$rt['goods_name'] = $rt['title'];
				$goodsdb[] = $rt;
			}
		}
		$arr = array('goodsdb' => $goodsdb, 'page' => $page->show(5), 'desc' => $page->page_desc());
		return $arr;
	}

}

// end