<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 文章列表接口
 * @author will.chen
 *
 */
class article_article_list_api extends Component_Event_Api {
	
    /**
     * @param  array $options	条件参数
     * @return array
     */
	public function call(&$options) {
		if (!is_array($options)) {
			return new ecjia_error('invalid_parameter', '参数无效');
		}
		return $this->articleslist($options);
	}
	
	
	/**
	 * 取得文章信息
	 * @param   array $options	条件参数
	 * @return  array   文章列表
	 */
	
	private function articleslist($options) {
		$dbview = RC_Loader::load_app_model('article_viewmodel', 'article');
		
		$filter = array();
		$filter['keywords']	  = empty($options['keywords']) ? '' : trim($options['keywords']);
		$filter['cat_id']     = empty($options['cat_id']) ? 0 : intval($options['cat_id']);
		$filter['sort_by']    = empty($options['sort_by']) ? 'a.article_id' : trim($options['sort_by']);
		$filter['sort_order'] = empty($options['sort_order']) ? 'DESC' : trim($options['sort_order']);
		$filter['is_open']	  = empty($options['is_open']) ? 1 : intval($options['is_open']);
		$filter['page_size']  = empty($options['page_size']) ? 15 : intval($options['page_size']);
		$filter['current_page'] = empty($options['current_page']) ? 1 : intval($options['current_page']);
		//不获取系统帮助文章的过滤

		$where = array();
		if (!empty($filter['keywords'])) {
			$where['a.title'] = array('like' => "%".$filter['keywords']."%");

		}
		if ($filter['cat_id'] && ($filter['cat_id'] > 0)) {
			$where[] = "a." . get_article_children($filter['cat_id']);
		}
		/* 是否显示 will.chen*/
		$where['a.is_open'] = $filter['is_open'];
		
		/* 文章总数 */
		$filter['record_count'] = '';
		$count = $dbview->join('article_cat')->where($where)->count('article_id');
		$page = new ecjia_page($count, $filter['page_size'], 5, '', $filter['current_page']);
		$filter['record_count'] = $count;
		$dbview->view = array(
				'article_cat' => array(
						'type'  => Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 'ac',
						'field' => 'a.* , ac.cat_name,ac.cat_type',
						'on'    => 'ac.cat_id  = a.cat_id',
				),
		);
		/* 判断是否需要分页 will.chen*/
		$limit = $options['is_page'] == 1 ? $page->limit() : null;
		
		$result = $dbview->where($where)->order(array($filter['sort_by'] => $filter['sort_order']))->limit($limit)->select();
		
		$arr = array();
		if(!empty($result)) {
			foreach ($result as $rows) {
				$rows['date'] = RC_Time::local_date(ecjia::config('time_format'), $rows['add_time']);
				$arr[] = $rows;
			}
		}
		return array('arr' => $arr, 'page' => $page->show(15), 'desc' => $page->page_desc());
	}
	
}


// end