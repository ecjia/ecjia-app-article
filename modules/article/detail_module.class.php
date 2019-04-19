<?php
//
//    ______         ______           __         __         ______
//   /\  ___\       /\  ___\         /\_\       /\_\       /\  __ \
//   \/\  __\       \/\ \____        \/\_\      \/\_\      \/\ \_\ \
//    \/\_____\      \/\_____\     /\_\/\_\      \/\_\      \/\_\ \_\
//     \/_____/       \/_____/     \/__\/_/       \/_/       \/_/ /_/
//
//   上海商创网络科技有限公司
//
//  ---------------------------------------------------------------------------------
//
//   一、协议的许可和权利
//
//    1. 您可以在完全遵守本协议的基础上，将本软件应用于商业用途；
//    2. 您可以在协议规定的约束和限制范围内修改本产品源代码或界面风格以适应您的要求；
//    3. 您拥有使用本产品中的全部内容资料、商品信息及其他信息的所有权，并独立承担与其内容相关的
//       法律义务；
//    4. 获得商业授权之后，您可以将本软件应用于商业用途，自授权时刻起，在技术支持期限内拥有通过
//       指定的方式获得指定范围内的技术支持服务；
//
//   二、协议的约束和限制
//
//    1. 未获商业授权之前，禁止将本软件用于商业用途（包括但不限于企业法人经营的产品、经营性产品
//       以及以盈利为目的或实现盈利产品）；
//    2. 未获商业授权之前，禁止在本产品的整体或在任何部分基础上发展任何派生版本、修改版本或第三
//       方版本用于重新开发；
//    3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回并承担相应法律责任；
//
//   三、有限担保和免责声明
//
//    1. 本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；
//    2. 用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未获得商业授权之前，我们不承
//       诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；
//    3. 上海商创网络科技有限公司不对使用本产品构建的商城中的内容信息承担责任，但在不侵犯用户隐
//       私信息的前提下，保留以任何方式获取用户信息及商品信息的权利；
//
//   有关本产品最终用户授权协议、商业授权与技术服务的详细内容，均由上海商创网络科技有限公司独家
//   提供。上海商创网络科技有限公司拥有在不事先通知的情况下，修改授权协议的权力，修改后的协议对
//   改变之日起的新授权用户生效。电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和
//   等同的法律效力。您一旦开始修改、安装或使用本产品，即被视为完全理解并接受本协议的各项条款，
//   在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本
//   授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
//
//  ---------------------------------------------------------------------------------
//
defined('IN_ECJIA') or exit('No permission resources.');

/**
 * 文章详情
 * @author zrl
 *
 */
