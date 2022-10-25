<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use core\config\controller\config;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\signin as signinController;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;
use modules\ecommerce\frontend\controller\user as userController;
use modules\ecommerce\frontend\controller\captcha as captchaController;
use modules\ecommerce\frontend\controller\personaldata as personaldataController;
use modules\ecommerce\frontend\controller\payment as paymentController;

// Models
use modules\ecommerce\model\user as userModel;

// Views
use modules\ecommerce\frontend\view\signin\signin as view;
use modules\ecommerce\frontend\view\signin\thanksForSignin as thanksForSigninView;

/**
 * Sign-in form webpage
 *
 * @author Dani Gilabert
 * 
 */
class signin extends signinController
{
    protected $_view;

    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
    }
    
    public function init()
    {
        // Render this page
        $this->renderPage();
    }    
    
    protected function _getTitle()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('signin_title');
    }           
    
    protected function _getDescription()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('signin_description');
    }           
    
    protected function _getKeywords()
    {
        return '';
    }           
    
    protected function _getRobots()
    {
        return 'noindex, nofollow';
    }
    
    protected function _renderMenu()
    {
        $html = '';
        
        // Render main menu
        $main_menu = new mainMenu(null, true, true);
        $html .= $main_menu->renderMainMenu();
        
        // Render breadcrumbs menu
        $breadcrumbs = array(
                                array('text' => lang::trans('signin_description'), 'url' => '')
                            );        
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'breadcrumbs' => $breadcrumbs
        ));          
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    }
    
    protected function _renderContent()
    {
        $html = '';
        
        // Render form
        $html .= $this->_view->renderStartForm();
        $html .= $this->_view->renderForm();
        $html .= $this->_view->renderEndForm();
        
        return $html;
    }
    
    public function validate($data)
    {
        $current_lang = lang::getCurrentLanguage();
        $firstname = $data->firstname;
        $lastname = $data->lastname;
        $email = $data->email;
        $password = md5($data->password);
        $confirmpassword = md5($data->confirmpassword);
        $captcha = (isset($data->captcha))? strtoupper($data->captcha) : '';
        $newsletters = (($data->newsletters == 'true')? true : false);        
        
        // Set last sign-in data
        $this->setFirstName($firstname);
        $this->setLastName($lastname);
        $this->setEmail($email);
        $this->setNewsletters($newsletters);
          
        // Check captcha
        $captcha_controller = new captchaController();
        $last_captcha = $captcha_controller->getCaptcha();
        if($captcha !== $last_captcha || empty($last_captcha))
        {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('text_does_not_match_image');
            $ret['fieldMsg'] = 'captcha';
            $ret = json_encode($ret);
            echo $ret;
            return;
        }
        
        // Check user
        $user = new userModel();
        $type = $user->type;
        $id = $type.'-'.$email;
        $user->loadData($id);
        if($user->exists())
        {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('this_email_is_already_registered');
            $ret['fieldMsg'] = 'email';
            $ret = json_encode($ret);
            echo $ret;
            return;
        }
        
        // Check the password
        if ($password !== $confirmpassword)
        {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('please_enter_same_value');
            $ret['fieldMsg'] = 'confirmPassword';
            $ret = json_encode($ret);
            echo $ret;
            return;
        }   
        
        // Save the new user
        $user->code = $email;
        $user->firstName = $firstname;
        $user->lastName = $lastname;
        $user->password = $password;
        $user->signinDate = date(config::getConfigParam(array("application", "dateformat_database"))->value);
        $user->signinTime = date(config::getConfigParam(array("application", "timeformat"))->value);            
        $user->newsletters = $newsletters;
        // Personal data
        $user->phone = '';
        $user->company = '';
        $user->address = '';
        $user->postalcode = '';
        $user->city = '';
        $user->country = '';
        $user->comments = '';
        // Payment data
        $user->paymentWay = '';  
        $user->cardToken = '';  
        $user->cardExpirationDate = '';      
        $user->save();

        // Set user (login session)
        $user_controller = new userController();
        $user_data = $user->getStorage();
        $user_controller->setUser($user_data);
        
        // Set personal data
        $personaldata_controller = new personaldataController();
        $personaldata_controller->setUserData($user_data);
        
        // Set payment data
        $payment_controller = new paymentController();
        $payment_controller->setUserData($user_data);

        // Window view when you've just sign-in successfully
        $thanks_for_signin_view = new thanksForSigninView();
        $thanks_for_signin_content = $thanks_for_signin_view->renderWindow($newsletters);

        // Flush sign-in data
        $this->flush();
        
        // Flush the captcha
        $captcha_controller->flush();
        
        // Happy end
        $ret['success'] = true;
        $ret['msg'] = '';
        $ret['fieldMsg'] = '';
        $ret['thanksForSigninWindow'] = $thanks_for_signin_content;
        $ret['redirectUrl'] = $this->getUrl(array($current_lang, 'showcase'));
        $ret = json_encode($ret);
        echo $ret;          
    }
    
}