<?php

namespace modules\ecommerce\frontend\view;

// Controllers
use core\device\controller\device;
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;
use modules\ecommerce\frontend\view\showcase\content as showcaseContentView;
use modules\ecommerce\frontend\mobile\view\showcase\content as mobileShowcaseContentView;
use modules\ecommerce\frontend\view\brands as brandsView;
use modules\ecommerce\frontend\mobile\view\brands as mobileBrandsView;

/**
 * Home webpage view
 *
 * @author Dani Gilabert
 * 
 */
class home extends ecommerceView
{ 
    public $website;
    public $current_lang;
    public $outstanding_articles = array();
    public $novelty_articles = array();
    public $brands = array();
    public $outstanding_brands = array();
    public $gammas = array();
    public $slider_images = array();
    public $banners_images = array();
    
    public function getWebpageName()
    {
        return 'home';
    } 
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/res/css/pslider/pgwslider.min.css',

            // Novelty articles
            '/modules/ecommerce/frontend/res/css/showcase/showcase-content-articles.css', 
            '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/showcase/showcase-content-articles.css', 

            // Outstanding brands
            '/modules/ecommerce/frontend/res/css/brands.css', 

            '/modules/ecommerce/frontend/res/css/home.css'
        );      
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }  
    
    public function getDevelopmentHeadScriptsPaths()
    {
        $ecommerce_scripts = $this->_getHeadEcommerceScriptsPaths();
        
        $scripts = array(
            '/res/js/pslider/pgwslider.min.js',

            '/modules/ecommerce/frontend/res/js/home.js'
        );

        $ret = array_merge($ecommerce_scripts, $scripts);      
        
        return $ret;
    }
    
    protected function _addJavascriptVars()
    {
        // Javascript vars and messages
        $html = $this->_renderHeadEcommerceJavascriptVars();
        
        return $html;
    }   
    
    protected function _addMicrodata()
    {
        // Add microdata
        $current_lang = $this->current_lang;
        $html = '';
        
        if (isset($this->website->udata->$current_lang))
        {
            $html .= $this->website->udata->$current_lang.PHP_EOL;
        }
        
        return $html;
    }
    
    public function renderStartContent()
    {
        $html = 
                '<div id="home-page">'.
                '';   
        
        return $html;
    } 
    
    public function renderEndContent()
    {
        $html = 
                '</div>'.
                '';   
        
        return $html;
    }    
    
    public function renderContent()
    {
        $html = '';

        $html .= '<div id="home-slider-mask"></div>';
                 
        $html .= $this->_renderSlider();
        $html .= $this->_renderBanners();
        $html .= $this->_renderOutstandingBrands();
        $html .= $this->_renderArticles('outstanding');
        $html .= $this->_renderArticles('novelty');
        
        return $html;
    }
    
    protected function _renderSlider()
    {
        $html = '';
        $rel_external = $this->getRelExternalTag();

        $html .= 
                '<div id="home-slider">'.
                    '<ul class="pgwSlider">';

        foreach ($this->slider_images as $img)
        {
            if (isset($img['url']) && !empty($img['url']))
            {
                $start_anchor_tag = '<a href="'.$img['url'].'"'.$rel_external.'>';//' target="_blank">';
                $end_anchor_tag = '</a>';
            }
            else {
                $start_anchor_tag = '';
                $end_anchor_tag = '';
            }
            
            $html .= 
                    '<li>'.
                        $start_anchor_tag.
                        '<img '.
                            'src="'.$img['src'].'" '.
                            'alt="'.$img['title'].'" '.
                            'data-description="'.$img['description'].'" '.
                        '/>'.
                        $end_anchor_tag.
                    '</li>';
        }
        
        $html .= 
                    '</ul>'.
                '</div>'.
                '<div id="home-slider-bottom"></div>'.
                '';
        
        return $html;
    }
    
    protected function _renderStartBanners()
    {
        $html = '';
        
        $html .= 
                '<div id="home-banners">'.
                    '<div class="home-page-center page-center home-banners-center">'.
                '';
        
        return $html;
    }
    
    protected function _renderEndBanners()
    {
        $html = '';
        
        $html .=                                     
                    '</div>'.
                '</div>'.
                '';
        
        return $html;
    }
    
    protected function _renderBanners()
    {
        $html = '';
        
        if (empty($this->banners_images))
        {
            return $html;
        }
        
        // Start
        $html .= $this->_renderStartBanners();
        
        // Content
        foreach ($this->banners_images as $key => $row)
        {
            $html .= 
                    '<table border="0" cellpadding="0" cellspacing="0" '.
                        'width="'.$row->width.'" '.
                        'height="'.$row->height.'" '.
                        'style="'.
                            'margin-top='.$row->marginTop.'; margin-right='.$row->marginRight.'; margin-bottom='.$row->marginBottom.'; margin-left='.$row->marginLeft.'; '.
                            'padding-top='.$row->paddingTop.'; padding-right='.$row->paddingRight.'; padding-bottom='.$row->paddingBottom.'; padding-left='.$row->paddingLeft.
                        ';" '.
                    '>'.
                        '<tr>';
            
            foreach ($row->columns as $column)
            {
                $html .= 
                            '<td '.
                                'width="'.$column->width.'" '.
                                'height="'.$column->height.'" '.
                                'style="'.
                                    'margin-top='.$column->marginTop.'; margin-right='.$column->marginRight.'; margin-bottom='.$column->marginBottom.'; margin-left='.$column->marginLeft.
                                    '; padding-top='.$column->paddingTop.'; padding-right='.$column->paddingRight.'; padding-bottom='.$column->paddingBottom.'; padding-left='.$column->paddingLeft.
                                ';" '.
                            '>';
                
                $html .= $this->_renderBanner($column);
                
                $html .= 
                            '</td>';
            }
            
            $html .= 
                        '</tr>'.
                    '</table>'.
                    '';
            
        }
        
        // End
        $html .= $this->_renderEndBanners();
        
        return $html;
    }
    
    protected function _renderBanner($column)
    {
        $rel_external = $this->getRelExternalTag();
        
        if (isset($column->url) && !empty($column->url))
        {
            $start_anchor_tag = '<a href="'.$column->url.'"'.$rel_external.'>';//' target="_blank">';
            $end_anchor_tag = '</a>';
        }
        else {
            $start_anchor_tag = '';
            $end_anchor_tag = '';
        }
        
        $html = 
                $start_anchor_tag.
                '<img '.
                    'width="100%" '.
                    'height="auto" '.                
                    'src="'.$column->src.'" '.
                    'alt="'.$column->title.'" '.
                    'data-description="'.$column->description.'" '.
                '/>'.
                $end_anchor_tag.              
            '';
        
        return $html;
    }
    
    protected function _renderArticles($type)
    {
        $html = '';
        
        $var = $type.'_articles';
        $articles = $this->$var;
        
        if (empty($articles))
        {
            return $html;
        }
    
        // Start
        $html .= 
                '<div id="home-'.$type.'-articles">'.
                    '<div class="home-page-center page-center">'.
                '';
        
        if ($type === 'novelty')
        {
            $title_key = 'novelties';
            $h = '1';
        }
        else
        {
            $title_key = 'outstanding_articles';
            $h = '2';
        }
        
        $html .= 
                '<h'.$h.' id="home-'.$type.'-articles-title" class="title">'.
                    strtoupper(lang::trans($title_key)).
                '</h'.$h.'>'.
                '';
        
        $html .= '<div id="home-'.$type.'-articles-content">';
        
        if (device::isMobileVersion())
        {
            $showcase_content_view = new mobileShowcaseContentView();
            $showcase_content_view->columns = 1; 
        }
        else
        {
            $showcase_content_view = new showcaseContentView();
        }
        $showcase_content_view->articles = $articles; 
        $showcase_content_view->brands = $this->brands;
        $showcase_content_view->gammas = $this->gammas;
        $showcase_content_view->show_addtocart_area = false; 
        $html .=  $showcase_content_view->renderArticles();
        
        $html .= '</div>';
                
        // End
        $html .= 
                    '</div>'.
                '</div>';
        
        return $html;
    }
    
    protected function _renderStartOutstandingBrandsTitle()
    {
        $html = '';
        
        $html .= 
                '<div class="home-outstanding-brands">'.
                    '<div class="home-page-center page-center">'.
                '';
        
        return $html;
    }
    
    protected function _renderEndOutstandingBrandsTitle()
    {
        $html = '';
        
        $html .= 
                    '</div>'.
                '</div>';
        
        return $html;
    }
    
    protected function _renderStartOutstandingBrandsContent()
    {
        $html = '';
        
        $html .= 
                '<div class="home-outstanding-brands home-outstanding-brands-background">'.
                    '<div class="home-page-center page-center">'.
                        '<div id="home-outstanding-brands-content">'.
                '';
        
        return $html;
    }
    
    protected function _renderEndOutstandingBrandsContent()
    {
        $html = '';
        
        $html .= 
                        '</div>'.
                    '</div>'.
                '</div>';
        
        return $html;
    }
    
    protected function _renderOutstandingBrands()
    {
        $html = '';
        $brands = $this->outstanding_brands;
        
        if (empty($brands))
        {
            return $html;
        }
    
        // Start
        $html .= $this->_renderStartOutstandingBrandsTitle();
        
        $html .= 
                '<h3 id="home-outstanding-brands-title" class="title">'.
                    strtoupper(lang::trans('outstanding_brands')).
                '</h3>'.
                '';
                
        // End
        $html .= $this->_renderEndOutstandingBrandsTitle();
    
        // Start
        $html .= $this->_renderStartOutstandingBrandsContent();
        
        if (device::isMobileVersion())
        {
            $brands_view = new mobileBrandsView();
        }
        else
        {
            $brands_view = new brandsView();
        }
        $brands_view->current_lang = $this->current_lang;
        $brands_view->brands = $this->brands;
        $brands_view->max_brands = 10;
        $brands_view->show_brand_name_text = false;
        $html .=  $brands_view->renderBrandsByLetter($this->outstanding_brands);
        
        // End
        $html .= $this->_renderEndOutstandingBrandsContent();
        
        return $html;
    }
    
}