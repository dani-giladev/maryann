<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\availability;
use modules\ecommerce\frontend\controller\session;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\frontend\controller\menu\searcher;

/**
 * Showcase controller
 *
 * @author Dani Gilabert
 * 
 */
class showcase extends ecommerce
{
    // Controllers
    private $_availability_controller = null;
    
    // Params
    private $_param = null;
    
    // Filters
    private $_category = null;
    private $_canonical_category = null;
    private $_brand = null;
    private $_min_price_filter = null;
    private $_max_price_filter = null;
    private $_price_bounds_filter = array();
    private $_outstanding_filter = null;
    private $_novelty_filter = null;
    private $_pack_filter = null;
    private $_christmas_filter = null;
    private $_promo_filter = null;
    private $_promo = null;
    private $_available_categories_filter = array();
    private $_checked_categories_filter = array();
    private $_brands_filter = array();
    private $_checked_brands_filter = array();
    private $_available_gamma_filter = array();
    private $_checked_gamma_filter = array();
    private $_available_article_properties_filter = array();
    private $_checked_article_properties_filter = array();
    private $_available_basic_filter = array();
    private $_search_filter = null;
    
    // Paging
    protected $_articles_per_page = null;
    protected $_current_page = null;
    protected $_sortby = null;
    
    // Bulk data
    protected $_categories_tree = array();
    protected $_categories = array();
    protected $_brands = array();
    protected $_gammas = array();
    protected $_article_types = array();
    protected $_article_properties = array();
    protected $_sale_rates = array();

    public function __construct()
    {
        parent::__construct();
        
        // Controllers
        $this->_availability_controller = new availability();
        
        // Filters
        $this->_category = $this->_getCategory();
        $this->_canonical_category = $this->_getCanonicalCategory();
        $this->_brand = $this->_getBrand();
        $this->_min_price_filter = $this->_getMinPriceFilter();
        $this->_max_price_filter = $this->_getMaxPriceFilter();
        $this->_price_bounds_filter = $this->_getPriceBoundsFilter();
        $this->_outstanding_filter = $this->_getOutstandingFilter();
        $this->_novelty_filter = $this->_getNoveltyFilter();
        $this->_pack_filter = $this->_getPackFilter();
        $this->_christmas_filter = $this->_getChristmasFilter();
        $this->_promo_filter = $this->_getPromoFilter();
        $this->_available_categories_filter = $this->_getAvailableCategoriesFilter();
        $this->_checked_categories_filter = $this->_getCheckedCategoriesFilter();
        $this->_brands_filter = $this->_getBrandsFilter();
        $this->_checked_brands_filter = $this->_getCheckedBrandsFilter();
        $this->_available_gamma_filter = $this->_getAvailableGammaFilter();
        $this->_checked_gamma_filter = $this->_getCheckedGammaFilter();
        $this->_available_article_properties_filter = $this->_getAvailableArticlePropertiesFilter();
        $this->_checked_article_properties_filter = $this->_getCheckedArticlePropertiesFilter();
        $this->_available_basic_filter = $this->_getAvailableBasicFilter();
        $this->_search_filter = $this->_getSearchFilter();
    
        // Paging
        $this->_articles_per_page = $this->_getArticlesPerPage();
        $this->_current_page = $this->_getCurrentPage();
        $this->_sortby = $this->_getSortby();
    }

    protected function _loadData()
    {
        $this->_setCategoriesTree();
        $this->_setCategories();
        $this->_setBrands();
        $this->_setGammas();
        //$this->_setArticleTypes();
        $this->_setArticleProperties();
        //$this->_setSaleRates();
    }

