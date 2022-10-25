<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\helpers\controller\helpers;
use core\globals\controller\globals;
use core\botplus\controller\botplus;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\rate;
use modules\ecommerce\frontend\controller\stock;
use modules\ecommerce\controller\article as articleController;
use modules\ecommerce\controller\articleType;
use modules\ecommerce\controller\articleProperty;
use modules\ecommerce\controller\laboratory;
use modules\ecommerce\controller\brand;
use modules\ecommerce\controller\gamma;
use modules\ecommerce\controller\saleRate;

// Models
use modules\ecommerce\model\articleType as articleTypeModel;

/**
 * Availability controller
 *
 * @author Dani Gilabert
 * 
 */
class availability extends ecommerce
{
    private $_article_controller = null;
    private $_botplus_controller = null;
            
    protected $_delegation = null;
    protected $_article_types = array();
    protected $_brands = array();
    protected $_gammas = array();
    protected $_sale_rates = array();
    
    public function __construct($delegation = null)
    {
        $this->_article_controller = $this->_getArticleController();
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
    
    public function getDelegation()
    {
        return $this->_delegation;
    }
    
    public function setDelegation($value)
    {
        $this->_delegation = $value;
    }
    
    public function getArticleTypes()
    {
        $cached_article_types = globals::getGlobalVar('ecommerce-article-types');
        if (isset($cached_article_types) && !empty($cached_article_types))
        {
            return $cached_article_types;
        }
        
        $ret = array();
        
        $controller = new articleType();
        $article_types = $controller->getAvailableArticleTypes(false, true); // It's always public=false
        if (isset($article_types) && !empty($article_types))
        {
            foreach ($article_types as $value)
            {
                $code = $value->code;
                $ret[$code] = $value;
            }
        }

        globals::setGlobalVar('ecommerce-article-types', $ret);
        return $ret;
    }
    
    public function setArticleTypes($article_types)
    {
        $this->_article_types = $article_types;
    }
    
    public function getArticleProperties()
    {
        $cached_article_properties = globals::getGlobalVar('ecommerce-article-properties');
        if (isset($cached_article_properties) && !empty($cached_article_properties))
        {
            return $cached_article_properties;
        }
        
        $ret = array();
        
        $controller = new articleProperty();
        $article_properties = $controller->getAvailableArticleProperties(false, true); // It's always public=false
        if (isset($article_properties) && !empty($article_properties))
        {
            foreach ($article_properties as $article_property)
            {
                $code = $article_property->code;
                $values = array();
                foreach ($article_property->values as $value) {
                    $values[$value->code] = $value;
                }
                $article_property->values = $values;
                $ret[$code] = $article_property;
            }
        }
        
        globals::setGlobalVar('ecommerce-article-properties', $ret);
        return $ret;
    }
    
    public function getBrands()
    {
        $ret = array();
        $brands = $this->_getBrands();
        $labs = $this->_getLaboratories();
        
        if (
                (!isset($brands) || empty($brands)) && 
                (!isset($labs) || empty($labs))
        )
        {
            return $ret;
        }
        
        // Add brands
        foreach ($brands as $value)
        {
            $code = $value->code;
            //$value->isBrand = true;
            $ret[$code] = $value;
        }
        
        // Add labs
        foreach ($labs as $key => $value)
        {
            $code = $value->code;
            if (isset($ret[$code]))
            {
                $value = $ret[$code];
                if (isset($labs[$key]->outstanding) && $labs[$key]->outstanding)
                {
                    $value->outstanding = true;
                }
            }
            $value->isLaboratory = true;
            $ret[$code] = $value;            
        }
        
        return $ret;
    }
    
    public function setBrands($brands)
    {
        $this->_brands = $brands;
    }
    
    private function _getBrands()
    {
        $cached_brands = globals::getGlobalVar('ecommerce-brands');
        if (isset($cached_brands) && !empty($cached_brands))
        {
            return $cached_brands;
        }
        
        $controller = new brand();
        $brands = $controller->getBrands(true, true);
        globals::setGlobalVar('ecommerce-brands', $brands);
        
        return $brands;
    }
    
    private function _getLaboratories()
    {
        $cached_labs = globals::getGlobalVar('ecommerce-labs');
        if (isset($cached_labs) && !empty($cached_labs))
        {
            return $cached_labs;
        }
        
        $controller = new laboratory();
        $labs = $controller->getLaboratories(true, true);
        globals::setGlobalVar('ecommerce-labs', $labs);
        
        return $labs;
    }
    
    public function getGammas()
    {
        $cached_gammas = globals::getGlobalVar('ecommerce-gammas');
        if (isset($cached_gammas) && !empty($cached_gammas))
        {
            return $cached_gammas;
        }
        
        $ret = array();
        
        $controller = new gamma();
        $gammas = $controller->getGammas(true, true);
        if (isset($gammas) && !empty($gammas))
        {
            foreach ($gammas as $value)
            {
                if (!$value->available) continue;
                $code = $value->code;
                $brand = $value->brand;
                $ret[$code][$brand] = $value;
            }
        }

        globals::setGlobalVar('ecommerce-gammas', $ret);
        return $ret;
    }
    
    public function setGammas($gammas)
    {
        $this->_gammas = $gammas;
    }
    
    public function getSaleRates()
    {
        $cached_sale_rates = globals::getGlobalVar('ecommerce-sale-rates');
        if (isset($cached_sale_rates) && !empty($cached_sale_rates))
        {
            return $cached_sale_rates;
        }
        
        $ret = array();
        
        $controller = new saleRate();
        $sale_rates = $controller->getAvailableSaleRates(false, true); // It's always public=false
        if (isset($sale_rates) && !empty($sale_rates))
        {
            foreach ($sale_rates as $value)
            {
                $code = $value->code;
                $ret[$code] = $value;
            }
        }
        
        globals::setGlobalVar('ecommerce-sale-rates', $ret);
        return $ret;
    }
    
    public function setSaleRates($sale_rates)
    {
        $this->_sale_rates = $sale_rates;
    }
    
    public function getArticlesForSale()
    {
        $cached_articles_for_sale = globals::getGlobalVar('ecommerce-articles-for-sale');
        if (isset($cached_articles_for_sale) && !empty($cached_articles_for_sale))
        {
            return $cached_articles_for_sale;
        }
                
        $articles_for_sale = array();
        $articles_not_available = array();
                    
        // Get available articles
        $available_articles = $this->_article_controller->getArticles(true, true);
        if (!isset($available_articles))
        {
            globals::setGlobalVar('ecommerce-articles-for-sale', $articles_for_sale);
            globals::setGlobalVar('ecommerce-articles-not-available', $articles_not_available);
            return $articles_for_sale;
        }

        if (empty($this->_article_types))
        {
            $this->_article_types = $this->getArticleTypes();
        }
        if (empty($this->_brands))
        {
            $this->_brands = $this->getBrands();
        }
        if (empty($this->_gammas))
        {
            $this->_gammas = $this->getGammas();
        }
        if (empty($this->_sale_rates))
        {
            $this->_sale_rates = $this->getSaleRates();
        }
        
        $rate = new rate($this->_delegation);
        $rate->setArticleTypes($this->_article_types);
        $rate->setGammas($this->_gammas);
        $rate->setSaleRates($this->_sale_rates);        
        
        // Geta all articles for sale
        foreach ($available_articles as $article) 
        {
            // Is available?
            if (!$this->isArticleAvailable($article))
            {
                $articles_not_available[$article->code] = $article;
                continue;
            }
            
            // Is delegation matching?
            if (!$this->_isDelegationMatching($article))
            {
                $articles_not_available[$article->code] = $article;
                continue;
            }

            // Is start and en date matching?
            if (!$this->_isStartEndDatesMatching($article))
            {
                $articles_not_available[$article->code] = $article;
                continue;
            }

            // Any stock?
            if (!$this->_anyStock($article) && !$this->_isVisibleIfNoStock($article))
            {
                $articles_not_available[$article->code] = $article;
                continue;
            }

            if (!$this->_isBrandAvailable($article->brand))
            {
                $articles_not_available[$article->code] = $article;
                continue;
            }
                
            // Price
            $price = 0;
            $prices = $rate->getArticlePrices($article);
            $article->prices = $prices;
            if (isset($prices))
            {
                $price = $prices->finalRetailPrice;
                if ($price <= 0)
                {
                    $articles_not_available[$article->code] = $article;
                    continue;
                }                  
            }

            // Add article
            $articles_for_sale[$article->code] = $article;
        }
        
        $objectized_articles_for_sale = helpers::objectize($articles_for_sale);
        globals::setGlobalVar('ecommerce-articles-for-sale', $objectized_articles_for_sale);
        
        $objectized_articles_not_available = helpers::objectize($articles_not_available);
        globals::setGlobalVar('ecommerce-articles-not-available', $objectized_articles_not_available);
        
        return $objectized_articles_for_sale;
    }
    
    public function getArticlesNotAvailable()
    {
        $cached_articles_not_available = globals::getGlobalVar('ecommerce-articles-not-available');
        if (isset($cached_articles_not_available) && !empty($cached_articles_not_available))
        {
            return $cached_articles_not_available;
        }
        
        return array();
    }
    
    public function getArticlesForSaleGroupedByDisplay()
    {
        $cached_articles_for_sale_grouped_by_display = globals::getGlobalVar('ecommerce-articles-for-sale-grouped-by-display');
        if (isset($cached_articles_for_sale_grouped_by_display) && !empty($cached_articles_for_sale_grouped_by_display))
        {
            return $cached_articles_for_sale_grouped_by_display;
        }
        
        $ret = array();
        
        $articles_for_sale = $this->getArticlesForSale();
        foreach ($articles_for_sale as $article_code => $article)
        {
            if (!isset($article->articleCode2GroupDisplays) || empty($article->articleCode2GroupDisplays))
            {
                continue;
            }
            
            // Add article
            $ret[$article->articleCode2GroupDisplays][$article_code] = $article;
        }
        
        $objectized_ret = helpers::objectize($ret);
        globals::setGlobalVar('ecommerce-articles-for-sale-grouped-by-display', $objectized_ret);
        return $objectized_ret;
    }

    public function getArticlesForSaleWithStock()
    {
        $cached_articles_for_sale_with_stock = globals::getGlobalVar('ecommerce-articles-for-sale-with-stock');
        if (isset($cached_articles_for_sale_with_stock) && !empty($cached_articles_for_sale_with_stock))
        {
            return $cached_articles_for_sale_with_stock;
        }
        
        $ret = array();
        
        $articles_for_sale = $this->getArticlesForSale();
        $stock = new stock();
        foreach ($articles_for_sale as $article_code => $article)
        {
            // Check stock
            if (!$stock->anyStock($article)) continue; 
            
            // Add article
            $ret[$article->code] = $article;
        }
        
        $objectized_ret = helpers::objectize($ret);
        globals::setGlobalVar('ecommerce-articles-for-sale-with-stock', $objectized_ret);
        return $objectized_ret;
    }

    public function getOutstandingArticles()
    {   
        $outstanding_articles = globals::getGlobalVar('ecommerce-articles-for-sale-outstanding');
        if (isset($outstanding_articles) && !empty($outstanding_articles))
        {
            return $outstanding_articles;
        }
        
        $ret = array();
        
        $articles = $this->getArticlesForSaleWithStock();
        foreach ($articles as $article_code => $article)
        {
            if (!$article->outstanding) continue; 
            
            // Add article
            $ret[$article_code] = $article;
        }
        
        $object_ret = helpers::objectize($ret);
        globals::setGlobalVar('ecommerce-articles-for-sale-outstanding', $object_ret); 
        return $object_ret;
    }

    public function getNoveltyArticles()
    {
        $novelty_articles = globals::getGlobalVar('ecommerce-articles-for-sale-novelty');
        if (isset($novelty_articles) && !empty($novelty_articles))
        {
            return $novelty_articles;
        }
        
        $ret = array();
        
        $articles = $this->getArticlesForSaleWithStock();
        foreach ($articles as $article_code => $article)
        {
            if (!$article->novelty) continue; 
            
            // Add article
            $ret[$article_code] = $article;
        }
        
        $object_ret = helpers::objectize($ret);
        globals::setGlobalVar('ecommerce-articles-for-sale-novelty', $object_ret); 
        return $object_ret;     
    }
    
    protected function _getArticleController()
    {
        return new articleController();
    }
    
    protected function _getArticleTypeController()
    {
        return new articleType();
    }
    
    protected function _getArticleTypeModel()
    {
        return new articleTypeModel();
    }
    
    protected function _getWebsite()
    {
        return $this->getWebsite();
    }
    
    public function isArticleAvailable($article)
    {
        return ($article->available && $article->validated && $this->_isAuthorizedByBotplus($article));
    } 
    
    protected function _isAuthorizedByBotplus($article)
    {
        if (is_null($this->_botplus_controller))
        {
            $this->_botplus_controller = new botplus();
        }
        $is_auth = $this->_botplus_controller->isAuthorized($article->code, $article->articleType);
        return $is_auth;
    }
    
    protected function _isDelegationMatching($article)
    {
        $delegations = explode("|", $article->delegations);
        return (in_array($this->_delegation, $delegations));
    }    
    
    protected function _isAssignedToAvailableArticleType($article)
    {
        if (!isset($article->articleType) || empty($article->articleType))
        {
            return false;
        }
        $model = $this->_getArticleTypeModel();
        $model_type = $model->type;
        $id = $model_type.'-'.strtolower($article->articleType);
        $storage = $model->loadData($id);
        if (is_null($storage)) return false;
        
        return $model->available;
    }   
    
    protected function _isStartEndDatesMatching($article)
    {
        $now = strtotime(date('d-m-Y'));
        
        if (isset($article->startDate) && !empty($article->startDate))
        {
            $startDate = strtotime($article->startDate);
            if ($now < $startDate) return false;
        }
        
        if (isset($article->endDate) && !empty($article->endDate))
        {
            $endDate = strtotime($article->endDate);
            if ($now > $endDate) return false;
        }
        
        return true;
    }
    
    protected function _anyStock($article)
    {
        return $this->_article_controller->anyStock($article);
    } 
    
    protected function _isVisibleIfNoStock($article)
    {
        return $this->_article_controller->isVisibleIfNoStock($article);
    } 
    
    protected function _isBrandAvailable($brand)
    {
        if (!isset($brand) || empty($brand)) return false;
        if (!isset($this->_brands) || empty($this->_brands)) return false;
        if (!isset($this->_brands[$brand])) return false;
        return $this->_brands[$brand]->available;
    }
}