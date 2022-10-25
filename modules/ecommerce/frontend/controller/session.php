<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\config\controller\config;
use core\globals\controller\globals;
use core\frontend\controller\session as frontendSession;
use modules\ecommerce\controller\article;
use modules\ecommerce\controller\brand;
use modules\ecommerce\controller\articleProperty;
use modules\ecommerce\controller\saleRate;

/**
 * Overrides core frontend session class
 *
 * @author Dani Gilabert
 * 
 */
class session extends frontendSession
{

    public function resetAll()
    {
        $this->resetCommonVars();
        
        // Reset categories
        $this->resetCategories();
        
        // Reset articles
        $article_controller = new article();
        $article_controller->updateViews();
        $this->resetArticles();
        
        // Reset brands
        $brand_controller = new brand();
        $brand_controller->updateViews();
        $this->resetBrands();
        
        // Reset article properties
        $article_property_controller = new articleProperty();
        $article_property_controller->updateViews();
        $this->resetArticleProperties();
        
        // Reset sale rates
        $sale_rate_controller = new saleRate();
        $sale_rate_controller->updateViews();
        $this->resetSaleRates();
    }

    public function resetCommonVars()
    {
        config::setConfig(null);
        
        self::setSessionVar('cms-available-langs', null);
        globals::setGlobalVar('admin-langs', null);
        globals::setGlobalVar('website-by-domain', null);
    }

    public function resetCategories()
    {
        globals::setGlobalVar('ecommerce-categories', null);
        globals::setGlobalVar('ecommerce-main-menu', null);
    }

    public function resetArticles()
    {
        globals::setGlobalVar('ecommerce-article-types', null);
        $this->resetArticlesForSale();
    }

    public function resetArticlesForSale()
    {
        globals::setGlobalVar('ecommerce-articles-by-url', null);
        globals::setGlobalVar('ecommerce-articles-for-sale', null);
        globals::setGlobalVar('ecommerce-articles-not-available', null);
        globals::setGlobalVar('ecommerce-articles-for-sale-grouped-by-display', null);
        globals::setGlobalVar('ecommerce-articles-for-sale-with-stock', null);
        globals::setGlobalVar('ecommerce-articles-for-sale-outstanding', null);
        globals::setGlobalVar('ecommerce-articles-for-sale-novelty', null);
    }

    public function resetBrands()
    {
        globals::setGlobalVar('ecommerce-labs', null);
        globals::setGlobalVar('ecommerce-brands', null);
        globals::setGlobalVar('ecommerce-gammas', null);
        $this->resetArticles();
    }

    public function resetArticleProperties()
    {
        globals::setGlobalVar('ecommerce-article-properties', null);
    }

    public function resetSaleRates()
    {
        globals::setGlobalVar('ecommerce-sale-rates', null);
        $this->resetArticles();
    }

    public function resetPromos()
    {
        globals::setGlobalVar('marketing-promos', null);
        $this->resetCategories();
    }

    public function resetArticleGroups()
    {
        globals::setGlobalVar('marketing-articlegroups', null);
    }
    
}