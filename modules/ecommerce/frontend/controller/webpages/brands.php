<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\brands as brandsController;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;

// Views
use modules\ecommerce\frontend\view\brands as view;

/**
 * Brands webpage
 *
 * @author Dani Gilabert
 * 
 */
class brands extends brandsController
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
        $this->_view->current_lang = lang::getCurrentLanguage();
        $this->_view->brands = $this->_getBrandsClassifiedByLetter();
        
        // Render this page
        $this->renderPage();
    }    
    
    protected function _getTitle()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('brands');
    }           
    
    protected function _getDescription()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('brands');
    }           
    
    protected function _renderMenu()
    {
        $html = '';
        
        // Render main menu
        $main_menu = new mainMenu(null, false, true);
        $html .= $main_menu->renderMainMenu();
        
        // Render breadcrumbs menu
        $breadcrumbs = array(array('text' => lang::trans('brands'), 'url' => ''));
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'breadcrumbs' => $breadcrumbs
        ));          
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    }
    
    protected function _renderContent()
    {
        $html = '';
        
        $html .= $this->_view->renderContent();
        
        return $html;
    }
    
}