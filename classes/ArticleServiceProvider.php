<?php

namespace Ecjia\App\Article;

use Royalcms\Component\App\AppParentServiceProvider;

class ArticleServiceProvider extends  AppParentServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-articlei');
    }
    
    public function register()
    {
        
    }
    
    
    
}