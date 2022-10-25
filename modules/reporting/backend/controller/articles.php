<?php

namespace modules\reporting\backend\controller;

// Controllers
use modules\reporting\backend\controller\reporting;
use modules\ecommerce\controller\article;
use modules\ecommerce\frontend\controller\availability;
use modules\ecommerce\frontend\controller\rate;

// Views
use modules\reporting\backend\view\articles as view;

/**
 * Backend Articles controller for reporting
 *
 * @author Dani Gilabert
 * 
 */
class articles extends reporting
{
    
    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
    }  
    
    public function renderReport()
    {
        $data = array();
        
        /*
         * 
         * Main summary
         * 
         */
        $data['total_articles'] = 0;
        $data['total_articles_for_sale'] = 0;
        $data['total_articles_for_sale_and_visible'] = 0;
        $data['total_articles_for_sale_with_stock'] = 0;
        $data['total_articles_for_sale_without_stock'] = 0;
        $data['total_articles_for_sale_without_stock_visible'] = 0;
        $data['total_articles_for_sale_without_stock_no_visible'] = 0;
        $data['total_articles_not_for_sale'] = 0;
        
        $article_controller = new article();
        $raw_all_articles_list = $article_controller->getArticles();
        $all_articles = array();
        foreach($raw_all_articles_list as $article)
        {
            $article->anyStock = $article_controller->anyStock($article);
            $article->inErp = $article_controller->isInErp($article);
            $all_articles[$article->code] = $article;
        }         
        $data['total_articles'] = count($all_articles);
        
        /*
         * 
         * Articles for sale
         * 
         */
        $data['articles_for_sale_with_stock_infinit'] = array();
        $data['articles_for_sale_and_visible_without_photos'] = array();
        $data['articles_for_sale_with_incoherent_sale_rate'] = array();
        $availability = new availability($this->_delegation);        
        $raw_articles_for_sale_list = $availability->getArticlesForSale(array(), true);
        $articles_for_sale = array();
        foreach ($raw_articles_for_sale_list as $article)
        {
            $articles_for_sale[$article->code] = $article;
        }
        $data['total_articles_for_sale'] = count($articles_for_sale);
        foreach ($articles_for_sale as $article_code => $article)
        {
            // Any stock
            if (isset($article->infinityStock) && $article->infinityStock)
            {
                $any_stock = true;
                $data['articles_for_sale_with_stock_infinit'][$article_code] = $article;
            }
            else
            {
                $any_stock = (isset($article->stock) && $article->stock > 0);
            }
            
            if ($any_stock)
            {
                $visible = true;
                $data['total_articles_for_sale_with_stock']++;
            }
            else
            {
                $data['total_articles_for_sale_without_stock']++;
                
                if (isset($article->visibleIfNoStock) && $article->visibleIfNoStock)
                {
                    $visible = true;
                }
                else
                {
                    $visible = false;
                }
            }
            
            if ($visible)
            {
                $data['total_articles_for_sale_and_visible']++;
                if (!isset($article->images) || empty($article->images))
                {
                    $data['articles_for_sale_and_visible_without_photos'][$article_code] = $article;
                }
            }
            
            if (!$any_stock)
            {
                if ($visible)
                {
                    $data['total_articles_for_sale_without_stock_visible']++;
                }
                else 
                {
                    $data['total_articles_for_sale_without_stock_no_visible']++;
                }
                
            }
            
            // Incoherent sale rate?
            if (empty($article->saleRate) && ($article->useMargin === 'saleRate' || $article->useDiscount === 'saleRate'))
            {
                $data['articles_for_sale_with_incoherent_sale_rate'][$article_code] = $article;
            }
        }
        
        /*
         * 
         * Articles not for sale
         * 
         */        
        $data['articles_not_for_sale'] = array();
        $data['articles_not_for_sale_and_pending_of_validate'] = array();
        $articles_not_for_sale = array();
        foreach($all_articles as $article_code => $article)
        {
            if (!isset($articles_for_sale[$article_code]))
            {
                $articles_not_for_sale[$article_code] = $article;
            }
        }  
        $data['total_articles_not_for_sale'] = count($articles_not_for_sale);
        $rate = new rate($this->_delegation);
        foreach($articles_not_for_sale as $article_code => $article)
        {
            $prices = $rate->getArticlePrices($article);
            $article->prices = $prices;
                
            if ($article->validated)
            {
                $data['articles_not_for_sale'][$article_code] = $article;
            }
            else
            {
                $data['articles_not_for_sale_and_pending_of_validate'][$article_code] = $article;
            }
        }
        usort($data['articles_not_for_sale'], function($a, $b)
        {
            return strcmp($a->brandName, $b->brandName);
        });        
        
        // Go!
        echo $this->_view->getHtmlReport($data);
    } 
    
    
}