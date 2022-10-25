<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\config\controller\config as config;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\shoppingcart;
use modules\ecommerce\frontend\controller\personaldata;
use modules\ecommerce\frontend\controller\payment;
use modules\ecommerce\frontend\controller\article;

/**
 * Validation controller
 *
 * @author Dani Gilabert
 * 
 */
class validation extends ecommerce
{
    
    /**
    * Validating a Credit Card
    *
    * @author Posted by William Steinmetz on June 18th, 2008
    * http://www.codeguru.com/cpp/i-n/internet/security/article.php/c15307/PHP-Tip-Validating-a-Credit-Card.htm
    * 
    */    
    public function validateCardNumber($cc_number)
    {
        
//        if ($this->isDevelopment())
//        {
//            return true;
//        }
                
        /* Validate; return value is card type if valid. */
        $false = false;
        $card_type = "";
        $card_regexes = array(
           "/^4\d{12}(\d\d\d){0,1}$/" => "visa",
           "/^5[12345]\d{14}$/"       => "mastercard",
           "/^3[47]\d{13}$/"          => "amex",
           "/^6011\d{12}$/"           => "discover",
           "/^30[012345]\d{11}$/"     => "diners",
           "/^3[68]\d{12}$/"          => "diners",
        );

        foreach ($card_regexes as $regex => $type) {
            if (preg_match($regex, $cc_number)) {
                $card_type = $type;
                break;
            }
        }

        if (!$card_type) {
            return $false;
        }

        /*  mod 10 checksum algorithm  */
        $revcode = strrev($cc_number);
        $checksum = 0; 

        for ($i = 0; $i < strlen($revcode); $i++) {
            $current_num = intval($revcode[$i]);  
            if($i & 1) {  /* Odd  position */
               $current_num *= 2;
            }
            /* Split digits and add. */
            $checksum += $current_num % 10; 
            if ($current_num >  9) {
                $checksum += 1;
            }
        }

        if ($checksum % 10 == 0) {
            return $card_type;
        } else {
            return $false;
        }        
    }     
    
    public function lastValidation()
    {
        $current_lang = lang::getCurrentLanguage();
        $shoppingcart = new shoppingcart();
        $personaldata = new personaldata();
        $payment = new payment();
        
        if ($shoppingcart->isEmpty() || $personaldata->isEmpty() || $payment->isEmpty())
        {
            return array(
                'success' => false,
                'msg' => lang::trans('expired_session'),
                'redirectUrl' => $this->getUrl(array($current_lang, 'showcase'), array('start'))
            );
        }
        
        if ($shoppingcart->isFinalTotalPriceInsufficient())
        {
            return array(
                'success' => false,
                'msg' => lang::trans('minimum_purchase_amount_to_order_is').
                         " : <b>".$shoppingcart->getMinimumFinalTotalPrice().'€</b>',
                'redirectUrl' => ''
            );
        }
        
        // Update the current and real stock properties
        $shoppingcart->updateStockProperties();
        
        // Get the shopping cart list
        $shoppingcart_list = $shoppingcart->getShoppingcart();
                    
        // Authorization to sell medicines?
        if (!config::getConfigParam(array("ecommerce", "enable_sale_of_medicines"))->value)
        {
            foreach ($shoppingcart_list as $article_code => $value) {
                $amount = $value->amount;
                $article = $value->article;

                if ($article->articleType == '2')
                {
                    return array(
                        'success' => false,
                        'msg' => 
                            '<font color="red">'.
                                'Está intentando realizar un pedido en el que algunos o todos los artículos del carrito, <b>són medicamentos</b>.'.
                            '</font>'.
                            '<br><br>'.
                            'Lo sentimos, en estos momentos <b>estamos tramitando la autorización</b> con la autoridad sanitaria competente para poder vender medicamentos de uso humano no sujetos a prescripción médica.'.
                            '<br><br>'.
                            'Por favor, antes de volver a probar a realizar el pedido, <b>elimine SÓLAMENTE los medicamentos desde el carrito de la compra</b>.'.
                            '<br><br>'.
                            'Gracias y disculpe las molestias.'.
                            '<br>'.
                            'Deemm.',
                        'redirectUrl' => $this->getUrl(array(lang::getCurrentLanguage(), 'shoppingcart'))
                    );
                }
            }
        }
        
        // Check stock for all articles
        $stock_error = false;
        $stock_msg = '';
        $article_controller = new article();
        foreach ($shoppingcart_list as $article_code => $value) {
            $amount = $value->amount;
            $article = $value->article;
            
            if (isset($article->infinityStock) && $article->infinityStock)
            {
                continue;
            }
            $stock = $article->stock;
            
            if ($amount > $stock)
            {
                $article_title = $article_controller->getTitle($article);
                $you_have_asked = $stock_error? lang::trans('you_are_also_demanding_to_us') : lang::trans('you_are_demanding_to_us');
                $stock_msg .= 
                        $you_have_asked." ".$amount." ".
                        lang::trans('units_of')." <i><u>".$article_title."</u></i>, ";
                if ($stock <= 0)
                {
                    $stock_msg .= lang::trans('and_at_this_moment_we_run_out_of_stock');
                }
                else
                {
                    $stock_msg .= lang::trans('and_at_this_moment_you_can_only_ask')." <b>".$stock."</b>";
                }
                $stock_msg .= "<br><br>";
                   
                $stock_error = true;
            }
            
        }
        if ($stock_error)
        {
            $msg = 
                    "<b>".lang::trans('we_are_sorry_just_now_we_cannot_deliver_your_order')."</b>"."<br>"."<br>".
                    $stock_msg.
                    lang::trans('please_goto_cart_update_articles').
                    "";
            return array(
                'success' => false,
                'msg' => $msg,
                'redirectUrl' => $this->getUrl(array(lang::getCurrentLanguage(), 'shoppingcart'))
            );
        }
        
        return array(
            'success' => true,
            'msg' => '',
            'redirectUrl' => ''
        );
    }
    
    public function isFakeCard($card_number, $card_expiration_date, $card_verification_code)
    {
        $fake_card_param = $this->_getFakeCardParam();
        return ($card_number === $fake_card_param->card_number &&
                $card_expiration_date === $fake_card_param->card_expiration_date &&
                $card_verification_code === $fake_card_param->card_verification_code);
    }
    
    protected function _getFakeCardParam()
    {
        return config::getConfigParam(array("ecommerce", "fake_card"))->value;
    }
    
}