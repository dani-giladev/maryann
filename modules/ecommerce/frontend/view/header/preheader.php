<?php

namespace modules\ecommerce\frontend\view\header;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\user as userController;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;
use modules\ecommerce\frontend\view\user\login as loginView;

/**
 * Pre-header view
 *
 * @author Dani Gilabert
 * 
 */
class preheader extends ecommerceView
{
    protected $_website;
    protected $_langs;
    protected $_user_controller;
    
    public function __construct($website, $langs)
    {
        parent::__construct();
        $this->_website = $website;
        $this->_langs = $langs;
        $this->_user_controller = new userController();
    }           
    
    public function render()
    {
        $website = $this->_website;
        $html = '';
        
        // Start pre-header
        $html .= 
                '<div id="pre-header" class="pre-header">'.
                    '<div id="pre-header-center" class="page-center">';
        
        // Social media
        if (
                (isset($website->facebook) && !empty($website->facebook)) ||
                (isset($website->twitter) && !empty($website->twitter)) ||
                (isset($website->googleplus) && !empty($website->googleplus))
            )
        {
            $html .= '<div id="pre-header-socialmedia">';
            if (isset($website->facebook) && !empty($website->facebook))
            {
                $html .= '<a href="'.$website->facebook.'" target="_blank"><img src="/res/ico/facebook.png" /></a>&nbsp;';
            }
            if (isset($website->twitter) && !empty($website->twitter))
            {
                $html .= '<a href="'.$website->twitter.'" target="_blank"><img src="/res/ico/twitter.png" /></a>&nbsp;';
            } 
            if (isset($website->googleplus) && !empty($website->googleplus))
            {
                $html .= '<a href="'.$website->googleplus.'" target="_blank"><img src="/res/ico/google_plus.png" /></a>&nbsp;';
            }                    
            $html .= '</div>';              
        }
        
        // Languages selection
        $html .= $this->_renderLanguageSelection();
        
        if ($this->_skin === 'none')
        {
            $html .= $this->_renderHello();
            $html .= $this->_renderLoginOrMyAccountButton();  
            $html .= $this->_renderSigninOrLogoutButton();           
        }
        else
        {  
            $html .= $this->_renderSigninOrLogoutButton(); 
            $html .= $this->_renderLoginOrMyAccountButton();
            $html .= $this->_renderHello();             
        }
        
        // End pre-header
        $html .= '</div>'.'</div>';  
        
        return $html;
    }       
    
    private function _renderLanguageSelection()
    {
        $html = '';
        if (!isset($this->_langs) || empty($this->_langs))
        {
            return $html;
        }
        
        $current_lang = lang::getCurrentLanguage();
        
        $html_content = '<div id="pre-header-languages-tooltip">';
        foreach ($this->_langs as $lang_code => $values) {
            $html_content .= 
                    '<a href="'.$values['url'].'"'.'>'.
                        '<div class="pre-header-languages-tooltip-lang">'.
                            '<img class="pre-header-languages-tooltip-lang-img" src="'.'/res/img/flags/'.$lang_code.'.png'.'" />'.     
                            '<span class="pre-header-languages-tooltip-lang-text">'.
                                $values['name'].
                            '</span>'.
                        '</div>'.
                    '</a>'.
                    '';
        }        
        $html_content .= '</div>';
        $_content_tag = '_content="'.htmlentities($html_content).'" ';
        
        $html .= 
                '<div '.
                    'id="pre-header-languages" '.
                    'class="'.
                        'pre-header-languages'.
                        ' pre-header-menu-border-right'.
                    '" '.
                    $_content_tag.
                '>'.
                    '<img id="pre-header-languages-img" '.
                        'src="'.'/res/img/flags/'.$current_lang.'.png'.'" />'.
                '</div>'.            
                '';        
        
        return $html;
    }
    
    private function _renderHello()
    {
        // Hello (user)
        $hello_text = lang::trans('hello').'!';
        $is_logged_user = $this->_user_controller->isLoggedUser();
        if ($is_logged_user)
        {
            $user = $this->_user_controller->getUser();
            $hello_text = lang::trans('hello').',&nbsp;'.$user->firstName.'&nbsp;'.$user->lastName;
        }
        return 
                '<div id="pre-header-hello" ondblclick="ecommerce.resetAll();">'.
                    $hello_text.
                '</div>'.            
                '';        
    }
    
    private function _renderLoginOrMyAccountButton()
    {
        $html = '';
        $current_lang = lang::getCurrentLanguage();
        $is_logged_user = $this->_user_controller->isLoggedUser();
        
        if (!$is_logged_user)
        {
            // Login
            $login_view = new loginView();
            $html_content = $login_view->renderLoginTooltip();
            $_content_tag = '_content="'.htmlentities($html_content).'" '; 
            $html .= 
                    '<div '.
                        'id="pre-header-menu-login" '.
                        'class="'.
                            'pre-header-menu-login '.
                            'pre-header-menu-border-left '.
                            'pre-header-menu-border-right'.
                        '" '.
                        $_content_tag.
                    '>'.
                        lang::trans('start_session').
                    '</div>'.            
                    '';            
        }
        else
        {        
            // My account
            $user_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'user'));
            $html .= 
                    '<a href="'.$user_url.'">'.
                        '<div class="'.
                            'pre-header-menu-login '.
                            'pre-header-menu-border-left '.
                            'pre-header-menu-border-right'.
                        '">'.
                            lang::trans('me').
                        '</div>'.
                    '</a>'.
                    '';                 
        }        
        
        return $html;
    }
    
    private function _renderSigninOrLogoutButton()
    {
        $html = '';
        $current_lang = lang::getCurrentLanguage();
        $is_logged_user = $this->_user_controller->isLoggedUser();
        
        if (!$is_logged_user)
        {
            // Sign-in
            $signin_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'signin'));
            $html .= 
                    '<a href="'.$signin_url.'">'.
                        '<div class="pre-header-menu-login pre-header-menu-border-right">'.
                            lang::trans('signin').
                        '</div>'.
                    '</a>'.
                    '';             
        }
        else
        {        
            // Logout
            $html .= 
                    '<div '.
                        'class="'.
                            'pre-header-menu-login '.
                            'pre-header-menu-border-right'.
                        '" '.
                        'onClick="logout()" '.
                    '>'.
                        lang::trans('close_session').
                    '</div>'.
                    '';                 
        }        
        
        return $html;        
    }
    
}