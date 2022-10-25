<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\shoppingcart;
use modules\ecommerce\controller\article as articleController;

// Models
use modules\ecommerce\model\article as articleModel;

/**
 * Stock controller for E-commerce
 *
 * @author Dani Gilabert
 * 
 */
class stock extends ecommerce
{
    private $_shoppingcart_controller = null;
        
    public function __construct()
    {
        $this->_shoppingcart_controller = new shoppingcart();
    }

    public function decreaseStock($article, $amount)
    {
        if (isset($article->infinityStock) && $article->infinityStock)
        {
            return false;
        }        

        $model = $this->getArticleModel();
        $type = $model->type;
        $id = $type.'-'.$article->code;
        
        $storage = $model->loadData($id);
        if (is_null($storage)) return false;
        
        $current_stock = $model->stock;
        if (isset($current_stock) && $current_stock > 0 & $current_stock > $amount)
        {
            $new_stock = $current_stock - $amount;
        }
        else
        {
            $new_stock = 0;
        }
        
        // Finally, update stock
        $model->stock = $new_stock;
        $model->save(true);
        $model->publish(true);
    }
    
    public function updateArticles()
    {
        $article_controller = $this->_getArticleController();
        
        // Refresh article views (only the main view)
        //$article_controller->updateMainView();
        $article_controller->updateViews();

        // Reset frontend vars (only articles for sale)
        $article_controller->resetArticlesForSaleFrontendVars(); 
    }
    
    public function getAmountToAdd($article)
    {
        $ret = array();
        
        $min_amount_to_add = 0;
        $max_amount_to_add = 0;                
        $article_amount_in_shoppingcart = $this->_shoppingcart_controller->getArticleAmount($article->code);
        
        $article_stock = (isset($article->stock))? $article->stock : 0;
        if (isset($article->infinityStock) && $article->infinityStock)
        {
            if (100 > $article_amount_in_shoppingcart)
            {
                $min_amount_to_add = 1;
                $max_amount_to_add = 100 - $article_amount_in_shoppingcart;
            } 
        }
        else
        {
            if ($article_stock > $article_amount_in_shoppingcart)
            {
                $min_amount_to_add = 1;
                $max_amount_to_add = $article_stock - $article_amount_in_shoppingcart;
            }             
        }
            
        $ret['minAmountToAdd'] = $min_amount_to_add;
        $ret['maxAmountToAdd'] = $max_amount_to_add;
        
        return helpers::objectize($ret);
    }
    
    protected function _getArticleController()
    {
        return new articleController();
    }
    
    public function getArticleModel($id = null)
    {
        return new articleModel($id);
    }
    
    protected function _getWebsite()
    {
        return $this->getWebsite();
    }
    
    public function anyStock($article)
    {
        $article_controller = $this->_getArticleController();
        return $article_controller->anyStock($article);
    }
    
}