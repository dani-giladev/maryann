<?php

namespace modules\ecommerce\frontend\mobile\view\menu;

// Views
use modules\ecommerce\frontend\view\menu\breadcrumbs as breadcrumbsView;

/**
 * E-commerce frontend mobile breadcrumbs view
 *
 * @author Dani Gilabert
 * 
 */
class breadcrumbs extends breadcrumbsView
{ 
    
    public function renderBreadcrumbsMenu($html_breadcrumbs)
    {        
        // Start
        $html = 
                '<div id="menu-breadcrumbs">'.
                    '<div id="menu-breadcrumbs-wrapper-text">'.
                        $html_breadcrumbs.
                    '</div>'.    
                '</div>'.
                ''.PHP_EOL.PHP_EOL; 
        
        return $html;
    }
    
}
