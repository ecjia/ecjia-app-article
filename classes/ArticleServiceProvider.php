<?php

namespace Ecjia\App\Article;

use Royalcms\Component\App\AppServiceProvider;

class ArticleServiceProvider extends  AppServiceProvider
{
    
    public function boot()
    {
        $this->package('ecjia/app-articlei');
    }
    
    public function register()
    {
        
    }
    
    
    
}