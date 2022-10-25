<?php

namespace modules\ecommerce\frontend\view;

// Controllers
use core\config\controller\config;
use core\device\controller\device;
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Footer view
 *
 * @author Dani Gilabert
 * 
 */
class footer extends ecommerceView
{   
    
    public function renderFooter($website)
    {
        $html = '';
        $html .= $this->_renderFirstRow($website);
        $html .= $this->_render4Icons();
        $html .= $this->_renderPayment();
        $html .= $this->_renderLegal();
        return $html;
    }

    protected function _renderFirstRow($website)
    {
        $html = '';
        
        // Start
        $html .= 
                '<div id="footer-firstrow">'.
                    '<div class="footer-center page-center">';
        
        // Start table
        $html .= 
                '<table id="footer-firstrow-table" border="0" cellpadding="0" cellspacing="0">'.
                    '<tr>'.
                '';
        
        // Logo
        $html .=         
                '<td class="footer-firstrow-table-column footer-firstrow-table-column-border-right">'.
                    $this->_getLogo($website).
                '</td>'.
                '';
                
        // Attention to customer
        $html .=         
                '<td class="footer-firstrow-table-column footer-firstrow-table-column-border-right">'.
                    $this->_getAttention2Customer($website).
                '</td>'.
                '';
        
        // Info column
        $html .=        
                '<td class="footer-firstrow-table-column">'.
                    $this->_getInfo().
                '</td>'.
                '';
        
        // End table
        $html .= 
                    '</tr>'.
                '</table>'.
                '';
        
        // End
        $html .= 
                    '</div>'.
                '</div>';
        
        return $html;
    }        
    
    protected function _getLogo($website)
    {
        $image_path = $this->_ecommerce_controller->getLogoPath($website);
        
        $html =         
                '<img id="footer-firstrow-table-column-logo-img" '.
                    'src="'.$image_path.'" />'.
                '';
        
        return $html;
    }
    
    protected function _getAttention2Customer($website)
    {
        $current_lang = lang::getCurrentLanguage();
        
        $html =         
                '<div id="footer-firstrow-table-column-attention-to-customer">'.
                    '<table border="0" cellpadding="0" cellspacing="0">'.
                        '<tr><td>'.
                            '<div id="footer-firstrow-table-column-attention-to-customer-title">'.
                                mb_strtoupper(lang::trans('customer_service')).
                            '</div>'.
                        '</td></tr>'.
                        '<tr><td>'.
                            '<div id="footer-firstrow-table-column-attention-to-customer-phone">'.
                                '<div id="footer-firstrow-table-column-attention-to-customer-phone-img"></div>'.
                                '<div id="footer-firstrow-table-column-attention-to-customer-phone-number">'.
                                    (isset($website->phone)? $website->phone : '').
                                '</div>'.
                                '<div id="footer-firstrow-table-column-attention-to-customer-phone-info">'.
                                    (isset($website->schedules->$current_lang)? $website->schedules->$current_lang : '').
                                '</div>'.                
                            '</div>'.
                        '</td></tr>'.
                        '<tr><td>'.
                            '<div id="footer-firstrow-table-column-attention-to-customer-email">'.
                                '<div id="footer-firstrow-table-column-attention-to-customer-email-img"></div>'.
                                '<div id="footer-firstrow-table-column-attention-to-customer-email-text">'.
                                    (isset($website->email)? $website->email : '').
                                '</div>'.                
                            '</div>'.
                        '</td></tr>'.
                    '</table>'.
                '</div>'.
                '';
        
        return $html;
    }
    
