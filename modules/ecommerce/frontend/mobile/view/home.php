<?php

namespace modules\ecommerce\frontend\mobile\view;

// Views
use modules\ecommerce\frontend\view\home as homeView;

/**
 * Home mobile view
 *
 * @author Dani Gilabert
 * 
 */
class home extends homeView
{ 
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            //'/res/css/swiper-3.1.7.min.css', 
            '/res/css/swiper-4.1.0.min.css', 

            // Novelty articles
            '/modules/ecommerce/frontend/mobile/res/css/showcase/showcase-content-articles.css', 
            '/modules/ecommerce/frontend/mobile/res/skins/'.$this->_skin.'/showcase/showcase-content-articles.css', 

            // Outstanding brands
            '/modules/ecommerce/frontend/mobile/res/css/brands.css', 

            '/modules/ecommerce/frontend/mobile/res/css/home.css'
        );   
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }  
    
    public function getDevelopmentHeadScriptsPaths()
    {
        $ecommerce_scripts = $this->_getHeadEcommerceScriptsPaths();
        
        $scripts = array(
            //'/res/js/swiper-3.1.7.min.js',
            '/res/js/swiper-4.1.0.min.js',

            '/modules/ecommerce/frontend/mobile/res/js/home.js'
        );  

        $ret = array_merge($ecommerce_scripts, $scripts);      
        
        return $ret;
    } 
    
    protected function _renderSlider()
    {
        $html = '';
        $rel_external = $this->getRelExternalTag();

        $html .= 
                '<div id="page-slider-wrapper" >'.
                    '<div class="swiper-container">'.
                        '<div class="swiper-wrapper"></div>'.
                        // <!-- Add slides -->
                        '<div class="swiper-slides-landscape">'.
                '';

        foreach ($this->slider_images as $img)
        {
            if (isset($img['url']) && !empty($img['url']))
            {
                $start_anchor_tag = '<a class="swiper-slide-link" href="'.$img['url'].'"'.$rel_external.'>';//' target="_blank">';
                $end_anchor_tag = '</a>';
            }
            else {
                $start_anchor_tag = '';
                $end_anchor_tag = '';
            }
            
            $html .=
                            '<div class="swiper-slide">'.
                                $start_anchor_tag.
                                '<img class="swiper-slide-img" '.
                                    'src="'.$img['src'].'" '.
                                    'alt="'.$img['title'].'" '.
                                    'data-description="'.$img['description'].'" '.
                                '>'.
                                '</img>'.
                                $end_anchor_tag.
                            '</div>'.
                            '';
        }
        
        $html .= 
                        '</div>'.
                        // <!-- Add Pagination -->
                        //'<div class="swiper-pagination infront-1"></div>'.
                        //<!-- Add Arrows -->
                        '<div class="swiper-button-next"></div>'.
                        '<div class="swiper-button-prev"></div>'.
                    '</div>'.
                '</div>'.
            '';
        
        return $html;
    }
    
    protected function _renderStartBanners()
    {
        $html = '';
        
        $html .= '<div class="home-banners-wrapper">';
        
        return $html;
    }
    
    protected function _renderEndBanners()
    {
        $html = '';
        
        $html .= '</div>';
        
        return $html;
    }
    
    protected function _renderStartOutstandingBrandsTitle()
    {
        $html = '';
        
        $html .= '<div class="home-outstanding-brands-wrapper">';
        
        return $html;
    }
    
    protected function _renderEndOutstandingBrandsTitle()
    {
        $html = '';
        
        return $html;
    }
    
    protected function _renderStartOutstandingBrandsContent()
    {
        $html = '';
        
        $html .= 
                        '<div id="home-outstanding-brands-content">'.
                '';
        
        return $html;
    }
    
    protected function _renderEndOutstandingBrandsContent()
    {
        $html = '';
        
        $html .= 
                    '</div>'.
                '</div>';
        
        return $html;
    }
    
}