<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use modules\cms\frontend\controller\sitemap as CmsSitemap;

// Views
use modules\ecommerce\frontend\view\sitemap as view;

/**
 * E-commerce frontend sitemap controller
 *
 * @author Dani Gilabert
 * 
 */
class sitemap extends CmsSitemap
{
    protected $_view = null;

    public function __construct($changefreq)
    {
        $this->_view = new view($changefreq);
    }
    
    public function rebuild($website)
    {
        $this->rebuildSitemap($website);
    }

    
}