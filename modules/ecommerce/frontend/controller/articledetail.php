<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\frontend\controller\brand;
use modules\ecommerce\frontend\controller\gamma;
use modules\ecommerce\frontend\controller\availability;
use modules\ecommerce\backend\controller\botplus;

/**
 * Article detail controller
 *
 * @author Dani Gilabert
 * 
 */
class articledetail extends ecommerce
{
    protected $_article;
    protected $_articles_for_sale = null;
    protected $_articles_not_available = null;
    protected $_canonical_article = null;
    protected $_article_controller;
    protected $_brand_controller;
    protected $_gamma_controller;
    protected $_availability_controller;

    public function __construct()
    {
        parent::__construct();
        $this->_article_controller = new article();
        $this->_brand_controller = new brand();
        $this->_gamma_controller = new gamma();
        $this->_availability_controller = new availability();
    }
    
    protected function _getBrands()
    {
        return $this->_availability_controller->getBrands();
    }
    
    protected function _getBrand($article, $brands)
    {
        if (!isset($brands[$article->brand]))
        {
            return null;
        }        
        
        return $brands[$article->brand];
    }
    
    protected function _getGammas()
    {
        return $this->_availability_controller->getGammas();
    }
    
    protected function _getGamma($article, $brands, $gammas)
    {
        $brand = $this->_getBrand($article, $brands);
        if (!empty($article->gamma) && isset($brand) && isset($gammas[$article->gamma][$article->brand]))
        {
            return $gammas[$article->gamma][$article->brand];
        }             
        
        return null;
    }
    
    private function _getArticlesForSale()
    {
        if (!isset($this->_articles_for_sale))
        {
            $this->_articles_for_sale = $this->_availability_controller->getArticlesForSale();            
        }
        
        return $this->_articles_for_sale;
    }
    
    private function _getArticlesNotAvailable()
    {
        if (!isset($this->_articles_not_available))
        {
            $this->_articles_not_available = $this->_availability_controller->getArticlesNotAvailable();            
        }
        
        return $this->_articles_not_available;
    }

    protected function _getRelatedArticles($art)
    {
        $category = $this->_getLastCategory($art);
        if (!isset($category))
        {
            return array();
        }
        
        $articles_for_sale = $this->_getArticlesForSale();
        if (empty($articles_for_sale))
        {
            return array();
        }
        
        $articles_for_sale = helpers::shuffleObject($articles_for_sale);
        
        $ret = array();
        foreach ($articles_for_sale as $article)
        {
            // We don't want the same
            if ($art->code === $article->code) continue;
            
            // Filter by category
            if (!isset($article->categories) || empty($article->categories)) continue;   
            $categories = explode('|', $article->categories);
            if (!in_array($category, $categories)) continue; 
    
            // Add article
            $ret[] = $article;
            
            if (count($ret) >= 3) break;
        }
        
        return $ret;
    } 
    
    private function _getLastCategory($article)
    {
        if (!isset($article->categories))
        {
            return null;
        }
        $categories_pieces = explode("|", $article->categories);
        if (empty($categories_pieces))
        {
            return null;
        }
        
        $ret = end($categories_pieces);
        return $ret;
    }

    protected function _getArticlesGroupedByDisplay($art)
    {
        if (!isset($art->articleCode2GroupDisplays) || empty($art->articleCode2GroupDisplays))
        {
            return array();
        }
        
        $code = $art->articleCode2GroupDisplays;
        
        $articles_for_sale_grouped_by_display =  $this->_availability_controller->getArticlesForSaleGroupedByDisplay();
        if (empty($articles_for_sale_grouped_by_display) || !isset($articles_for_sale_grouped_by_display->$code))
        {
            return array();
        }
        
        return $articles_for_sale_grouped_by_display->$code;
    }
    
    protected function _getCanonicalArticle()
    {
        if (!isset($this->_article->canonical) || empty($this->_article->canonical))
        {
            return null;
        }
        
        $code = $this->_article->canonical;
        
        $articles_for_sale = $this->_getArticlesForSale();
        if (isset($articles_for_sale->$code))
        {
            return $articles_for_sale->$code;
        }
        
        $articles_not_available = $this->_getArticlesNotAvailable();
        if (isset($articles_not_available->$code))
        {
            return $articles_not_available->$code;
        }        
        
        return null;
    } 
    
    protected function _getBotplusData($article)
    {
        $botplus = new botplus();
        return $botplus->getBotplusDataByCode($article->code);
    }
    
    protected function _isArticleAvailable($article)
    {
        return $this->_availability_controller->isArticleAvailable($article);
    }
    
}