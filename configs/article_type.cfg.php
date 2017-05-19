<?php 

/**
 * 文章类型
 */

defined('IN_ECJIA') or exit('No permission resources.');
	return array(
			array(
				'article_type' 		 => 'article',
				'article_type_name'  => '普通文章',
		    ),
			array(
				'article_type' 		 => 'redirect',
				'article_type_name'  => '跳转链接',
			),
			array(
				'article_type' 		 => 'download',
				'article_type_name'  => '附件点击标题直接下载',
			),
			array(
				'article_type' 		 => 'related',
				'article_type_name'  => '附件在文章内容底部相关下载',
			)
)
;
//end