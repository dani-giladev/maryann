<?php

namespace modules\ecommerce\frontend\mobile\controller;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\webpages\error404 as error404Controller;
use modules\ecommerce\frontend\mobile\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\mobile\controller\menu\breadcrumbs as breadcrumbsMenu;

// Views
use modules\ecommerce\frontend\mobile\view\error404 as view;

/**
 * Error 404 mobile webpage
 *
 * @author Dani Gilabert
 * 
 */
class error404 extends error404Controller
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
        $breadcrumbs = array(
                                array('text' => lang::trans('error_404_description'), 'url' => '')
                            );        
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'breadcrumbs' => $breadcrumbs
        ));         
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    } 
    

    
}