    protected function _getInfo()
    {
        $current_lang = lang::getCurrentLanguage();
        $rel_external = $this->getRelExternalTag();
        
        $faq_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'faq'));
        $faq_link = 
                '<div><a href="'.$faq_url.'"'.$rel_external.' class="footer-firstrow-content-link">'.
                    lang::trans('faq').
                '</a></div>';  
        $shipments_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'shipments'));
        $shipments_link = 
                '<div class="footer-firstrow-content-link-div"><a href="'.$shipments_url.'"'.$rel_external.' class="footer-firstrow-content-link">'.
                    lang::trans('shipments').
                '</a></div>';
        $devolutions_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'devolutions'));
        $devolutions_link = 
                '<div class="footer-firstrow-content-link-div"><a href="'.$devolutions_url.'"'.$rel_external.' class="footer-firstrow-content-link">'.
                    lang::trans('devolutions').
                '</a></div>';
        $sale_of_medicines_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'sale-of-medicines'));
        $sale_of_medicines_link = 
                '<div class="footer-firstrow-content-link-div"><a href="'.$sale_of_medicines_url.'"'.$rel_external.' class="footer-firstrow-content-link">'.
                    lang::trans('sale_of_medicines').
                '</a></div>';      
        
        $html =         
                '<table id="footer-firstrow-table-column-info" border="0" cellpadding="0" cellspacing="0">'.
                    '<tr>'.
                        '<td>'.
                            '<img '.
                                'src="'."/modules/ecommerce/frontend/res/img/info-icon-white-50x50.png".'" />'.    
                        '</td>'.
                        '<td class="footer-firstrow-table-column-info-links">'.
                            $faq_link.'<br>'.
                            $shipments_link.'<br>'.
                            $devolutions_link.'<br>'.
                            $sale_of_medicines_link.
                        '</td>'.
                    '</tr>'.
                '</table>'.
                '';
        
        return $html;
    }    
    
    protected function _render4Icons()
    {
        $shipping_cost = $this->renderPriceFormat(config::getConfigParam(array("ecommerce", "shipping_cost"))->value);
        $free_shipping_cost_from = $this->renderPriceFormat(config::getConfigParam(array("ecommerce", "free_shipping_cost_from"))->value, true);
        
        $html = '';
        
        // Start
        $html .= 
                '<div id="footer-4icons">'.
                    '<div class="footer-center page-center">';
        
        $html .=
                    '<table id="footer-4icons-table" border="0" cellpadding="0" cellspacing="0">'.
                        '<tr>'.
                            '<td class="footer-4icons-table-column">'.
                                '<div class="footer-4icons-wrapper">'.
                                    '<img class="footer-4icons-img" '.
                                        'src="'.'/modules/ecommerce/frontend/res/img/truck-black.png'.'" '.
                                    '/>'.
                                    '<span class="footer-4icons-text">'.
                                        lang::trans("shipping_cost").' : '.$shipping_cost.'&euro;'.'</br>'.
                                        lang::trans("free_from").' '.$free_shipping_cost_from.'&euro;'.
                                    '</span>'.                
                                '</div>'.
                            '</td>'.
                            '<td class="footer-4icons-table-column">'.
                                '<div class="footer-4icons-wrapper">'.
                                    '<img class="footer-4icons-img" '.
                                        'src="'.'/modules/ecommerce/frontend/res/img/delivery-black.png'.'" '.
                                    '/>'.
                                    '<span class="footer-4icons-text">'.
                                        lang::trans('express_shipping').'</br>'.         
                                        lang::trans('shippings_between').
                                    '</span>'.
                                '</div>'.
                            '</td>'.
                            '<td class="footer-4icons-table-column">'.
                                '<div class="footer-4icons-wrapper">'.
                                    '<img class="footer-4icons-img" '.
                                        'src="'.'/modules/ecommerce/frontend/res/img/padlock-black.png'.'" '.
                                    '/>'.
                                    '<span class="footer-4icons-text">'.
                                        lang::trans('payment').' ONLINE'.'</br>'.
                                        '100% '.lang::trans('safe').
                                    '</span>'.
                                '</div>'.
                            '</td>'.
                            '<td class="footer-4icons-table-column">'.
                                '<div class="footer-4icons-wrapper">'.
                                    '<img class="footer-4icons-img" '.
                                        'src="'.'/modules/ecommerce/frontend/res/img/heart-black.png'.'" '.
                                    '/>'.
                                    '<span class="footer-4icons-text">'.
                                        lang::trans('devolution_insurance').'</br>'.
                                        lang::trans('satisfaction_guaranteed').
                                    '</span>'.
                                '</div>'.
                            '</td>'.
                        '</tr>'.
                    '</table>'.      
                '';
        
        // End
        $html .= 
                    '</div>'.
                '</div>';
        
        return $html;
    }
    
    protected function _renderPayment()
    {
        $html = '';

        // Start
        $html .= 
                '<div id="footer-payment">'.
                    '<div class="footer-center page-center">';
        
        // Start content
        $html .= '<div id="footer-payment-content">';
        
        // Secure payment
        $html .=
                '<div id="footer-payment-content-secure-payment">'.
                    '<img id="footer-payment-content-secure-payment-img" '.
                        'src="'."/modules/ecommerce/frontend/res/img/payment/secure-payment-2.png".'" />'.
                    '<span id="footer-payment-content-secure-payment-text">'.
                        strtoupper(lang::trans('payment')).' ONLINE'.'</br>'.'100% '.strtoupper(lang::trans('safe')).
                    '</span>'.                  
                '</div>'.
                '';
        
        // Payment ways
        $html .= $this->_renderPaymentWays();
        
        // End content
        $html .= '</div>';
        
        // End
        $html .= 
                    '</div>'.
                    '<div class="footer-center footer-delimiter"/>'.
                '</div>';
        
        return $html;
    }                  
    
    protected function _renderPaymentWays()
    {
        $html = '';
        $payment_ways_params =  config::getConfigParam(array("ecommerce", "payment_ways"))->value;
        
        // Pay pal
        if (isset($payment_ways_params->paypal) && $payment_ways_params->paypal)
        {
            $html .=         
                    '<img id="footer-payment-content-paypal" '.
                        'src="'."/modules/ecommerce/frontend/res/img/payment/paypal/paypal-text-150x40.png".'" />'.               
                    '';             
        }
        
        // iupay
        if (isset($payment_ways_params->iupay) && $payment_ways_params->iupay)
        {
            $html .=         
                    '<img id="footer-payment-content-iupay" '.
                        'src="'."/modules/ecommerce/frontend/res/img/payment/iupay/iupay-logo.png".'" />'.               
                    '';             
        }
        
        // Credit cards
        if (isset($payment_ways_params->credit_card) && $payment_ways_params->credit_card)
        {
            $html .=         
                    '<img id="footer-payment-content-credit-cards" '.
                        'src="'."/modules/ecommerce/frontend/res/img/payment/visa-mastercard-visa-electron-maestro.png".'" />'.               
                    '';                 
        }  
        
        return $html;
    }    
    
    protected function _renderLegal()
    {
        $html = '';
        $current_lang = lang::getCurrentLanguage();
        $rel_external = $this->getRelExternalTag();
        
        // Start
        $html .= 
                '<div id="footer-legal">'.
                    '<div class="footer-center page-center">';
        
        // Start content
        $html .= '<div id="footer-legal-content">';
        
        $legal_notice_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'legal-notice'));
        $html .= 
                '<a href="'.$legal_notice_url.'"'.$rel_external.' class="footer-legal-content-link">'.
                    lang::trans('legal_notice').
                '</a>';
        
        $html .= '<span class="footer-legal-content-delimiter">'.'-'.'</span>';
                        
        $privacy_policy_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'privacy-policy'));
        $html .= 
                '<a href="'.$privacy_policy_url.'"'.$rel_external.' class="footer-legal-content-link">'.
                    lang::trans('privacy_policy').
                '</a>';
        
        $html .= '<span class="footer-legal-content-delimiter">'.'-'.'</span>';
                        
        $cookies_policy_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'cookies-policy'));
        $html .= 
                '<a href="'.$cookies_policy_url.'"'.$rel_external.' class="footer-legal-content-link">'.
                    lang::trans('cookies_policy').
                '</a>';
        
        $html .= '<span class="footer-legal-content-delimiter">'.'-'.'</span>';
        
        $conditions_of_sale_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'conditions-of-sale'));
        $html .= 
                '<a href="'.$conditions_of_sale_url.'"'.$rel_external.' class="footer-legal-content-link">'.
                    lang::trans('conditions_of_sale').
                '</a>';   
        
        // End content
        $html .= '</div>';
        
        // End
        $html .= 
                    '</div>'.
                '</div>';
        
        return $html;
    }
    
}