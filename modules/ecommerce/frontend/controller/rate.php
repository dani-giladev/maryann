<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\controller\article as articleController;
use modules\ecommerce\controller\articleType;
use modules\ecommerce\controller\saleRate;
use modules\ecommerce\controller\gamma;

/**
 * Rate controller
 *
 * @author Dani Gilabert
 * 
 */
class rate extends ecommerce
{
    protected $_delegation = null;
    protected $_article_types = array();
    protected $_gammas = array();
    protected $_sale_rates = array();
    
    public function __construct($delegation = null)
    {
        $this->_setDelegation($delegation);
    }
    
    private function _setDelegation($delegation)
    {
        if (!is_null($delegation))
        {
            $this->_delegation = $delegation;
        }
        else
        {
            $website = $this->_getWebsite();
            if (isset($website) && isset($website->delegation))
            {
                $this->_delegation = $website->delegation;
            }            
        }
    }
    
    public function setArticleTypes($article_types)
    {
        $this->_article_types = $article_types;
    }
    
    public function setGammas($gammas)
    {
        $this->_gammas = $gammas;
    }
    
    public function setSaleRates($sale_rates)
    {
        $this->_sale_rates = $sale_rates;
    }

    public function getArticlePrices($article)
    {
        $ret = array();
        $controller = $this->_getArticleController();
        $sale_rate = null;
        
        $prices = $controller->getPricesByArticle($article);
        if (!isset($prices))
        {
            return null;
        }
        
        $cost_price = $prices->costPrice;
        $article_margin = $prices->margin;
        $use_margin = $prices->useMargin;
        $base_price_for_cost_price_0 = $prices->basePriceForCostPrice0;
        $included_vat = $prices->includedVat;
        $recommended_retail_price = $prices->recommendedRetailPrice;
        $article_discount = $prices->discount;
        $use_discount = $prices->useDiscount;
        $final_retail_price = $prices->finalRetailPrice;
        
        // Get Vat
        $vat = 0;
        if (!$included_vat)
        {
            $vat = $this->_getVat($article);
        }
        
        // Margin
        if ($use_margin == 'saleRate')
        {
            $margin = $this->_getSaleRateValue($article, 'profitMargin', 'float', $sale_rate);
        }
        else
        {
            $margin = $article_margin;
        }
        
        // Base price
        if ($cost_price == 0)
        {
            $margin = 100;
            $use_margin = 'article';
            $base_price = $base_price_for_cost_price_0;
        }
        else
        {
            $base_price = $this->_getBasePrice($cost_price, $margin);
        }
        
        // Retail Price
        $vat_tax = ($base_price * $vat) / 100;
        $retail_price = $base_price + $vat_tax;
        
        // Recommended Retail Price
        if ($recommended_retail_price > 0)
        {
            $retail_price = $recommended_retail_price;
        }
        
        // Discount
        $hide_discount = false;
        $hide_discount_badge = false;
        if ($use_discount == 'saleRate')
        {
            $discount = $this->_getSaleRateValue($article, 'discount', 'float', $sale_rate);
            $hide_discount = $this->_getSaleRateValue($article, 'hideDiscount', 'boolean', $sale_rate);
            $hide_discount_badge = $this->_getSaleRateValue($article, 'hideDiscountBadge', 'boolean', $sale_rate);
        }
        elseif ($use_discount == 'gamma')
        {
            $discount = $this->_getGammaDiscount($article);
        }
        else
        {
            $discount = $article_discount;
        }
        
        // Final Retail Price (with discount)
        if ($final_retail_price == 0)
        {
            $discount_tax = ($retail_price * $discount) / 100;
            $final_retail_price = $retail_price - $discount_tax;            
        }
        else
        {
            $discount = 100 - (($final_retail_price * 100) / $retail_price);
            $discount = ceil($discount);
        }
        
        // Final margin
        $final_margin = 0;
        if ($included_vat)
        {
            $retail_price_aux = $final_retail_price;
        }
        else
        {
            $discount_over_base_price_tax = ($base_price * $discount) / 100;
            $retail_price_aux = $base_price - $discount_over_base_price_tax;
        }
        if ($retail_price_aux > 0)
        {
            $final_margin = 100 - (($cost_price * 100) / $retail_price_aux);
        }   
            
        $ret['costPrice'] = number_format(round($cost_price, 2), 2, ".", "");
        $ret['margin'] = $margin;
        $ret['basePrice'] = number_format(round($base_price, 2), 2, ".", "");
        $ret['includedVat'] = $included_vat;
        $ret['vat'] = $vat;
        $ret['retailPrice'] = number_format(round($retail_price, 2), 2, ".", "");
        $ret['discount'] = $discount;
        $ret['hideDiscount'] = $hide_discount;
        $ret['hideDiscountBadge'] = $hide_discount_badge;
        $ret['finalRetailPrice'] = number_format(round($final_retail_price, 2), 2, ".", "");
        $ret['finalMargin'] = $final_margin;
        
        return helpers::objectize($ret);
    }
    
