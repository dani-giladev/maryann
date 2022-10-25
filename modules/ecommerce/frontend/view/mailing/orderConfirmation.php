<?php

namespace modules\ecommerce\frontend\view\mailing;

// Views
use modules\ecommerce\frontend\view\mailing\mail as mailView;
use modules\ecommerce\frontend\view\bond as bondView;

/**
 * Order confirmation email view
 *
 * @author Dani Gilabert
 * 
 */
class orderConfirmation extends mailView
{
    public $sale_data;         
    protected $_bond_view;

    public function __construct()
    {
        parent::__construct();
        $this->_bond_view = new bondView();
        $this->_bond_view->is_email = true;
    }

    protected function _renderHeadStyles()
    {
        $html = $this->_renderHeadEcommerceStyles();
        
        $html .= 
                '<style type="text/css">'.
                    $this->getStyleSheetFileContent("modules/ecommerce/frontend/res/css/bond.css").  
                    $this->getStyleSheetFileContent("modules/ecommerce/frontend/res/css/shoppingcart/shoppingcart-summary.css").
                    $this->getStyleSheetFileContent("modules/ecommerce/frontend/res/css/mailing/mailing.css").
                '</style>';
        
        return $html;
    }                
    
    protected function _renderHeadScripts($version)
    {
        $html = $this->_getHeadEcommerceScriptsPaths();
        
        return $html;
    }
    
    protected function _renderPreHeader()
    {
        $html = '';
        
        $html .= $this->_bond_view->renderSuccessText();
        $html .= $this->_bond_view->renderThanks();
        
        return $html; 
    }
    
    public function renderBodyContent($website = null)
    {
        $html = '';
        
        $this->_bond_view->sale_data = $this->sale_data;
        $html .= $this->_bond_view->renderContent();
         
        return $html; 
    }
    
}