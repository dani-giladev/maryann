<?php

namespace modules\ecommerce\frontend\mobile\controller;

// Controllers
use modules\ecommerce\frontend\controller\webpages\home as homeController;
use modules\ecommerce\frontend\mobile\controller\menu\main as mainMenu;

// Views
use modules\ecommerce\frontend\mobile\view\home as view;

/**
 * Home webpage
 *
 * @author Dani Gilabert
 * 
 */
class home extends homeController
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
        
}