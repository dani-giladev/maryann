<?php

namespace modules\ecommerce\frontend\controller\menu;

// Controllers
use core\device\controller\device;
use core\globals\controller\globals;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\menu\menu;
use modules\ecommerce\frontend\controller\availability;

// Views
use modules\ecommerce\frontend\view\menu\main as view;

/**
 * E-commerce frontend main menu controller
 *
 * @author Dani Gilabert
 * 
 */
class main extends menu
{ 
    
    public function __construct($categories = null,
                                $show_shoppingcart_button = true,
                                $show_ordering_button = true)
    {
        parent::__construct($categories);
        
        $this->_view = new view($categories,
                                $show_shoppingcart_button, 
                                $show_ordering_button);            
    }
    
    public function setAvailableLangs($value)
    {    
        $this->_view->available_langs = $value;
    }
    
    public function renderMainMenu()
    {    
        return $this->_getMainMenu();
    }
    
    protected function _getMainMenu()
    {
        $mobile = device::isMobileVersion()? 'mobile' : 'notmobile';
        $touch = device::isTouchDevice()? 'touch' : 'nottouch';
        $current_lang = lang::getCurrentLanguage();
        
        $main_menu = globals::getGlobalVar('ecommerce-main-menu');
        if (isset($main_menu) && isset($main_menu[$mobile][$touch]) && isset($main_menu[$mobile][$touch][$current_lang]))
        {
            return $main_menu[$mobile][$touch][$current_lang];
        }
        
        $main_menu[$mobile][$touch][$current_lang] = $this->_renderMainMenu();
        globals::setGlobalVar('ecommerce-main-menu', $main_menu);
        
        return $main_menu[$mobile][$touch][$current_lang];
    }  
    
    private function _renderMainMenu()
    {
        $html = '';

        if (!isset($this->_categories)) return $html;
        if (!isset($this->_categories->tree)) return $html;
        $tree = $this->_categories->tree[0]->children; // Take out root
        if (!isset($tree) || empty($tree)) return $html;
        
        if (!device::isMobileVersion())
        {
            $availability = new availability();
            $outstanding_articles = $availability->getOutstandingArticles();
            $this->_view->setOutstandingArticles($outstanding_articles);            
        }
        
        $html = $this->_view->renderMainMenu($tree);

        return $html; 
    }   
    
}