class article_detail_module extends api_front implements api_interface {
    public function handleRequest(\Royalcms\Component\HttpKernel\Request $request) {
    	$id = $this->requestData('article_id', 0);
    	if ($id <= 0) {
			return new ecjia_error('invalid_parameter', __('参数无效', 'article'));
		}
		
		$cache_article_key = 'article_info_'.$id;
		$cache_id = sprintf('%X', crc32($cache_article_key));
		$article_db = RC_Model::model('article/orm_article_model');
    	$article_detail = $article_db->get_cache_item($cache_id);
    	
		if (empty($article_detail)) {
			$article_info = $this->get_article_info($id);
			if (empty($article_info)) {
				return new ecjia_error('does not exist', __('不存在的信息', 'article'));
			}
			if ($article_info['store_id'] > 0) {
				$store_name = RC_DB::table('store_franchisee')->where('store_id', $article_info['store_id'])->pluck('merchants_name');
				$store_logo = RC_DB::table('merchants_config')->where('store_id', $article_info['store_id'])->where('code', 'shop_logo')->pluck('value');
				$store_total_articles = RC_DB::table('article')->where('store_id', $article_info['store_id'])->count('article_id');
			}
			/*平台自营文章总数*/
			$total_articles = RC_DB::table('article')->where('store_id', 0)->count('article_id');
			/*当前用户是否点赞过此文章*/
			$discuss_likes_info = '';
			if ($_SESSION['user_id'] > 0) {
				$discuss_likes_info =  RC_DB::table('discuss_likes')->where('id_value', $id)->where('like_type', 'article')->where('user_id', $_SESSION['user_id'])->first();
			}
			
			/*关联商品*/
			$article_related_goods_ids = RC_DB::table('goods_article')->where('article_id', $id)->lists('goods_id');
			
			
			$list = array();

			if (!empty($article_related_goods_ids)) {
				//用户端商品展示基础条件
				$filters = [
					'goods_ids' 		=> $article_related_goods_ids   //文章关联的商品id
				];
				
				//会员等级价格
				$filters['user_rank'] = $_SESSION['user_rank'];
				$filters['user_rank_discount'] = $_SESSION['discount'];
				//分页信息
				$filters['size'] = 6;
				$filters['page'] = 1;
					
				$collection = (new \Ecjia\App\Goods\GoodsSearch\GoodsApiCollection($filters))->getData();
					
				$list = $collection['goods_list'];
			}
			
			/*推荐商品*/
			$recommend_goods = array();
			$goods_list = array();
			
			
			//用户端商品展示基础条件
			$filters = [
				'store_unclosed' 		=> 0,    //店铺未关闭的
				'is_delete'		 		=> 0,	 //未删除的
				'is_on_sale'	 		=> 1,    //已上架的
				'is_alone_sale'	 		=> 1,	 //单独销售的
				'review_status'  		=> 2,    //审核通过的
				'no_need_cashier_goods'	=> true, //不需要收银台商品和散装商品
			];
			
			//是否展示货品
			if (ecjia::config('show_product') == 1) {
				$filters['product'] = true;
			}
			//定位附近店铺id
			$filters['store_id'] = $article_info['store_id'];
			
			//平台推荐
			$filters['is_best'] = 1;
			
			//排序
			$order_by = array('sort_order' => 'asc', 'goods_id' => 'desc');
			if (!empty($order_by)) {
				$filters['sort_by'] = $order_by;
			}
			
			//会员等级价格
			$filters['user_rank'] = $_SESSION['user_rank'];
			$filters['user_rank_discount'] = $_SESSION['discount'];
			//分页信息
			$filters['size'] = 6;
			$filters['page'] = 1;
			
			$recommend_collection = (new \Ecjia\App\Goods\GoodsSearch\GoodsApiCollection($filters))->getData();
			
			$recommend_goods = $recommend_collection['goods_list'];
			
			
			
			/*店铺是否关闭*/
			if ($article_info['store_id'] > 0) {
				$shop_close = RC_DB::table('store_franchisee')->where('store_id', $article_info['store_id'])->pluck('shop_close');
				if ($shop_close == 1) {
					$recommend_goods = array();
				}
			}
			$platform = RC_Uri::admin_url('statics/images/platform_logo.png');//平台默认logo
			
			/*文章内容*/
			$base = sprintf('<base href="%s/" />', dirname(SITE_URL));
			$article_info['content'] = preg_replace('/\\\"/', '"', $article_info['content']);
			$content = '<!DOCTYPE html><html><head><title>'.$article_info['title'].'</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="viewport" content="initial-scale=1.0"><meta name="viewport" content="initial-scale = 1.0 , minimum-scale = 1.0 , maximum-scale = 1.0" /><style>img {width: auto\9;height: auto;vertical-align: middle;border: 0;-ms-interpolation-mode: bicubic;max-width: 100%; }html { font-size:100%; }p{word-wrap : break-word ;word-break:break-all;} </style>'.$base.'</head><body>'.$article_info['content'].'</body></html>';
			
			$article_detail[] = array(
					'article_id'			=> intval($article_info['article_id']),
					'title'					=> trim($article_info['title']),
					'add_time'				=> !empty($article_info['add_time']) ? RC_Time::local_date(ecjia::config('time_format'), $article_info['add_time']) : '',
					'store_id'				=> $article_info['store_id'],
					'store_name'			=> $article_info['store_id'] > 0 ? $store_name : '小编推荐',
					'store_logo'			=> $article_info['store_id'] > 0 ? RC_Upload::upload_url($store_logo) : $platform,
					'total_articles'		=> $article_info['store_id'] > 0 ? $store_total_articles : $total_articles,
					'like_count'			=> $article_info['like_count'],
					'comment_count'			=> $article_info['comment_count'],
					'is_like'				=> !empty($discuss_likes_info) ? 1 : 0,
					'article_related_goods' => $list,
					'recommend_goods'		=> $recommend_goods,
					'content'				=> $content,
					'article_type'			=> $article_info['article_type'],
					'link_url'				=> !empty($article_info['link']) ? $article_info['link'] : ''
			);
			$article_db->set_cache_item($cache_id, $article_detail);
		}
		/*更新文章浏览量*/
		RC_DB::table('article')->where('article_id', $id)->increment('click_count');
		return $article_detail;
	}
	
	private function get_article_info($article_id) {
		/* 获得文章的信息 */
		$row = RC_DB::table('article')->where('article_approved', 1)->where( 'article_id', $article_id)->first();
		return $row;
	}
}

// end