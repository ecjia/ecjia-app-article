<?php
defined('IN_ECJIA') or exit('No permission resources.');

class article_term_meta_model extends Component_Model_Model {
	public $table_name = '';
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'term_meta';
		parent::__construct();
	}
	
	public function term_meta_manage($data, $where=array()) {
		if (!empty($where)) {
			return $this->where($where)->update($data);
		}
		return $this->insert($data);
	}
	
	public function term_meta_remove($where) {
		return $this->where($where)->delete();
	}
	
	public function term_meta_select($field='*', $where=array(), $group='') {
		if (!empty($group)) {
			return $this->field($field)->where($where)->group($group)->select();
		}
		return $this->field($field)->where($where)->select();
	}
}

// end