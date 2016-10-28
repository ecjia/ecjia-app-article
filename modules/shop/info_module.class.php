<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 商店帮助列表
 * @author royalwang
 *
 */
class info_module extends api_front implements api_interface
{
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request)
    {	
    	$this->authSession();
    	$article_db = RC_DB::table('article');
    	$article_db->where('cat_id' , '=', 0);
    	$article_db->where('content' , '<>', '');
    	$article_db->where('title' , '<>', '');
    	
    	$res = $article_db->get();
    	
    	$list = array();
    	if (!empty($res)) {
    		foreach ($res as $row) {
    			$list[] =  array(
    				'id'	=> $row['article_id'],
    				'image' => !empty($row['file_url']) ? RC_Upload::upload_url($row['file_url']) : '',
    				'title'	=> $row['title'],
    			);
    		}
    	}
    	
    	return $list;
	}
}


// end