<?php
/**
 * 文章及文章分类相关函数库
 */
defined ( 'IN_ECJIA' ) or exit ( 'No permission resources.' );

/**
 * 获得指定分类下的子分类的数组
 *
 * @access public
 * @param int $cat_id
 *        	分类的ID
 * @param int $selected
 *        	当前选中分类的ID
 * @param boolean $re_type
 *        	返回的类型: 值为真时返回下拉列表,否则返回数组
 * @param int $level
 *        	限定返回的级数。为0时返回所有级数
 * @return mix
 */
// function article_cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0) {
// 	$res = RC_Model::model('article/article_cat_viewmodel')->join(array('article_cat','article'))->field('c.*, COUNT(s.cat_id) AS has_children, COUNT(a.article_id) AS article_num')->where( 'c.parent_id not in (1,2,3) and c.cat_id<>1' )->group('c.cat_id')->order (array('parent_id' => 'asc', 'sort_order' => 'ASC'))->select();
// 	if (empty($res) == true) {
// 		return $re_type ? '' : array ();
// 	}
// 	$options = article_cat_options($cat_id, $res); // 获得指定分类下的子分类的数组

// 	/* 截取到指定的缩减级别 */
// 	if ($level > 0) {
// 		if ($cat_id == 0) {
// 			$end_level = $level;
// 		} else {
// 			$first_item = reset ( $options ); // 获取第一个元素
// 			$end_level  = $first_item['level'] + $level;
// 		}

// 		/* 保留level小于end_level的部分 */
// 		foreach ($options as $key => $val ) {
// 			if ($val['level'] >= $end_level) {
// 				unset ( $options[$key] );
// 			}
// 		}
// 	}
// 	$pre_key = 0;
// 	if (! empty ( $options )) {
// 		foreach ( $options as $key => $value ) {
// 			$options[$key]['has_children'] = 1;
// 			if ($pre_key > 0) {
// 				if ($options[$pre_key]['cat_id'] == $options[$key]['parent_id']) {
// 					$options[$pre_key]['has_children'] = 1;
// 				}
// 			}
// 			$pre_key = $key;
// 		}
// 	}
// 	if ($re_type == true) {
// 		$select = '';
// 		foreach ( $options as $var ) {
// 			$select .= '<option value="' . $var['cat_id'] . '" ';
// 			$select .= ' cat_type="' . $var['cat_type'] . '" ';
// 			$select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
// 			$select .= '>';
// 			if ($var['level'] > 0) {
// 				$select .= str_repeat ( '&nbsp;', $var['level'] * 4 );
// 			}
// 			$select .= htmlspecialchars ( addslashes ( $var['cat_name'] ) ) . '</option>';
// 		}

// 		return $select;
// 	} else {
// 		if (!empty($options)) {
// 			foreach($options as $key => $value) {
// 				$options[$key]['url'] = build_uri('article_cat', array ('acid' => $value['cat_id']), $value['cat_name'] );
// 			}
// 		}
// 		return $options;
// 	}
// }

/**
 * 获得指定文章分类下所有底层分类的ID
 *
 * @access public
 * @param integer $cat 指定的分类ID
 * @return void
 */
// function get_article_children($cat = 0) {
// 	RC_Loader::load_app_func('common', 'goods');
// 	return db_create_in( array_unique( array_merge( array( $cat ), array_keys(RC_Model::model('article/article_cat_viewmodel')->article_cat_list($cat, 0, false) )) ), 'cat_id' );
// }

/**
 * 过滤和排序所有文章分类，返回一个带有缩进级别的数组
 *
 * @access private
 * @param int $cat_id
 *        	上级分类ID
 * @param array $arr
 *        	含有所有分类的数组
 * @param int $level
 *        	级别
 * @return void
 */
// function article_cat_options($spec_cat_id, $arr) {
// 	static $cat_options = array ();

// 	if (isset ( $cat_options [$spec_cat_id] )) {
// 		return $cat_options [$spec_cat_id];
// 	}
// 	if (!isset ( $cat_options [0] )) {
// 		$level = $last_cat_id = 0;
// 		$options = $cat_id_array = $level_array = array ();
// 		while ( ! empty ( $arr ) ) {
// 			foreach ( $arr as $key => $value ) {
// 				$cat_id = $value ['cat_id'];
// 				if ($level == 0 && $last_cat_id == 0) {
// 					if ($value ['parent_id'] > 0) {
// 						break;
// 					}
						
// 					$options [$cat_id] = $value;
// 					$options [$cat_id] ['level'] = $level;
// 					$options [$cat_id] ['id'] = $cat_id;
// 					$options [$cat_id] ['name'] = $value ['cat_name'];
// 					unset ( $arr [$key] );
						
