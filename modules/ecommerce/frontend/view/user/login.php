<?php

namespace modules\ecommerce\frontend\view\user;

// Controllers
use core\device\controller\device;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce as ecommerceController;

/**
 * Login view
 *
 * @author Dani Gilabert
 * 
 */
class login
{       
    
    protected $_ecommerce_controller;
    
    public function __construct()
    {
        $this->_ecommerce_controller = new ecommerceController();
    }
    
    public function renderLogoutTooltip($user_name)
    {
        $html = '';
        
        // Start render
        $html .= '<div id="login-tooltip">';
        
        $html .= '<div class="login-tooltip-title">'.$user_name.', '.lang::trans('you_have_already_active_session').'</div>';
        $html .= '<div class="login-tooltip-info label-info">'.lang::trans('do_you_wanna_close').'</div>';

        // Login button
        $html .= 
                '<button '.
                    'type="button" '.                        
                    'class="'.
                        'button '.
                        'button-ordering '.
                        'login-tooltip-button'.
                        '" '.
                    'onClick="logout()" '.
                '>'.
                    lang::trans('close_session').
                '</button>'.
                '';
            
        // End render
        $html .= '</div>';
        
        return $html;
    }
    
    public function renderLoginTooltip()
    {
        $html = '';
        $current_lang = lang::getCurrentLanguage();
        
        // Start render
        $html .= '<div id="login-tooltip">';
        
        $html .= '<div class="login-tooltip-title">'.lang::trans('start_session_in_your_account').'</div>';
        $html .= '<div class="login-tooltip-info label-info">'.lang::trans('so_you_will_shop_faster').'</div>';
        
        // Email field
        $html .= '<div class="label">'.lang::trans('email').'</div>';
        $html .= 
                '<input '.
                    'id="login-tooltip-loginuser" '.
                    'class="login-tooltip-fields-field field" '.
                    'name="loginuser" '.
                    'type="text" '.
                    'value="'.''.'" '.
                '>';
        
        // Forgotten password
        $forgottenpassword_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'forgottenpassword'));
        $html .= 
                '<div class="label">'.
                    '<span>'.lang::trans('password').'</span>';
        
        if (!device::isMobileVersion())
        {
            $html .=
                    '<a class="login-tooltip-forget-password" href="'.$forgottenpassword_url.'">'.
                        lang::trans('have_you_forgotten_your_password').
                    '</a>';                    
        }
        $html .=   
                '</div>';
        
        // Password field
        $html .= 
                '<input '.
                    'id="login-tooltip-loginpassword" '.
                    'class="login-tooltip-fields-field field" '.
                    'name="loginpassword" '.
                    'type="password" '.
                    'value="'.''.'" '.
                '>';
        
        // Error message
        $html .= '<div id="login-tooltip-error-msg">'.'</div>';

        // Login button
        $html .= 
                '<button '.
                    'type="button" '.                        
                    'class="'.
                        'button '.
                        'button-ordering '.
                        'login-tooltip-button'.
                        '" '.
                    'onClick="login()" '.
                '>'.
                    lang::trans('start_session').
                '</button>'.
                '';
        
        // Sign-in
        if (!device::isMobileVersion())
        {
            $signin_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'signin'));
            $html .= 
                    '<div class="label-info">'.
                        lang::trans('dont_you_have_any_account').'&nbsp;'.
                            '<a href="'.$signin_url.'">'.
                                lang::trans('make_your_account').'!'.
                            '</a>'.            
                    '</div>';            
        }
            
        // End render
        $html .= '</div>';
        
        return $html;        
    }
    
}