    protected function _init()
    {
        // Reset filters
        $this->_setMinPriceFilter(null);
        $this->_setMaxPriceFilter(null);
        $this->_setPriceBoundsFilter(array());
        $this->_setOutstandingFilter(null);
        $this->_setNoveltyFilter(null);
        $this->_setPackFilter(null);
        $this->_setChristmasFilter(null);
        $this->_setPromoFilter(null);
        $this->_setAvailableCategoriesFilter(array());
        $this->_setCheckedCategoriesFilter(array());
        $this->_setAvailableBrandsFilter(array());
        $this->_setCheckedBrandsFilter(array());
        $this->_setAvailableGammaFilter(array());
        $this->_setCheckedGammaFilter(array());
        $this->_setAvailableArticlePropertiesFilter(array());
        $this->_setCheckedArticlePropertiesFilter(array());
        $this->_setAvailableBasicFilter(array());
        $this->_setSearchFilter(null);
        
        // Reset current page
        $this->_setCurrentPage(null);
        
        // Reset scroll page
        $this->setScrollPosition(0);
    }
    
    protected function _getParam()
    {
        $value = session::getSessionVar('ecommerce-showcase-param');
        return (isset($value) && !empty($value))? $value : null;
    }
    
    protected function _setParam($value)
    {
        session::setSessionVar('ecommerce-showcase-param', $value);
        $this->_param = $value;
    }
    
    protected function _getCategory()
    {
        $value = session::getSessionVar('ecommerce-showcase-category');
        return (isset($value) && !empty($value))? $value : null;
    }
    
    protected function _setCategory($value)
    {
        session::setSessionVar('ecommerce-showcase-category', $value);
        $this->_category = $value;
    }
    
    protected function _getCanonicalCategory()
    {
        $value = session::getSessionVar('ecommerce-showcase-canonical-category');
        return (isset($value) && !empty($value))? $value : null;
    }
    
    protected function _setCanonicalCategory($value)
    {
        session::setSessionVar('ecommerce-showcase-canonical-category', $value);
        $this->_canonical_category = $value;
    }
    
    protected function _getBrand()
    {
        $value = session::getSessionVar('ecommerce-showcase-brand');
        return (isset($value) && !empty($value))? $value : null;
    }
    
    protected function _setBrand($value)
    {
        session::setSessionVar('ecommerce-showcase-brand', $value);
        $this->_brand = $value;
    }
    
