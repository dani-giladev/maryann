<?php

namespace modules\ecommerce\frontend\mobile\controller;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\webpages\brands as brandsController;
use modules\ecommerce\frontend\mobile\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\mobile\controller\menu\breadcrumbs as breadcrumbsMenu;

// Views
use modules\ecommerce\frontend\mobile\view\brands as view;

/**
 * Brands mobile webpage
 *
 * @author Dani Gilabert
 * 
 */
class brands extends brandsController
{

    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
    }
    
    protected function _renderMenu()
    {
        $html = '';
        
        // Render main menu
        $main_menu = new mainMenu();
        $available_langs = $this->_getAvailableLanguages();
        $main_menu->setAvailableLangs($available_langs);
        $html .= $main_menu->renderMainMenu();
     
        return $html;
    }    
    
    protected function _renderBreadcrumbs()
    {
        $html = '';
        
        // Render breadcrumbs menu
        $breadcrumbs = array(array('text' => lang::trans('brands'), 'url' => ''));
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'breadcrumbs' => $breadcrumbs
        ));           
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    } 
    

    
}