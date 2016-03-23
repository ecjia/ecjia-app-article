<?php
defined('IN_ECJIA') or exit('No permission resources.');

class sys_article_cat_viewmodel extends Component_Model_View {
	public $table_name = '';
	public  $view =array();
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'article_cat';
		$this->table_alias_name	= 'a';
		
		//定义视图选项
		$this->view =array(
				'article_cat' => array(
						'type'  =>	Component_Model_View::TYPE_LEFT_JOIN,
						'field' => 	'article_cat.*, COUNT(s.cat_id)|has_children',
						'on'   	=> 	'ecs_article_cat.parent_id = ecs_article_cat.cat_id'
				),
				'article' => array(
						'type'  =>	Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 	'a',
						'on'   	=> 	'ecs_article.cat_id = ecs_article_cat.cat_id'
				)
		);
		
		
		parent::__construct();
	}



}

// end