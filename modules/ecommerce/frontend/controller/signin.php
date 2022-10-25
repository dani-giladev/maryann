<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\session;

/**
 * Sign-in data controller
 *
 * @author Dani Gilabert
 * 
 */
class signin extends ecommerce
{

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getFirstName()
    {
        $value = session::getSessionVar('ecommerce-signinform-firstname');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setFirstName($value)
    {
        session::setSessionVar('ecommerce-signinform-firstname', $value);
    } 
    
    public function getLastName()
    {
        $value = session::getSessionVar('ecommerce-signinform-lastname');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setLastName($value)
    {
        session::setSessionVar('ecommerce-signinform-lastname', $value);
    }  
    
    public function getEmail()
    {
        $value = session::getSessionVar('ecommerce-signinform-email');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setEmail($value)
    {
        session::setSessionVar('ecommerce-signinform-email', $value);
    }  
    
    public function getNewsletters()
    {
        $value = session::getSessionVar('ecommerce-signinform-newsletters');
        return (isset($value))? $value : false;
    }
    
    public function setNewsletters($value)
    {
        session::setSessionVar('ecommerce-signinform-newsletters', $value);
    }   
    
    public function flush()
    {
        $this->setFirstName('');
        $this->setLastName('');
        $this->setEmail('');
        $this->setNewsletters(false);
    }
    
}