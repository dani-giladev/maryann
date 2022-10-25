<?php

namespace modules\ecommerce\frontend\mobile\view;

// Controllers
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\payment as paymentView;

/**
 * Payment form mobile view
 *
 * @author Dani Gilabert
 * 
 */
class payment extends paymentView
{   
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/mobile/res/css/payment/payment.css',
            '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/payment/payment.css',
            
            '/modules/ecommerce/frontend/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/action-buttons.css'
        );   
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }
    
    protected function _renderBasicDialog() 
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
    
    protected function _renderClickToPayInfo($html_content) 
    {
        $html = 
            '<a id="payment-paymentway-clicktopay-moreinfo" href="#popup-clicktopay-moreinfo" data-rel="popup" data-position-to="window" data-transition="pop">'.
                '+info'.
            '</a>'.                  
            '<div data-role="popup" id="popup-clicktopay-moreinfo" data-theme="a" class="ui-corner-all" style="max-width:400px;">'.
                '<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>'.
                '<div style="padding:10px;">'.
                    $html_content.
                '</div>'.
            '</div>';
        
        return $html;
    }
    
}