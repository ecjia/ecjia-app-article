<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 后台文章菜单API
 * @author royalwang
 *
 */
class article_admin_menu_api extends Component_Event_Api
{

    public function call(&$options)
    {
        $menus = ecjia_admin::make_admin_menu('07_content', __('文章管理'), '', 5);
        
        $submenus = array(
            ecjia_admin::make_admin_menu('01_article_list', __('文章列表'), RC_Uri::url('article/admin/init'), 1)->add_purview('article_manage'),
        	ecjia_admin::make_admin_menu('02_article_add', __('添加文章'), RC_Uri::url('article/admin/add'), 2)->add_purview('article_manage'),
            ecjia_admin::make_admin_menu('03_articlecat_list', __('文章分类'), RC_Uri::url('article/admin_articlecat/init'), 3)->add_purview('article_cat_manage'),
        	
//             new admin_menu('article_auto', '文章自动发布', RC_Uri::url('article/admin_article_auto/init'))->add_purview('article_auto'),
            ecjia_admin::make_admin_menu('divider', '', '', 4)->add_purview(array('shophelp_manage', 'shopinfo_manage')),
            ecjia_admin::make_admin_menu('article_help', __('网店帮助'), RC_Uri::url('article/admin_shophelp/init'), 5)->add_purview('shophelp_manage'),
            ecjia_admin::make_admin_menu('article_info', __('网店信息'), RC_Uri::url('article/admin_shopinfo/init'), 6)->add_purview('shopinfo_manage')
        );
        
        $menus->add_submenu($submenus);
        
        return $menus;
    }
}

// end