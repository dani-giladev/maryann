<?php

namespace modules\ecommerce\frontend\mobile\view;

// Views
use modules\ecommerce\frontend\view\validation as validationView;

/**
 * Validation form mobile view
 *
 * @author Dani Gilabert
 * 
 */
class validation extends validationView
{   
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/res/css/bond.css',
            '/modules/ecommerce/frontend/mobile/res/css/validation.css',
            
            '/modules/ecommerce/frontend/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/res/css/shoppingcart/shoppingcart-summary.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/action-buttons.css'
        );   
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }
    
}