// 					if ($value ['has_children'] == 0) {
// 						continue;
// 					}
// 					$last_cat_id = $cat_id;
// 					$cat_id_array = array (
// 							$cat_id
// 					);
// 					$level_array [$last_cat_id] = ++ $level;
// 					continue;
// 				}

// 				if ($value ['parent_id'] == $last_cat_id) {
// 					$options [$cat_id] = $value;
// 					$options [$cat_id] ['level'] = $level;
// 					$options [$cat_id] ['id'] = $cat_id;
// 					$options [$cat_id] ['name'] = $value ['cat_name'];
// 					unset ( $arr [$key] );
						
// 					if ($value ['has_children'] > 0) {
// 						if (end ( $cat_id_array ) != $last_cat_id) {
// 							$cat_id_array [] = $last_cat_id;
// 						}
// 						$last_cat_id = $cat_id;
// 						$cat_id_array [] = $cat_id;
// 						$level_array [$last_cat_id] = ++ $level;
// 					}
// 				} elseif ($value ['parent_id'] > $last_cat_id) {
// 					break;
// 				}
// 			}
				
// 			$count = count ( $cat_id_array );
// 			if ($count > 1) {
// 				$last_cat_id = array_pop ( $cat_id_array );
// 			} elseif ($count == 1) {
// 				if ($last_cat_id != end ( $cat_id_array )) {
// 					$last_cat_id = end ( $cat_id_array );
// 				} else {
// 					$level = 0;
// 					$last_cat_id = 0;
// 					$cat_id_array = array ();
// 					continue;
// 				}
// 			}
				
// 			if ($last_cat_id && isset ( $level_array [$last_cat_id] )) {
// 				$level = $level_array [$last_cat_id];
// 			} else {
// 				$level = 0;
// 			}
// 		}
// 		$cat_options [0] = $options;
// 	} else {
// 		$options = $cat_options [0];
// 	}

// 	if (! $spec_cat_id) {
// 		return $options;
// 	} else {
// 		if (empty ( $options [$spec_cat_id] )) {
// 			return array ();
// 		}

// 		$spec_cat_id_level = $options [$spec_cat_id] ['level'];

// 		foreach ( $options as $key => $value ) {
// 			if ($key != $spec_cat_id) {
// 				unset ( $options [$key] );
// 			} else {
// 				break;
// 			}
// 		}

// 		$spec_cat_id_array = array ();
// 		foreach ( $options as $key => $value ) {
// 			if (($spec_cat_id_level == $value ['level'] && $value ['cat_id'] != $spec_cat_id) || ($spec_cat_id_level > $value ['level'])) {
// 				break;
// 			} else {
// 				$spec_cat_id_array [$key] = $value;
// 			}
// 		}
// 		$cat_options [$spec_cat_id] = $spec_cat_id_array;
// 		return $spec_cat_id_array;
// 	}
// }

/**未调用方法汇总**----start**/

/**
 * 删除关联商品
 *
 * @param
 *        	$goods_id
 * @param
 *        	$article_id
 */
// function drop_link_goods($goods_id, $article_id) {
// 	$db = RC_Loader::load_app_model('goods_article_model', 'goods');
// 	$db->where(array('goods_id' => $goods_id, 'article_id' => $article_id))->delete();
// }

/**
 * 获得指定的文章的详细信息--原global.func
 *  
 * @access  private
 * @param   integer     $article_id
 * @return  array
 */
function get_article_info($article_id) {
	$db = RC_Model::model('article/article_viewmodel');
	$db->view = array(
		'comment' => array(
			'type'  => Component_Model_View::TYPE_LEFT_JOIN,
			'alias' => 'r',
			'field' => 'a.*, IFNULL(AVG(r.comment_rank), 0) AS comment_rank',
			'on'    => 'r.id_value = a.article_id AND comment_type = 1',
		),
	);
	$row = $db->group('a.article_id')->find(array('a.is_open' => 1, 'a.article_id' => $article_id));
	if ($row !== false) {
		/* 用户评论级别取整  */
		$row['comment_rank'] = ceil($row['comment_rank']);
		/* 修正添加时间显示  */
		$row['add_time']     = RC_Time::local_date(ecjia::config('date_format'), $row['add_time']);

		/* 作者信息如果为空，则用网站名称替换 */
		if (empty($row['author']) || $row['author'] == '_SHOPHELP') {
			$row['author'] = ecjia::config('shop_name');
		}
	}
	return $row;
}

/**
 * 获得文章分类下的文章列表
 *
 * @access public
 * @param integer $cat_id
 * @param integer $page
 * @param integer $size
 *
 * @return array
 */
