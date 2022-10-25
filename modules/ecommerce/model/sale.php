<?php

namespace modules\ecommerce\model;

use core\model\controller\model;

/**
 * Sale type model for E-commerce
 *
 * @author Dani Gilabert
 * 
 */
class sale extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'ecommerce-sale'),
        'code' => array('type' => 'string'),
        'shoppingcart' => array('type' => 'array'),
        'date' => array('type' => 'string'),
        'time' => array('type' => 'string'),
        
        // Personal data
        'firstName' => array('type' => 'string'),
        'lastName' => array('type' => 'string'),
        'email' => array('type' => 'string'),
        'phone' => array('type' => 'string'),
        'company' => array('type' => 'string'),
        'address' => array('type' => 'string'),
        'postalCode' => array('type' => 'string'),
        'city' => array('type' => 'string'),
        'country' => array('type' => 'string'),
        'comments' => array('type' => 'string'),
        
        // Payment
        'paymentWay' => array('type' => 'string'),
        'cardToken' => array('type' => 'string'),
        'cardExpirationDate' => array('type' => 'string'),
        'paypalPayerId' => array('type' => 'string'),
        'paypalPaymentId' => array('type' => 'string'),
        'paypalPaymentToken' => array('type' => 'string'),
        
        'delegation' => array('type' => 'string'),
        'delegationName' => array('type' => 'string'),
        'totalPrice' => array('type' => 'float'),
        'shippingCost' => array('type' => 'float'),
        'voucher' => array('type' => 'array'),
        'voucherDiscount' => array('type' => 'float'),
        'secondUnitDiscount' => array('type' => 'float'),
        'finalTotalPrice' => array('type' => 'float'),
        'sentEmail' => array('type' => 'boolean'),
        'isFake' => array('type' => 'boolean'),
        'cancelled' => array('type' => 'boolean'),
        'cancellationReason' => array('type' => 'string'),
        'mobile' => array('type' => 'boolean')
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
    public function getNewCode()
    {
        //$code = date("YmdHis").rand(100, 999);
        $code = time();
        return $code;
    }
    
}