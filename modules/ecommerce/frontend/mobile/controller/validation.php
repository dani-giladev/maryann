<?php

namespace modules\ecommerce\frontend\mobile\controller;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\webpages\validation as validationController;
use modules\ecommerce\frontend\mobile\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\mobile\controller\menu\breadcrumbs as breadcrumbsMenu;

// Views
use modules\ecommerce\frontend\mobile\view\validation as view;
use modules\ecommerce\frontend\mobile\view\finalSteps as finalStepsView;

/**
 * Validation form mobile webpage
 *
 * @author Dani Gilabert
 * 
 */
class validation extends validationController
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
        $current_lang = lang::getCurrentLanguage();
        
        // Render breadcrumbs menu
        $breadcrumbs = array(
                                array('text' => lang::trans('order_summary'), 'url' => $this->getUrl(array($current_lang, 'shoppingcart'))),            
                                array('text' => lang::trans('shipping_data'), 'url' => $this->getUrl(array($current_lang, 'personaldata'))),            
                                array('text' => lang::trans('confirmation'), 'url' => '')
                            );
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'breadcrumbs' => $breadcrumbs, 
            'categories' => $this->_categories, 
            'show_shoppingcart_button' => false,
            'show_ordering_button' => false
        ));        
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    } 
    
}