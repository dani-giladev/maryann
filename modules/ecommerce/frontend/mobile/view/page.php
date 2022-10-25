<?php

namespace modules\ecommerce\frontend\mobile\view;

// Views
use modules\ecommerce\frontend\view\page as pageView;

/**
 * Empty mobile webpage view (dynamic content)
 *
 * @author Dani Gilabert
 * 
 */
class page extends pageView
{ 
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/mobile/res/css/page.css'
        );
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }     
    
    public function renderContent($content)
    {
        $html = '<div id="page-content">';  
        $html .= $content;    
        $html .= '</div>'; 
        
        return $html;
    }  
    
}