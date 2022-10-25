<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\config\controller\config;
use core\device\controller\device;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\personaldata;
use modules\ecommerce\frontend\controller\shoppingcart;
use modules\ecommerce\frontend\controller\payment;
use modules\ecommerce\frontend\controller\stock;
use modules\ecommerce\frontend\controller\user;
use modules\ecommerce\frontend\controller\voucher;
use modules\ecommerce\frontend\controller\mailing\orderConfirmation as orderConfirmationMailer;

// Models
use modules\ecommerce\model\sale as saleModel;

/**
 * Controller in order to finalize shopping (frontend ecommerce)
 *
 * @author Dani Gilabert
 * 
 */
class ordering extends ecommerce
{
    protected $_personaldata_controller;
    protected $_shoppingcart_controller;
    protected $_voucher_controller;

    public function __construct()
    {
        parent::__construct();
        $this->_personaldata_controller = new personaldata();
        $this->_shoppingcart_controller = new shoppingcart();
        $this->_voucher_controller = new voucher();
    } 
    
    public function getOrderCode()
    {
        $value = session::getSessionVar('ecommerce-ordering-ordercode');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setOrderCode($value)
    {
        session::setSessionVar('ecommerce-ordering-ordercode', $value);
    }
    
    public function createNewOrderCode()
    {
        $sale = new saleModel();
        $this->setOrderCode($sale->getNewCode());
    }
    
    public function ordering()
    {
        $user_controller = new user();
        $payment_controller = new payment();
        
        // Is test?
        $is_fake = ($user_controller->isLoggedUser() && $this->_personaldata_controller->getEmail() === 'tpvvirtualtest@deemm.com');

        // Create order
        $code = $this->_createOrder($is_fake);
        
        // Decrease the stock
        if (!$is_fake)
        {
            $this->_decreaseStock($this->_shoppingcart_controller->getShoppingcart());
        }

        // Update user
        $user_controller->updateUserAfterOrdering();    

        // Flush data
        $this->_shoppingcart_controller->flush();
        $payment_controller->flushAfterOrdering();

        // Send email
        $this->_sendOrderEmail($code);
        
        // Happy end
        return $code;        
    }
    
    protected function _createOrder($is_fake)
    {
        $payment_controller = new payment();
        $sale = new saleModel();
        
        // General properties
        $code = $this->getOrderCode();
        $sale->code = $code;
        $sale->date = date(config::getConfigParam(array("application", "dateformat_database"))->value);
        $sale->time = date(config::getConfigParam(array("application", "timeformat"))->value);
        
        // Set articles (shopping cart)
        $shoppingcart = $this->_shoppingcart_controller->getShoppingcart();
        $sale->shoppingcart = $shoppingcart;
              
        // Set personal data
        $sale->firstName = $this->_personaldata_controller->getFirstName();
        $sale->lastName = $this->_personaldata_controller->getLastName();
        $email = $this->_personaldata_controller->getEmail();
        $sale->email = $email;
        $sale->phone = $this->_personaldata_controller->getPhone();
        $sale->company = $this->_personaldata_controller->getCompany();
        $sale->address = $this->_personaldata_controller->getAddress();
        $sale->postalCode = $this->_personaldata_controller->getPostalCode();
        $sale->city = $this->_personaldata_controller->getCity();
        $sale->country = $this->_personaldata_controller->getCountry();
        $sale->comments = $this->_personaldata_controller->getComments();
        
        // Set payment data
        $sale->paymentWay = $payment_controller->getPaymentWay();
        if ($sale->paymentWay === 'card')
        {
            $sale->cardToken = $payment_controller->getCardToken();
            $sale->cardExpirationDate = $payment_controller->getCardExpirationDate();
        }
        $sale->paypalPayerId = $payment_controller->getPaypalPayerId();
        $sale->paypalPaymentId = $payment_controller->getPaypalPaymentId();
        $sale->paypalPaymentToken = $payment_controller->getPaypalPaymentToken();
        
        // Set delegation
        $website = $this->getWebsite();
        $sale->delegation = $website->delegation;
        $sale->delegationName = $website->delegationName;
        
        // Set prices
        $sale->totalPrice = $this->_shoppingcart_controller->getTotalPrice();
        $sale->shippingCost = $this->_shoppingcart_controller->getShippingCost();
        $sale->voucher = $this->_voucher_controller->getVoucher();
        $sale->voucherDiscount = $this->_shoppingcart_controller->getVoucherDiscount();
        $sale->secondUnitDiscount = $this->_shoppingcart_controller->get2ndUnitDiscount();
        $sale->finalTotalPrice = $this->_shoppingcart_controller->getFinalTotalPrice();
        
        // Fake?
        $sale->isFake = $is_fake;
        
        // Others
        $sale->mobile = device::isMobileVersion();
        
      
        // Save order!
        $sale->save();   
        
        // Happy end
        return $code;       
    }
    
    protected function _sendOrderEmail($code)
    {
        $id = 'ecommerce-sale-'.strtolower($code);
        $sale = new saleModel($id);
        
        $order_confirmation_mailer = new orderConfirmationMailer($sale);
        $sent = $order_confirmation_mailer->sendEmail(); 
        $sale->sentEmail = $sent;
        $sale->save(true);
    }

    protected function _decreaseStock($shoppingcart)
    {
        $controller = new stock();
        foreach ($shoppingcart as $shoppingcart_value) {
            $controller->decreaseStock($shoppingcart_value->article, $shoppingcart_value->amount);
        }
        
        // Update articles
        $controller->updateArticles();
    }
    
    private function _deleteAllPreviousSales()
    {
        // Test (delete all previous sales)
        $model_test1 = new saleModel();
        $model_type = "ecommerce-sale";
        $object = $model_test1->getDataView($model_type, $model_type);
        foreach ($object->rows as $value)
        {
            $model_test2 = new saleModel($value->id);
            $model_test2->delete();
        }        
    }
}