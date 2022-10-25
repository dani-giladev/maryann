<?php

namespace modules\ecommerce\frontend\mobile\controller;

// Controllers
use modules\ecommerce\frontend\controller\webpages\articledetail as articledetailController;
use modules\ecommerce\frontend\mobile\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\mobile\controller\menu\breadcrumbs as breadcrumbsMenu;
use modules\ecommerce\frontend\mobile\controller\showcase as showcaseController;

// Views
use modules\ecommerce\frontend\mobile\view\articledetail\articledetail as view;

/**
 * Article detail mobile webpage
 *
 * @author Dani Gilabert
 * 
 */
class articledetail extends articledetailController
{
    protected function _initView()
    {
        $this->_view = new view($this->_article);
    }
    
    protected function _renderMenu()
    {
        $html = '';
        
        // Render main menu
        $main_menu = new mainMenu();
        $available_langs = $this->_getAvailableLanguages();
        $main_menu->setAvailableLangs($available_langs);
        $html .= $main_menu->renderMainMenu();
        $this->_categories = $main_menu->getCategoriesTree();
     
        return $html;
    }    
    
    protected function _renderBreadcrumbs()
    {
        $html = '';
        
        // Render breadcrumbs menu
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'categories' => $this->_categories, 
            'article' => $this->_article
        ));          
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    } 
    
    public function addIndividualArticleToShoppingcart($data)
    {
        $showcase_controller = new showcaseController();
        $showcase_controller->addToShoppingcart($data, true, true);
    }
    
}