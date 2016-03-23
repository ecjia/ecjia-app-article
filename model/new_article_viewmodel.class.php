<?php
defined('IN_ECJIA') or exit('No permission resources.');

class new_article_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'article';
		$this->table_alias_name = 'a';

		$this->view = array(
				'article_cat' => array(
						'type'  =>	Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 	'ac',
						'on'    => 	'ac.cat_id  = a.cat_id',
				),
				'comment' => array(
						'type'  =>	Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 	'c',
						'on'    => 	'c.id_value = a.article_id AND comment_type = 1',
				)
		);
		
		parent::__construct();
	}

}

// end