<?php

namespace modules\ecommerce\frontend\view;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\personaldata as personaldataController;
use modules\ecommerce\frontend\controller\user as userController;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Personal (Customer) data form view
 *
 * @author Dani Gilabert
 * 
 */
class personaldata extends ecommerceView
{ 
    
    public function getWebpageName()
    {
        return 'personaldata';
    }
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/res/css/personal-data.css',
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

            '/modules/ecommerce/frontend/res/js/personaldata.js'
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
                '</script>'.PHP_EOL.
                ''; 
        
        return $html;
    }
    
    public function renderStartContent()
    {
        $html = 
                '<div id="personal-data-parent">'.
                    '<div id="personal-data-parent-center">'.
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
        $html = '<form method="post" id="personal-data-wrapper">';    
        
        return $html;
    }  
    
    public function renderEndForm()
    {
        $html = '</form>';
        
        return $html;
    }
    
    public function renderForm()
    {
        $controller = new personaldataController();
        
        // Start form
        $html =  '<div id="personal-data">';
        
        // We recomend to start session
        $user_controller = new userController();
        $is_logged_user = $user_controller->isLoggedUser();
        if (!$is_logged_user)
        {
            $html .= 
                    '<div class="personal-data-start-session-info label-info">'.
                        '¡&nbsp;'.lang::trans('purchase_faster_starting_session').'&nbsp;!'.
                    '</div>';             
        }
        
        // **************************
        // PERSONAL DATA
        // **************************
        // Title
        $html .= 
                '<div>'.
                    '<table class="personal-data-title title"><tr><td>'.
                        strtoupper(lang::trans('personal_data')).
                    '</td></tr></table>'.                
                '</div>';
        
        $html .=  '<div class="personal-data-fields-wrapper">';
        
        // First name
        $html .= $this->_renderFirstName($controller->getFirstName());
        
        // Last name
        $html .= $this->_renderLastName($controller->getLastName());  
        
        // Email
        $html .= $this->_renderEmail($controller->getEmail()); 
        
        // Phone
        $html .= $this->_renderPhone($controller->getPhone()); 
        
        $html .= '</div>';
        // **************************
        
        // **************************
        // DELIVERY ADDRESS
        // **************************
        // Title
        $html .= 
                '<div>'.
                    '<table class="personal-data-title title"><tr><td>'.
                        mb_strtoupper(lang::trans('delivery_address')).
                    '</td></tr></table>'.                
                '</div>';
        
        $html .=  '<div class="personal-data-fields-wrapper">';
        
        // Company
        //$html .= $this->_renderCompany($controller->getCompany());
        
        // Address
        $html .= $this->_renderAddress($controller->getAddress());
        
        // Postal code
        $html .= $this->_renderPostalCode($controller->getPostalCode());
        
        // City
        $html .= $this->_renderCity($controller->getCity());
        
        // Country
        $html .= $this->_renderCountry($controller->getCountry());
        
        // Comments
        $html .= $this->_renderComments($controller->getComments());
        
        $html .= 
                '<div class="personal-data-fields-info label-info">'.
                    '*&nbsp;'.lang::trans('mandatory_fields').
                '</div>'; 
        
        $html .= '</div>';
        // **************************
         
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
                    'class="field" '.
                    'name="email" '.
                    'type="text" '.
                    'value="'.$value.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderPhone($value)
    {
        // Render phone field
        $html = '<div class="label">'.lang::trans('phone').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'class="field field-phone" '.
                    'name="phone" '.
                    'type="text" '.
                    'onkeypress=\'validateNumber(event)\' '.
                    'value="'.$value.'" '.
                '>';   
        
        return $html;
    }
    
    private function _renderCompany($value)
    {
        // Render company field
        $html = '<div class="label">'.lang::trans('company').'</div>';
        $html .= 
                '<input '.
                    'class="field" '.
                    'name="company" '.
                    'type="text" '.
                    'value="'.$value.'" '.
                '>';  
        
        return $html;
    }
    
    private function _renderAddress($value)
    {
        // Render address field
        $html = '<div class="label">'.lang::trans('address').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'class="field" '.
                    'name="address" '.
                    'type="text" '.
                    'value="'.$value.'" '.
                '>';  
        
        return $html;
    }
    
    private function _renderPostalCode($value)
    {
        // Render postal code field
        $html = '<div class="label">'.lang::trans('postal_code').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'class="field field-postalcode" '.
                    'name="postalCode" '.
                    'type="text" '.
                    'onkeypress=\'validateNumber(event)\' '.
                    'value="'.$value.'" '.
                '>';  
        
        return $html;
    }
    
    private function _renderCity($value)
    {
        // Render city field
        $html = '<div class="label">'.lang::trans('city').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'class="field" '.
                    'name="city" '.
                    'type="text" '.
                    'value="'.$value.'" '.
                '>';  
        
        return $html;
    }
    
    private function _renderCountry($value)
    {
        // Render country field
        $current_lang = lang::getCurrentLanguage();
        $countries_list = helpers::getCountriesList($current_lang);
        $country['ES'] = $countries_list['ES'];
        
        $html = '<div class="label">'.lang::trans('country').'&nbsp;*'.'</div>';
        $html .= 
                '<select '.
                    'class="field field-country" '.
                    'name="country" '.
                '>';
        foreach ($country as $country_key => $country_value) {
            if ($country_key == $value)
            {
                $html .= '<option value="'.$country_key.'" selected>'.$country_value.'</option>';
            }
            else
            {
                $html .= '<option value="'.$country_key.'">'.$country_value.'</option>';
            }
        }
        $html .= '</select>';
        
        $html .= '<div class="label" style="color:red; margin-bottom:10px;">'.lang::trans('country_info').'</div>';
        
        return $html;
    }
    
    private function _renderComments($value)
    {
        // Render comments field
        $html = '<div class="label">'.lang::trans('additional_info_to_delibery').'</div>';
        $html .= 
                '<textarea '.
                    'class="field" '.
                    'name="comments" '.
                '>'.$value.'</textarea>';   
        
        return $html;
    }
    
}