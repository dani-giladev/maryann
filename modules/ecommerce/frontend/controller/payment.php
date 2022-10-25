<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\config\controller\config;
use core\redsys\controller\redsys;
use core\url\controller\url;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\session;
use modules\ecommerce\frontend\controller\validation;
use modules\ecommerce\frontend\controller\shoppingcart;
use modules\ecommerce\frontend\controller\user;
use modules\ecommerce\frontend\controller\ordering;

// Models
use modules\ecommerce\model\sale as saleModel;

/**
 * Payment (Customer) controller
 *
 * @author Dani Gilabert
 * 
 */
class payment extends ecommerce
{
    protected $_redsys_controller;
    protected $_shoppingcart_controller;
    protected $_user_controller;
    protected $_ordering_controller;

    public function __construct()
    {
        parent::__construct();
        $this->_redsys_controller = new redsys();
        $this->_shoppingcart_controller = new shoppingcart();
        $this->_user_controller = new user();
        $this->_ordering_controller = new ordering();
    }
    
    public function getPaymentWay()
    {
        $value = session::getSessionVar('ecommerce-payment-paymentway');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setPaymentWay($value)
    {
        session::setSessionVar('ecommerce-payment-paymentway', $value);
    }
    
    public function getCardToken()
    {
        $value = session::getSessionVar('ecommerce-payment-cardtoken');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setCardToken($value)
    {
        session::setSessionVar('ecommerce-payment-cardtoken', $value);
    }
    
    public function getCardExpirationDate()
    {
        $value = session::getSessionVar('ecommerce-payment-cardexpirationyear');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setCardExpirationDate($value)
    {
        session::setSessionVar('ecommerce-payment-cardexpirationyear', $value);
    } 
    
    public function getPaypalPayerId()
    {
        $value = session::getSessionVar('ecommerce-payment-paypal-payerid');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setPaypalPayerId($value)
    {
        session::setSessionVar('ecommerce-payment-paypal-payerid', $value);
    }  
    
    public function getPaypalPaymentId()
    {
        $value = session::getSessionVar('ecommerce-payment-paypal-paymentid');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setPaypalPaymentId($value)
    {
        session::setSessionVar('ecommerce-payment-paypal-paymentid', $value);
    }
    
    public function getPaypalPaymentToken()
    {
        $value = session::getSessionVar('ecommerce-payment-paypal-paymenttoken');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    public function setPaypalPaymentToken($value)
    {
        session::setSessionVar('ecommerce-payment-paypal-paymenttoken', $value);
    }
    
    public function isEmpty()
    {
        $payment_way = $this->getPaymentWay();
        if (empty($payment_way))
        {
            return true;
        }
        
        if ($payment_way === 'paypal')
        {
            return (
                    empty($this->getPaypalPayerId()) || 
                    empty($this->getPaypalPaymentId()) || 
                    empty($this->getPaypalPaymentToken())
            );
        }
        
        return false;
    }  
    
    public function setUserData($user)
    {
        $this->setPaymentWay($user->paymentWay);
        $this->setCardToken($user->cardToken);
        $this->setCardExpirationDate($user->cardExpirationDate);
    }  
    
    public function flushAfterOrdering()
    {
        $this->_ordering_controller->setOrderCode('');

        $this->setPaypalPayerId('');
        $this->setPaypalPaymentId('');
        $this->setPaypalPaymentToken('');            
    }
    
    public function flush($payment_way = null)
    {
        $this->setPaymentWay('');
        
        if (is_null($payment_way) || $payment_way !== 'card')
        {
//            $this->setCardToken('');
//            $this->setCardExpirationDate('');
        }
        
        if (is_null($payment_way) || $payment_way !== 'paypal')
        {
            $this->setPaypalPayerId('');
            $this->setPaypalPaymentId('');
            $this->setPaypalPaymentToken('');            
        }        

    }
    
    protected function _validate($data)
    {
        // Clean
        $this->flush($data->paymentWay);
        
        // Set last data
        $this->setPaymentWay($data->paymentWay);
        if ($data->paymentWay === 'paypal')
        {
            $this->setPaypalPayerId($data->paypalPayerId);
            $this->setPaypalPaymentId($data->paypalPaymentId);
            $this->setPaypalPaymentToken($data->paypalPaymentToken);
        }

        // Check the last validation before save
        $validation_controller = new validation();
        $last_validation_ret = $validation_controller->lastValidation();
        if (!$last_validation_ret['success'])
        {
            return array(
                'success' => false,
                'msg' => $last_validation_ret['msg'],
                'redirectUrl' => $last_validation_ret['redirectUrl']
            );
        } 

        return array(
            'success' => true,
            'msg' => ''
        );    
    } 
    
    protected function _getCardParams($payment_way)
    {
        $current_lang = lang::getCurrentLanguage();
        $total_price = $this->_shoppingcart_controller->getFinalTotalPrice();
        $sid = session_id();
        
	// Se crea Objeto
	$redsysAPI = $this->_redsys_controller->getNewApiHandler();
		
	// Valores de entrada
        $redsys_total_price = $this->_redsys_controller->getTotalPriceFormat($total_price);
        $payment_params =  config::getConfigParam(array("application", "redsys"))->value;
	$merchant_code = $payment_params->merchant_code;
	$terminal = $payment_params->terminal;
	$currency = $payment_params->currency;
	$amount = $redsys_total_price;
	$order_code = $this->_ordering_controller->getOrderCode();
	$trans_type = "0";
	$merchant_url = url::getProtocol().url::getServerName()."/redsys/onlinenotification?sid=".$sid;
        $urlOK = $this->getUrl(array($current_lang, 'bond'), array('code' => $order_code));
	$urlKO = $this->getUrl(array($current_lang, 'payment'));
	switch ($current_lang)
        {
            case 'es':
                $lang = '001';
                break;
            case 'ca':
                $lang = '003';
                break;
            default:
                $lang = '002';
        }
	// Se Rellenan los campos
	$redsysAPI->setParameter("DS_MERCHANT_MERCHANTCODE", $merchant_code);
	$redsysAPI->setParameter("DS_MERCHANT_TERMINAL", $terminal);
	$redsysAPI->setParameter("DS_MERCHANT_CURRENCY", $currency);
	$redsysAPI->setParameter("DS_MERCHANT_AMOUNT", $amount);
	$redsysAPI->setParameter("DS_MERCHANT_ORDER", $order_code);
	$redsysAPI->setParameter("DS_MERCHANT_TRANSACTIONTYPE", $trans_type);
	$redsysAPI->setParameter("DS_MERCHANT_MERCHANTURL", $merchant_url);
	$redsysAPI->setParameter("DS_MERCHANT_URLOK", $urlOK);
	$redsysAPI->setParameter("DS_MERCHANT_URLKO", $urlKO);
	$redsysAPI->setParameter("DS_MERCHANT_CONSUMERLANGUAGE", $lang);
        
        // Set parameters according to payment ways
        if ($payment_way === 'card')
        {
            $redsysAPI->setParameter("DS_MERCHANT_PAYMETHODS", "C");
            if ($this->_user_controller->isLoggedUser())
            {
                $redsysAPI->setParameter("DS_MERCHANT_IDENTIFIER", "REQUIRED");
            }
        }
        elseif ($payment_way === 'clicktopay')
        {
            $redsysAPI->setParameter("DS_MERCHANT_PAYMETHODS", "C");
            $redsysAPI->setParameter("DS_MERCHANT_IDENTIFIER", $this->getCardToken());
        }
        else
        {
            // iupay
            $redsysAPI->setParameter("DS_MERCHANT_PAYMETHODS", "O");
        }
        
	// Datos de configuración
        $env = ($this->isDevelopment())? 'development' : 'production';
	$signature_version = $payment_params->signature_version;
	$kc = $payment_params->$env->secret_encryption_key; // Clave recuperada de CANALES
	
        // Se generan los parámetros de la petición
        $url = $payment_params->$env->url;
	$merchant_parameters = $redsysAPI->createMerchantParameters();
	$signature = $redsysAPI->createMerchantSignature($kc);
        
        return array(
            'url' => $url,
            'merchant_parameters' => $merchant_parameters,
            'signature_version' => $signature_version,
            'signature' => $signature
        );
    }  
    
    protected function _getPaypalParams()
    {
        $current_lang = lang::getCurrentLanguage();
        $paypal_env = ($this->isDevelopment())? 'sandbox' : 'production';
        $paypal_locale = ($current_lang === 'es' || $current_lang === 'ca')? 'es_ES' : 'en_EN';
        $total_price = $this->_shoppingcart_controller->getFinalTotalPrice();
        $paypal_total_price = number_format(round($total_price, 2), 2, ".", "");  
        
        return array(
            'paypal_env' => $paypal_env,
            'paypal_locale' => $paypal_locale,
            'paypal_total_price' => $paypal_total_price
        );
    }
    
    public function onRedsysOnlineNotification($data)
    {
        $sid = $data->sid;
        
        //$version = $data->Ds_SignatureVersion;
        $b64_MerchantParameters = $data->Ds_MerchantParameters;
	$ReceivedSignature = $data->Ds_Signature;
        
	$redsysAPI = $this->_redsys_controller->getNewApiHandler();
        
        // Check signature
        if (!$this->_redsys_controller->isSignatureOK($redsysAPI, $b64_MerchantParameters, $ReceivedSignature))
        {
            $this->_writeLog(
                    'La firma del pago (signature) no és válida',
                    $data);
            return;
        }
        
        // Get code
        $response_code = $this->_redsys_controller->getCode($redsysAPI);
        
        // Is authorized?
        if (!$this->_redsys_controller->isAuthorized($response_code))
        {
            $this->_writeLog(
                    $this->_redsys_controller->getMsg($response_code),
                    $data);
            return;
        }
        
        // Check order code
	$order_code = $this->_ordering_controller->getOrderCode();
        if (empty($order_code))
        {
            $this->_writeLog(
                    'La sesión ha expirado. No se ha podido recuperar el id de sesión: '.$sid,
                    $data);
            return;
            
        }
        $ds_order = $redsysAPI->getParameter("Ds_Order");
        if ($order_code != $ds_order)
        {
            $this->_writeLog(
                    'El número de pedido del TPV virtual:'.$ds_order.' no corresponde con el del pedido en curso:'.$order_code,
                    $data);
            return;
        }
        $id = 'ecommerce-sale-'.strtolower($order_code);
        $sale_model = new saleModel($id);
        if ($sale_model->exists())
        {
            $this->_writeLog(
                    'El pedido: '.$order_code.' ya existe',
                    $data);
            return; 
        }

        // Validate data
        $data->paymentWay = $this->getPaymentWay();
        $ret_validation = $this->_validate($data);
        if (!$ret_validation['success'])
        {
            $this->_writeLog(
                    $ret_validation['msg'],
                    $data);
            return;
        }        
        
        // Set returned params by reference
        if ($this->_user_controller->isLoggedUser() && 
            $this->getPaymentWay() === 'card')
        {
            $last_token = $this->getCardToken();
            $new_token = $redsysAPI->getParameter("Ds_Merchant_Identifier");
            $expiry_date = $redsysAPI->getParameter("Ds_ExpiryDate");
            if ($last_token !== $new_token)
            {
                $this->setCardToken($new_token);
                $expiration_date = substr($expiry_date, 2, 2).'/'.substr($expiry_date, 0, 2);
                $this->setCardExpirationDate($expiration_date);                
            }
        }        
        
        // Happy end
        $code = $this->_ordering_controller->ordering();         

        $this->_writeLog(
                'Last order: '.$code,
                $data, false);        

    }
    
    private function _writeLog($text, $data, $is_error = true)
    {
        $version = $data->Ds_SignatureVersion;
        $b64_MerchantParameters = $data->Ds_MerchantParameters;
	$ReceivedSignature = $data->Ds_Signature;
        
        if ($is_error)
        {
            $text = 'Error: '.$text;
            $filename = '/tmp/error-redsysOnlineNotification-'.date('YmdHis').'.log';
        }
        else
        {
            $filename = '/tmp/redsysOnlineNotification-OK.log';
        }
        
        $content = 
                $text.PHP_EOL.PHP_EOL.
                $version.PHP_EOL.PHP_EOL.
                $b64_MerchantParameters.PHP_EOL.PHP_EOL.
                $ReceivedSignature;
        
        file_put_contents($filename, $content);        
    }
    
    protected function _beautyPaymentFailedMsg($msg)
    {
        return 
            lang::trans('payment_failed_description').'<br><br>'.
            '<font color=\'red\'>'.
                $msg.
            '</font>';        
    }
    
}