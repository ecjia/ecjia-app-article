<?php
defined('IN_ECJIA') or exit('No permission resources.');

class sys_article_viewmodel extends Component_Model_View {
	public $table_name = '';
	public  $view =array();
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'article';
		$this->table_alias_name	= 'a';
		
		//定义视图选项
		$this->view =array(
				'article_cat' => array(
						'type'  =>	Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 	'c',
						'field' => 	'c.cat_id, c.cat_name, c.sort_order, a.article_id, a.title, a.file_url,a.content , a.open_type',
						'on'   	=> 	'a.cat_id = c.cat_id'
				)
		);
		
		
		parent::__construct();
	}



}

// end