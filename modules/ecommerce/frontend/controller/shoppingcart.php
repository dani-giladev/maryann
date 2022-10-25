<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\config\controller\config;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\session;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\frontend\controller\voucher;

/**
 * Shoppingcart controller
 *
 * @author Dani Gilabert
 * 
 */
class shoppingcart extends ecommerce
{
    protected $_voucher_controller;

    public function __construct()
    {
        parent::__construct();
        $this->_voucher_controller = new voucher();
    }
    
    public function getShoppingcart()
    {
        $value = session::getSessionVar('ecommerce-shoppingcart');
        return (isset($value))? $value : new \stdClass();
    }
    
    public function setShoppingcart($object)
    {
        session::setSessionVar('ecommerce-shoppingcart', $object);
    }
    
    public function flush()
    {
        $this->setShoppingcart(new \stdClass());
        $this->_voucher_controller->flush();
    }
    
    public function isEmpty($shoppingcart = null)
    {
        if (is_null($shoppingcart))
        {
            $shoppingcart = (array) $this->getShoppingcart();
        }
        
        if (!isset($shoppingcart) || empty((array) $shoppingcart))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function isFinalTotalPriceInsufficient()
    {
        $final_total_price = $this->getFinalTotalPrice();
        $minimum_final_total_price = $this->getMinimumFinalTotalPrice();
        if ($final_total_price < $minimum_final_total_price)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getTotalAmount()
    {
        $ret = 0;
        
        $shoppingcart = $this->getShoppingcart();
        if ($this->isEmpty($shoppingcart)) return $ret;
        
        foreach ($shoppingcart as $value) {
            $ret += $value->amount;
        }  
        
        return $ret;
    }
    
    public function getFinalTotalPrice()
    {
        $ret = 0;
        
        // Add total price of articles
        $ret += $this->getTotalPrice();
        
        // Add shipping cost
        $ret += $this->getShippingCost();
        
        // Add voucher
        $ret += $this->getVoucherDiscount();
        
        // Add discount by second unit
        $ret += $this->get2ndUnitDiscount();
        
        return $ret;
    }
    
    public function getTotalPrice()
    {
        $ret = 0;
        
        $shoppingcart = $this->getShoppingcart();
        if ($this->isEmpty($shoppingcart)) return $ret;
        
        foreach ($shoppingcart as $value) {
            $ret += ($value->amount * $value->price);
        }
        
        return $ret;
    }
    
    public function getShippingCost()
    {
        $total_price_of_articles = $this->getTotalPrice();
        if ($total_price_of_articles === 0)
        {
            return 0;
        }
        
        $free_shipping_cost_from = $this->getFreeShippingCostFrom();
        if ($total_price_of_articles >= $free_shipping_cost_from)
        {
            return 0;
        }
        
        return config::getConfigParam(array("ecommerce", "shipping_cost"))->value;
    }
    
    public function getFreeShippingCostFrom()
    {
        return config::getConfigParam(array("ecommerce", "free_shipping_cost_from"))->value;
    }
    
    public function getMinimumFinalTotalPrice()
    {
        return config::getConfigParam(array("ecommerce", "minimum_purchase_amount"))->value;
    }
    
    public function addArticle($article, $amount = 1)
    {
        $code = $article->code;
        $shoppingcart = $this->getShoppingcart();
        
        if (!$this->isEmpty($shoppingcart) && isset($shoppingcart->$code))
        {
            $shoppingcart->$code->amount += $amount;
        }
        else
        {
            $shoppingcart->$code = new \stdClass();
            $shoppingcart->$code->amount = $amount;  
            $shoppingcart->$code->article = $article;
            
            $price = 0;
            $prices = $article->prices;
            if (isset($prices))
            {
                $price = $prices->finalRetailPrice;
            }  
            $shoppingcart->$code->price = $price;          
        }
        
        $this->setShoppingcart($shoppingcart);
    }
    
    public function removeArticle($code, $is_mobile = false)
    {
        $shoppingcart = $this->getShoppingcart();
        if (!$this->isEmpty($shoppingcart) && isset($shoppingcart->$code))
        {
            if ($is_mobile)
            {
                $shoppingcart->$code->amount--;
                if ($shoppingcart->$code->amount <= 0)
                {
                    unset($shoppingcart->$code);
                }
            }
            else
            {
                unset($shoppingcart->$code);
            }
        }
        $this->setShoppingcart($shoppingcart);
    }
    
    public function getArticleAmount($code)
    {
        $shoppingcart = $this->getShoppingcart();
        if ($this->isEmpty($shoppingcart) || !isset($shoppingcart->$code))
        {
            return 0;
        }
        return $shoppingcart->$code->amount;
    }
    
    public function setArticleAmount($code, $amount)
    {
        $shoppingcart = $this->getShoppingcart();
        if (!$this->isEmpty($shoppingcart) && isset($shoppingcart->$code))
        {
            $shoppingcart->$code->amount = $amount;
        }
        $this->setShoppingcart($shoppingcart);        
    }
    
    public function getTotalArticlePrice($code, $amount)
    {
        $price = 0;
        $shoppingcart = $this->getShoppingcart();
        if (!$this->isEmpty($shoppingcart) && isset($shoppingcart->$code))
        {
            $price = $shoppingcart->$code->price * $shoppingcart->$code->amount;
        }
        return $price;        
    }
    
    public function updateStockProperties()
    {
        $shoppingcart = $this->getShoppingcart();
        
        $article_controller = new article();
        
        foreach ($shoppingcart as $article_code => $value) {
         
            // Get article by code
            $article_model = $article_controller->getArticleByCode($value->article->code);
            $article = $article_model->getStorage();
            
            // Set the real/current stock article
            $shoppingcart->$article_code->article->stock = $article->stock;
        }
        
        $this->setShoppingcart($shoppingcart);        
    }
    
    public function getVoucherDiscount()
    {
        $voucher = $this->_voucher_controller->getVoucher();
        if ($this->_voucher_controller->isEmpty($voucher))
        {
            return 0;
        }
        
        if ($voucher->voucherType === 'sumup-in-total')
        {
            return $voucher->value;
        }
        else if ($voucher->voucherType === 'free-shippingcost')
        {
            $shipping_cost = $this->getShippingCost();
            if ($shipping_cost === 0)
            {
                return 0;
            }
            return $voucher->value;
        }        
        elseif ($voucher->voucherType === 'percentage-over-total')
        {
            //$total_price = $this->getTotalPrice();
            $total_price = 0;
            $shoppingcart = $this->getShoppingcart();
            foreach ($shoppingcart as $article_code => $value) {

                $article_type = $value->article->articleType;
                if ($article_type === '1')
                {
                    $total_price += ($value->amount * $value->price);
                }
            }            
            
            return ($total_price * $voucher->value) / 100;
        }
        
        return 0;
    }
    
    public function get2ndUnitDiscount()
    {
        $ret = 0;
        
        $shoppingcart = $this->getShoppingcart();
        foreach ($shoppingcart as $article_code => $value) {

            if (!isset($value->article->secondUnitDiscount) || empty($value->article->secondUnitDiscount))
            {
                continue;
            }
            
            $num = $value->amount;
            if ($num <= 1)
            {
                continue;
            }
            $is_even = ($num % 2 == 0);
            if (!$is_even)
            {
                $num--;
            }
            $num = $num / 2;
            
            $discount = ($value->price * $value->article->secondUnitDiscount) / 100;
            
            $ret -= ($num * $discount);
        }            
        
        return $ret;
    }
    
}