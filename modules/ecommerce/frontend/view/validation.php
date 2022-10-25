<?php

namespace modules\ecommerce\frontend\view;

// Controllers
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;
use modules\ecommerce\frontend\view\bond as bondView;

/**
 * Validation form view
 *
 * @author Dani Gilabert
 * 
 */
class validation extends ecommerceView
{     
    public $sale_data; 
    protected $_bond_view;

    public function __construct()
    {
        parent::__construct();
        $this->_bond_view = new bondView();
    }
    
    public function getWebpageName()
    {
        return 'validation';
    }
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/res/css/bond.css',
            '/modules/ecommerce/frontend/res/css/validation.css',
            '/modules/ecommerce/frontend/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/res/css/shoppingcart/shoppingcart-summary.css',
            '/modules/ecommerce/frontend/res/css/common/action-buttons.css'
        );
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }
    
    public function getDevelopmentHeadScriptsPaths()
    {
        $ecommerce_scripts = $this->_getHeadEcommerceScriptsPaths();

        $scripts = array(
            '/modules/ecommerce/frontend/res/js/validation.js'
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
                '<div id="validation-parent">'.
                    '<div id="validation-parent-center">'.
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
    
    public function renderForm()
    {
        $html = '';
        
        // Start form
        $html .=  '<div id="validation">';

        // Title
        $html .= 
                '<div>'.
                    '<table class="validation-title title"><tr><td>'.
                        strtoupper(lang::trans('confirm_your_order')).
                    '</td></tr></table>'.                
                '</div>';
        
        // Start content
        $html .= 
                '<div id="validation-content">'.
                    '<div id="validation-content-wrapper">';
        
        // Thanks
        $html .= 
                //lang::trans("thanks_for_trusting_us").'&nbsp;'.
                '<span id="validation-content-thanks">'.lang::trans("verify_your_data").'</span>'.
                '</br>'.
                '</br>'.
                '';
        
        $this->_bond_view->sale_data = $this->sale_data;
        $this->_bond_view->is_validation = true;
        $html .= $this->_bond_view->renderContent();
        
        // End content
        $html .=  '</div></div>';
         
        // End form
         $html .=  '</div>';
         
        return $html; 
    }
    
}