// function get_cat_articles($cat_id, $page = 1, $size = 20, $requirement = '') {
// 	$db_article = RC_Loader::load_app_model ( 'article_model', 'article');

// 	// 取出所有非0的文章
// 	if ($cat_id == '-1') {
// 		$cat_str = 'cat_id > 0';
// 	} else {
// 		$cat_str = get_article_children ( $cat_id );
// 	}
// 	$where = '';
// 	// 增加搜索条件，如果有搜索内容就进行搜索
// 	if ($requirement != '') {
// 		$where = "is_open = 1 AND title like '%" . $requirement . "%'";
// 	} else {
// 		$where = "is_open = 1 AND " . $cat_str;
// 	}
// 	$limit = ($page - 1) * $size . " , " . $size;
// 	$order = "`article_type`,`article_id` DESC";

// 	$res = $db_article->field( 'article_id, title, author, add_time, file_url, open_type' )->where( $where )->order( $order )->limit( $limit )->select ();
// 	$arr = array ();
// 	if ($res) {
// 		foreach( $res as $row ) {
// 			$article_id = $row['article_id'];
// 			$arr[$article_id]['id'] 		= $article_id;
// 			$arr[$article_id]['title']	    = $row['title'];
// 			$arr[$article_id]['short_title']= ecjia::config('article_title_length') > 0 ? RC_String::sub_str($row ['title'], ecjia::config('article_title_length')) : $row ['title'];
// 			$arr[$article_id]['author'] 	= empty ( $row ['author'] ) || $row ['author'] == '_SHOPHELP' ? ecjia::config ( 'shop_name' ) : $row ['author'];
// 			$arr[$article_id]['url'] 		= $row['open_type'] != 1 ? build_uri( 'article', array('aid' => $article_id), $row ['title']) : trim($row ['file_url']);
// 			$arr[$article_id]['add_time']   = date( ecjia::config( 'date_format' ), $row['add_time'] );
// 		}
// 	}
// 	return $arr;
// }

/**
 * 获得指定分类下的文章总数
 *
 * @param integer $cat_id
 *
 * @return integer
 */
// function get_article_count($cat_id, $requirement = '') {
// 	$db_article = RC_Loader::load_app_model( 'article_model', 'article');
// 	if ($requirement != '') {
// 		$count = $db_article->where ( get_article_children ( $cat_id ) . ' AND  title like \'%' . $requirement . '%\'  AND is_open = 1' )->count ();
// 	} else {
// 		$count = $db_article->where ( get_article_children ( $cat_id ) . ' AND is_open = 1' )->count ();
// 	}
// 	return $count;
// }

/**
 * 获得商品列表
 *
 * @access public
 * @param
 *            s integer $isdelete
 * @param
 *            s integer $real_goods
 * @param
 *            s integer $conditions
 * @return array
 */
// function goods_list($is_delete, $real_goods = 1, $conditions = '') {
// 	$db = RC_Loader::load_app_model('goods_auto_viewmodel', 'goods');
// 	/* 过滤条件 */
// 	$param_str = '-' . $is_delete . '-' . $real_goods;
// 	$day = getdate();
// 	$today = RC_Time::local_mktime(23, 59, 59, $day ['mon'], $day ['mday'], $day ['year']);

// 	$filter ['cat_id'] 			= empty ($_REQUEST ['cat_id']) 			? 0 	: intval($_REQUEST ['cat_id']);
// 	$filter ['intro_type'] 		= empty ($_REQUEST ['intro_type']) 		? '' 	: trim($_REQUEST ['intro_type']);
// 	$filter ['is_promote'] 		= empty ($_REQUEST ['is_promote']) 		? 0 	: intval($_REQUEST ['is_promote']);
// 	$filter ['stock_warning'] 	= empty ($_REQUEST ['stock_warning']) 	? 0 	: intval($_REQUEST ['stock_warning']);
// 	$filter ['brand_id'] 		= empty ($_REQUEST ['brand_id']) 		? 0 	: intval($_REQUEST ['brand_id']);
// 	$filter ['keyword'] 		= empty ($_REQUEST ['keyword']) 		? '' 	: trim($_REQUEST ['keyword']);
// 	$filter ['suppliers_id'] 	= isset ($_REQUEST ['suppliers_id']) 	? (empty ($_REQUEST ['suppliers_id']) ? '' : trim($_REQUEST ['suppliers_id'])) : '';
// 	$filter ['is_on_sale'] 		= !empty($_REQUEST ['is_on_sale']) 		? ($_REQUEST ['is_on_sale'] == 1 ? 1 : 2) : 0;

