<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use modules\ecommerce\frontend\controller\session;
use modules\marketing\controller\voucher as merketingVoucher;

/**
 * Voucher controller
 *
 * @author Dani Gilabert
 * 
 */
class voucher extends merketingVoucher
{
    
    public function getVoucher()
    {
        $value = session::getSessionVar('ecommerce-voucher');
        return (isset($value))? $value : null;
    }
    
    public function setVoucher($value)
    {
        session::setSessionVar('ecommerce-voucher', $value);
    }
    
    public function flush()
    {
        $this->setVoucher(null);
    }
    
    public function isEmpty($voucher)
    {
        return (!isset($voucher) || empty((array) $voucher));
    }
   
}