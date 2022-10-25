<?php

namespace modules\ecommerce\frontend\view\user;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\user as userController;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * User form view
 *
 * @author Dani Gilabert
 * 
 */
class user extends ecommerceView
{        
    protected $_user;
    
    public function __construct()
    {
        parent::__construct();
        $user_controller = new userController();
        $this->_user = $user_controller->getUser();
    } 
    
    public function getWebpageName()
    {
        return 'user';
    }
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/res/css/jquery/fancybox/jquery.fancybox.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-buttons.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-thumbs.css',
                
            '/modules/ecommerce/frontend/res/css/user/user.css'
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

            '/modules/ecommerce/frontend/res/js/user.js'
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
                '<div id="user-parent">'.
                    '<div id="user-parent-center">'.
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
        $html = '<form method="post" id="user-wrapper">';    
        
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
         $html =  '<div id="user">';
        
        // **************************
        // PERSONAL DATA
        // **************************         
        // Title
        $html .= 
                '<div>'.
                    '<table class="user-title title"><tr><td>'.
                        strtoupper(lang::trans('personal_data')).
                    '</td></tr></table>'.                
                '</div>';
        
        $html .=  '<div class="user-fields-wrapper">';
        
        // First name
        $html .= $this->_renderFirstName();
        
        // Last name
        $html .= $this->_renderLastName();  
        
        // Email
        $html .= $this->_renderEmail();   
        
        // Password
        $html .= $this->_renderPassword(); 
        
        // Confirm password
        $html .= $this->_renderConfirmPassword();  
        
        // Phone
        $html .= $this->_renderPhone(); 
        
        $html .= '</div>';
        // **************************
        
        // **************************
        // DELIVERY ADDRESS
        // **************************
        // Title
        $html .= 
                '<div>'.
                    '<table class="user-title title"><tr><td>'.
                        strtoupper(lang::trans('delivery_address')).
                    '</td></tr></table>'.                
                '</div>';
        
        $html .=  '<div class="user-fields-wrapper">';
        
        // Company
        //$html .= $this->_renderCompany();
        
        // Address
        $html .= $this->_renderAddress();
        
        // Postal code
        $html .= $this->_renderPostalCode();
        
        // City
        $html .= $this->_renderCity();
        
        // Country
        $html .= $this->_renderCountry();
        
        // Comments
        $html .= $this->_renderComments();
        
        $html .= 
                '<div class="label-info">'.
                    '*&nbsp;'.lang::trans('mandatory_fields').
                '</div>'; 
        
        // Newsletters checkbox option
        $html .= $this->_renderNewslettersOption();
        
        $html .= '</div>';
        // **************************
        
        // Action button
        $html .= $this->_renderActionButton();
        
        // End form
         $html .=  '</div>';
        
