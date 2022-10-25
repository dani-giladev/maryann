<?php

namespace modules\ecommerce\frontend\view;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Empty webpage view (dynamic content)
 *
 * @author Dani Gilabert
 * 
 */
class page extends ecommerceView
{ 
    
    public function getWebpageName()
    {
        return 'page';
    } 
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/res/css/page.css'
        );
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }  
    
    public function getDevelopmentHeadScriptsPaths()
    {
        $ecommerce_scripts = $this->_getHeadEcommerceScriptsPaths();

        $scripts = array(

        );

        $ret = array_merge($ecommerce_scripts, $scripts);      
        
        return $ret;
    }    
    
    protected function _addJavascriptVars()
    {
        // Javascript vars and messages
        $html = $this->_renderHeadEcommerceJavascriptVars();
        
        return $html;
    }
    
    public function renderStartContent()
    {
        $html = 
                '<div id="page-parent">'.
                    '<div id="page-parent-center">'.
                        '<div id="page-content">'.
                '';   
        
        return $html;
    } 
    
    public function renderEndContent()
    {
        $html = 
                        '</div>'.
                    '</div>'.
                '</div>'.
                '';   
        
        return $html;
    }    
    
    public function renderContent($content)
    {
        $html = $content;    
        
        return $html;
    }  
    
}