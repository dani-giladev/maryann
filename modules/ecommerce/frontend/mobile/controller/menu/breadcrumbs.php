<?php

namespace modules\ecommerce\frontend\mobile\controller\menu;

// Controllers
use modules\ecommerce\frontend\controller\menu\breadcrumbs as menuBreadcrumbs;

// Views
use modules\ecommerce\frontend\mobile\view\menu\breadcrumbs as view;

/**
 * E-commerce frontend mobile breadcrumbs controller
 *
 * @author Dani Gilabert
 * 
 */
class breadcrumbs extends menuBreadcrumbs
{       
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->_view = new view();    
        $this->_view->show_shoppingcart = false;
        $this->_view->show_shoppingcart_button = false;
        $this->_view->show_ordering_button = false;  
        $this->_view->webpage = $this->_webpage;
    }
    
}