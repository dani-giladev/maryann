<?php

namespace modules\ecommerce\frontend\mobile\controller;

// Controllers
use modules\ecommerce\frontend\controller\webpages\showcase as showcaseController;
use modules\ecommerce\frontend\mobile\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\mobile\controller\menu\breadcrumbs as breadcrumbsMenu;

// Views
use modules\ecommerce\frontend\mobile\view\showcase\showcase as view;
use modules\ecommerce\frontend\mobile\view\showcase\content as contentView;
use modules\ecommerce\frontend\mobile\view\showcase\sidebar as sidebarView;
use modules\ecommerce\frontend\mobile\view\articledetail\articledetail as articledetailView;
use modules\ecommerce\frontend\mobile\view\shoppingcart\shoppingcart as mobileShoppingcartView;

/**
 * Showcase mobile webpage
 *
 * @author Dani Gilabert
 * 
 */
class showcase extends showcaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
    }
    
    protected function _getBreadcrumbsMenu($param, $param_values = null) {
        return new breadcrumbsMenu($this->_getBreadcrumbsMenuParams($param, $param_values));
    }
    
    protected function _renderMenu()
    {
        $html = '';
        
        // Render main menu
        $html .= $this->_renderMainMenu();
        
        // Render sub-categories
        $html .= $this->_html_subcategories_menu;
        
        return $html;
    } 
    
    protected function _getMainMenu()
    {
        $main_menu = new mainMenu($this->_categories_tree);
        $available_langs = $this->_getAvailableLanguages();
        $main_menu->setAvailableLangs($available_langs);
        return $main_menu;
    } 
    
    protected function _getContentView()
    { 
        $content_view = new contentView();
        $content_view->columns = 1;
        return $content_view;
    }
    
    protected function _getArticledetailView($article)
    {
        return new articledetailView($article);
    }
    
    protected function _getShoppingcartTooltipView()
    {
        return new mobileShoppingcartView();
    }
    
    protected function _getSidebarView()
    {
        return new sidebarView();
    }
    
}