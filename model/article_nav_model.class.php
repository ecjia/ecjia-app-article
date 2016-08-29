<?php
defined('IN_ECJIA') or exit('No permission resources.');

class article_nav_model extends Component_Model_Model {
	public $table_name = '';
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'nav';
		parent::__construct();
	}

	public function nav_max($where, $field) {
		return $this->where($where)->max($field);
	}

	public function nav_manage($data, $where=array()) {
		if (!empty($where)) {
			return $this->where($where)->update($data);
		}
		return $this->insert($data);
	}
	
	public function nav_find($field='*', $where) {
		return $this->field($field)->where($where)->find();
	}
	
	public function nav_delete($where) {
		return $this->where($where)->delete();
	}
	
	public function nav_select($field='*', $where) {
		return $this->field($field)->where($where)->select();
	}
}

// end