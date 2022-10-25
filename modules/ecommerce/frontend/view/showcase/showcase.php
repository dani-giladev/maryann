<?php

namespace modules\ecommerce\frontend\view\showcase;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\showcase as showcaseController;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Showcase view
 *
 * @author Dani Gilabert
 * 
 */
class showcase extends ecommerceView
{
    
    public function getWebpageName()
    {
        return 'showcase';
    }
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();

        $styles = array(
            '/res/css/jquery/jquery.jcarousel/jquery.jcarousel-type1.css',
            '/res/css/jquery/jquery.rondellcarousel/jquery.rondell-1.1.0.min.css',
            '/res/css/jquery/fancybox/jquery.fancybox.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-buttons.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-thumbs.css',

            '/modules/ecommerce/frontend/res/css/showcase/showcase.css',
            '/modules/ecommerce/frontend/res/css/showcase/showcase-sidebar.css',
            '/modules/ecommerce/frontend/res/css/showcase/showcase-content.css', 
            '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/showcase/showcase-content.css', 
            '/modules/ecommerce/frontend/res/css/showcase/showcase-content-articles.css',
            '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/showcase/showcase-content-articles.css', 
            '/modules/ecommerce/frontend/res/css/shoppingcart/window-after-add-to-shoppingcart.css', 
            '/modules/ecommerce/frontend/res/css/common/medicines-info.css'
        );

        $ret = array_merge($ecommerce_styles, $styles);      
        
        return $ret;    
    }
    
    public function getDevelopmentHeadScriptsPaths()
    {
        $ecommerce_scripts = $this->_getHeadEcommerceScriptsPaths();

        $scripts = array(
            '/res/js/jquery/jquery.jcarousel/jquery.jcarousel-0.3.1.js',
            '/res/js/jquery/jquery.jcarousel/jquery.jcarousel-type1.js',
            //'/res/js/jquery/jquery.rondellcarousel/jquery.rondell-1.1.0.min.js',
            //'/res/js/modernizr-2.0.6.min.js',
            //'/res/js/jquery/jquery.mousewheel-3.0.6.min.js',
            '/res/js/jquery/fancybox/jquery.fancybox.js',
            //'/res/js/jquery/fancybox/helpers/jquery.fancybox-buttons.js',
            //'/res/js/jquery/fancybox/helpers/jquery.fancybox-media.js',
            //'/res/js/jquery/fancybox/helpers/jquery.fancybox-thumbs.js',

            '/modules/ecommerce/frontend/res/js/showcase.js'
        );

        $ret = array_merge($ecommerce_scripts, $scripts);      
        
        return $ret;    
    }     
    
    protected function _addJavascriptVars()
    {
        // Javascript vars and messages
        $html = $this->_renderHeadEcommerceJavascriptVars();
        $html .= $this->_renderAddToCartDialogWarningScriptsMessages();
        
        $showcase_controller = new showcaseController();
        $html .= 
                '<script type="text/javascript">'.PHP_EOL.
                    'var scroll_position = "'.$showcase_controller->getScrollPosition().'";'.PHP_EOL.
                    'var msg_processing_your_request = "'.lang::trans('processing_your_request').'";'.PHP_EOL.
                '</script>'.PHP_EOL.
                '';
        
        return $html;
    } 
    
    public function renderStartContent()
    {
        $html = 
                '<div id="showcase">'.
                    '<div id="showcase-center" class="page-center">'.
                '';   

        return $html;
    } 
    
    public function renderEndContent()
    {
        $html = 
                    '</div>'.
                '</div>'.
                '';   
        
        return $html;
    }  
    
}