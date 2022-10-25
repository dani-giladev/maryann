<?php

namespace modules\ecommerce\frontend\view\mailing;

// Controllers
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\mailing\mail as mailView;

/**
 * Forgotten password email view
 *
 * @author Dani Gilabert
 * 
 */
class forgottenPassword extends mailView
{   
    public $user; 

    protected function _renderHeadStyles()
    {
        $html = $this->_renderHeadEcommerceStyles();
        
        $html .= 
                '<style type="text/css">'.
                    $this->getStyleSheetFileContent("modules/ecommerce/frontend/res/css/mailing/mailing.css").
                    $this->getStyleSheetFileContent("modules/ecommerce/frontend/res/css/forgottenpassword.css").
                '</style>';
        
        return $html;
    }               
    
    protected function _renderHeadScripts($version)
    {
        $html = $this->_getHeadEcommerceScriptsPaths();
        
        return $html;
    } 
    
    protected function _renderPreHeader()
    {
        $html = '';     
        
        $html .=  
                '<div id="fp-main-text" class="label-info">'.
                    lang::trans('you_ask_provisional_password').'.'.
                '</div>'.
                '</br>'.
                ''; 
        
        return $html; 
    }
    
    public function renderBodyContent($website = null)
    {
        $html = '';      
        
        $html .= 
                $this->_wrapField(lang::trans('your_provisional_password_is'), $this->user->provisionalPassword).
                '</br>'.
                '';
        
        $html .=  
                lang::trans('forgotten_password_email_info').
                '</br>'.
                ''; 
        
        return $html;
    }
    
    private function _wrapField($title, $text, $text_color = null)
    {
        if (!is_null($text_color))
        {
            $text = '<font color="'.$text_color.'">'.$text.'</font>';
        }
        
        $html = 
                '<div class="fp-field-wrapper">'.
                    '<label class="label">'.$title.'&nbsp;:&nbsp;</label>&nbsp;&nbsp;&nbsp;'.
                    '<label class="label-info"><b>'.$text.'</b></label>'.
                '</div>'.
                '';        
        
        return $html;
    }
    
}