<?php

namespace modules\ecommerce\frontend\view;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\payment as paymentController;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Payment (Customer) form view
 *
 * @author Dani Gilabert
 * 
 */
class payment extends ecommerceView
{ 
    public $payment_failed_msg = null;
    public $credit_card_options = array();
    public $clicktopay_options = array();
    public $iupay_options = array();
    public $paypal_options = array();
    
    protected $_payment_controller;
    
    public function __construct()
    {
        parent::__construct();
        $this->_payment_controller = new paymentController();
    }
    
    public function getWebpageName()
    {
        return 'payment';
    }
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/res/css/payment/payment.css',
            '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/payment/payment.css',
            '/modules/ecommerce/frontend/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/res/css/common/action-buttons.css'
        );
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }
    
    public function getDevelopmentHeadScriptsPaths()
    {
        $ecommerce_scripts = $this->_getHeadEcommerceScriptsPaths();

        $scripts = array(
            '/res/js/jquery/jquery.validate-1.9.0.min.js',
            
            '/modules/ecommerce/frontend/res/js/payment.js'
        );

        $ret = array_merge($ecommerce_scripts, $scripts);      
        
        return $ret;
    }
    
    protected function _addJavascriptVars()
    {
        // Javascript vars and messages
        $html = $this->_renderHeadEcommerceJavascriptVars();
        
        // Messages
        $html .= 
                '<script type="text/javascript">'.PHP_EOL.
                    'var msg_required_field = "'.lang::trans('required_field').'";'.PHP_EOL.
                    'var msg_please_enter_numeric_value_without_spaces = "'.lang::trans('please_enter_numeric_value_without_spaces').'";'.PHP_EOL.
                    'var msg_processing_your_order = "'.lang::trans('processing_your_order').'";'.PHP_EOL.
                    'var msg_payment_ways = "'.lang::trans('payment_ways').'";'.PHP_EOL.
                    'var msg_select_payment_way = "'.lang::trans('select_payment_way').'";'.PHP_EOL.
                    'var msg_payment_failed_title = "'.lang::trans('payment_failed_title').'";'.PHP_EOL.
                    'var msg_payment_failed_msg = "'.$this->payment_failed_msg.'";'.PHP_EOL.
                '</script>'.PHP_EOL.
                '';   
        
        // Paypal vars
        $html .= 
                '<script type="text/javascript">'.PHP_EOL.
                    'var payment_way = "'.$this->_payment_controller->getPaymentWay().'";'.PHP_EOL.
                '';
        if (!empty($this->paypal_options))
        {
            $html .= 
                    'var paypal_env = "'.$this->paypal_options['paypal_env'].'";'.PHP_EOL.
                    'var paypal_locale = "'.$this->paypal_options['paypal_locale'].'";'.PHP_EOL.
                    'var paypal_total_price = "'.$this->paypal_options['paypal_total_price'].'";'.PHP_EOL.
                    '';                    
        }
        $html .= 
                '</script>'.PHP_EOL.
                '';    
        
        // Paypal script
        if (!empty($this->paypal_options))
        {
            $html .= '<script type="text/javascript" src="https://www.paypalobjects.com/api/checkout.js"></script>';
        }
        
        return $html;
    }    
    
    public function renderStartContent()
    {
        $html = 
                '<div id="payment">'.
                    '<div id="payment-center">'.
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
    
    protected function _renderBasicDialog() 
    {
        $html = '<div id="basic-dialog" style="display:none"></div>';
        return $html;
    }
    
    public function renderForm()
    {
        $html = '';
        
        // Show a basic dialog
        $html .= $this->_renderBasicDialog();
        
        // Start
        $html .=  '<div id="payment-content">';
        
        // Title
        $html .= 
                '<div>'.
                    '<table class="payment-title title"><tr><td>'.
                        strtoupper(lang::trans('payment_ways')).
                    '</td></tr></table>'.                
                '</div>';
        
        // Payment ways
        $html .= 
                '<div class="payment-paymentways-wrapper">';
                
        if (!empty($this->credit_card_options))
        {
            $html .= 
                    // Card
                    $this->_renderCard(
                        'card',
                        mb_strtoupper(lang::trans('credit_card')),
                        ''
                    );     
        }                 
        
        if (!empty($this->clicktopay_options))
        {
            $html_content = $this->_renderClickToPayTooltip();
            $more_info_link = $this->_renderClickToPayInfo($html_content);
                                    
            $html .= 
                    // Click to pay
                    $this->_renderCard(
                        'clicktopay',
                        mb_strtoupper(lang::trans('clicktopay')).' ('.mb_strtoupper(lang::trans('credit_card')).')',
                        '<span class="label-info">'.
                            lang::trans('clicktopay_expiration_date_card_info').' :'.
                            ' <b>'.$this->_payment_controller->getCardExpirationDate().'</b>'.
                        '</span>',
                        $more_info_link                            
                    );
        }
        
        if (!empty($this->iupay_options))
        {
            $html .= 
                    // iupay
                    $this->_renderIUPAY();        
        }        
                
        if (!empty($this->paypal_options))
        {
            $html .= 
                    // PayPal
                    $this->_renderPaypal();            
        }

                
        if ($this->_ecommerce_controller->isDevelopment())
        {
            $html .= 
                    // Fake
                    $this->_renderFake();                    
        }
             
        $html .= 
                '</div>';
                     
        // Accept policies
        $html .= $this->_renderAcceptPolicies();

        // Submit buttons
        $html .= $this->_renderSubmitButtons();
        
        // End
        $html .=  '</div>';
         
        return $html;
    }
    
    protected function _renderClickToPayInfo($html_content) 
    {
        $_content_tag = '_content="'.htmlentities($html_content).'" ';
        $html = 
            '<a id="payment-paymentway-clicktopay-moreinfo" '.$_content_tag.'>'.
                '+info'.
            '</a>';
        
        return $html;
    }
    
    private function _renderClickToPayTooltip()
    {
        $current_lang = lang::getCurrentLanguage();
        $html = '';
        
        // Start render
        $html .= '<div id="payment-paymentway-clicktopay-tooltip">';
     
        if ($current_lang === 'es' || $current_lang === 'en')
        {
            $html .= "
                <b>PAGO EN 1-CLIC</b>
                <br><br>
                Cuando se realiza el pago por primera vez en Deemm, el TPV Virtual (entidad bancaria) recoge los datos de la tarjeta y los guarda de forma segura, devolviendo a Deemm una referencia que identifica unívocamente los datos de cliente. Deemm guarda esta referencia asociada a la tarjeta, como un dato más, sin riesgo, porque no es el número de una tarjeta real.
                <br><br>
                Para siguientes compras, al volver a realizar el pago en 1-clic, no será necesario tener que teclear de nuevo los datos de la tarjeta. Deemm envía a la entidad bancaria la referencia que identifica la tarjeta y el TPV Virtual hace el resto.
            ";
        }
        else
        {
            $html .= "
                <b>PAGAMENT EN 1-CLIC</b>
                <br><br>
                Quan es realitza el pagament per primer cop a Deemm, el TPV Virtual (entitat bancària) recull les dades de la targeta i els guarda de manera segura, retornant a Deemm una referència que identifica unívocament les dades de client. Deemm guarda aquesta referència associada a la targeta, com una dada més, sense risc, perquè no és el número d'una targeta real.
                <br><br>
                Per següents compres, en tornar a realitzar el pagament en 1-clic, no serà necessari haver de teclejar de nou les dades de la targeta. Deemm envia a l'entitat bancària la referència que identifica la targeta i el TPV Virtual fa la resta.
            ";            
        }
            
        // End render
        $html .= '</div>';
        
        return $html;    
    }
    
    private function _renderCard($payment_way, $title, $additional_info, $more_info_link = null)
    {
        $html = $this->_renderPaymentWay(
            $payment_way, 
            $title,
            lang::trans('payment_card_info').
            '<div>'.
                '<table class="payment-paymentway-card-table" border="0" cellpadding="0" cellspacing="0">'.
                    '<tr>'.
                        '<td class="payment-paymentway-card-column">'.
                            '<img class="payment-paymentway-card-column-img" '.
                                'src="'."/modules/ecommerce/frontend/res/img/payment/visa-mastercard-visa-electron-maestro.png".'" />'.
                        '</td>'.
                        '<td class="payment-paymentway-card-column payment-paymentway-card-column-additionalinfo">'.
                            $additional_info.
                        '</td>'.
                    '</tr>'.
                '</table>'.
            '</div>'.
            '',
            $more_info_link
        );
        
        return $html;
    }
    
    private function _renderIUPAY()
    {
        $html = $this->_renderPaymentWay(
            'iupay', 
            'IUPAY',
            lang::trans('payment_iupay_info').
            '<div>'.
                '<img id="payment-paymentway-iupay-img" '.
                    'src="'."/modules/ecommerce/frontend/res/img/payment/iupay/iupay-logo.png".'" />'.
            '</div>'.
            ''
        );
        
        return $html;
    }    
    
    private function _renderPaypal()
    {
        $html = $this->_renderPaymentWay(
            'paypal', 
            'PAYPAL',
            lang::trans('payment_paypal_info').
            '<div>'.
                '<img id="payment-paymentway-paypal-img" '.
                    'src="'."/modules/ecommerce/frontend/res/img/payment/paypal/paypal-text-150x40.png".'" />'.
            '</div>'.
            ''
        );
        
        return $html;
    }
    
    private function _renderFake()
    {
        $html = $this->_renderPaymentWay(
            'fake', 
            'FAKE',
            "It's a fake order.".
            ''
        );
        
        return $html;
    }
    
    private function _renderPaymentWay($payment_way, $title, $content, $more_info_link = null)
    {
        $html =
                '<div id="payment-paymentway-'.$payment_way.'" class="payment-paymentway">'.
                    '<table class="payment-paymentway-table" border="0" cellpadding="0" cellspacing="0">'.
                        '<tr>'.
                            '<td class="payment-paymentway-column-radio">'.
                                '<input '.
                                    'type="radio" '.
                                    'name="paymentway" '.
                                    'id="payment-paymentway-'.$payment_way.'-radio" '.
                                    'class="payment-paymentway-radio" '.
                                    'value="'.$payment_way.'" '.
                                '>'.
                            '</td>'.
                            '<td class="payment-paymentway-column-content">'.
                                '<span class="label-info"><b>'.$title.'</b></span><br><br>'.
                                $content.
                            '</td>'.
                        '</tr>'.
                    '</table>'.
                    '<div class="payment-paymentway-mask" onclick="onClickPaymentWay(\''.$payment_way.'\')"></div>'.
                '';
        
        if (!is_null($html))
        {
            $html .= $more_info_link;
        }
        
        $html .=
                '</div>'.
                '';
        
        return $html;
    }
    
    private function _renderAcceptPolicies()
    {
        $current_lang = lang::getCurrentLanguage();
        $html = '';
        
        $privacy_policy_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'privacy-policy'));
        $privacy_policy_tag = 
                '<a href="'.$privacy_policy_url.'"'.$this->_rel_external.'>'.
                    lang::trans('privacy_policy').
                '</a>';
        
        $conditions_of_sale_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'conditions-of-sale'));
        $conditions_of_sale_tag = 
                '<a href="'.$conditions_of_sale_url.'"'.$this->_rel_external.'>'.
                    lang::trans('conditions_of_sale').
                '</a>';
                        
        $cookies_policy_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'page'), array('code' => 'cookies-policy'));
        $cookies_policy_tag = 
                '<a href="'.$cookies_policy_url.'"'.$this->_rel_external.'>'.
                    lang::trans('cookies_policy').
                '</a>';
        
        $html .= 
                '<div id="payment-paymentways-accept-policies" class="label-info">'.
                    lang::trans('accept_policies_before_payment_1').' '.$privacy_policy_tag.
                    ' '.lang::trans('accept_policies_before_payment_2').' '.$conditions_of_sale_tag.
                    ', '.lang::trans('accept_policies_before_payment_3').' '.$cookies_policy_tag.'.'.
                '</div>';
        
        return $html;        
    }
    
    private function _renderSubmitButtons()
    {
        $html = 
                '<div id="payment-submit-buttons">';
        
        if (!empty($this->credit_card_options))
        {
            $html .= 
                    $this->_renderCardSubmitButton('card', $this->credit_card_options);
        }        
        
        if (!empty($this->clicktopay_options))
        {
            $html .= 
                    $this->_renderCardSubmitButton('clicktopay', $this->clicktopay_options);
        }       
        
        if (!empty($this->iupay_options))
        {
            $html .= 
                    $this->_renderCardSubmitButton('iupay', $this->iupay_options);
        }       
        
        if (!empty($this->paypal_options))
        {
            $html .= 
                    $this->_renderPaypalSubmitButton();
        }
        
        if ($this->_ecommerce_controller->isDevelopment())
        {
            $html .= 
                    $this->_renderFakeSubmitButton();                    
        }
             
        $html .=         
                '</div>';
                
        return $html;        
    }
    
    private function _renderPaypalSubmitButton()
    {
        $html = 
                    '<div id="payment-submit-paypal-button" '.
                        'class="action-buttons-button" '.
                        'style="padding-left:25px;"'.
                    '></div>';
                
        return $html;        
    }
    
    private function _renderFakeSubmitButton()
    {
        $html = 
                '<div class="action-buttons-center-position">'.
                    '<button '.
                        'id="payment-submit-fake-button" '.
                        'type="button" '.
                        'class="action-buttons-button '.
                               'action-buttons-center-position '.
                               'button '.
                               'button-ordering" '.
                        'onclick="onFakeSubmit()" '.
                    '>'.
                        lang::trans('pay_and_finish_order').
                    '</button>'.
                '</div>'.
                '';
                
        return $html;        
    }
    
    private function _renderCardSubmitButton($payment_way, $card_params)
    {
        $url = $card_params['url'];
        $signature_version = $card_params['signature_version'];
        $merchant_parameters = $card_params['merchant_parameters'];
        $signature = $card_params['signature'];
         
        $html = 
            '<form id="payment-submit-'.$payment_way.'-form" action="'.$url.'" method="POST">'.
                '<input type="hidden" name="Ds_SignatureVersion" value="'.$signature_version.'"/>'.
                '<input type="hidden" name="Ds_MerchantParameters" value="'.$merchant_parameters.'"/>'.
                '<input type="hidden" name="Ds_Signature" value="'.$signature.'"/>'.
                '<div class="action-buttons-center-position">'.
                    '<button '.
                        'id="payment-submit-'.$payment_way.'-button" '.
                        'type="submit" '.
                        'class="action-buttons-button '.
                               'action-buttons-center-position '.
                               'button '.
                               'button-ordering" '.         
                    '>'.
                        lang::trans('pay_and_finish_order').
                    '</button>'.
                '</div>'.
            '</form>'.
        '';
                
        return $html;        
    }
    
}