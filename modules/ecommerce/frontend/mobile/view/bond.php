<?php

namespace modules\ecommerce\frontend\mobile\view;

// Views
use modules\ecommerce\frontend\view\bond as bondView;

/**
 * Payment form mobile view
 *
 * @author Dani Gilabert
 * 
 */
class bond extends bondView
{   
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/mobile/res/css/bond.css',
            '/modules/ecommerce/frontend/res/css/shoppingcart/shoppingcart-summary.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/action-buttons.css'
        );   
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }
    
}