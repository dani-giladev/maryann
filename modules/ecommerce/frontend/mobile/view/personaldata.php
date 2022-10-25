<?php

namespace modules\ecommerce\frontend\mobile\view;

// Views
use modules\ecommerce\frontend\view\personaldata as personaldataView;

/**
 * Personal (Customer) data form mobile view
 *
 * @author Dani Gilabert
 * 
 */
class personaldata extends personaldataView
{   
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/mobile/res/css/personal-data.css',
            
            '/modules/ecommerce/frontend/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/action-buttons.css'
        );   
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }
    
}