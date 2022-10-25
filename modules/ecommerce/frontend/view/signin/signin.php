<?php

namespace modules\ecommerce\frontend\view\signin;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\signin as signinController;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Sign-in form view
 *
 * @author Dani Gilabert
 * 
 */
class signin extends ecommerceView
{        
    
    protected $_signin_controller;
    
    public function __construct()
    {
        parent::__construct();
        $this->_signin_controller = new signinController();
    }    
    
    public function getWebpageName()
    {
        return 'signin';
    }
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/res/css/jquery/fancybox/jquery.fancybox.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-buttons.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-thumbs.css',
                
            '/modules/ecommerce/frontend/res/css/signin/signin.css',
            '/modules/ecommerce/frontend/res/css/signin/thanks-for-signin.css'
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

            '/modules/ecommerce/frontend/res/js/signin.js'
        );

        $ret = array_merge($ecommerce_scripts, $scripts);      
        
        return $ret;
    }
    
    protected function _addJavascriptVars()
    {
        // Javascript vars and messages
        $html = $this->_renderHeadEcommerceJavascriptVars();
        
        $html .= 
                '<script type="text/javascript">'.PHP_EOL.
                    'var msg_required_field = "'.lang::trans('required_field').'";'.PHP_EOL.
                    'var msg_invalid_email = "'.lang::trans('invalid_email').'";'.PHP_EOL.
                    'var msg_please_enter_numeric_value_without_spaces = "'.lang::trans('please_enter_numeric_value_without_spaces').'";'.PHP_EOL.
                    'var msg_please_enter_same_value = "'.lang::trans('please_enter_same_value').'";'.PHP_EOL.
                    'var msg_password_too_short = "'.lang::trans('password_too_short').'";'.PHP_EOL.
                    'var msg_processing_your_request = "'.lang::trans('processing_your_request').'";'.PHP_EOL.
                '</script>'.PHP_EOL.
                ''; 
        
        return $html;
    }
    
    public function renderStartContent()
    {
        $html = 
                '<div id="signin-parent">'.
                    '<div id="signin-parent-center">'.
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
    
    public function renderStartForm()
    {
        $html = '<form method="post" id="signin-wrapper">';    
        
        return $html;
    }  
    
    public function renderEndForm()
    {
        $html = '</form>';
        
        return $html;
    }
    
    public function renderForm()
    {
        // Start form
         $html =  '<div id="signin">';
        
        // Title
        $html .= 
                '<div>'.
                    '<table class="signin-title title"><tr><td>'.
                        strtoupper(lang::trans('signin_description')).
                    '</td></tr></table>'.                
                '</div>';
        
        $html .=  '<div class="signin-fields-wrapper">';
        
        // First name
        $html .= $this->_renderFirstName($this->_signin_controller->getFirstName());
        
        // Last name
        $html .= $this->_renderLastName($this->_signin_controller->getLastName());  
        
        // Email
        $html .= $this->_renderEmail($this->_signin_controller->getEmail());   
        
        // Password
        $html .= $this->_renderPassword(); 
        
        // Confirm password
        $html .= $this->_renderConfirmPassword(); 
        
        // Captcha
        $html .= $this->_renderCaptcha(); 
                
        $html .= 
                '<div class="label-info">'.
                    '*&nbsp;'.lang::trans('mandatory_fields').
                '</div>'; 
        
        // Newsletters checkbox option
        $html .= $this->_renderNewslettersOption($this->_signin_controller->getNewsletters());
        
        $html .= '</div>';
        
        // Action button
        $html .= $this->_renderActionButton();
        
        // Legal text
        $html .= $this->_renderLegalText();
        
        // End form
         $html .=  '</div>';
        
        return $html;
    }
    
    private function _renderFirstName($value)
    {
        // Render first name field
        $html = '<div class="label">'.lang::trans('firstname').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="signin-field-firstname" '.
                    'class="field" '.
                    'name="firstName" '.
                    'type="text" '.
                    'value="'.$value.'" '.
                '>';
        
        return $html;
    }
    
    private function _renderLastName($value)
    {
        // Render last name field
        $html = '<div class="label">'.lang::trans('lastname').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="signin-field-lastname" '.
                    'class="field" '.
                    'name="lastName" '.
                    'type="text" '.
                    'value="'.$value.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderEmail($value)
    {
        // Render email field
        $html = '<div class="label">'.lang::trans('email').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="signin-field-email" '.
                    'class="field" '.
                    'name="email" '.
                    'type="text" '.
                    'value="'.$value.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderPassword()
    {
        // Render password field
        $html = '<div class="label">'.lang::trans('password').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="signin-field-password" '.
                    'class="field" '.
                    'name="password" '.
                    'type="password" '.
                    'value="'.''.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderConfirmPassword()
    {
        // Render confirm password field
        $html = '<div class="label">'.lang::trans('repeat_password').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="signin-field-confirm-password" '.
                    'class="field" '.
                    'name="confirmPassword" '.
                    'type="password" '.
                    'value="'.''.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderCaptcha()
    {
        // Render captcha
        $captcha_src = 
                $this->_signin_controller->getUrl().
                '/index.php?'.
                'controller=modules\\ecommerce\\frontend\\controller\\captcha&method=renderCaptcha';
        $html = 
                '<div>'.
                    '<img '.
                        'id="signin-captcha-img" '.
                        'src="'.$captcha_src.'" '.
                        'alt="" '.
                    '/>'.                             
                    '<img '.
                        'id="signin-captcha-refresh" '.
                        'src="/modules/ecommerce/frontend/res/img/refresh-1.png" '.
                        'alt="" '.
                        'title="'.lang::trans('refresh').'" '.
                        'onClick="refreshCaptcha();" '.  
                    '/>'.  
                '</div>';
        $html .= '<div class="label">'.lang::trans('write_text_you_see_in_the_image').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="signin-field-captcha" '.
                    'class="field" '.
                    'name="captcha" '.
                    'type="text" '.
                    'value="'.''.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderNewslettersOption($checked)
    {
        // Render the newsletters checkbox option
        $html = 
                '<label class="checkbox-label">'.
                    '<input '.
                        'id="signin-field-newsletters" '.
                        'class="checkbox checkbox-multi-items" '.
                        'type="checkbox" '.
                        (($checked)? 'checked ' : '').
                    '/>'.
                    '&nbsp&nbsp'.lang::trans('sendme_newsletters_with_secret_offers').
                '</label>';           
        
        return $html;  
    }
    
    private function _renderActionButton()
    {
        // Render the action button
        $html = '<div id="signin-action-button-wapper">'; 
        $html .= 
                '<button '.
                    'type="submit" '.
                    'class="signin-action-button '.
                           'button '.
                           'button-ordering" '.
                '>'.
                    lang::trans('signin_description').
                '</button>';             
        
        $html .= '</div>';    
        
        return $html;  
    }
    
    private function _renderLegalText()
    {
        // Render the legal text
        $current_lang = lang::getCurrentLanguage();
        
        $privacy_policy_url = $this->_signin_controller->getUrl(array($current_lang, 'page'), array('code' => 'privacy-policy'));
        $privacy_policy_tag = 
                '<a href="'.$privacy_policy_url.'">'.
                    lang::trans('privacy_policy').
                '</a>';       
        $html = 
                '<div id="signin-fields-legal-info">'.
                    lang::trans('i_have_read_and_accept_the')." ".
                    $privacy_policy_tag.
                    ".".
                '</div>';
        
        return $html;  
    }
    
}