    protected function _getMinPriceFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-min-price-filter');
        return (isset($value) && !empty($value))? $value : null;
    }
    
    protected function _setMinPriceFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-min-price-filter', $value);
        $this->_min_price_filter = $value;
    }
    
    protected function _getMaxPriceFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-max-price-filter');
        return (isset($value) && !empty($value))? $value : null;
    }
    
    protected function _setMaxPriceFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-max-price-filter', $value);
        $this->_max_price_filter = $value;
    }
    
    protected function _getPriceBoundsFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-price-bounds-filter');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    protected function _setPriceBoundsFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-price-bounds-filter', $value);
        $this->_price_bounds_filter = $value;
    }
    
    protected function _getOutstandingFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-outstanding-filter');
        return (isset($value))? $value : null;
    }
    
    protected function _setOutstandingFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-outstanding-filter', $value);
        $this->_outstanding_filter = $value;
    }
    
    protected function _getNoveltyFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-novelty-filter');
        return (isset($value))? $value : null;
    }
    
    protected function _setNoveltyFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-novelty-filter', $value);
        $this->_novelty_filter = $value;
    }
    
    protected function _getPackFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-pack-filter');
        return (isset($value))? $value : null;
    }
    
    protected function _setPackFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-pack-filter', $value);
        $this->_pack_filter = $value;
    }
    
    protected function _getChristmasFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-christmas-filter');
        return (isset($value))? $value : null;
    }
    
    protected function _setChristmasFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-christmas-filter', $value);
        $this->_christmas_filter = $value;
    }
    
    protected function _getPromoFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-promo-filter');
        return (isset($value))? $value : null;
    }
    
    protected function _setPromoFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-promo-filter', $value);
        $this->_promo_filter = $value;
    }
    
    protected function _getSearchFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-search-filter');
        return (isset($value))? $value : null;
    }
    
    protected function _setSearchFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-search-filter', $value);
        $this->_search_filter = $value;
    }
    
    protected function _getAvailableCategoriesFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-available-categories-filter');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    protected function _setAvailableCategoriesFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-available-categories-filter', $value);
        $this->_available_categories_filter = $value;
    }
    
    protected function _getCheckedCategoriesFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-checked-categories-filter');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    protected function _setCheckedCategoriesFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-checked-categories-filter', $value);
        $this->_checked_categories_filter = $value;
    }
    
    protected function _getBrandsFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-available-brands-filter');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    protected function _setAvailableBrandsFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-available-brands-filter', $value);
        $this->_brands_filter = $value;
    }
    
    protected function _getCheckedBrandsFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-checked-brands-filter');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    protected function _setCheckedBrandsFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-checked-brands-filter', $value);
        $this->_checked_brands_filter = $value;
    }
    
    protected function _getAvailableGammaFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-available-gamma-filter');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    protected function _setAvailableGammaFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-available-gamma-filter', $value);
        $this->_available_gamma_filter = $value;
    }
    
    protected function _getCheckedGammaFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-checked-gamma-filter');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    protected function _setCheckedGammaFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-checked-gamma-filter', $value);
        $this->_checked_gamma_filter = $value;
    }
    
    protected function _getAvailableArticlePropertiesFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-available-article-properties-filter');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    protected function _setAvailableArticlePropertiesFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-available-article-properties-filter', $value);
        $this->_available_article_properties_filter = $value;
    }
    
    protected function _getCheckedArticlePropertiesFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-checked-article-properties-filter');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    protected function _setCheckedArticlePropertiesFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-checked-article-properties-filter', $value);
        $this->_checked_article_properties_filter = $value;
    }
    
    protected function _getAvailableBasicFilter()
    {
        $value = session::getSessionVar('ecommerce-showcase-available-basic-filter');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    protected function _setAvailableBasicFilter($value)
    {
        session::setSessionVar('ecommerce-showcase-available-basic-filter', $value);
        $this->_available_basic_filter = $value;
    }
    
    public function getScrollPosition()
    {
        $value = session::getSessionVar('ecommerce-showcase-scroll-position');
        return (isset($value) && !empty($value))? $value : 0;
    }
    
    public function setScrollPosition($value)
    {
        session::setSessionVar('ecommerce-showcase-scroll-position', $value);
    }
    
    protected function _getArticlesPerPage()
    {
        if (isset($this->_articles_per_page)) return $this->_articles_per_page;
        $value = session::getSessionVar('ecommerce-showcase-content-articlesperpage');
        return (isset($value) && !empty($value))? $value : 15;
    }
    
    protected function _setArticlesPerPage($value)
    {
        session::setSessionVar('ecommerce-showcase-content-articlesperpage', $value);
        $this->_articles_per_page = $value;
    }
    
    protected function _getCurrentPage()
    {
        if (isset($this->_current_page)) return $this->_current_page;
        $value = session::getSessionVar('ecommerce-showcase-content-currentpage');
        return (isset($value) && !empty($value))? $value : 1;
    }
    
    protected function _setCurrentPage($value)
    {
        session::setSessionVar('ecommerce-showcase-content-currentpage', $value);
        $this->_current_page = $value;
    }

    protected function _getSortby()
    {
        if (isset($this->_sortby)) return $this->_sortby;
        $value = session::getSessionVar('ecommerce-showcase-content-sortby');
        return (isset($value) && !empty($value))? $value : 'a-z';
    }
    
    protected function _setSortby($value)
    {
        session::setSessionVar('ecommerce-showcase-content-sortby', $value);
        $this->_sortby = $value;
    }
    
    protected function _getArticles()
    {
        $articles_for_sale = array();
        $min_price = 0;
        $max_price = 0;
        $available_categories_filter = array();
        $brands_filter = array();
        $available_gamma_filter = array();
        $available_article_properties_filter = array();
        $basic_filter = array(
            'outstanding' => false,
            'novelty' => false,
            'pack' => false,
            'christmas' => false
        );
        
        if (isset($this->_promo_filter) && !empty($this->_promo_filter))
        {
            $this->_setPromo($this->_promo_filter);
        }
        
        if (isset($this->_search_filter) && !empty($this->_search_filter))
        {
            $searcher_controller = new searcher();
            $needles = explode(' ', $this->_search_filter);
            $searcher_controller->setBrands($this->_brands);
            $searcher_controller->setGammas($this->_gammas);
        }
        
        //$this->_availability_controller->setArticleTypes($this->_article_types);
        $this->_availability_controller->setBrands($this->_brands);
        $this->_availability_controller->setGammas($this->_gammas);
        //$this->_availability_controller->setSaleRates($this->_sale_rates);
        $articles_for_sale_list = $this->_availability_controller->getArticlesForSale();
        if (!empty($articles_for_sale_list))
        {
            foreach ($articles_for_sale_list as $article_code => $article)
            {
                if (!$this->_isBrandAvailable($article->brand)) continue;
                $laboratory = $this->_getLaboratory($article->brand);

                // Filtering
                if (isset($this->_category))
                {
                    // By category
                    if (!isset($article->categories) || empty($article->categories)) continue;
                    $article_categories = explode('|', $article->categories);
                    $matched = false;
                    if (in_array($this->_category, $article_categories)) $matched = true;
                    if (!$matched && isset($this->_canonical_category) && in_array($this->_canonical_category, $article_categories)) $matched = true;
                    if (!$matched) continue;                    
                }
                elseif (isset($this->_brand))
                {
                    // By brand
                    if ($article->brand !== $this->_brand)
                    {
                        // Is a laboratory?
                        if (!$this->_isLaboratory($this->_brand)) continue;
                        if (empty($laboratory) || $this->_brand !== $laboratory) continue;
                    }
                }
                else
                {
                    if (isset($this->_search_filter) && !empty($this->_search_filter))
                    {
                        $article_title = "";
                        if (!$searcher_controller->isArticleMacthed($article, $needles, $article_title))
                        {
                            continue;
                        }
                    }   
                    
                    if (isset($this->_promo))
                    {
                        if (!$this->_isArticleIncludedInPromo($article)) continue;
                    }   
                }

                // First filters
                $check_first_filters = false;
                $continue = false;
                if (!$continue && isset($this->_outstanding_filter) && $this->_outstanding_filter)
                {
                    $check_first_filters = true;
                    if ($article->outstanding)
                    {
                        $continue = true;
                    }
                }
                if (!$continue && isset($this->_novelty_filter) && $this->_novelty_filter)
                {
                    $check_first_filters = true;
                    if ($article->novelty) $continue = true;
                }    
                if (!$continue && isset($this->_pack_filter) && $this->_pack_filter)
                {
                    $check_first_filters = true;
                    if (isset($article->pack) && $article->pack) $continue = true;
                }         
                if (!$continue && isset($this->_christmas_filter) && $this->_christmas_filter)
                {
                    $check_first_filters = true;
                    if (isset($article->christmas) && $article->christmas) $continue = true;
                }       
                if ($check_first_filters && !$continue) continue;
                
                // Filter by categories
                if (!empty($this->_checked_categories_filter))
                {
                    $found = false;
                    $article_categories = explode('|', $article->categories);
                    foreach ($this->_checked_categories_filter as $value)
                    {
                        if (in_array($value, $article_categories))
                        {
                            $found = true;
                            break;  
                        }
                    }
                    if(!$found) continue;               
                }

                // Filter by brands
                if (!empty($this->_checked_brands_filter))
                {
                    if (!in_array($article->brand, $this->_checked_brands_filter) && 
                        !in_array($laboratory, $this->_checked_brands_filter))
                    {
                        continue;
                    }
                }

                // Filter by gamma
                if (!empty($this->_checked_gamma_filter))
                {
                    if (!in_array($article->gamma, $this->_checked_gamma_filter)) continue;            
                }

                // Filter by article properties
                if (!empty($this->_checked_article_properties_filter))
                {
                    if (!isset($article->properties)) continue;
                    $matched = false;
                    foreach ($article->properties as $prop)
                    {
                        $code = $prop->code;
                        $amount = $prop->amount;
                        $value = $prop->value;

                        $checked_filters = $this->_checked_article_properties_filter;
                        if (isset($checked_filters[$code]['amounts'][$amount]['values'][$value]))
                        {
                            $matched = true;
                            break;
                        }
                    }
                    if (!$matched) continue;            
                }

                // Price
                $price = $article->prices->finalRetailPrice;

                // Filter by price range
                if (
                    isset($this->_min_price_filter) && isset($this->_max_price_filter) &&
                    ($price < $this->_min_price_filter || $price > $this->_max_price_filter)    
                   ) continue;

                // Set min price
                if ($min_price == 0 || $price < $min_price)
                {
                    $min_price = $price;
                }

                // Set max price
                if ($max_price == 0 || $price > $max_price)
                {
                    $max_price = $price;
                }     

                // Add categories to filter
                //$this->_addCategoryFilter($article->categories, $available_categories_filter);

                // Add brands to filter
                $this->_addBrandFilter($article->brand, $brands_filter);
                $this->_addBrandFilter($laboratory, $brands_filter);

                // Add gamma to filter
                $this->_addGammaFilter($article, $available_gamma_filter);

                // Add article property to filter
                $this->_addArticlePropertyFilter($article, $available_article_properties_filter);

                // Add basic filter (special offers, novelties, etc.)
                $this->_addBasicFilter($article, $basic_filter);
                        
                // Add article
                $articles_for_sale[] = $article;
            }    

            // Sort articles
            $articles_for_sale = $this->_sortArticles($articles_for_sale);        
        }

        // Set available categories to filter
        if (empty($this->_available_categories_filter))
        {
            $this->_setAvailableCategoriesFilter($available_categories_filter);
        }        
        
        // Set available brands to filter
        if (empty($this->_brands_filter))
        {
            $this->_setAvailableBrandsFilter($brands_filter);
        }        
        
        // Set available gamma to filter
        if (empty($this->_available_gamma_filter))
        {
            $this->_setAvailableGammaFilter($available_gamma_filter);
        }     
        
        // Set available article properties to filter
        if (empty($this->_available_article_properties_filter))
        {
            $this->_setAvailableArticlePropertiesFilter($available_article_properties_filter);
        }    
        
        // Set available article properties to filter
        if (empty($this->_available_basic_filter))
        {
            $this->_setAvailableBasicFilter($basic_filter);
        }
        
        // Set price bounds filter
        if (empty($this->_price_bounds_filter))
        {
            $this->_setPriceBoundsFilter(array('min' => $min_price, 'max' => $max_price));
        }
        
        return $articles_for_sale;
    }  
    
    private function _sortArticles($articles)
    {
        // Sort articles
        usort($articles, function($a, $b)
        {
            $sortby = $this->_getSortby();
            if ($sortby === 'a-z' || $sortby === 'z-a')
            {
                $article_controller = new article();
                $t1 = $article_controller->getTitle($a);     
                $t2 = $article_controller->getTitle($b);
                if ($sortby === 'a-z')
                {
                    return strcmp($t1, $t2);
                }
                else
                {
                    return strcmp($t2, $t1);
                }
            }
            else
            {
                $p1 = (isset($a->prices) && $a->prices->finalRetailPrice > 0)? $a->prices->finalRetailPrice : 0;
                $p2 = (isset($b->prices) && $b->prices->finalRetailPrice > 0)? $b->prices->finalRetailPrice : 0;
                if ($sortby === 'cheaper-first')
                {
                    return ($p1 >= $p2);
                }
                else
                {
                    // 'more-expensive-first'
                    return ($p1 < $p2);
                }
            }
        });
  
        return $articles;
    }

    private function _addCategoryFilter($categories, &$available_categories_filter)
    {
        if (!isset($categories) || empty($categories)) return;
        if (!isset($this->_categories) || empty($this->_categories)) return;
        
        $categories_pieces = explode('|', $categories);
        foreach ($categories_pieces as $value) {
            if (!isset($this->_categories->$value)) continue;
            if (array_key_exists($value, $this->_available_categories_filter)) continue;

            $available_categories_filter[$value] = $this->_categories->$value;
        }
    }     
    
    private function _addBrandFilter($brand, &$brands_filter)
    {
        if (!$this->_isBrandVisible($brand)) return;
        if (array_key_exists($brand, $this->_brands_filter)) return;

        $brands_filter[$brand] = $this->_brands[$brand];
    }     
    
    private function _addGammaFilter($article, &$available_gamma_filter)
    {
        $gamma = $article->gamma;
        $brand = $article->brand;
                
        if (!isset($this->_brand)) return;
        if (!isset($brand)) return;
        if (!isset($gamma) || empty($gamma)) return;
        if (!isset($this->_gammas) || empty($this->_gammas)) return;
        if (!isset($this->_gammas[$gamma][$brand])) return;
        if (!$this->_gammas[$gamma][$brand]->visible) return;
        if (array_key_exists($gamma, $this->_available_gamma_filter)) return;

        $available_gamma_filter[$gamma] = $this->_gammas[$gamma][$brand];
    }   
    
    private function _addArticlePropertyFilter($article, &$available_article_properties_filter)
    {
        if (!isset($article->properties) || empty($article->properties)) return;
        if (!isset($this->_article_properties) || empty($this->_article_properties)) return;
        
        foreach ($article->properties as $prop)
        {
            $code = $prop->code;
            $amount = $prop->amount;
            $value = $prop->value;
            if (!isset($this->_article_properties[$code])) continue;
            $property = $this->_article_properties[$code];
            if (!$property->available) continue;
            
            if (isset($available_article_properties_filter[$code]) &&
                isset($available_article_properties_filter[$code]['amounts'][$amount]) &&
                isset($available_article_properties_filter[$code]['amounts'][$amount]['values'][$value]))
            {
                continue;
            }
            
            if (!isset($property->values[$value])) continue;
            $thevalue = $property->values[$value];
            if (!$thevalue->available) continue;
            
            // Finally, add property
            $available_article_properties_filter[$code]['property'] = $property;
            $available_article_properties_filter[$code]['amounts'][$amount]['values'][$value] = $thevalue;
        }
    }  
    
    private function _addBasicFilter($article, &$basic_filter)
    {
        if ($article->outstanding && !$basic_filter['outstanding'])
        {
            $basic_filter['outstanding'] = true;
        }
        if ($article->novelty && !$basic_filter['novelty'])
        {
            $basic_filter['novelty'] = true;
        }
        if (isset($article->pack) && $article->pack && !$basic_filter['pack'])
        {
            $basic_filter['pack'] = true;
        }
        if (isset($article->christmas) && $article->christmas && !$basic_filter['christmas'])
        {
            $basic_filter['christmas'] = true;
        }
    }
    
    protected function _getCategoriesTree()
    {
        return $this->getCategoriesTree();
    }
    
    private function _setCategoriesTree()
    {
        $this->_categories_tree = $this->_getCategoriesTree();
    }
    
    protected function _getCategories()
    {
        return $this->_categories;
    }
    
    private function _setCategories()
    {
        if (!isset($this->_categories_tree)) return;
        if (!isset($this->_categories_tree->categories)) return;
        
        $this->_categories = $this->_categories_tree->categories;
    }
    
    protected function _getBrands()
    {
        return $this->_brands;
    }
    
    private function _setBrands()
    {
        $this->_brands = $this->_availability_controller->getBrands();
    }
    
    protected function _getGammas()
    {
        return $this->_gammas;
    }
    
    protected function _setGammas()
    {
        $this->_gammas = $this->_availability_controller->getGammas();
    }
    
    private function _setArticleTypes()
    {
        $this->_article_types = $this->_availability_controller->getArticleTypes();
    }
    
    private function _setArticleProperties()
    {
        $this->_article_properties = $this->_availability_controller->getArticleProperties();
    }
    
    protected function _setSaleRates()
    {
        $this->_sale_rates = $this->_availability_controller->getSaleRates();
    }
    
    protected function _getCategoryByParam($category)
    {
        $current_lang = lang::getCurrentLanguage();
        $category_key_matched = null;
        
        foreach ($this->_categories as $key => $value)
        {
            $url_property = 'url'.ucfirst($current_lang);
            if (isset($value->$url_property) && 
                !empty($value->$url_property) && 
                $category === $value->$url_property)
            {
                $canonical_category = null;
                if (isset($value->canonical) && !empty($value->canonical))
                {
                    $canonical_category = $value->canonical;
                }
                return array(
                    'category' => $key,
                    'canonical_category' => $canonical_category
                );
            }
            if ($category === $key)
            {
                $category_key_matched = $key;
            }
        }
        
        return array(
            'category' => $category_key_matched,
            'canonical_category' => null
        );
    }
    
    protected function _getBrandByParam($brand)
    {
        return (isset($this->_brands[$brand]))? $brand : null;
    }
    
    protected function _getOutstandingArticles($article_data)
    {
        $ret = array();
        
        if (empty($article_data))
        {
            return $ret;
        }
        
        foreach ($article_data as $article)
        {
            if (!$article->outstanding) continue;
            $ret[] = $article;
        }
        
        return $ret;
    }
    
    private function _isBrandAvailable($brand)
    {
        if (!isset($brand) || empty($brand)) return false;
        if (!isset($this->_brands) || empty($this->_brands)) return false;
        if (!isset($this->_brands[$brand])) return false;
        return $this->_brands[$brand]->available;
    }
    
    private function _isBrandVisible($brand)
    {
        if (!isset($brand) || empty($brand)) return false;
        if (!isset($this->_brands) || empty($this->_brands)) return false;
        if (!isset($this->_brands[$brand])) return false;

        if ($this->_isLaboratory($brand))
        {
            return true;
        }

        return (!isset($this->_brands[$brand]->visible) || $this->_brands[$brand]->visible);
    }
    
    private function _isLaboratory($brand)
    {
        // Is a laboratory?
        return (isset($this->_brands[$brand]) && 
                isset($this->_brands[$brand]->isLaboratory) && 
                $this->_brands[$brand]->isLaboratory);
    }
    
    private function _getLaboratory($brand)
    {
        // Is the brand assigned to some laboratory?
        if (!isset($this->_brands[$brand]) ||
            !isset($this->_brands[$brand]->laboratory) || 
            empty($this->_brands[$brand]->laboratory)
        )
        {
            return '';
        }
        
        return $this->_brands[$brand]->laboratory;
    }

    protected function _setPromo($promo_code)
    {
        $promos = $this->_getPromos();
        if (isset($promos) && isset($promos->$promo_code))
        {
            $promo = $promos->$promo_code;
            
            // Add articles group
            if (isset($promo->articleGroup) && !empty($promo->articleGroup))
            {
                $article_group_code = $promo->articleGroup;
                $article_groups = $this->_getArticleGroups();
                if (isset($article_groups) && !empty($article_groups))
                {
                    if (isset($article_groups->$article_group_code))
                    {
                        $promo->articleGroupData = $article_groups->$article_group_code;
                        $this->_promo = $promo;
                    }
                }                
            }
        }        
    }
    
    protected function _getPromo()
    {
        return $this->_promo;
    }
    
    protected function _getParamValues()
    {
        return $this->_promo;
    }
    
    private function _isArticleIncludedInPromo($article)
    {
        if (!isset($this->_promo))
        {
            return false;
        }
        
        $article_group = $this->_promo->articleGroupData;
        
        $article_types = $article_group->articleTypes;
        $brands = $article_group->brands;
        $gammas = $article_group->gammas;
        $articles = $article_group->articles;
        
        // Check article type
        if (!empty($article_types))
        {
            foreach ($article_types as $values) {
                if ($article->articleType === $values->code)
                {
                    return true;
                }
            }
        }
        
        // Check brand
        if (!empty($brands))
        {
            foreach ($brands as $values) {
                if ($article->brand === $values->code)
                {
                    return true;
                }
            }
        }
        
        // Check gamma
        if (!empty($gammas) && !empty($article->gamma))
        {
            foreach ($gammas as $values) {
                if ($article->gamma === $values->code && $article->brand === $values->brand)
                {
                    return true;
                }
            }
        }
        
        // Check articles
        if (!empty($articles))
        {
            foreach ($articles as $values) {
                if ($article->code === $values->code)
                {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    protected function _getTotalPages() 
    {
        $total_articles = count($this->_article_data);
        if ($total_articles <= $this->_articles_per_page)
        {
            return 0;
        }
        
        $pages = ceil($total_articles / $this->_articles_per_page);
        
        return $pages;
    }
    
}