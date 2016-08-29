<?php
defined('IN_ECJIA') or exit('No permission resources.');

class article_cat_model extends Component_Model_Model {
	public $table_name = '';
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'article_cat';
		parent::__construct();
	}
	
	/**
	 * 获得网店帮助文章分类
	 * @return array
	 */
	public function shophelp_select($option) {
		return $this->field($option['field'])->where($option['where'])->order($option['order'])->select();
	}
	
	/*分类更新*/
	public function cat_update($cat_id, $args) {
	    if (empty($args) || empty($cat_id)) {
	        return false;
	    }
	    return $this->where(array('cat_id' => $cat_id))->update($args);
	}
	
	/* 判断重复 */
	public function article_cat_count($where) {
	    return $this->where($where)->count();
	}
	
	/* 文章分类管理 */
	public function article_cat_manage($parameter, $where='') {
	    if (empty($where)) {
	        return $this->insert($parameter);
	    } else {
	        return $this->where($where)->update($parameter);
	    }
	}
	
	/* 查询分类信息 */
	public function article_cat_info($id) {
	    return $this->find(array('cat_id' => $id));
	}
	
	/* 删除文章分类 */
	public function article_cat_delete($id) {
	    return $this->where(array('cat_id' => $id))->delete();
	}
	
	/* 查询字段信息 */
	public function article_cat_field($where, $field) {
	    return $this->where($where)->get_field($field);
	}
}

// end