        return $html;
    }
    
    private function _renderFirstName()
    {
        // Render first name field
        $html = '<div class="label">'.lang::trans('firstname').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="user-field-firstname" '.
                    'class="field" '.
                    'name="firstName" '.
                    'type="text" '.
                    'value="'.$this->_user->firstName.'" '.
                '>';
        
        return $html;
    }
    
    private function _renderLastName()
    {
        // Render last name field
        $html = '<div class="label">'.lang::trans('lastname').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="user-field-lastname" '.
                    'class="field" '.
                    'name="lastName" '.
                    'type="text" '.
                    'value="'.$this->_user->lastName.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderEmail()
    {
        // Render email field
        $html = '<div class="label">'.lang::trans('email').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="user-field-email" '.
                    'class="field" '.
                    'name="email" '.
                    'type="text" '.
                    'value="'.$this->_user->code.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderPassword()
    {
        // Render password field
        $html = '<div class="label">'.lang::trans('password').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="user-field-password" '.
                    'class="field" '.
                    'name="password" '.
                    'type="password" '.
                    'value="'.$this->_user->password.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderConfirmPassword()
    {
        // Render confirm password field
        $html = '<div class="label">'.lang::trans('repeat_password').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'id="user-field-confirm-password" '.
                    'class="field" '.
                    'name="confirmPassword" '.
                    'type="password" '.
                    'value="'.$this->_user->password.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderPhone()
    {
        // Render phone field
        $html = '<div class="label">'.lang::trans('phone').'</div>';
        $html .= 
                '<input '.
                    'id="user-field-phone" '.
                    'class="field" '.
                    'name="phone" '.
                    'type="text" '.
                    'onkeypress=\'validateNumber(event)\' '.
                    'value="'.$this->_user->phone.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderCompany()
    {
        // Render company field
        $html = '<div class="label">'.lang::trans('company').'</div>';
        $html .= 
                '<input '.
                    'id="user-field-company" '.
                    'class="field" '.
                    'name="company" '.
                    'type="text" '.
                    'value="'.$this->_user->company.'" '.
                '>';  
        
        return $html;
    }
    
    private function _renderAddress()
    {
        // Render address field
        $html = '<div class="label">'.lang::trans('address').'</div>';
        $html .= 
                '<input '.
                    'id="user-field-address" '.
                    'class="field" '.
                    'name="address" '.
                    'type="text" '.
                    'value="'.$this->_user->address.'" '.
                '>';  
        
        return $html;
    }
    
    private function _renderPostalCode()
    {
        // Render postal code field
        $html = '<div class="label">'.lang::trans('postal_code').'</div>';
        $html .= 
                '<input '.
                    'id="user-field-postalcode" '.
                    'class="field" '.
                    'name="postalCode" '.
                    'type="text" '.
                    'onkeypress=\'validateNumber(event)\' '.
                    'value="'.$this->_user->postalcode.'" '.
                '>';  
        
        return $html;
    }
    
    private function _renderCity()
    {
        // Render city field
        $html = '<div class="label">'.lang::trans('city').'</div>';
        $html .= 
                '<input '.
                    'id="user-field-city" '.
                    'class="field" '.
                    'name="city" '.
                    'type="text" '.
                    'value="'.$this->_user->city.'" '.
                '>';  
        
        return $html;
    }
    
    private function _renderCountry()
    {
        // Render country field
        $current_lang = lang::getCurrentLanguage();
        $countries_list = helpers::getCountriesList($current_lang);
        $country['ES'] = $countries_list['ES'];
        
        $html = '<div class="label">'.lang::trans('country').'</div>';
        $html .= 
                '<select '.
                    'id="user-field-country" '.
                    'class="field" '.
                    'name="country" '.
                '>';
        foreach ($country as $country_key => $country_value) {
            if ($country_key == $this->_user->country)
            {
                $html .= '<option value="'.$country_key.'" selected>'.$country_value.'</option>';
            }
            else
            {
                $html .= '<option value="'.$country_key.'">'.$country_value.'</option>';
            }
        }
        $html .= '</select>';       
        
        return $html;
    }
    
    private function _renderComments()
    {
        // Render comments field
        $html = '<div class="label">'.lang::trans('additional_info_to_delibery').'</div>';
        $html .= 
                '<textarea '.
                    'id="user-field-comments" '.
                    'class="field" '.
                    'name="comments" '.
                '>'.$this->_user->comments.'</textarea>';   
        
        return $html;
    }
    
    private function _renderNewslettersOption()
    {
        // Render the newsletters checkbox option
        $checked = $this->_user->newsletters;
        $html = 
                '<label class="checkbox-label">'.
                    '<input '.
                        'id="user-field-newsletters" '.
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
        $html = '<div id="user-action-button-wapper">'; 
        $html .= 
                '<button '.
                    'type="submit" '.
                    'class="user-action-button '.
                           'button '.
                           'button-ordering" '.
                '>'.
                    lang::trans('update_data').
                '</button>';             
        
        $html .= '</div>';    
        
        return $html;  
    }    
    
    public function renderWindowAfterSubmit($success)
    {
        // Start render
        $html = '<div id="user-message-after-submit" >';
        
        if ($success)
        {
            $html .= 
                    '<div id="user-message-after-submit-success" class="label-info" >'.
                        lang::trans('your_data_have_been_saved_successfully').'!'.
                    '</div>'.
                    '';            
        }
        else
        {
            $html .= 
                    '<div id="user-message-after-submit-unsuccess" >'.
                        lang::trans('error_updating_user_data').'!'.
                    '</div>'.
                    '';             
        }
        
        // End render
        $html .= '</div>';         
  
        return $html;
    }  
    
}