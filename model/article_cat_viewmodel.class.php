<?php
defined('IN_ECJIA') or exit('No permission resources.');

class article_cat_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'article_cat';
		$this->table_alias_name = 'c';

		$this->view = array(
			'article_cat' => array(
				'type'  =>	Component_Model_View::TYPE_LEFT_JOIN,
				'alias' => 	's',
				'on'    => 	's.parent_id = c.cat_id',
			),
			'article' => array(
				'type'  =>	Component_Model_View::TYPE_LEFT_JOIN,
				'alias' => 	'a',
				'on'   => 	'a.cat_id = c.cat_id'
			)
		);
		parent::__construct();
	}
	
// 	public function article_cat_select($option) {
// 		return $this->join(array('article_cat', 'article'))->field($option['field'])->where($option['where'])->group($option['group'])->order($option['order'])->select();
// 	}
}

// end