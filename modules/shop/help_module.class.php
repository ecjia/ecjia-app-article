<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 商店帮助列表
 * @author royalwang
 *
 */
class detail_module extends api_front implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {
    	
    	$this->authSession();
		$data = get_shop_help2();
		$out = array();
		foreach ($data as $value) {
			$value['article'] && $value['article'] = array_values($value['article']);
			$out[] = $value;
		}
		return $out;
	}
}

function get_shop_help2() {
	$dbview = RC_Loader::load_app_model('article_viewmodel', 'article');
	$res = $dbview->join('article_cat')
				->where(array('ac.cat_type' => 5, 'ac.parent_id' => 3, 'a.is_open' => 1, 'a.open_type' => 0, 'cat_name is not null'))
				->order(array('ac.sort_order' => 'ASC', 'a.article_id' => 'ASC'))
				->select();
	
    $arr = array();
	if (!empty($res)) {
	    foreach ($res AS $key => $row) {
		    if (!empty($row['link']) && $row['link'] != 'http://' && $row['link'] != 'https://') {
				continue;
			}
			if (empty($row['content']) || empty($row['cat_name'])) {
				continue;
			}

	        $arr[$row['cat_id']]['name']                     	 = $row['cat_name'];
	        $arr[$row['cat_id']]['article'][$key]['id']  		 = $row['article_id'];
	        $arr[$row['cat_id']]['article'][$key]['title']       = $row['title'];
	        $arr[$row['cat_id']]['article'][$key]['short_title'] = ecjia::config('article_title_length') > 0 ?
	        RC_String::sub_str($row['title'], ecjia::config('article_title_length')) : $row['title'];
	    }
	}
	return $arr;
}

// end