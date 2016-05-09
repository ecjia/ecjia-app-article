<?php
/**
* 添加管理员记录日志操作对象
*/
defined('IN_ECJIA') or exit('No permission resources.');

function assign_adminlog_contents() {
	ecjia_admin_log::instance()->add_action('batch_setup', '批量设置');
}

//end