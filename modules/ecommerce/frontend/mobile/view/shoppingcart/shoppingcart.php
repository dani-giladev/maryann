<?php

namespace modules\ecommerce\frontend\mobile\view\shoppingcart;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\shoppingcart as shoppingcartController;

// Views
use modules\ecommerce\frontend\view\shoppingcart\shoppingcart as shoppingcartView;

/**
 * Shoppingcart mobile webpage view
 *
 * @author Dani Gilabert
 * 
 */
class shoppingcart extends shoppingcartView
{ 
    protected $_shoppingcart_controller;
    
    public function __construct()
    {
        parent::__construct();
        $this->_shoppingcart_controller = new shoppingcartController();
    }
 
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/res/css/jquery/fancybox/jquery.fancybox.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-buttons.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-thumbs.css',
            
            '/modules/ecommerce/frontend/mobile/res/css/shoppingcart/shoppingcart.css',
            '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/shoppingcart/shoppingcart.css',
            '/modules/ecommerce/frontend/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/action-buttons.css'
        );
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }
    
    public function getDevelopmentHeadScriptsPaths()
    {
        $ecommerce_scripts = $this->_getHeadEcommerceScriptsPaths();

        $scripts = array(
            '/res/js/jquery/jquery.validate-1.9.0.min.js',
            '/res/js/jquery/fancybox/jquery.fancybox.js',
            
            '/modules/ecommerce/frontend/res/js/shoppingcart.js'
        );

        $ret = array_merge($ecommerce_scripts, $scripts);      
        
        return $ret;
    }
    
    public function renderShoppingcartMenuAmount()
    {
        $total_amount = $this->_shoppingcart_controller->getTotalAmount();
        
        $html = 
                '<div id="shoppingcart-menu-option-amount-wrapper">'.
                    '<div id="shoppingcart-menu-option-amount">'.
                        '<div id="shoppingcart-menu-option-amount-text">'.
                            $total_amount.
                        '</div>'.  
                    '</div>'.  
                '</div>'.
            '';
        
        return $html;   
    }
    
    public function renderHeaderTitles()
    {
        $html = 
                '<div data-role="popup" id="basic-dialog" data-dismissible="false" class="ui-content" style="max-width:400px;">'.
                    //'<div data-role="header"><h1>?</h1></div>'.
                    //'<div role="main" class="ui-content">'.                
                    '<h3 class="ui-title">?</h3>'.               
                    '<p class="basic-dialog-content">?</p>'.
                    '<a onclick="ecommerce.onClickAcceptMobileBasicDialog();" data-transition="flow" class="ui-btn ui-corner-all ui-shadow ui-icon-check ui-btn-icon-left ui-btn-inline">'.lang::trans('accept').'</a>'.
                    //'<a onclick="$(\'#basic-dialog\').popup(\'close\');" class="ui-btn ui-corner-all ui-shadow ui-icon-delete ui-btn-icon-left ui-btn-inline">'.lang::trans('cancel').'</a>'.               
                    '</div>'.
                '</div>'.
                '';
        
        return $html;
    }
    
    protected function _renderArticleRows($shoppingcart_value)
    {
        $html = '';
        
        $amount = $shoppingcart_value->amount;
        if ($amount < 1) return $html;
        
        $data = $this->_geShoppingcartData($shoppingcart_value);
        
        for ($i=1; $i<=$amount; $i++)
        {
            $html .= $this->_renderArticleRow($data);
        }
        
        return $html;
    }
    
    protected function _renderArticleRef($data)
    {
        $html = '';     
                
        return $html;
    }
    
    protected function _renderAmountColumn($data)
    {
        $html = '';     
                
        return $html;   
    }
    
    protected function _renderArticleTotalPrice($data)
    {
        return $data['rendered_price'];
    }
    
  
}