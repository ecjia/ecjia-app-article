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

        ];

        return $data;
    }

    public function getConfigs()
    {
        $config = [

        ];

        return $config;

    }
}