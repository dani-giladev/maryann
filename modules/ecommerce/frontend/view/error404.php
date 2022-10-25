<?php

namespace modules\ecommerce\frontend\view;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Error 404 view
 *
 * @author Dani Gilabert
 * 
 */
class error404 extends ecommerceView
{ 
    
    public function getWebpageName()
    {
        return 'error404';
    }
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/res/css/common/error-404.css'
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
                '<div id="error-404-parent">'.
                    '<div id="error-404-parent-center">'.
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
    
    public function renderContent()
    {
        $html = '';
        
        $html .= 
                '<div id="error-404-content">'.
                    '<img id="error-404-content-img" src="/res/img/error-404-1.jpg" />'.
                '</div>'.
                '';  
        
        return $html;
    }  
    
    
}