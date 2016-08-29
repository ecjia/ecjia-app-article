<?php
defined('IN_ECJIA') or exit('No permission resources.');

class article_model extends Component_Model_Model {
	public $table_name = '';
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'article';
		parent::__construct();
	}
	
	/* 判断重复 */
	public function article_count($where = array()) {
	    return $this->where($where)->count();
	}
	
	/* 文章管理 */
	public function article_manage($parameter) {
	    if (!isset($parameter['article_id'])) {
	        $id = $this->insert($parameter);
	    } else {
	        $where = array('article_id' => $parameter['article_id']);
	
	        $this->where($where)->update($parameter);
	        $id = $parameter['article_id'];
	    }
	    return $id;
	}
	
	/* 文章详情 */
	public function article_find($id) {
	    return $this->where(array('article_id' => $id))->find();
	}
	
	/* 删除文章 */
	public function article_delete($id) {
	    return $this->where(array('article_id' => $id))->delete();
	}
	
	/* 查询字段信息 */
	public function article_field($where, $field, $bool=false) {
	    return $this->where($where)->get_field($field, $bool);
	}
	
	public function article_batch($where, $type, $data=array()) {
	    if ($type == 'select') {
	        return $this->in($where)->select();
	    } elseif ($type == 'delete') {
	        return $this->in($where)->delete();
	    } elseif ($type == 'update') {
	        return $this->in($where)->update($data);
	    }
	}
	
	public function article_select($where, $field, $in=false) {
	    if ($in) {
	        return $this->field($field)->in($where)->select();
	    }
	    return $this->field($field)->where($where)->select();
	}
	
	/**
	 * 网店帮助分类下的文章
	 */
	public function shophelp_article_select($option) {
		return $this->field($option['field'])->where($option['where'])->order($option['order'])->limit($option['limit'])->select();
	}
	
	/**
	 * 获取网店信息文章数据
	 */
	public function shopinfo_article_select($option) {
		return $this->field($option['field'])->where($option['where'])->order($option['order'])->select();
	}
}

// end