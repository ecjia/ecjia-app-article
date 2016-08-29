<?php
defined('IN_ECJIA') or exit('No permission resources.');

class article_comment_model extends Component_Model_Model {
	public $table_name = '';
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'comment';
		parent::__construct();
	}
	
	public function comment_remove($where) {
		return $this->where($where)->delete();
	}
}

// end