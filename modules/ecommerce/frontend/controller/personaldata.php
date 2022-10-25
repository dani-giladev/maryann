<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\session;

/**
 * Personal (Customer) data controller
 *
 * @author Dani Gilabert
 * 
 */
class personaldata extends ecommerce
{

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getFirstName()
    {
        $value = session::getSessionVar('ecommerce-personaldata-firstname');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setFirstName($value)
    {
        session::setSessionVar('ecommerce-personaldata-firstname', $value);
    } 
    
    public function getLastName()
    {
        $value = session::getSessionVar('ecommerce-personaldata-lastname');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setLastName($value)
    {
        session::setSessionVar('ecommerce-personaldata-lastname', $value);
    }  
    
    public function getEmail()
    {
        $value = session::getSessionVar('ecommerce-personaldata-email');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setEmail($value)
    {
        session::setSessionVar('ecommerce-personaldata-email', $value);
    }  
    
    public function getPhone()
    {
        $value = session::getSessionVar('ecommerce-personaldata-phone');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setPhone($value)
    {
        session::setSessionVar('ecommerce-personaldata-phone', $value);
    } 
    
    public function getCompany()
    {
        $value = session::getSessionVar('ecommerce-personaldata-company');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setCompany($value)
    {
        session::setSessionVar('ecommerce-personaldata-company', $value);
    }  
    
    public function getAddress()
    {
        $value = session::getSessionVar('ecommerce-personaldata-address');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setAddress($value)
    {
        session::setSessionVar('ecommerce-personaldata-address', $value);
    }  
    
    public function getPostalCode()
    {
        $value = session::getSessionVar('ecommerce-personaldata-postalcode');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setPostalCode($value)
    {
        session::setSessionVar('ecommerce-personaldata-postalcode', $value);
    }   
    
    public function getCity()
    {
        $value = session::getSessionVar('ecommerce-personaldata-city');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setCity($value)
    {
        session::setSessionVar('ecommerce-personaldata-city', $value);
    }   
    
    public function getCountry()
    {
        $value = session::getSessionVar('ecommerce-personaldata-country');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setCountry($value)
    {
        session::setSessionVar('ecommerce-personaldata-country', $value);
    }     
    
    public function getComments()
    {
        $value = session::getSessionVar('ecommerce-personaldata-comments');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setComments($value)
    {
        session::setSessionVar('ecommerce-personaldata-comments', $value);
    } 
    
    public function isEmpty()
    {
        return (
                empty($this->getFirstName()) || 
                empty($this->getLastName()) || 
                empty($this->getEmail()) || 
                empty($this->getPhone()) || 
                empty($this->getAddress()) || 
                empty($this->getPostalCode()) || 
                empty($this->getCity()) || 
                empty($this->getCountry())
                );
    }
    
    public function setUserData($user)
    {
        $this->setFirstName($user->firstName);
        $this->setLastName($user->lastName);
        $this->setEmail($user->code);
        $this->setPhone($user->phone);
        $this->setCompany($user->company);
        $this->setAddress($user->address);
        $this->setPostalcode($user->postalcode);
        $this->setCity($user->city);
        $this->setCountry($user->country);
        $this->setComments($user->comments);
    }   
    
    public function flush()
    {
        $this->setFirstName('');
        $this->setLastName('');
        $this->setEmail('');
        $this->setPhone('');
        $this->setCompany('');
        $this->setAddress('');
        $this->setPostalcode('');
        $this->setCity('');
        $this->setCountry('');
        $this->setComments('');
    }
    
}