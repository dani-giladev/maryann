<?php

namespace modules\reporting\backend\controller;

// Controllers
use modules\reporting\backend\controller\reporting;
use modules\ecommerce\frontend\controller\availability;
use modules\ecommerce\controller\laboratory;
use modules\ecommerce\controller\brand;
use modules\ecommerce\controller\gamma;
use modules\ecommerce\controller\article;

// Views
use modules\reporting\backend\view\brandsAndGammas as view;

/**
 * Backend Brands and gammas controller for reporting
 *
 * @author Dani Gilabert
 * 
 */
class brandsAndGammas extends reporting
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
        $data['total_brands'] = 0;
        $data['total_gammas'] = 0;
        $data['total_labs'] = 0;
        
        // Get all labs
        $lab_controller = new laboratory();
        $raw_all_labs_list = $lab_controller->getLaboratories();
        $all_labs = array();
        foreach($raw_all_labs_list as $lab)
        {
            $all_labs[$lab->code] = $lab;
        }         
        $data['total_labs'] = count($all_labs);
        
        // Get all brands
        $brand_controller = new brand();
        $raw_all_brands_list = $brand_controller->getBrands();
        $all_brands = array();
        foreach($raw_all_brands_list as $brand)
        {
            $all_brands[$brand->code] = $brand;
        }         
        $data['total_brands'] = count($all_brands);
        
        // Get all gammas
        $gamma_controller = new gamma();
        $raw_all_gammas_list = $gamma_controller->getGammas();
        $all_gammas = array();
        foreach($raw_all_gammas_list as $gamma)
        {
            $all_gammas[] = $gamma;
        }         
        $data['total_gammas'] = count($all_gammas);
        
        /*
         * 
         * Brands
         * 
         */
        $data['available_brands'] = array();
        $data['total_available_brands_visible'] = 0;
        $data['total_available_brands_no_visible'] = 0;
        $data['not_available_brands'] = array();
        $data['articles_with_inexistent_brands'] = array();
        $data['articles_with_incoherent_type'] = array();
        foreach($all_brands as $code => $brand)
        {
            if ($brand->available)
            {
                $data['available_brands'][$code] = $brand;
                if ($brand->visible)
                {
                    $data['total_available_brands_visible']++;
                }
                else
                {
                    $data['total_available_brands_no_visible']++;
                }
            }
            else
            {
                $data['not_available_brands'][$code] = $brand;
            }
        }        
        
        // Get brands and labs
        $availability = new availability($this->_delegation); 
        $brands = $availability->getBrands();
        
        // Get all articles
        $article_controller = new article();
        $raw_all_articles_list = $article_controller->getArticles();
        $all_articles = array();
        foreach($raw_all_articles_list as $article)
        {
            $all_articles[$article->code] = $article;
        }          

        foreach($all_articles as $article_code => $article)
        {
            if (!isset($brands[$article->brand]))
            {
                $data['articles_with_inexistent_brands'][$article_code] = $article;
            }
            
            $brand = $brands[$article->brand];
            
            // Check inconsistencies with medical properties
            $article_type = $article->articleType;
            if ($article_type == '2')
            {
                if (!isset($brand->medicines) || !$brand->medicines)
                {
                    $data['articles_with_incoherent_type'][$article_code] = $article;
                } 
            }
            else
            {
                if (isset($brand->medicines) && $brand->medicines)
                {
                    $data['articles_with_incoherent_type'][$article_code] = $article;
                }
            }
        }           
        
        /*
         * 
         * Gammas
         * 
         */
        $data['available_gammas'] = array();
        $data['not_available_gammas'] = array();
        foreach($all_gammas as $gamma)
        {
            if ($gamma->available)
            {
                $data['available_gammas'][] = $gamma;
            }
            else
            {
                $data['not_available_gammas'][] = $gamma;
            }
        }   
        
        /*
         * 
         * Labs
         * 
         */
        $data['available_labs'] = array();
        $data['not_available_labs'] = array();
        foreach($all_labs as $code => $lab)
        {
            if ($lab->available)
            {
                $data['available_labs'][$code] = $lab;
            }
            else
            {
                $data['not_available_labs'][$code] = $lab;
            }
        }    
        
        // Go!
        echo $this->_view->getHtmlReport($data);
    } 
    
    
}