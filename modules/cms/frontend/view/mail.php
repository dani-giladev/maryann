<?php

namespace modules\cms\frontend\view;

// Controllers
use modules\cms\frontend\controller\webpage as webpageController;

// Views
use modules\cms\frontend\view\webpage;

/**
 * Mail view
 *
 * @author Dani Gilabert
 * 
 */
class mail extends webpage
{   
    public $title;  
    protected $_webpage_controller;
    
    public function __construct()
    {
        $this->_webpage_controller = new webpageController();
    } 
    
    protected function _getHeadStyleSheetsPaths()
    {
        return array();
    }
    
    protected function _renderHeadCommonStyles()
    {
        $html = 
                '<style type="text/css">'.
                
                    '#mailing-header-content {'.
                        'background: #272624;'.
                    '}'.
                
                    '#mailing-header-content-logo {'.
                        'width: 250px;'.
                        'height: 40px;'.
                        'padding: 20px 0px 5px 0px;'.
                   '}'.
                
                    '#mailing-header-content-title {'.
                        'padding: 13px;'.
                        'font-weight: bold;'.
                        'font-size: 14px;'.
                        'font-style: italic;'.
                        'background: #c0a00a;'.
                        'border-bottom: 1px solid black;'.
                    '}'.
                '</style>'.
                '';
        
        return $html;
    }                 
    
    protected function _getHeadCommonScriptsPaths()
    {
        $html = '';      
        
        return $html;
    }     
    
    public function renderContent($website)
    {
        $html = '';
        
        // Start content
        $html .= $this->_renderStartContent();
        
        // Pre-header (always text to avoid the weird url image)
        $html .= $this->_renderPreHeader();
        
        // Header content (logo)
        $html .= $this->_renderHeaderContent($website);
        
        // Body content
        $html .= $this->_renderStartBodyContent();
        $html .= $this->renderBodyContent($website);
        $html .= $this->_renderEndBodyContent();
        
        // Footer content
        $html .= $this->_renderFooterContent();
                
        // End content
        $html .= $this->_renderEndContent();
        
        return $html;
    }
    
    private function _renderStartContent()
    {
        $html = '';
        
        return $html;          
    }                   
    
    private function _renderEndContent()
    {
        $html = '';
        
        return $html;          
    }
    
    protected function _renderPreHeader()
    {
        $html = '';
        
        return $html; 
    }
    
    private function _renderHeaderContent($website)
    {
        // Start
        $html =  '<div id="mailing-header-content">';

        // Logo
        $image_path = $this->_webpage_controller->getLogoPath($website);
        $image_path = $this->_webpage_controller->getUrl().$image_path;
        
        $html .= 
                '<div>'.
                    '<img id="mailing-header-content-logo" src="'.$image_path.'" alt="Logo" />'.
                '</div>'.
                '';   
        
        /*
        // Website name
        $html .=  
                '<label id="mailing-header-content-website-name">'.
                    $website->name.
                '</label>';      
        */
        
        // Title
        $html .= 
                '<div id="mailing-header-content-title">'.
                    $this->title.
                '</div>'.
                ''; 
        
        // End
        $html .=  '</div>';
        
        return $html;
    }
    
    private function _renderStartBodyContent()
    {
        $html = '<div id="mailing-body-content">';
        
        return $html;          
    }                   
    
    private function _renderEndBodyContent()
    {
        $html = '</div>';
        
        return $html;          
    }

    private function _renderFooterContent()
    {
        $html = '';     
        
        return $html;
    }
    
}