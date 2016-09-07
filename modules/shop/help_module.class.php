<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 商店帮助列表
 * @author royalwang
 *
 */
class help_module extends api_front implements api_interface {
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
	$data = RC_Model::model('article/article_viewmodel')->get_shop_help();
	return $data;
}

// end