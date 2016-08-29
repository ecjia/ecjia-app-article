<?php
defined('IN_ECJIA') or exit('No permission resources.');

class article_goods_article_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'goods_article';
		$this->table_alias_name = 'ga';
		
		$this->view =array(
			'goods' => array(
				'type'  => Component_Model_View::TYPE_LEFT_JOIN,
				'alias' => 'g',
				'on'    => 'g.goods_id  = ga.goods_id',
			),
		);
		parent::__construct();
	}
	
	/*获取文章关联商品*/
	public function get_article_goods($article_id) {
		return $this->join('goods')->field('g.goods_id, g.goods_name')->where(array('ga.article_id' => $article_id))->select();
	}
}

// end