// 	$filter ['sort_by'] 		= empty ($_REQUEST ['sort_by']) 		? 'goods_id' 	: trim($_REQUEST ['sort_by']);
// 	$filter ['sort_order'] 		= empty ($_REQUEST ['sort_order']) 		? 'DESC' 		: trim($_REQUEST ['sort_order']);
// 	$filter ['extension_code'] 	= empty ($_REQUEST ['extension_code']) 	? '' 			: trim($_REQUEST ['extension_code']);
// 	$filter ['is_delete'] 		= $is_delete;
// 	$filter ['real_goods'] 		= $real_goods;

// 	$where = $filter ['cat_id'] > 0 ? " AND " . get_children($filter ['cat_id']) : '';

// 	/* 推荐类型 */
// 	switch ($filter ['intro_type']) {
// 		case 'is_best' :
// 			$where .= " AND is_best=1";
// 			break;
// 		case 'is_hot' :
// 			$where .= ' AND is_hot=1';
// 			break;
// 		case 'is_new' :
// 			$where .= ' AND is_new=1';
// 			break;
// 		case 'is_promote' :
// 			$where .= " AND is_promote = 1 AND promote_price > 0 AND promote_start_date <= '$today' AND promote_end_date >= '$today'";
// 			break;
// 		case 'all_type' :
// 			$where .= " AND (is_best=1 OR is_hot=1 OR is_new=1 OR (is_promote = 1 AND promote_price > 0 AND promote_start_date <= '" . $today . "' AND promote_end_date >= '" . $today . "'))";
// 	}

// 	/* 库存警告 */
// 	if ($filter ['stock_warning']) {
// 		$where .= ' AND goods_number <= warn_number ';
// 	}

// 	/* 品牌 */
// 	if ($filter ['brand_id']) {
// 		$where .= " AND brand_id='$filter[brand_id]'";
// 	}

// 	/* 扩展 */
// 	if ($filter ['extension_code']) {
// 		$where .= " AND extension_code='$filter[extension_code]'";
// 	}

// 	/* 关键字 */
// 	if (!empty ($filter ['keyword'])) {
// 		$where .= " AND (goods_sn LIKE '%" . mysql_like_quote($filter ['keyword']) . "%' OR goods_name LIKE '%" . mysql_like_quote($filter ['keyword']) . "%')";
// 	}

// 	if ($real_goods > -1) {
// 		$where .= " AND is_real='$real_goods'";
// 	}

// 	/* 是否上架 */
// 	if ($filter ['is_on_sale'] != 0) {
// 		$filter ['is_on_sale'] == 2 && $filter ['is_on_sale'] = 0;
// 		$where .= " AND (is_on_sale = '" . $filter ['is_on_sale'] . "')";
// 	}

// 	/* 供货商 */
// 	if (!empty ($filter ['suppliers_id'])) {
// 		$where .= " AND (suppliers_id = '" . $filter ['suppliers_id'] . "')";
// 	}

// 	$where .= $conditions;
// 	/* 记录总数 */
// 	$count = $db->join(null)->where('is_delete = ' . $is_delete . '' . $where)->count();

// 	$count_where = array(
// 		'is_delete' 	=> $is_delete,
// 		'is_real' 		=> 1,
// 		'is_on_sale' 	=> 1
// 	);

// 	if ($filter ['extension_code']) {
// 		$count_where['is_real'] = 0;
// 		$count_where['extension_code'] = $filter[extension_code];
// 	}

// 	//TODO  已上架数据
// 	$count_on_sale = $db->join(null)->where($count_where)->count();
// 	$count_where['is_on_sale'] = 0;
// 	//TODO  未上架数据
// 	$count_not_sale = $db->join(null)->where($count_where)->count();
// 	$page = new ecjia_page ($count, 10, 5);
// 	$filter ['record_count'] = $count;
// 	$sql = $db->field('goods_id, goods_name, goods_type, goods_sn, shop_price, is_on_sale, is_best, is_new, is_hot, sort_order, goods_number, integral,(promote_price > 0 AND promote_start_date <= ' . $today . ' AND promote_end_date >= ' . $today . ')|is_promote')->where('is_delete = ' . $is_delete . '' . $where)->order(array($filter['sort_by'] => $filter['sort_order']))->limit($page->limit())->select();
// 	$filter ['keyword'] 		= stripslashes($filter ['keyword']);
// 	$filter ['count_on_sale']	= $count_on_sale;
// 	$filter ['count_not_sale']	= $count_not_sale;
// 	$filter ['count_goods_num']	= $count_not_sale + $count_on_sale;
// 	$filter ['count']			= $count;
// 	$row = $sql;
// 	return array(
// 		'goods'		=> $row,
// 		'filter'	=> $filter,
// 		'page'		=> $page->show(5),
// 		'desc'		=> $page->page_desc()
// 	);
// }
/***未调用方法汇总*----end**/

// end