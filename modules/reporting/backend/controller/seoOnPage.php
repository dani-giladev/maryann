<?php

namespace modules\reporting\backend\controller;

// Controllers
use modules\reporting\backend\controller\reporting;
use modules\cms\controller\website;
use modules\cms\frontend\controller\lang;
use modules\ecommerce\frontend\controller\availability;

// Views
use modules\reporting\backend\view\seoOnPage as view;

/**
 * Backend SEO-On-Page controller for reporting
 *
 * @author Dani Gilabert
 * 
 */
class seoOnPage extends reporting
{
    
    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
    }  
    
    public function renderReport()
    {
        $data = array();

        // Get website
        $website_controller = new website();
        $website = $website_controller->getWebsiteByCode('ecommerce', 'farmacia', true);
        
        // Get available langs
        $available_langs = lang::getAvailableLanguages($website); 
        if (!isset($available_langs))
        {
            echo "The website doesn't have assigned any language.";
            return;
        }  
        
        // Get articles for sale
        $availability = new availability($this->_delegation);        
        $raw_articles_for_sale_list = $availability->getArticlesForSale(array(), true);
        $articles_for_sale = array();
        foreach ($raw_articles_for_sale_list as $article)
        {
            $articles_for_sale[$article->code] = $article;
        }
        
        // Articles with repeated urls
        $data['articles_with_repeated_urls'] = array();
        foreach ($available_langs as $lang_code => $lang_name)
        {
            $url_article_property = 'url'.ucfirst($lang_code);
            $urls = array();
            foreach ($articles_for_sale as $article_code => $article)
            {
                $url = $article->$url_article_property;
                if (isset($urls[$url]))
                {
                    $data['articles_with_repeated_urls'][] = array(
                        'lang_code' => $lang_code,
                        'url' => $url,
                        'article1' => $urls[$url],
                        'article2' => $article
                    );
                    continue;
                }
                
                $urls[$url] = $article;
            }
        }
        
        // Get brands and labs
        $brands = $availability->getBrands();
        
        // Brands with repeated urls
        $data['brands_with_repeated_urls'] = array();
        foreach ($available_langs as $lang_code => $lang_name)
        {
            $urls = array();
            foreach($brands as $brand_code => $brand)
            {
                $url = $brand_code;
                if (isset($urls[$url]))
                {
                    $data['brands_with_repeated_urls'][] = array(
                        'lang_code' => $lang_code,
                        'url' => $url,
                        'brand' => $brand
                    );
                    continue;
                }
                
                $urls[$url] = $brand;
            }
        }
        
        // Empty brands?
        $data['empty_brands_not_marked_as_empty'] = array();
        $data['not_empty_brands_marked_as_empty'] = array();
        foreach($brands as $brand_code => $brand)
        {
            if (!$brand->available || (isset($brand->visible) && !$brand->visible))
            {
                continue;
            }
            
            $is_empty = true;
            foreach ($articles_for_sale as $article_code => $article)
            {
                $article_laboratory = $this->_getLaboratory($article->brand, $brands);
                if ($article->brand !== $brand->code)
                {
                    // Is a laboratory?
                    if (!$this->_isLaboratory($brand->code, $brands))
                    {
                        continue;
                    }
                    if (empty($article_laboratory) || $brand->code !== $article_laboratory)
                    {
                        continue;
                    }   
                }

                $is_empty = false;
                break;
            }
            if ($is_empty && (!isset($brand->empty) || !$brand->empty))
            {
                $data['empty_brands_not_marked_as_empty'][$brand_code] = $brand;
            }
            elseif (!$is_empty && (isset($brand->empty) && $brand->empty))
            {
                $data['not_empty_brands_marked_as_empty'][$brand_code] = $brand;
            }
        }        
        
        // Get categories
        $categories = $availability->getCategoriesTree();
        
        // Categories with repeated urls
        $data['categories_with_repeated_urls'] = array();
        foreach ($available_langs as $lang_code => $lang_name)
        {
            $url_category_property = 'url'.ucfirst($lang_code);
            $urls = array();
            foreach($categories->categories as $category_code => $category)
            {
                if (isset($category->$url_category_property) &&
                    !empty($category->$url_category_property))
                {
                    $url = $category->$url_category_property;
                }
                else
                {
                    $url = $category->code; 
                }  
                if (isset($urls[$url]))
                {
                    $data['categories_with_repeated_urls'][] = array(
                        'lang_code' => $lang_code,
                        'url' => $url,
                        'category' => $category,
                        'repeated_category' => $urls[$url]
                    );
                    continue;
                }
                
                $urls[$url] = $category;
            }
        }
        
        // Empty and not available categories
        $data['empty_categories_not_marked_as_empty'] = array();
        $data['not_empty_categories_marked_as_empty'] = array();
        $data['not_available_categories'] = array();
        foreach($categories->categories as $category_code => $category)
        {
            $category_code_to_check = (isset($category->canonical) && !empty($category->canonical))? $category->canonical : $category_code;
            $is_empty = true;
            foreach ($articles_for_sale as $article_code => $article)
            {
                if (!isset($article->categories) || empty($article->categories)) continue;   
                $article_categories = explode('|', $article->categories);
                if (!in_array($category_code_to_check, $article_categories)) continue; 

                $is_empty = false;
                break;
            }
            
            $this->_addBreadcrumb($category, $category_code, $categories);

            if ($is_empty && (!isset($category->empty) || !$category->empty))
            {
                $data['empty_categories_not_marked_as_empty'][$category_code] = $category;
            }
            elseif (!$is_empty && (isset($category->empty) && $category->empty))
            {
                $data['not_empty_categories_marked_as_empty'][$category_code] = $category;
            }

            if (!$category->available)
            {
                $data['not_available_categories'][$category_code] = $category;
                continue;
            } 
        }
        
        // Go!
        echo $this->_view->getHtmlReport($data);
    } 
    
    private function _addBreadcrumb(&$category, $category_code, $categories)
    {
        $category->breadcrumb = '';
        if (isset($categories->breadcrumbs) && isset($categories->breadcrumbs->$category_code))
        {
            $is_first = true;
            foreach ($categories->breadcrumbs->$category_code as $value)
            {
                if (!$is_first)
                {
                    $category->breadcrumb .= '   >   ';
                }
                $category->breadcrumb .= $value->name;
                $is_first = false;
            }
        }        
    }
    
    private function _isLaboratory($brand, $brands)
    {
        // Is a laboratory?
        return (isset($brands[$brand]) && 
                isset($brands[$brand]->isLaboratory) && 
                $brands[$brand]->isLaboratory);
    }
    
    private function _getLaboratory($brand, $brands)
    {
        // Is the brand assigned to some laboratory?
        if (!isset($brands[$brand]) ||
            !isset($brands[$brand]->laboratory) || 
            empty($brands[$brand]->laboratory)
        )
        {
            return '';
        }
        
        return $brands[$brand]->laboratory;
    }
    
    
}