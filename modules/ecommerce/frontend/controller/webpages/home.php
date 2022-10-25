<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\home as homeController;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;

// Views
use modules\ecommerce\frontend\view\home as view;

/**
 * Home webpage
 *
 * @author Dani Gilabert
 * 
 */
class home extends homeController
{
    protected $_view;  

    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
    }
    
    public function init($data)
    {
        // Create objects and setting view properties
        $this->_view->website = $this->getWebsite();
        $this->_view->current_lang = lang::getCurrentLanguage();
        $this->_view->outstanding_articles = $this->_getOutstandingArticles();
        $this->_view->novelty_articles = $this->_getNoveltyArticles();
        $this->_view->gammas = $this->_getGammas();
        $this->_view->brands = $this->_getBrands();
        $this->_view->outstanding_brands = $this->_getOutstandingBrands();
        $this->_view->slider_images = $this->_getSlider();
        $this->_view->banners_images = $this->_getBanners();
        
        // Render this page
        $this->renderPage();
    }    
    
    protected function _renderMenu()
    {
        $html = '';
        
        // Render main menu
        $main_menu = new mainMenu();
        $html .= $main_menu->renderMainMenu();
     
        return $html;
    }

    protected function _renderContent()
    {
        $html = '';
        
        $html .= $this->_view->renderContent();
        
        return $html;
    }
    
    protected function _getCanonicalUrl()
    {
        $current_lang = lang::getCurrentLanguage();
        $canonical_url = $this->getUrl(array($current_lang));
        
        $path_info = (isset($_SERVER['REDIRECT_URL']))? $_SERVER['REDIRECT_URL'] : '';
        $path_info = substr($path_info, 1); // Remove the first slash
        if (empty($path_info))
        {
            return $canonical_url;
        }
        
        $splitted_url = preg_split("/\//", $path_info);
        if (count($splitted_url) === 1)
        {
            return '';
        }
        
        return $canonical_url;
    }
    
}