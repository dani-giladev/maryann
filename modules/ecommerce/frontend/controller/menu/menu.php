<?php

namespace modules\ecommerce\frontend\controller\menu;

// Controllers
use modules\ecommerce\frontend\controller\ecommerce;

// Views
use modules\ecommerce\frontend\view\menu\menu as view;

/**
 * E-commerce frontend menu controller
 *
 * @author Dani Gilabert
 * 
 */
class menu
{
    protected $_view;
    protected $_ecommerce_controller;
    protected $_webpage;
    protected $_categories;
    
    public function __construct($categories = null)
    {
        $this->_view = new view();
        $this->_ecommerce_controller = new ecommerce();
        $this->_webpage = $this->_ecommerce_controller->getWebpage();
        if (isset($categories))
        {
            $this->_categories = $categories;
        }
        else    
        {
            $this->_categories = $this->_getCategoriesTree();
        }
    }
    
    public function renderArticleSubcategoriesMenu($category)
    {    
        if (!isset($this->_categories)) return null;
        if (!isset($this->_categories->subcategories)) return null;
        if (!isset($this->_categories->subcategories) || 
            !isset($this->_categories->subcategories->$category) || 
            empty($this->_categories->subcategories->$category)) return null;
        
        $html = $this->_view->renderMenu($this->_categories->subcategories->$category);
        return $html;
    }
    
    public function renderCategoriesMenu()
    {    
        if (!isset($this->_categories)) return null;
        if (!isset($this->_categories->tree)) return null;
        $tree = $this->_categories->tree[0]->children; // Take out root
        if (!isset($tree) || empty($tree)) return null;
        
        $html = $this->_view->renderMenu($tree);
        return $html; 
    }    
    
    protected function _getCategoriesTree()
    {
        return $this->_ecommerce_controller->getCategoriesTree();
    }  
    
    public function getCategoriesTree()
    {
        return $this->_categories;
    }
    
}