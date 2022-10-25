<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\user;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;
use modules\ecommerce\frontend\controller\mailing\forgottenPassword as forgottenPasswordMailer;

// Models
use modules\ecommerce\model\user as userModel;

// Views
use modules\ecommerce\frontend\view\user\forgottenpassword as view;

/**
 * Forgotten password webpage
 *
 * @author Dani Gilabert
 * 
 */
class forgottenpassword extends user
{
    protected $_view;

    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
    }
    
    public function init()
    {
//        // Test
//        $this->test();
        
        // Render this page
        $this->renderPage();
    }    
    
    protected function _getTitle()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('have_you_forgotten_your_password');
    }           
    
    protected function _getDescription()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('have_you_forgotten_your_password');
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
        $main_menu = new mainMenu();
        $html .= $main_menu->renderMainMenu();
        
        // Render breadcrumbs menu
        $breadcrumbs = array(
                                array('text' => lang::trans('have_you_forgotten_your_password'), 'url' => '')
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
    
    public function submit($data)
    {
        $current_lang = lang::getCurrentLanguage();
        $email = $data->email;      
        
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
        
        // Update provisional password
        $provisional_password = $this->getNewProvisionalPassword();
        $user->provisionalPassword = $provisional_password;
        $user->provisionalPasswordDate = date('Y-m-d H:i:s');
        $user->save();
        
        // Send email
        $forgotten_password_mailer = new forgottenPasswordMailer($user);
        $sent = $forgotten_password_mailer->sendEmail(); 
        if (!$sent)
        {
            $ret['success'] = false;
            $ret['msg'] = '';
            $ret['fieldMsg'] = 'sendingEmail';
            $ret['messageAfterSubmitWindow'] = $this->_view->renderWindowAfterSubmit(false);
            $ret = json_encode($ret);
            echo $ret;
            return;            
        }

        // Happy end
        $ret['success'] = true;
        $ret['msg'] = '';
        $ret['fieldMsg'] = '';
        $ret['messageAfterSubmitWindow'] = $this->_view->renderWindowAfterSubmit(true);
        $ret['redirectUrl'] = $this->getUrl(array($current_lang, 'showcase'));
        $ret = json_encode($ret);
        echo $ret;
    }
    
    public function test()
    {     
        $email = 'dgilabert@hotmail.com';
        
        $user = new userModel();
        $type = $user->type;
        $id = $type.'-'.$email;
        $user->loadData($id);
        if (!$user->exists())
        {
            return;
        }
        
        $provisional_password = $this->getNewProvisionalPassword();
        $user->provisionalPassword = $provisional_password;
        $user->provisionalPasswordDate = date('Y-m-d H:i:s');
        
        // Send email
        $forgotten_password_mailer = new forgottenPasswordMailer($user);
        $html = $forgotten_password_mailer->renderPage(); 
        echo $html;
        die();
    }
    
}