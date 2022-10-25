<?php

namespace modules\ecommerce\frontend\controller\mailing;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\mailing\mail;

// Views
use modules\ecommerce\frontend\view\mailing\orderConfirmation as view;

/**
 * Order confirmation email controller
 *
 * @author Dani Gilabert
 * 
 */
class orderConfirmation extends mail
{
    protected $_view;
    protected $_title;
    protected $_sale_data;

    public function __construct($sale_data)
    {
        parent::__construct();
        $this->_view = new view();
        $title = lang::trans('order_confirmation');
        $this->_view->title = $title;
        $this->_view->sale_data = $sale_data;
        $this->_title = $title;
        $this->_sale_data = $sale_data;
    }

    public function sendEmail()
    {
        $website = $this->getWebsite();
        
        // Set subject
        $subject = $website->name. ' - '.$this->_title;
        
        // Set body
        $body = $this->renderPage();
        
        // Set recipients
        $to = array();       
        $customer_email = $this->_sale_data->email;
        $customer_name = $this->_sale_data->firstName.' '.$this->_sale_data->lastName;
        $to[$customer_email] = $customer_name;
        $ret = $this->send($subject, $body, $to);
        
        // Send ecommerce emails
        $ecommerce_to = $this->getMailAddresses();
        $this->send($subject, $body, $ecommerce_to);
        
        // Send customer email
        return $ret;
    }
    
}