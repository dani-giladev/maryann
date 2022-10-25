<?php

namespace modules\ecommerce\frontend\view\header;

// Controllers
use core\config\controller\config;
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;
use modules\ecommerce\frontend\view\menu\searcher as searcherView;

/**
 * Header view
 *
 * @author Dani Gilabert
 * 
 */
class header extends ecommerceView
{
    protected $_website;
    
    public function __construct($website)
    {
        parent::__construct();
        $this->_website = $website;
    }           
    
    public function render()
    {
        $html = '';
        
        // Start header 
        $html .= 
                '<div id="header" class="header">'.
                    '<div id="header-center" class="page-center">';
        
        // Logo
        $html .= $this->_renderLogo();
        
        // Free shipping info
        $html .= $this->_renderFreeShippingInfo();
        
        // Build top menu (special menu)
        $html .= $this->_renderSpecialmenu();
        
        // Searcher
        $searcher_view = new searcherView();        
        $html .= $searcher_view->renderSearcher();
        
        // End header 
        $html .= '</div>'.'</div>';
        
        return $html;
    }
    
    private function _renderLogo()
    {
        $html = '';
        $website = $this->_website;
        $current_lang = lang::getCurrentLanguage();
        
        if (!isset($website->logo) || empty($website->logo))
        {
           return $html;
        }
        
        // Start
        $html .=  '<div id="header-logo-wrapper">';
                
        // Title
        $html .= 
                '<div id="header-logo-title">'.
//                    lang::trans('pharmacies').
                    str_replace('à', 'a', lang::trans('pharmacy')).
                '</div>'.
                '';
        
        // Image
        $home_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'home'));        
        $image_path = $this->_ecommerce_controller->getLogoPath($website);
        $html .= 
                '<a href="'.$home_url.'">'.
                    '<img id="header-logo-img" src="'.$image_path.'" />'.
                '</a>'.                
                '';  
        
        // Base line
        $html .= 
                '<div id="header-logo-baseline">'.
                    'A HEALTH EXPERIENCE'.
                '</div>'.
                '';
        
        // End
        $html .=  '</div>';
        
        return $html;
    }
    
    private function _renderFreeShippingInfo()
    {
        $free_shipping_cost_from = config::getConfigParam(array("ecommerce", "free_shipping_cost_from"))->value;
        
        $img_path = "/modules/ecommerce/frontend/res/img/truck-white.png";
        
        $html =  
                '<div id="header-freeshipping-wrapper">'.
                    '<div id="header-freeshipping-text" class="label-info">'.
                        lang::trans("free_shipping_cost_from").' '.$free_shipping_cost_from.'&euro;'.
                    '</div>'.
                    '<img id="header-freeshipping-img" src="'.$img_path.'" />'.
                '</div>'.
                '';  
        
        return $html;
    }
    
    private function _renderSpecialmenu()
    {
        $html = '<div id="header-specialmenu-wrapper" class="fadeout">';
        
        $menus = $this->_ecommerce_controller->getSpecialMenuData();
        $total = count($menus);
        foreach ($menus as $key => $menu)
        {
            $html .= 
                    '<a href="'.$menu['url'].'" class="header-specialmenu">'.
                        mb_strtoupper($menu['text']).
                    '</a>';
            if ($key < ($total-1))
            {
                $html .= '<div class="header-specialmenu-space">-</div>';
            }
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
}