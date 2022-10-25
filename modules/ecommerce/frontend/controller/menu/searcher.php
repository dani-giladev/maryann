<?php

namespace modules\ecommerce\frontend\controller\menu;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\availability;
use modules\ecommerce\frontend\controller\article;

// Views
use modules\ecommerce\frontend\view\menu\searcher as view;

/**
 * E-commerce frontend articles searcher controller
 *
 * @author Dani Gilabert
 * 
 */
class searcher extends ecommerce
{ 
    protected $_view;
    protected $_availability;
    protected $_brands = array();
    protected $_gammas = array();
    protected $_categories = array();
    
    public function __construct()
    {
        $this->_view = new view();
        $this->_article_controller = new article();
        $this->_availability = new availability();
    }
    
    public function search($data)
    {
        $needles = explode(' ', $data->value);
        
        // Set brands and gammas
        $this->_setBrands();
        $this->_setGammas();
        $this->_setCategories();
        $this->_view->brands = $this->_brands;
        $this->_view->gammas = $this->_gammas;
        $this->_view->categories = $this->_categories;
        
        // Get data
        $brands = $this->_getBrands($needles);
        $articles = $this->_getArticles($needles);
        $categories = $this->_getCategories($needles);
        
        // Render
        $ret = $this->renderSearchResult($needles, $articles, $brands, $categories);
        echo json_encode($ret);
    }
    
    protected function renderSearchResult($needles, $articles, $brands, $categories) 
    {
        $nonutf8 = $this->_view->renderSearchResult($needles, $articles, $brands, $categories);
        $utf8 =  helpers::removeNoUtf8Chars($nonutf8);
        $ret['searchResult'] = $utf8;
        return $ret;
    }
    
    public function setBrands($brands)
    {
        $this->_brands = $brands;
        $this->_availability->setBrands($this->_brands);
        $this->_view->brands = $this->_brands;
    }
    
    protected function _setBrands()
    {
        $this->_brands = $this->_availability->getBrands();
        $this->_availability->setBrands($this->_brands);
    }
    
    public function setGammas($gammas)
    {
        $this->_gammas = $gammas;
        $this->_availability->setGammas($this->_gammas);
        $this->_view->gammas = $this->_gammas;
    }
    
    protected function _setGammas()
    {
        $this->_gammas = $this->_availability->getGammas();
        $this->_availability->setGammas($this->_gammas);
    }
    
    protected function _setCategories()
    {
        $this->_categories = $this->_availability->getCategoriesTree();
    }
    
    protected function _getBrands($needles)
    {
        $ret = array();
        
        if (empty($this->_brands))
        {
            return $ret;
        }
        
        foreach ($this->_brands as $brand)
        {
            if (
                    !$brand->available || 
                    (isset($brand->visible) && !$brand->visible) || 
                    (isset($brand->empty) && $brand->empty)
            )
            {
                continue;
            }
            
            $brand_name = $brand->name;
            $cleaned_brand_name = $this->_cleanString($brand_name);

            foreach ($needles as $needle)
            {
                if (strlen($needle) < 3)
                {
                    continue;
                }
                
                $cleaned_needle = $this->_cleanString($needle);
                
                if (strpos(mb_strtolower($cleaned_brand_name), mb_strtolower($cleaned_needle)) !== false)
                {
                    $ret[$brand_name] = $brand;
                    break;
                }
            }

        }
        
        ksort($ret);
        
        return $ret;
    }
    
    protected function _getArticles($needles)
    {
        $ret = array();

        $list = $this->_availability->getArticlesForSale();
        
        if (!empty($list))
        {
            $counter = 1;
            foreach ($list as $article)
            {
                $article_title = "";
                if (!$this->isArticleMacthed($article, $needles, $article_title))
                {
                    continue;
                }
                
                $ret[$article_title] = $article;
                //if ($counter > 10) break;
                $counter++;
            }
        }
        
        ksort($ret);
        
        return $ret;
    }
    
    public function isArticleMacthed($article, $needles, &$article_title)
    {
        $article_title = $this->_view->getArticleTitle($article);
        if (!isset($article_title))
        {
            return false;
        }

        $cleaned_article_title = $this->_cleanString($article_title);

        // Search by title
        $matched = true;
        foreach ($needles as $needle)
        {
            $cleaned_needle = $this->_cleanString($needle);
            if (strpos(mb_strtolower($cleaned_article_title), mb_strtolower($cleaned_needle)) === false)
            {
                $matched = false;
                break;
            }
        }

        if (!$matched)
        {
            // Search by description
            $article_description = $article->code.' - '.strip_tags($this->_article_controller->getShortDescription($article));
            $matched = true;
            foreach ($needles as $needle)
            {
                if (strlen($needle) < 3)
                {
                    continue;
                }
                if (strpos(mb_strtolower($article_description), mb_strtolower($needle)) === false)
                {
                    $matched = false;
                    break;
                }
            }                    
        }

        if (!$matched)
        {
            return false;
        }
        
        return true;
    }
    
    protected function _getCategories($needles)
    {
        $ret = array();
        
        if (empty($this->_categories))
        {
            return $ret;
        }
        
        $current_lang = lang::getCurrentLanguage();
        $title_property = 'titles-'.$current_lang;
        
        foreach($this->_categories->categories as $category_code => $category)
        {
            if (!$category->available)
            {
                continue;
            }
            
            if (isset($category->$title_property) &&
                !empty($category->$title_property))
            {
                $category_name = $category->$title_property;
            }
            else
            {
                $category_name = $category->name;
            }
            
            $cleaned_category_name = $this->_cleanString($category_name);

            foreach ($needles as $needle)
            {
                $cleaned_needle = $this->_cleanString($needle);
                
                if (strpos(mb_strtolower($cleaned_category_name), mb_strtolower($cleaned_needle)) === false)
                {
                    continue 2;
                }
            }
            
            $ret[$category_code] = $category;
        }
        
        return $ret;
    }
    
    private function _cleanString($str)
    {
        $normalized = helpers::normalizeSpecialChars($str);
        $cleaned = helpers::removeSpecialChars($normalized);
        return $cleaned;
    }
    
}