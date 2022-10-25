<?php

namespace modules\ecommerce\frontend\view\mailing;

// Views
use modules\cms\frontend\view\mail as cmsMail;
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Mail view
 *
 * @author Dani Gilabert
 * 
 */
class mail extends cmsMail
{   
    public $title;  
    protected $_ecommerce_view;
    
    public function __construct()
    {
        parent::__construct();
        $this->_ecommerce_view = new ecommerceView();
    }
        
    protected function _renderHeadEcommerceStyles()
    {
        $html = $this->_renderHeadCommonStyles();
        
        $html .= 
                '<style type="text/css">'.
                    $this->getStyleSheetFileContent("modules/ecommerce/frontend/res/css/ecommerce.css").
                    $this->getStyleSheetFileContent("modules/ecommerce/frontend/res/skins/".$this->_ecommerce_view->getSkin()."/common/ecommerce.css").
                '</style>';
        
        return $html;
    }                
    
    protected function _getHeadEcommerceScriptsPaths()
    {
        $html = $this->_getHeadCommonScriptsPaths();
        
        return $html;
    }      
    
    public function renderPriceFormat($price)
    {
        return $this->_ecommerce_view->renderPriceFormat($price);
    }
        
}