<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 后台权限API
 * @author royalwang
 *
 */
class article_admin_purview_api extends Component_Event_Api {
    
    public function call(&$options) {
        $purviews = array(
        	array('action_name' => __('文章添加/更新'), 'action_code' => 'article_manage', 'relevance'   => ''),
        	array('action_name' => __('文章删除'), 'action_code' => 'article_delete', 'relevance'   => ''),
        	
        	array('action_name' => __('分类添加/更新'), 'action_code' => 'article_cat_manage', 'relevance'   => ''),
        	array('action_name' => __('分类删除'), 'action_code' => 'article_cat_delete', 'relevance'   => ''),
        		
            array('action_name' => __('网店信息管理'), 'action_code' => 'shopinfo_manage', 'relevance'   => ''),
        		
            array('action_name' => __('网店帮助管理'), 'action_code' => 'shophelp_manage', 'relevance'   => ''),
        		
//          array('action_name' => __('文章自动发布'), 'action_code' => 'article_auto_manage', 'relevance'   => ''),
//         	array('action_name' => __('文章自动更新'), 'action_code' => 'article_auto_update', 'relevance'   => ''),
//         	array('action_name' => __('文章自动删除'), 'action_code' => 'article_auto_delete', 'relevance'   => ''),
        );
        
        return $purviews;
    }
}

// end