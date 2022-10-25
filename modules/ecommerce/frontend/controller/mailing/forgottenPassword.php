<?php

namespace modules\ecommerce\frontend\controller\mailing;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\mailing\mail;

// Views
use modules\ecommerce\frontend\view\mailing\forgottenPassword as view;

/**
 * Forgotten password email controller
 *
 * @author Dani Gilabert
 * 
 */
class forgottenPassword extends mail
{
    protected $_view;
    protected $_title;
    protected $_user;

    public function __construct($user)
    {
        parent::__construct();
        $this->_view = new view();
        $title = lang::trans('have_you_forgotten_your_password');
        $this->_view->title = $title;
        $this->_view->user = $user;
        $this->_title = $title;
        $this->_user = $user;
    }

    public function sendEmail()
    {
        $website = $this->getWebsite();
        
        // Set subject
        $subject = $website->name. ' - '.$this->_title;
        
        // Set body
        $body = $this->renderPage();
        
        // Set destinations
        $to = array();       
        $customer_email = $this->_user->code;
        $customer_name = $this->_user->firstName.' '.$this->_user->lastName;
        $to[$customer_email] = $customer_name;      
        
        // Send the email
        return $this->send($subject, $body, $to);          
    }
    
}