<?php

namespace modules\ecommerce\frontend\mobile\controller\menu;

// Controllers
use modules\ecommerce\frontend\controller\menu\main as menuMain;

// Views
use modules\ecommerce\frontend\mobile\view\menu\main as view;

/**
 * E-commerce frontend mobile main menu controller
 *
 * @author Dani Gilabert
 * 
 */
class main extends menuMain
{     
    public function __construct($categories = null,
                                $show_shoppingcart_button = true,
                                $show_ordering_button = true)
    {
        parent::__construct($categories);
        
        $this->_view = new view($categories);            
    }
    
    public function renderMainMenu()
    {   
        $html = '';
        
        $html .= $this->_view->renderLanguageSelectionPage();
        $html .= $this->_getMainMenu();
        
        return $html;
    }

    
}