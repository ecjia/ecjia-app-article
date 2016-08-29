<?php
defined('IN_ECJIA') or exit('No permission resources.');

class article_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'article';
		$this->table_alias_name = 'a';

		$this->view = array(
			'article_cat' => array(
				'type'  => Component_Model_View::TYPE_LEFT_JOIN,
				'alias' => 'ac',
				'on'    => 'ac.cat_id = a.cat_id',
			),
			'auto_manage' => array(
				'type'  => Component_Model_View::TYPE_LEFT_JOIN,
				'alias' => 'am',
				'on'    => 'a.article_id = am.item_id AND am.type = "article"',
			),
		);
		parent::__construct();
	}
	
	public function article_count($where = array(), $table = null, $field = '*') {
		return $this->join($table)->where($where)->count($field);
	}
	
	public function article_select($option) {
		return $this->join($option['table'])->field($option['field'])->where($option['where'])->order($option['order'])->limit($option['limit'])->select();
	}
}

// end