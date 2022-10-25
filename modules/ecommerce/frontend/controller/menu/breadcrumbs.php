<?php

namespace modules\ecommerce\frontend\controller\menu;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\menu\menu;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\controller\brand;

// Views
use modules\ecommerce\frontend\view\menu\breadcrumbs as view;

/**
 * E-commerce frontend breadcrumbs controller
 *
 * @author Dani Gilabert
 * 
 */
class breadcrumbs extends menu
{  
    protected $_breadcrumbs = null;
    protected $_category = null;
    protected $_brand = null;
    protected $_article = null;
    protected $_last_item_text = null;
    protected $_param = null;
    protected $_param_values = null;
    protected $_brands;
    
    private $_article_controller;
    
    public function __construct($data)
    {
        $categories = isset($data['categories'])? $data['categories'] : null;
        parent::__construct($categories);
        
        // Set protected vars
        $this->_breadcrumbs = isset($data['breadcrumbs'])? $data['breadcrumbs'] : array();
        $this->_category = isset($data['category'])? $data['category'] : null;
        $this->_brand = isset($data['brand'])? $data['brand'] : null;
        $this->_brands = isset($data['brands'])? $data['brands'] : null;
        $this->_article = isset($data['article'])? $data['article'] : null;
        $this->_param = isset($data['param'])? $data['param'] : null;
        $this->_param_values = isset($data['param_values'])? $data['param_values'] : null;
        
        // Set private vars
        $this->_article_controller = new article();
        
        // Set view
        $this->_view = new view();
        $this->_view->show_shoppingcart = isset($data['show_shoppingcart'])? $data['show_shoppingcart'] : true;
        $this->_view->show_shoppingcart_button = isset($data['show_shoppingcart_button'])? $data['show_shoppingcart_button'] : false;
        $this->_view->show_ordering_button = isset($data['show_ordering_button'])? $data['show_ordering_button'] : true;
        $this->_view->webpage = $this->_webpage;
    }
    
    public function getLastItemText()
    {
        return $this->_last_item_text;
    }
    
    public function renderBreadcrumbsMenu()
    {    
        $set_last_item_text = true;
        
        if (isset($this->_category))
        {
            // Bread crumbs of categories
            $breadcrumbs = $this->getBreadcrumbsCategories();
            $html_breadcrumbs = $this->_view->renderCategories($breadcrumbs);
        }
        elseif (isset($this->_brand))
        {
            // The brand
            $brand_name = $this->_getBrandName($this->_brand);
            $html_breadcrumbs = $this->_view->renderBrandText($brand_name);
            $this->_view->last_item_text = $this->_brand;
        }
        elseif (isset($this->_article))
        {
            // The article with their categories
            $article_name = $this->_getArticleTitle($this->_article);
            $article_display = $this->_getArticleDisplay($this->_article);
            if (!empty($article_display))
            {
                $article_name .= ' - '.$article_display;
            }
            if (isset($this->_article->categories) && !empty($this->_article->categories))
            {
                $categories = explode('|', $this->_article->categories);
                $category = $categories[count($categories)-1];
                $breadcrumbs = $this->getBreadcrumbsCategories($category);
                $html_breadcrumbs = $this->_view->renderCategoriesAndArticle($breadcrumbs, $article_name);
            }
            else
            {
                $html_breadcrumbs = $this->_view->renderArticleText($article_name);                
            }
        }
        elseif (!empty($this->_breadcrumbs))
        {
            // Bread crumbs
            $html_breadcrumbs = $this->_view->renderBreadcrumbs($this->_breadcrumbs);
        }
        else
        {
            // Showcase
            $text = '';
            $set_last_item_text = false;
            if (isset($this->_param))
            {
                if ($this->_param === 'christmas')
                {
                    $text = lang::trans('special_christmas');
                }      
                elseif ($this->_param === 'packs')
                {
                    $text = 'Packs';
                }          
                elseif ($this->_param === 'novelties')
                {
                    $text = lang::trans('novelties');
                }
                elseif ($this->_param === 'specialoffers')
                {
                    $text = lang::trans('special_offers');
                }
                elseif ($this->_param === 'search')
                {
                    $text = lang::trans('search');
                }
                elseif ($this->_param === 'promo' && isset($this->_param_values))
                {
                    $current_lang = lang::getCurrentLanguage();
                    $text = $this->_param_values->titles->$current_lang;
                }
            }

            if (empty($text))
            {
                //$text = lang::trans('all_articles');
                $text = lang::trans('showcase');                
            }
            
            $breadcrumbs = array(array('text' => $text, 'url' => ''));
            $html_breadcrumbs = $this->_view->renderBreadcrumbs($breadcrumbs);
        }            
        
        $html = $this->_view->renderBreadcrumbsMenu($html_breadcrumbs);
        
        $this->_last_item_text = (!$set_last_item_text)? null : $this->_view->last_item_text;
                
        return $html;
    }
    
    public function getBreadcrumbsCategories($category = null)
    {
        if (!isset($this->_categories))
        {
            $this->_categories = $this->_getCategoriesTree();
        }
        if (!isset($this->_categories)) return null;
        if (!isset($this->_categories->breadcrumbs)) return null;
        if (!isset($category))
        {
            $category = $this->_category;
        }
        if (!isset($this->_categories->breadcrumbs) || 
            !isset($this->_categories->breadcrumbs->$category) || 
            empty($this->_categories->breadcrumbs->$category)) return null;
        
        return $this->_categories->breadcrumbs->$category;
    }
    
    private function _getBrandName($code)
    {
        if (isset($this->_brands) && isset($this->_brands[$code]))
        {
            $brand = $this->_brands[$code];
        }
        else
        {
            $controller = new brand();
            $brand = $controller->getBrandByCode($code, true);
        }
        
        if (!isset($brand)) return '';
        return $brand->name;
    }
    
    private function _getArticleTitle($article)
    {
        return $this->_article_controller->getTitle($article);
    }
    
    private function _getArticleDisplay($article)
    {
        return $this->_article_controller->getDisplay($article);
    }
    
}