<?php

namespace modules\ecommerce\model;

use core\model\controller\model;

/**
 * User type model for E-commerce
 *
 * @author Dani Gilabert
 * 
 */
class user extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'ecommerce-user'),
        'code' => array('type' => 'string'),
        
        // Personal data
        'firstName' => array('type' => 'string'),
        'lastName' => array('type' => 'string'),
        'password' => array('type' => 'string'),
        'newsletters' => array('type' => 'boolean'),
        'signinDate' => array('type' => 'string'),
        'signinTime' => array('type' => 'string'),
        'phone' => array('type' => 'string'),
        'company' => array('type' => 'string'),
        'address' => array('type' => 'string'),
        'postalcode' => array('type' => 'string'),
        'city' => array('type' => 'string'),
        'country' => array('type' => 'string'),
        'comments' => array('type' => 'string'),
        'provisionalPassword' => array('type' => 'string'),
        'provisionalPasswordDate' => array('type' => 'date'),
        
        // Payment
        'paymentWay' => array('type' => 'string'),
        'cardToken' => array('type' => 'string'),
        'cardExpirationDate' => array('type' => 'string'),
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}