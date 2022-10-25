<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\session;
use modules\ecommerce\frontend\controller\personaldata;
use modules\ecommerce\frontend\controller\payment;

// Models
use modules\ecommerce\model\user as userModel;

/**
 * User controller
 *
 * @author Dani Gilabert
 * 
 */
class user extends ecommerce
{
    
    public function getUser()
    {
        $value = session::getSessionVar('ecommerce-login-user');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    public function setUser($value)
    {
        session::setSessionVar('ecommerce-login-user', $value);
    } 
    
    public function isLoggedUser()
    {
        return (!empty($this->getUser()));
    }
    
    public function updateUserAfterOrdering()
    {
        if (!$this->isLoggedUser())
        {
            return;
        }
        
        // Check user
        $personaldata_controller = new personaldata();
        $email = $personaldata_controller->getEmail();
        $model = new userModel();
        $type = $model->type;
        $id = $type.'-'.$email;
        
        $storage = $model->loadData($id);
        if (is_null($storage)) return false;        

        // Update personal data
        $model->firstName = $personaldata_controller->getFirstName();
        $model->lastName = $personaldata_controller->getLastName();
        $model->phone = $personaldata_controller->getPhone();
        $model->company = $personaldata_controller->getCompany();
        $model->address = $personaldata_controller->getAddress();
        $model->postalcode = $personaldata_controller->getPostalCode();
        $model->city = $personaldata_controller->getCity();
        $model->country = $personaldata_controller->getCountry();
        $model->comments = $personaldata_controller->getComments();
        
        // Update payment data
        $payment_controller = new payment();
        $model->paymentWay = $payment_controller->getPaymentWay();
        $model->cardToken = $payment_controller->getCardToken();
        $model->cardExpirationDate = $payment_controller->getCardExpirationDate();            
        
        $model->save(true);
        
        return true;
    }
    
    public function getNewProvisionalPassword()
    { 
        $ret = '';
        
        for ($i = 0; $i < 8; $i++) 
        {
            if ($i === 2 || $i === 5)
            {
                // 0-9
                $ret .= chr(rand(48, 57));
            }
            else
            {
                // a-z
                $ret .= chr(rand(97, 122));
            }
        }
        
        return $ret;
    }
    
}