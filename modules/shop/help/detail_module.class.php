<?php
defined('IN_ECJIA') or exit('No permission resources.');

class detail_module extends api_front implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {	
		
		$this->authSession();	
    	$id = $this->requestData('article_id', 0);
   		if ($id <= 0) {
			return new ecjia_error('invalid_parameter', RC_Lang::get('system::system.invalid_parameter'));
		}
		
		if (!$article = get_article_info($id)) {
			return new ecjia_error('does not exist', '不存在的信息');
		}
		
		$base = sprintf('<base href="%s/" />', dirname(SITE_URL));
		$html['data'] = '<!DOCTYPE html><html><head><title>'.$article['title'].'</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="viewport" content="initial-scale=1.0"><meta name="viewport" content="initial-scale = 1.0 , minimum-scale = 1.0 , maximum-scale = 1.0" /><style>img {width: auto\9;height: auto;vertical-align: middle;border: 0;-ms-interpolation-mode: bicubic;max-width: 100%; }html { font-size:100%; }p{word-wrap : break-word ;word-break:break-all;} </style>'.$base.'</head><body>'.$article['content'].'</body></html>';
		
		return $html;
	}
}


function get_article_info($article_id) {
	/* 获得文章的信息 */
    $row = RC_Model::model('article/article_model')->field('article_id as id, title, content')->find(array('is_open' => 1, 'article_id' => $article_id));
    return $row;
}

// end