<?php

namespace modules\ecommerce\frontend\view;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;
use modules\ecommerce\frontend\view\shoppingcart\summary as shoppingcartSummaryView;

/**
 * Bond page webpage view
 *
 * @author Dani Gilabert
 * 
 */
class bond extends ecommerceView
{ 
    public $sale_data; 
    public $is_email = false;
    public $is_validation = false;
    
    public function getWebpageName()
    {
        return 'bond';
    }
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/res/css/bond.css',
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
                '';
        
        return $html;
    } 
    
    public function renderEndContent()
    {
        $html = 
                '';  
        
        return $html;
    }      
    
    public function renderBondContent()
    {
        $html = 
                '<div id="bond-wrapper">'.
                    '<div id="bond-wrapper-center">'.
                        '<div id="bond-content">'.
                
                            $this->renderContent().
                
                        '</div>'.
                    '</div>'.
                '</div>'.                
                '';
        
        return $html;
    } 
    
    public function renderContent()
    {
        // Start content
        $html = '';          
        
        // Success text
        $html .= $this->_renderSuccessText();
        
        // Thanks
        $html .= $this->_renderThanks();
        
        // Order number
        $html .= $this->_renderOrderNumber();        
        
        // Personal data
        $html .= $this->_renderPersonalData();       
        
        // Shipping data
        $html .= $this->_renderShippingData();
        
        // Shopping cart (articles)
        $html .= $this->_renderShoppingCart(); 
        
        // End content
        $html .= ''; 
        
        return $html;
    }
    
    public function renderSuccessText() {
        
        return   
                '<label id="bond-success-text" class="label-info">'.
                    '<b>'.strtoupper(lang::trans("congratulations")).'!'.'</b>'.
                    '&nbsp;'.
                    lang::trans("your_order_has_been_processed_successfully").".".
                '</label>'.
                '</br>'.
                '</br>'; 
        
    }
    
    protected function _renderSuccessText()
    {
        $html = '';   
        
        if ($this->is_validation || $this->is_email)
        {
            return $html;
        }
        
        $html .= $this->renderSuccessText();
        
        return $html;
    }
    
    public function renderThanks()
    {
        $html = ''; 
        
        $html .=  
                '<b>'.lang::trans('thanks_for_trusting_us').'</b>'.
                '&nbsp;'.
                lang::trans('now_we_outline_order_details');
        
        if (!$this->is_email)
        {
            $html .= '&nbsp;'.lang::trans('you_should_have_received_by_email');
            
            if (strpos(strtolower($this->sale_data->email), "hotmail"))
            {
                $html .= 
                        '</br></br>'.
                        '<font color="silver">'.
                            lang::trans('if_you_havent_received_this_email').'</br>'.
                        '</font>';
            }
        }
                
        $html .=                  
                '</br>'.
                '</br>';
        
        return $html;        
    }
    
    protected function _renderThanks()
    {
        $html = '';   
        
        if ($this->is_validation || $this->is_email)
        {
            return $html;
        }
        
        $html .= $this->renderThanks();
        
        return $html;
    }
    
    private function _renderOrderNumber()
    {
        $html = '';   
        
        if ($this->is_validation)
        {
            return $html;
        }
        
        $html .= 
                $this->_wrapField(lang::trans('order_number'), $this->sale_data->code).
                '</br>'.
                '';
        
        return $html;
    }
    
    private function _renderPersonalData()
    {
        $firstname = $this->sale_data->firstName;
        $lastname = $this->sale_data->lastName;
        $email = $this->sale_data->email;  
        $phone = $this->sale_data->phone;  
        
        $html = 
                '<div class="bond-title title">'.
                    mb_strtoupper(lang::trans("personal_data")).
                '</div>'.
                '</br>'.
                '';
        
        $html .= 
                $this->_wrapField(lang::trans('name'), $firstname.'&nbsp;'.$lastname).
                $this->_wrapField(lang::trans('email'), $email, 'blue').
                $this->_wrapField(lang::trans('phone'), $phone).
                '</br>'.
                '';
        
        return $html;
    }
    
    private function _renderShippingData()
    {
        $current_lang = lang::getCurrentLanguage();
        
        //$company = $this->sale_data->company;
        $address = $this->sale_data->address;
        $postalcode = $this->sale_data->postalCode;
        $city = $this->sale_data->city;
        $countries_list = helpers::getCountriesList($current_lang);
        $country = $countries_list[$this->sale_data->country];
        $comments = $this->sale_data->comments;
        
        $html = 
                '<div class="bond-title title">'.
                    mb_strtoupper(lang::trans("delivery_address")).
                '</div>'.
                '</br>'.
                '';
        
        $html .= 
                $this->_wrapField(lang::trans('address'), $address).
                $this->_wrapField(lang::trans('postal_code'), $postalcode).
                $this->_wrapField(lang::trans('city'), $city).
                $this->_wrapField(lang::trans('country'), $country).
                $this->_wrapField(lang::trans('comments'), $comments, "red").
                '</br>'.
                '';  
        
        $html .= '</span>';
        
        return $html;
    }
    
    private function _renderShoppingCart()
    {
        $html = 
                '<div class="bond-title title">'.
                    mb_strtoupper(lang::trans("your_order_description")).
                '</div>'.
                '</br>'.
                '';
        
        $shoppingcart_view = new shoppingcartSummaryView();
        $shoppingcart_view->is_email = $this->is_email;
        $shoppingcart = $this->sale_data->shoppingcart;  
        $html .= $shoppingcart_view->renderArticles($shoppingcart);
        $html .= $shoppingcart_view->renderTotals(
                $this->sale_data->totalPrice, 
                $this->sale_data->shippingCost, 
                $this->sale_data->voucher, 
                $this->sale_data->voucherDiscount, 
                $this->sale_data->secondUnitDiscount, 
                $this->sale_data->finalTotalPrice
        );
        
        return $html;
    }
    
    private function _wrapField($title, $text, $text_color = null)
    {
        if (!is_null($text_color))
        {
            $text = '<font color="'.$text_color.'">'.$text.'</font>';
        }
        
        $html = 
                '<div class="bond-field-wrapper">'.
                    '<span class="label">'.$title.'&nbsp;:&nbsp;</span>&nbsp;&nbsp;&nbsp;'.
                    '<span class="label-info"><b>'.$text.'</b></span>'.
                '</div>'.
                '';        
        
        return $html;
    }
    
}