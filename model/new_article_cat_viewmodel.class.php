<?php
defined('IN_ECJIA') or exit('No permission resources.');

class new_article_cat_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'article_cat';
		$this->table_alias_name = 'ac';

		$this->view = array(
				'article_cat' => array(
						'type'  =>	Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 	'acs',
						'on'    => 	'acs.parent_id = ac.cat_id',
				),
				'article' => array(
						'type'  =>	Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 	'a',
						'on'   => 	'a.cat_id = ac.cat_id'
				)
		);
		
		parent::__construct();
	}

}

// end