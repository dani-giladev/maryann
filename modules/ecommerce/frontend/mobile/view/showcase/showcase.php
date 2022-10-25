<?php

namespace modules\ecommerce\frontend\mobile\view\showcase;

// Views
use modules\ecommerce\frontend\view\showcase\showcase as showcaseView;

/**
 * Showcase mobile webpage view
 *
 * @author Dani Gilabert
 * 
 */
class showcase extends showcaseView
{   
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/res/css/jquery/fancybox/jquery.fancybox.css',
            
            '/modules/ecommerce/frontend/mobile/res/css/showcase/showcase-sidebar.css',
            '/modules/ecommerce/frontend/mobile/res/css/showcase/showcase-content.css', 
            '/modules/ecommerce/frontend/mobile/res/skins/'.$this->_skin.'/showcase/showcase-content.css', 
            '/modules/ecommerce/frontend/mobile/res/css/showcase/showcase-content-articles.css',
            '/modules/ecommerce/frontend/mobile/res/skins/'.$this->_skin.'/showcase/showcase-content-articles.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/medicines-info.css' 
        );   
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }
    
    public function getDevelopmentHeadScriptsPaths()
    {
        $ecommerce_scripts = $this->_getHeadEcommerceScriptsPaths();

        $scripts = array(
            '/res/js/jquery/fancybox/jquery.fancybox.js',
            
            '/modules/ecommerce/frontend/res/js/showcase.js'
        );

        $ret = array_merge($ecommerce_scripts, $scripts);      
        
        return $ret;    
    }  
    
}