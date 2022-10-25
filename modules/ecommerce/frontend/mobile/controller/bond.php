<?php

namespace modules\ecommerce\frontend\mobile\controller;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\webpages\bond as bondController;
use modules\ecommerce\frontend\mobile\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\mobile\controller\menu\breadcrumbs as breadcrumbsMenu;

// Views
use modules\ecommerce\frontend\mobile\view\bond as view;
use modules\ecommerce\frontend\mobile\view\finalSteps as finalStepsView;

/**
 * Payment form mobile webpage
 *
 * @author Dani Gilabert
 * 
 */
class bond extends bondController
{

    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
        $this->_final_steps_view = new finalStepsView();
    }
    
    protected function _renderMenu()
    {
        $html = '';
        
        // Render main menu
        $main_menu = new mainMenu();
        $available_langs = $this->_getAvailableLanguages();
        $main_menu->setAvailableLangs($available_langs);
        $html .= $main_menu->renderMainMenu();
        $this->_categories = $main_menu->getCategoriesTree();
     
        return $html;
    }    
    
    protected function _renderBreadcrumbs()
    {
        $html = '';
        
        // Render breadcrumbs menu
        $breadcrumbs = array(
                                array('text' => lang::trans('order_confirmation'), 'url' => '')
                            );
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'breadcrumbs' => $breadcrumbs, 
            'categories' => $this->_categories,
            'show_shoppingcart' => false
        ));          
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    } 
    
}