    public function isAvailablePrice($article)
    {
        $prices = $this->getArticlePrices($article);
        if (!isset($prices)) return false;
        
        $price = (float) $prices->finalRetailPrice;
        return ($price > 0);
    }
    
    protected function _getVat($article)
    {
        if (
                isset($this->_article_types) && 
                isset($this->_article_types[$article->articleType]) && 
                isset($this->_article_types[$article->articleType]->vat)
           )
        {
            $vat = (float) $this->_article_types[$article->articleType]->vat;
            return $vat;            
            
        }
        
        if (!isset($article->articleType) || empty($article->articleType)) return 0;
        $controller = new articleType();
        $article_type = $controller->getArticleTypeByCode($article->articleType);
        if (!isset($article_type) || empty($article_type)) return 0;
        $vat =  (float) $article_type->vat;
        return $vat;
    } 
    
    protected function _getSaleRateValue($article, $property, $type = 'float', &$sale_rate = null)
    {
        if ($type === 'boolean')
        {
            $default_value = false;
        }
        else
        {
            $default_value = 0;
        }
        
        if (!isset($article->saleRate) || empty($article->saleRate)) return $default_value;
        
        if (
                isset($this->_sale_rates) && !empty($this->_sale_rates) &&
                isset($this->_sale_rates[$article->saleRate]) && 
                isset($this->_sale_rates[$article->saleRate]->$property)
           )
        {
            $value = $this->_sale_rates[$article->saleRate]->$property;
            if ($type === 'boolean')
            {
                $ret = (bool) $value;
            }
            else
            {
                $ret = (float) $value;
            }
            return $ret;            
            
        }
        
        // Special behaviour
        if (!empty($this->_sale_rates) && ($property === 'hideDiscountBadge' || $property === 'hideDiscount'))
        {
            return $default_value;
        }
        
        if (is_null($sale_rate))
        {
            $controller = new saleRate();
            $sale_rate = $controller->getSaleRateByCode($article->saleRate);            
        }
        if (!isset($sale_rate) || empty($sale_rate)) return $default_value;
        $value =  $sale_rate->$property;
        if ($type === 'boolean')
        {
            $ret = (bool) $value;
        }
        else
        {
            $ret = (float) $value;
        }
        return $ret; 
    }
    
    protected function _getBasePrice($cost_price, $margin)
    {      
        // Precio = Coste / (1 – %margen)
        // http://manueldelgado.com/como-calcular-el-precio-de-venta-coste-margen/
        $base_price = $cost_price / (1 - ( $margin / 100));
        return $base_price;
    }
    
    protected function _getGammaDiscount($article)
    {
        $discount = 0;
        
        if (!isset($article->gamma) || $this->_isGammaEmpty($article->gamma) ||
            !isset($article->brand) || empty($article->brand))
        {
            return $discount;            
        } 
        
        if (
                isset($this->_gammas) && !empty($this->_gammas) &&
                isset($this->_gammas[$article->gamma]) && 
                isset($this->_gammas[$article->gamma][$article->brand])
           )
        {
            $gamma = $this->_gammas[$article->gamma][$article->brand];
            if (isset($gamma->discount))
            {
                $discount = (float) $gamma->discount;
            }
            return $discount;            
        }
        
        $controller = new gamma();
        $gamma = $controller->getGammaByCode($article->gamma, $article->brand, true);
        if (!isset($gamma) || empty($gamma)) return $discount;
        if (isset($gamma->discount))
        {
            $discount = (float) $gamma->discount;
        }        
        return $discount;
    }
    
    protected function _isGammaEmpty($gamma) {
        $is_empty = (
            empty($gamma) ||
            strpos($gamma, '...') !== false
        );
        return $is_empty;
    }
    
    protected function _getArticleController()
    {
        return new articleController();
    }
    
    protected function _getWebsite()
    {
        return $this->getWebsite();
    }
    
}