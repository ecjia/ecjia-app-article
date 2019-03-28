<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2019-03-28
 * Time: 10:04
 */

namespace Ecjia\App\Article\SettingComponents;


use Ecjia\App\Setting\ComponentAbstract;

class ArticleSetting extends ComponentAbstract
{

    /**
     * 代号标识
     * @var string
     */
    protected $code = 'article';

    /**
     * 名称
     * @var string
     */
    protected $name = '文章设置';

    /**
     * 描述
     * @var string
     */
    protected $description = '';


    public function handle()
    {

        $data = [
            ['code' => 'article_title_length', 'value' => '20', 'options' => ['type' => 'text']],
            ['code' => 'article_page_size', 'value' => '20', 'options' => ['type' => 'text']],
            ['code' => 'help_open', 'value' => '1', 'options' => ['type' => 'select', 'store_range' => '0,1']],

        ];

        return $data;
    }

    public function getConfigs()
    {
        $config = [
            [
                'cfg_code' => 'article_title_length',
                'cfg_name' => __('文章标题的长度', 'article'),
                'cfg_desc' => '',
                'cfg_range' => '',
            ],

            [
                'cfg_code' => 'article_page_size',
                'cfg_name' => __('文章分类页列表的数量', 'article'),
                'cfg_desc' => '',
                'cfg_range' => '',
            ],

            [
                'cfg_code' => 'help_open',
                'cfg_name' => __('用户帮助是否打开', 'article'),
                'cfg_desc' => '',
                'cfg_range' => array(
                    '0' => __('关闭', 'goods'),
                    '1' => __('打开', 'goods'),
                ),
            ],


        ];

        return $config;

    }
}