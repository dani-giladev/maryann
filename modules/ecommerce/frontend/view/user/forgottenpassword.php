<?php

namespace modules\ecommerce\frontend\view\user;

// Controllers
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Forgotten password view
 *
 * @author Dani Gilabert
 * 
 */
class forgottenpassword extends ecommerceView
{    
    
    public function getWebpageName()
    {
        return 'forgottenpassword';
    }     
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/res/css/jquery/fancybox/jquery.fancybox.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-buttons.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-thumbs.css',
                
            '/modules/ecommerce/frontend/res/css/user/forgotten-password.css'
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

            '/modules/ecommerce/frontend/res/js/forgottenpassword.js'
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
                    'var msg_processing_your_request = "'.lang::trans('processing_your_request').'";'.PHP_EOL.
                '</script>'.PHP_EOL.
                ''; 
        
        return $html;
    }
    
    public function renderStartContent()
    {
        $html = 
                '<div id="forgotten-password-parent">'.
                    '<div id="forgotten-password-parent-center">'.
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
        $html = '<form method="post" id="forgotten-password-wrapper">';    
        
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
         $html =  '<div id="forgotten-password">';
        
        // **************************
        // PERSONAL DATA
        // **************************         
        // Title
        $html .= 
                '<div>'.
                    '<table class="forgotten-password-title title"><tr><td>'.
                        strtoupper(lang::trans('have_you_forgotten_your_password')).
                    '</td></tr></table>'.                
                '</div>';
        
        $html .=  '<div class="forgotten-password-fields-wrapper">'; 
        
        // Email
        $html .= $this->_renderEmail();   
        
        $html .= 
                '<div class="label-info">'.
                    '*&nbsp;'.lang::trans('enter_email_to_restore_password').
                '</div>'; 
        
        $html .= '</div>';
        
        // Action button
        $html .= $this->_renderActionButton();
        
        // End form
         $html .=  '</div>';
        
        return $html;
    }
    
    private function _renderEmail()
    {
        // Render email field
        $html = '<div class="label">'.lang::trans('email').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="forgotten-password-field-email" '.
                    'class="field" '.
                    'name="email" '.
                    'type="text" '.
                    'value="'.''.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderActionButton()
    {
        // Render the action button
        $html = '<div id="forgotten-password-action-button-wapper">'; 
        $html .= 
                '<button '.
                    'type="submit" '.
                    'class="forgotten-password-action-button '.
                           'button '.
                           'button-ordering" '.
                '>'.
                    lang::trans('send').
                '</button>';             
        
        $html .= '</div>';    
        
        return $html;  
    }    
    
    public function renderWindowAfterSubmit($success)
    {
        // Start render
        $html = '<div id="forgotten-password-message-after-submit" >';
        
        $html .= 
                '<div id="forgotten-password-message-after-submit-title" >'.
                    lang::trans('password_recovery').
                '</div>'.
                '';
        
        if ($success)
        {
            $html .= 
                    '<div id="forgotten-password-message-after-submit-success" class="label-info">'.
                        lang::trans('check_your_email_we_have_sent_instructions').
                    '</div>'.
                    '';            
        }
        else
        {
            $html .= 
                    '<div id="forgotten-password-message-after-submit-unsuccess" >'.
                        lang::trans('error_sending_email').
                    '</div>'.
                    '';             
        }
        
        // End render
        $html .= '</div>';         
  
        return $html;
    }  
    
}