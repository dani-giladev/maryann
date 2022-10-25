<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use core\config\controller\config;
use core\url\controller\url;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\session;
use modules\ecommerce\frontend\controller\rate;
use modules\ecommerce\frontend\controller\showcase as showcaseController;
use modules\ecommerce\frontend\controller\shoppingcart;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;
use modules\ecommerce\frontend\controller\mailing\mail;
use modules\ecommerce\controller\article;

// Views
use modules\ecommerce\frontend\view\showcase\showcase as view;
use modules\ecommerce\frontend\view\showcase\sidebar as sidebarView;
use modules\ecommerce\frontend\view\showcase\content as contentView;
use modules\ecommerce\frontend\view\shoppingcart\tooltip as shoppingcartTooltipView;
use modules\ecommerce\frontend\view\shoppingcart\windowAfterAddToShoppingcart as windowAfterAddToShoppingcartView;
use modules\ecommerce\frontend\view\articledetail\articledetail as articledetailView;
use modules\ecommerce\frontend\view\medicinesInfo as medicinesInfoView;

/**
 * Showcase webpage
 *
 * @author Dani Gilabert
 * 
 */
class showcase extends showcaseController
{
    protected $_view;
    protected $_article_data;
    protected $_content_title;
    protected $_last_breadscrumbs_item_text;
    protected $_breadcrumbs_menu;
    protected $_html_breadcrumbs_menu;
    protected $_title;
    protected $_indexable = true;
    protected $_main_menu_controller;
    protected $_html_subcategories_menu;
    
    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
    }
    
    public function init($data)
    {        
        $this->_loadData();
       
        $param = null;
        $param_value = null;
        $init = false;
        
        if (isset($data->start) || 
            isset($data->novelties) || 
            isset($data->specialoffers) || 
            isset($data->packs) || 
            isset($data->christmas) ||
            isset($data->promo) ||
            isset($data->search))
        {
            if (isset($data->novelties))
            {
                $param = 'novelties';
            }
            elseif (isset($data->specialoffers))
            {
                $param = 'specialoffers';
            }
            elseif (isset($data->packs))
            {
                $param = 'packs';
            }
            elseif (isset($data->christmas))
            {
                $param = 'christmas';
            }
            elseif (isset($data->promo))
            {
                $param = 'promo';
                $param_value = $data->promo;
            }
            elseif (isset($data->search))
            {
                $param = 'search';
                $param_value = $data->search;
            }
            else
            {
                $param = 'start';
                $this->_indexable = false;
            }
            
            $last_param = $this->_getParam();
            $this->_setCategory(null);
            $this->_setCanonicalCategory(null);
            $this->_setBrand(null);
            $this->_setParam(array('code' => $param, 'value' => $param_value));
            if (!isset($last_param) || $param !== $last_param['code'])
            {
                $init = true;
            }
        }
        elseif (isset($data->category))
        {
            // Set the category
            $cat_ret_param = $this->_getCategoryByParam($data->category);
            $category = $cat_ret_param['category'];
            $canonical_category = $cat_ret_param['canonical_category'];
            if (!isset($category))
            {
                $this->goToError404Webpage();
                return;
            }
            
            $last_category = $this->_getCategory();
            $last_brand = $this->_getBrand();
            $this->_setCategory($category);
            $this->_setCanonicalCategory($canonical_category);
            $this->_setBrand(null);
            $this->_setParam(null);
            if ($category !== $last_category || isset($last_brand))
            {
                $init = true;
            }
        }
        elseif (isset($data->brand))
        {
            // Set the brand
            $data->brand = $this->_getBrandByParam($data->brand);
            if (!isset($data->brand))
            {
                $this->goToError404Webpage();
                return;
            }
            
            $last_category = $this->_getCategory();
            $last_brand = $this->_getBrand();
            $this->_setCategory(null);
            $this->_setCanonicalCategory(null);
            $this->_setBrand($data->brand);
            $this->_setParam(null);
            if ($data->brand !== $last_brand || isset($last_category))
            {
                $init = true;
            }
        }
        else
        {
            $this->_indexable = false;            
        }
        
        if ($init)
        {
            $this->_init();
        }
        
        $category = $this->_getCategory();
        $brand = $this->_getBrand();
        
        /*
        // Set article data
        $this->_article_data = $this->_getArticles();
        
        // Set outstanding filter to true if the category and brand are not defined
        $outstanding_filter = $this->_getOutstandingFilter();
        if (!isset($category) && !isset($brand) && ($init || !isset($outstanding_filter)))
        {
            $this->_setOutstandingFilter(true);
            $this->_article_data = $this->_getOutstandingArticles($this->_article_data);
        }
        */
        
        $param = $this->_getParam();
        if (isset($param))
        {
            if ($param['code'] === 'specialoffers')
            {
                $this->_setOutstandingFilter(true);
            }
            elseif ($param['code'] === 'packs')
            {
                $this->_setPackFilter(true);
            }
            elseif ($param['code'] === 'christmas')
            {
                $this->_setChristmasFilter(true);
            }
            elseif ($param['code'] === 'promo')
            {
                $this->_setPromoFilter($param['value']);
            }
            elseif ($param['code'] === 'search')
            {
                $this->_setSearchFilter($param['value']);
            }
            else
            {
                $this->_setNoveltyFilter(true);
            }
        }
        else
        {
            if (!isset($category) && !isset($brand))
            {
                $this->_setParam(array('code' => 'novelties', 'value' => null));
                $this->_setNoveltyFilter(true);
            }
        }
        
        // Set gammas
        if (isset($data->gamma))
        {
            if ($init) $this->_article_data = $this->_getArticles();
            $gamma_pieces = explode(",", $data->gamma);
            $this->_setCheckedGammaFilter($gamma_pieces);
        }
        
        // Set article data
        $this->_article_data = $this->_getArticles();
        
        // Paging
        $this->_initPaging($data, $init);    

        // Init and render the breadcrumbs menu
        $param_values = $this->_getParamValues();
        $this->_breadcrumbs_menu = $this->_getBreadcrumbsMenu((isset($param)? $param['code'] : null), $param_values);
        $this->_html_breadcrumbs_menu = $this->_breadcrumbs_menu->renderBreadcrumbsMenu();
        $this->_setLastBreadscrumbsItemText($this->_breadcrumbs_menu->getLastItemText());
        
        // Get sub-categories
        $this->_main_menu_controller = $this->_getMainMenu();
        if (isset($category))
        {
            $this->_html_subcategories_menu = $this->_main_menu_controller->renderArticleSubcategoriesMenu($category);
        }
        else
        {
            $this->_html_subcategories_menu = null; //$this->_main_menu_controller->renderCategoriesMenu();
        }        
        
        // Set content title
        if (isset($data->title))
        {
            $this->_setContentTitle($data->title);
        }
        else
        {
            $this->_setContentTitle(null);
        }
        
        // Render this page
        $this->renderPage();
    }
    
    protected function _getBreadcrumbsMenu($param, $param_values = null) {
        return new breadcrumbsMenu($this->_getBreadcrumbsMenuParams($param, $param_values));
    }
    
    protected function _getBreadcrumbsMenuParams($param, $param_values = null) {
        return array(
            'category' => $this->_getCategory(), 
            'brand' => $this->_getBrand(), 
            'categories' => $this->_categories_tree,
            'brands' => $this->_brands,
            'show_shoppingcart_button' => false,
            'show_ordering_button' => true,
            'param' => $param,
            'param_values' => $param_values
        );
    }
    
    protected function _getTitle()
    {
        $website = $this->getWebsite();
        $current_lang = lang::getCurrentLanguage();
        
        $category = $this->_getCategory();
        $brand = $this->_getBrand();
        $title = '';
        if (isset($category))
        {
            $breadcrumbs_categories = $this->_breadcrumbs_menu->getBreadcrumbsCategories();
            //$title = lang::trans('category').' ';
            $first_time = true;
            foreach ($breadcrumbs_categories as $cat) {
                $key = 'titles-'.$current_lang;
                if (isset($cat->$key))
                {
                    $value = $cat->$key;
                }
                else
                {
                    $default_language =  config::getConfigParam(array("application", "default_language"))->value;
                    $key = 'titles-'.$default_language;
                    $value = isset($cat->$key)? $cat->$key : "";
                }
                if (!$first_time)
                {
                    $title .= ', ';
                }
                $title .= $value;
                $first_time = false;
            }
        }
        elseif (isset($brand))
        {
            if (isset($this->_brands) && isset($this->_brands[$brand]))
            {
                $title = 
                        //lang::trans('brand').' '.
                        $this->_brands[$brand]->name;
            }            
        }
        else
        {
            $title = $this->_getContentTitle();
        }
        
        $ret = '';
        if (!empty($title))
        {
            $ret = $title.' | ';
        }
        else
        {
            if (empty($title) && isset($website->titles->$current_lang))
            {
                $ret = $website->titles->$current_lang.' | ';
            }
        }
        $ret .= $website->name;
        
        $this->_title = $ret;
        return $ret;
    }           
    
    protected function _getDescription()
    {
        $current_lang = lang::getCurrentLanguage();
        $ret = '';
        
        $category = $this->_getCategory();
        $brand = $this->_getBrand();
        if (isset($category))
        {
            if (isset($this->_categories_tree->categories->$category))
            {
                $cat = $this->_categories_tree->categories->$category;
                $key = 'descriptions-'.$current_lang;
                if (isset($cat->$key) && !empty($cat->$key))
                {
                    $ret = $cat->$key;
                }
                else
                {
                    $default_language =  config::getConfigParam(array("application", "default_language"))->value;
                    $key = 'descriptions-'.$default_language;
                    if (isset($cat->$key) && !empty($cat->$key))
                    {
                        $ret = $cat->$key;
                    }
                }
            }
        }
        elseif (isset($brand))
        {
            if (isset($this->_brands) && isset($this->_brands[$brand]))
            {
                $b = $this->_brands[$brand];
                if (isset($b->descriptions->$current_lang) && !empty($b->descriptions->$current_lang))
                {
                    $ret = $b->descriptions->$current_lang;
                }
                else
                {
                    $default_language =  config::getConfigParam(array("application", "default_language"))->value;
                    if (isset($b->descriptions->$default_language) && !empty($b->descriptions->$default_language))
                    {
                        $ret = $b->descriptions->$default_language;
                    }
                }                
            }            
        }
        
        if (empty($ret))
        {
            $ret = $this->_title;
        }
        
        return $ret;
    }
    
    protected function _getKeywords()
    {
        $current_lang = lang::getCurrentLanguage();
        $ret = '';
        
        $category = $this->_getCategory();
        $brand = $this->_getBrand();
        if (isset($category))
        {
            if (isset($this->_categories_tree->categories->$category))
            {
                $cat = $this->_categories_tree->categories->$category;
                $key = 'keywords-'.$current_lang;
                if (isset($cat->$key) && !empty($cat->$key))
                {
                    $ret = $cat->$key;
                }
                else
                {
                    $default_language =  config::getConfigParam(array("application", "default_language"))->value;
                    $key = 'keywords-'.$default_language;
                    if (isset($cat->$key) && !empty($cat->$key))
                    {
                        $ret = $cat->$key;
                    }
                }
            }
        }
        elseif (isset($brand))
        {
            if (isset($this->_brands) && isset($this->_brands[$brand]))
            {
                $b = $this->_brands[$brand];
                if (isset($b->keywords->$current_lang) && !empty($b->keywords->$current_lang))
                {
                    $ret = $b->keywords->$current_lang;
                }
                else
                {
                    $default_language =  config::getConfigParam(array("application", "default_language"))->value;
                    if (isset($b->keywords->$default_language) && !empty($b->keywords->$default_language))
                    {
                        $ret = $b->keywords->$default_language;
                    }
                }                
            }            
        }
        
        return $ret;
    }
    
    protected function _renderMenu()
    {
        $html = '';
        
        // Render main menu
        $html .= $this->_renderMainMenu();
        
        // Render breadcrumbs menu      
        $html .= $this->_renderBreadcrumbs();
        
        return $html;
    }
    
    protected function _renderMainMenu()
    {
        $html = '';
        
        // Render main menu
        $html .= $this->_main_menu_controller->renderMainMenu();
        
        return $html;
    } 
    
    protected function _getMainMenu()
    {
        $main_menu = new mainMenu($this->_categories_tree);
        return $main_menu;
    }
    
    protected function _renderBreadcrumbs()
    {
        $html = '';
        
        // Render breadcrumbs menu      
        $html .= $this->_html_breadcrumbs_menu;
        
        return $html;
    }
    
    protected function _renderContent()
    {
        $html = '';

        // Sidebar
        $html .= $this->_renderSidebar();
        
        // Content (articles)
        $content_view = $this->_getNewContentView();
        $html .= $content_view->renderStart();
        $html .= $content_view->renderContent();     
        $html .= $content_view->renderEnd();  
        
        return $html;
    }
    
    protected function _getNewContentView()
    {
        $content_view = $this->_getContentView();
        $content_view->title = $this->_getContentTitle();
        $content_view->articles = $this->_article_data;
        $content_view->brands = $this->_getBrands();
        $content_view->gammas = $this->_getGammas();
        $content_view->category = $this->_getCategory();
        $content_view->categories = $this->_getCategories();
        $content_view->articles_per_page = $this->_getArticlesPerPage();
        $content_view->current_page = $this->_getCurrentPage();
        $content_view->total_pages = $this->_getTotalPages();
        $content_view->sortby = $this->_getSortby();
        $content_view->search_filter = $this->_getSearchFilter();
        $content_view->subcategories_menu_is_rendered = (!is_null($this->_html_subcategories_menu));
        $content_view->current_url_without_params = $this->_getCurrentUrlWithoutParams();
        $content_view->current_url_params = $this->_getCurrentUrlParams();
        
        return $content_view;
    } 
    
    protected function _getContentView()
    { 
        return new contentView();
    }
    
    protected function _getArticledetailView($article)
    {
        return new articledetailView($article);
    }
    
    protected function _renderAdditionalContent()
    {      
        // Medicines info
        $html = $this->_renderMedicinesInfo();
        return $html;
    }
    
    private function _renderMedicinesInfo()
    {
        $html = '';
        
        $category = $this->_getCategory();
        $brand = $this->_getBrand();
        if (!isset($category) && !isset($brand))
        {
            return $html;
        }
        
        if (isset($category))
        {
            $categories = $this->_getCategoriesTree();
            
//            $a = $categories->breadcrumbs;
//            $b = $categories->breadcrumbs->$category;
//            $c = $categories->breadcrumbs->$category[0];
                    
            if (
                    !isset($categories->breadcrumbs) || empty($categories->breadcrumbs) && 
                    !isset($categories->breadcrumbs->$category) || empty($categories->breadcrumbs->$category) && 
                    !isset($categories->breadcrumbs->$category[0]) || empty($categories->breadcrumbs->$category[0])
            )
            {
                return $html;
            }
            
            $cat_root = $categories->breadcrumbs->$category[0];
            if ($cat_root->code !== 'med')
            {
                return $html;
            }            
        }
        else
        {
            $brands = $this->_getBrands();
                    
            if (!isset($brands[$brand]))
            {
                return $html;
            }
            
            $brand = $brands[$brand];
            /*if (!isset($brand->brandType) || $brand->brandType !== '2')
            {
                return $html;
            }*/
            if (!isset($brand->medicines) || !$brand->medicines)
            {
                return $html;
            }
        }
        
        $medicines_info_view = new medicinesInfoView();
        $medicines_info_view->any_selected_category = (isset($category));
        $medicines_info_view->any_selected_brand = (isset($brand));
        $html .= $medicines_info_view->render();
        
        return $html; 
    }
    
    protected function _getSidebarView()
    {
        return new sidebarView();
    }

    private function _renderSidebar()
    {
        $param = $this->_getParam();
        
        // Assign properties and render sidebar
        $sidebar_view = $this->_getSidebarView();
        $sidebar_view->param = (isset($param)? $param['code'] : null);
        $sidebar_view->category = $this->_getCategory();
        $sidebar_view->brand = $this->_getBrand();
        $sidebar_view->html_subcategories_menu = $this->_html_subcategories_menu;
        $sidebar_view->brands = $this->_brands; // All brands to fill the combo        
        $sidebar_view->min_price_filter = $this->_getMinPriceFilter();
        $sidebar_view->max_price_filter = $this->_getMaxPriceFilter();
        $sidebar_view->price_bounds_filter = $this->_getPriceBoundsFilter();
        $sidebar_view->outstanding_filter = $this->_getOutstandingFilter();
        $sidebar_view->novelty_filter = $this->_getNoveltyFilter();
        $sidebar_view->pack_filter = $this->_getPackFilter();
        $sidebar_view->christmas_filter = $this->_getChristmasFilter();
        $sidebar_view->available_categories_filter = $this->_getAvailableCategoriesFilter();
        $sidebar_view->checked_categories_filter = $this->_getCheckedCategoriesFilter();
        $sidebar_view->brands_filter = $this->_getBrandsFilter();
        $sidebar_view->checked_brands_filter = $this->_getCheckedBrandsFilter();
        $sidebar_view->available_gamma_filter = $this->_getAvailableGammaFilter();
        $sidebar_view->checked_gamma_filter = $this->_getCheckedGammaFilter();
        $sidebar_view->available_article_properties_filter = $this->_getAvailableArticlePropertiesFilter();
        $sidebar_view->checked_article_properties_filter = $this->_getCheckedArticlePropertiesFilter();
        $sidebar_view->available_basic_filter = $this->_getAvailableBasicFilter();
        $sidebar_view->any_articles = !empty($this->_article_data);
        return $sidebar_view->renderSidebar();        
    }

    public function sendContentToClient($data)
    {
        $this->_loadData();
        
        $this->_setMinPriceFilter((isset($data->min_price) && !empty($data->min_price))? (float) $data->min_price : null);
        $this->_setMaxPriceFilter((isset($data->max_price) && !empty($data->max_price))? (float) $data->max_price : null);
        $this->_setOutstandingFilter((isset($data->outstanding) && !empty($data->outstanding))? (($data->outstanding == 'true')? true : false) : null);
        $this->_setNoveltyFilter((isset($data->novelty) && !empty($data->novelty))? (($data->novelty == 'true')? true : false) : null);
        $this->_setPackFilter((isset($data->pack) && !empty($data->pack))? (($data->pack == 'true')? true : false) : null);
        $this->_setChristmasFilter((isset($data->christmas) && !empty($data->christmas))? (($data->christmas == 'true')? true : false) : null);
        $this->_setCheckedCategoriesFilter((isset($data->categories) && !empty($data->categories))? json_decode($data->categories) : array());
        $this->_setCheckedBrandsFilter((isset($data->brands) && !empty($data->brands))? json_decode($data->brands) : array()); 
        $this->_setCheckedGammaFilter((isset($data->gamma) && !empty($data->gamma))? json_decode($data->gamma) : array()); 
        $this->_setCheckedArticlePropertiesFilter((isset($data->article_properties) && !empty($data->article_properties))? json_decode($data->article_properties, true) : array());
        
        // Paging
        $this->_setCurrentPage(null);
        $this->setScrollPosition(0); // Reset scroll page
        if (isset($data->articlesperpage) && !empty($data->articlesperpage))
        {
            $this->_setArticlesPerPage($data->articlesperpage);            
        }
        
        // Menu
        if (isset($data->sortby) && !empty($data->sortby))
        {
            $this->_setSortby($data->sortby);            
        }
        
        // Articles
        $this->_article_data = $this->_getArticles();
        
        // Happy end
        $content_view = $this->_getNewContentView();
        $articles_content = $content_view->renderContent();
        $ret['articlesContent'] = $articles_content; 
        $ret = json_encode($ret);
        echo $ret;
    }
    
    public function addToShoppingcart($data, $comes_from_article_detail = false, $is_mobile = false)
    {
        $code = $data->code;
        $amount = $data->amount;
        
        // Get article by code
        $controller = new article();
        $article_model = $controller->getArticleByCode($code, true);
        $article = $article_model->getStorage();
        
        // Set prices
        $rate = new rate();
        $prices = $rate->getArticlePrices($article);
        $article->prices = $prices;
        
        // Add article to shoppingcart
        $shoppingcart_controller = new shoppingcart();
        $shoppingcart_controller->addArticle($article, $amount);

        $shoppingcart_menu_option_view = $this->_getShoppingcartTooltipView();
        $ret['shoppingcartAmount'] = $shoppingcart_menu_option_view->renderShoppingcartMenuAmount();
        if (!$is_mobile)
        {
            
            $ret['shoppingcartTotalPrice'] = $shoppingcart_menu_option_view->renderShoppingcartMenuTotalPrice();
            $ret['shoppingcartTooltip'] = $shoppingcart_menu_option_view->renderShoppingcartTooltip();
            
            // Window content when we add article to shoppingcart
            $window_after_add_to_shoppingcart_view = new windowAfterAddToShoppingcartView();
            $window_after_add_to_shoppingcart_content = $window_after_add_to_shoppingcart_view->renderWindow($article, $amount);
            $ret['windowAfterAddToShoppingcart'] = $window_after_add_to_shoppingcart_content;          
        }

        if ($comes_from_article_detail)
        {
            $article_detail_view = $this->_getArticledetailView($article);
            $ret['addToShoppingcartWidgetsContent'] = $article_detail_view->renderAddToShoppingcartWidgets($article, $is_mobile);
        }
        else
        {
            $content_view = $this->_getContentView();
            $ret['addToShoppingcartWidgetsContent'] = $content_view->renderAddToShoppingcartWidgets($article);
        }
        $ret = json_encode($ret);
        echo $ret;        
    }
    
    protected function _getShoppingcartTooltipView()
    {
        return new shoppingcartTooltipView();
    }
    
    public function saveScrollPosition($data)
    {
        $position = $data->position;
        $this->setScrollPosition($position);
    }
    
    private function _getLastBreadscrumbsItemText()
    {
        if (isset($this->_last_breadscrumbs_item_text)) return $this->_last_breadscrumbs_item_text;
        $value = session::getSessionVar('ecommerce-showcase-last-breadscrumbs-item-text');
        return (isset($value) && !empty($value))? $value : null;
    }
    
    private function _setLastBreadscrumbsItemText($value)
    {
        session::setSessionVar('ecommerce-showcase-last-breadscrumbs-item-text', $value);
        $this->_last_breadscrumbs_item_text = $value;
    } 
    
    private function _getContentTitle()
    {
        if (isset($this->_content_title))
        {
            return $this->_content_title;
        }
        
        $value = session::getSessionVar('ecommerce-showcase-contenttitle');
        if (isset($value) && !empty($value))
        {
            $this->_content_title = $value;
            return $value;
        }
        
        $default_value =  $this->_getDefaultContentTitle();
        $this->_setContentTitle($default_value);
        return $default_value;
    }     
    
    protected function _setContentTitle($value)
    {
        session::setSessionVar('ecommerce-showcase-contenttitle', $value);
        $this->_content_title = $value;
    }    
    
    private function _getDefaultContentTitle()
    {
        $last_breadscrumbs_item_text = $this->_getLastBreadscrumbsItemText();
        if (isset($last_breadscrumbs_item_text))
        {
            return $last_breadscrumbs_item_text;
        }
        
        $outstanding_filter = $this->_getOutstandingFilter();
        $novelty_filter = $this->_getNoveltyFilter();
        $pack_filter = $this->_getPackFilter();
        $christmas_filter = $this->_getChristmasFilter();
        $search_filter = $this->_getSearchFilter();
        $promo_filter = $this->_getPromoFilter();
        
        $title= '';
        if ($christmas_filter)
        {
            $title = lang::trans('special_christmas');
        }
        elseif ($pack_filter)
        {
            $title = 'Packs';
        }
        elseif ($outstanding_filter && $novelty_filter)
        {
            $title = lang::trans('special_offers_and_novelties');
        }
        elseif ($novelty_filter)
        {
            $title = lang::trans('novelties');
        }
        elseif ($outstanding_filter)
        {
            $title = lang::trans('special_offers');
        }
        elseif ($search_filter)
        {
            $title = lang::trans('search');
        }
        elseif (isset($promo_filter))
        {
            $current_lang = lang::getCurrentLanguage();
            $promo = $this->_getPromo();
            if (isset($promo) && isset($promo->titles->$current_lang))
            {
                $title = $promo->titles->$current_lang;
            }
        }
        
        if (empty($title))
        {
            $title = lang::trans('all_articles');
        }
        
        return $title;
    }  
    
    protected function _getRobots()
    {
        if (!$this->_indexable)
        {
            return 'noindex, nofollow';
        }
        
        $website = $this->getWebsite();
        return $website->robots;
    }
    
    public function sendSearcherMsg($params)
    {
        $ret = array();
        $msg = $params->msg;
        $email = $params->email;
        
        if (strlen($msg) < 10)
        {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('the_msg_should_be_minimum');
            echo json_encode($ret);
            return;
        }
        
        if (empty($email)) {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('give_us_your_email');
            echo json_encode($ret);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('email_is_not_correct');
            echo json_encode($ret);
            return;
        }
        
        // Send ecommerce emails
        $mail = new mail();
        $website = $this->getWebsite();
        $subject = $website->name. ' - '.'Usuari no troba el que busca';
        $body = 
                'E-mail : '.$email.'</br>'.
                'Texte : '.$msg.'</br>'.
                '';
        $to = $mail->getMailAddresses();
        $mail->send($subject, $body, $to);       

        $ret['success'] = true;
        $ret['htmlMsg'] = 
                '<div id="showcase-content-searchresult-form-answer">'.
                    "<span class='label-info' style='font-weight:bold;'>".
                        lang::trans('your_msg_has_been_sent_successfully').
                    "</span>".
                    "<br><br>".
                    lang::trans('we_answer_soon_as_possible').
                '</div>';
        echo json_encode($ret);
    }
    
    protected function _getCanonicalUrl()
    {
        $ret = '';
        
        $canonical_category = $this->_getCanonicalCategory();
        
        if (!isset($canonical_category) || empty($canonical_category))
        {
            return $ret;
        }
        
        $current_lang = lang::getCurrentLanguage();
        
        foreach ($this->_categories as $key => $value)
        {
            if ($canonical_category === $key)
            {
                $url_property = 'url'.ucfirst($current_lang);
                if (isset($value->$url_property) && 
                    !empty($value->$url_property))
                {
                    $url_value = $value->$url_property;
                    $ret = $this->getUrl(array($current_lang, lang::trans('url-categories', $current_lang), $url_value));
                }                
                break;
            }
        }

        return $ret;
    } 
    
    private function _initPaging($data, $init)
    {
        $set_current_page = false;
        $set_current_page_value = null;
        $set_scroll_position = false;
        $set_scroll_position_value = 0;
        
        if (isset($data->page))
        {
            $last_page = $this->_getCurrentPage();
            if ($data->page !== $last_page)
            {
                $total_pages = $this->_getTotalPages();
                $set_current_page = true;
                if (!$init)
                {
                    $set_scroll_position = true;
                }                 
                if ($data->page <= $total_pages)
                {
                    $set_current_page_value = $data->page;
                    
                    $last_current_url_without_params = $this->_getCurrentUrlWithoutParams();
                    $current_url_without_params = url::getCurrentUrlWithoutParams();
                    if ($current_url_without_params !== $last_current_url_without_params)
                    {
                        $set_current_page = false;
                        $set_scroll_position = false;
                    }
                }              
            }
        }
        else
        {
            if (!$init)
            {
                // Reset current page
                $set_current_page = true;
            }
        }
        
        if ($set_current_page)
        {
            $this->_setCurrentPage($set_current_page_value);
        }
        if ($set_scroll_position)
        {
            $this->setScrollPosition($set_scroll_position_value); // Reset scroll page
        }        
    }
    
    protected function _getPagination()
    {
        $ret = array();
        
        $page = $this->_getCurrentPage();
        $total_pages = $this->_getTotalPages();
        
        if ($total_pages === 0)
        {
            return $ret;
        }
        
        // Prev
        $prev_page = $page - 1;
        if ($prev_page > 0)
        {
            $url = $this->_getUrlWithPage($prev_page);
            $ret[] = array(
                'rel' => 'prev',
                'url' => $url
            );            
        }
        
        // Next
        $next_page = $page + 1;
        if ($next_page <= $total_pages)
        {
            $url = $this->_getUrlWithPage($next_page);
            $ret[] = array(
                'rel' => 'next',
                'url' => $url
            );            
        }
        
        return $ret;
    }
    
    private function _getUrlWithPage($page)
    {
        $current_url_without_params = $this->_getCurrentUrlWithoutParams();
        $current_url_params = $this->_getCurrentUrlParams();        
        
        if ($page > 1)
        {
            $url = url::updateParameters(array('page' => $page), array(), $current_url_without_params, $current_url_params);
        }
        else
        {
            $url = url::updateParameters(array(), array('page'), $current_url_without_params, $current_url_params);
        }
        
        return $url;
    }
    
}