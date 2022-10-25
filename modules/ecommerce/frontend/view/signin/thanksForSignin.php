<?php

namespace modules\ecommerce\frontend\view\signin;

// Controllers
use modules\ecommerce\frontend\controller\lang;

/**
 * Window view when you've just sign-in successfully
 *
 * @author Dani Gilabert
 * 
 */
class thanksForSignin
{      
    
    public function renderWindow($newsletters)
    {
        // Start render
        $html = '<div id="thanks-for-signin" >';
        
        $html .= 
                '<div id="thanks-for-signin-title" >'.
                    lang::trans('thanks_for_signin').'!'.
                '</div>'.
                '';
        
        $html .= 
                '<div id="thanks-for-signin-description" >'.
                    lang::trans('henceforth_you_will_enjoy').
                '</div>'.
                '';
        
        $html .= 
                '<ul id="thanks-for-signin-options" >'.
                    '<li>'.lang::trans('make_your_shopping_faster').'</li>'.
                    ($newsletters? ('<li>'.lang::trans('receive_secret_offers').'</li>') : '').
                '</ul>'.
                '';
        
        // End render
        $html .= '</div>';         
  
        return $html;
    }   
}