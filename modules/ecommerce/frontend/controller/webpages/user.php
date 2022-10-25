<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\user as userController;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;
use modules\ecommerce\frontend\controller\personaldata as personaldataController;
use modules\ecommerce\frontend\controller\payment as paymentController;

// Models
use modules\ecommerce\model\user as userModel;

// Views
use modules\ecommerce\frontend\view\user\user as view;

/**
 * User form webpage
 *
 * @author Dani Gilabert
 * 
 */
class user extends userController
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
        return $website->name.' - '.lang::trans('my_account');
    }           
    
    protected function _getDescription()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('my_account');
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
                                array('text' => lang::trans('my_account'), 'url' => '')
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
    
    public function login($data)
    {
        $email = $data->email;
        $raw_password = $data->password;        
        
        if (empty($email) || empty($raw_password))
        {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('email_password_are_mandatory');
            echo json_encode($ret); 
            return;
        }
        
        // Coding password
        $password = md5($raw_password); 
        
        // Check user
        $user = new userModel();
        $type = $user->type;
        $id = $type.'-'.$email;
        $user->loadData($id);
        if(!$user->exists())
        {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('email_password_are_incorrect');
            echo json_encode($ret); 
            return;            
        }
        
        // Check the provisional password
        $is_provisional_password = false;
        $provisional_password = $user->provisionalPassword;
        $provisional_password_date = $user->provisionalPasswordDate;
        if (
            isset($provisional_password) && !empty($provisional_password) && 
            $raw_password === $provisional_password &&
            isset($provisional_password_date) && !empty($provisional_password_date)
        )
        {
            $now = date('Y-m-d H:i:s');
            $exceeded = (strtotime($now) - strtotime($provisional_password_date)) / 60;
            $is_provisional_password = ($exceeded < 10);
        }
        
        // Check the password
        if ($password !== $user->password && !$is_provisional_password)
        {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('email_password_are_incorrect');
            echo json_encode($ret); 
            return;     
        }
        
        // Set user (login session)
        $user_data = $user->getStorage();
        $this->setUser($user_data);         
        
        // Set personal data
        $personaldata_controller = new personaldataController();
        $personaldata_controller->setUserData($user_data);
        
        // Set payment data
        $payment_controller = new paymentController();
        $payment_controller->setUserData($user_data);
            
        // Happy end
        $ret['success'] = true;
        $ret['msg'] = '';
        echo json_encode($ret);          
    }
    
    public function logout()
    {
        $current_lang = lang::getCurrentLanguage();
        
        // Flush login session
        $this->setUser(array());
        
        // Flush personal data
        $personaldata_controller = new personaldataController();
        $personaldata_controller->flush();
        
        // Flush payment
        $payment_controller = new paymentController();
        $payment_controller->flush();
        
        $ret['success'] = true;
        $ret['msg'] = '';
        
        $webpage = $this->getWebpage();
        if ($webpage === 'user')
        {
            $ret['redirectUrl'] = $this->getUrl(array($current_lang, 'showcase'));
        }
        
        echo json_encode($ret);
    }
    
    public function save($data)
    {
        $current_lang = lang::getCurrentLanguage();
        
        $firstname = $data->firstname;
        $lastname = $data->lastname;
        $email = $data->email;
        $currentpassword = $data->currentpassword;
        $password = $data->password;
        $confirmpassword = $data->confirmpassword;
        $newsletters = (($data->newsletters == 'true')? true : false);
        $phone = $data->phone;
        $company = $data->company;
        $address = $data->address;
        $postalcode = $data->postalcode;
        $city = $data->city;
        $country = $data->country;
        $comments = $data->comments;
        
        // Check new passwords
        if ($password !== $confirmpassword)
        {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('please_enter_same_value');
            $ret['fieldMsg'] = 'confirmPassword';
            $ret = json_encode($ret);
            echo $ret;
            return;
        }        
        
        // Check user
        $user = new userModel();
        $type = $user->type;
        $id = $type.'-'.$email;
        $user->loadData($id);
        if(!$user->exists())
        {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('this_email_is_not_registered');
            $ret['fieldMsg'] = 'email';
            $ret = json_encode($ret);
            echo $ret;
            return;
        }
        
        // Check the current password
        if ($currentpassword !== $user->password)
        {
            // Window message after submit
            $ret['success'] = false;
            $ret['msg'] = '';
            $ret['fieldMsg'] = 'currentPassword';
            $ret['messageAfterSubmitWindow'] = $this->_view->renderWindowAfterSubmit(false);
            $ret['redirectUrl'] = $this->getUrl(array($current_lang, 'showcase'));
            $ret = json_encode($ret);
            echo $ret;
            return;
        }         
        
        // Updating user
        $user->firstName = $firstname;
        $user->lastName = $lastname;
        if ($currentpassword !== $password)
        {
            $user->password = md5($password);
        }
        $user->newsletters = $newsletters;
        // Personal data
        $user->phone = $phone;
        $user->company = $company;
        $user->address = $address;
        $user->postalcode = $postalcode;
        $user->city = $city;
        $user->country = $country;
        $user->comments = $comments;        
        $user->save();

        // Set user (login session)
        $user_data = $user->getStorage();
        $this->setUser($user_data);
        
        // Set personal data
        $personaldata_controller = new personaldataController();
        $personaldata_controller->setUserData($user_data);
        
        // Set payment data
        $payment_controller = new paymentController();
        $payment_controller->setUserData($user_data);

        // Happy end
        $ret['success'] = true;
        $ret['msg'] = '';
        $ret['fieldMsg'] = '';
        $ret['messageAfterSubmitWindow'] = $this->_view->renderWindowAfterSubmit(true);
        $ret['redirectUrl'] = $this->getUrl(array($current_lang, 'showcase'));
        $ret = json_encode($ret);
        echo $ret;
    }
    
}