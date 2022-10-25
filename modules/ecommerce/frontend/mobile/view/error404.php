<?php

namespace modules\ecommerce\frontend\mobile\view;

// Views
use modules\ecommerce\frontend\view\error404 as error404View;

/**
 * Error 404 mobile webpage view
 *
 * @author Dani Gilabert
 * 
 */
class error404 extends error404View
{   
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/mobile/res/css/common/error-404.css'
        );   
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }
    
}