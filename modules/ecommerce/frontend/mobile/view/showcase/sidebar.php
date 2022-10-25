<?php

namespace modules\ecommerce\frontend\mobile\view\showcase;

// Controllers
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\showcase\sidebar as showcaseSidebarView;

/**
 * Showcase mobile view (sidebar)
 *
 * @author Dani Gilabert
 * 
 */
class sidebar extends showcaseSidebarView
{        
    
    protected function _renderStartSidebar()
    {
        $html = 
                '<div data-role="panel" id="showcase-sidebar" data-position="left" data-display="overlay">'.
                    '<a id="showcase-sidebar-close-button" data-rel="close" class="ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-notext ui-icon-delete"></a>'.
                    '<div id="showcase-sidebar-title" class="title">'.
                        strtoupper(lang::trans('filters')).
                    '</div>'.                
                '';
        
        return $html;
    }   
    
    protected function _renderEndSidebar()
    {
        $html = 
                '</div>'.
                ''; 
        
        return $html;
    }    
    
    protected function _renderContent()
    {
        $html = '';
        
//        // Building categories menu
//        $html .= $this->_renderArticleSubcategoriesMenu(); 
//        
//        // Building brands and filters
        $html .= $this->_renderBrandsAndFilters();
        
        return $html;
    } 
    
    protected function _renderBrandsAndFilters()
    {
        $html = '';
        
        // Building brands combo
        $html .= '<div id="showcase-sidebar-brands-and-filters">';
        
        // Filters
        $html .=    $this->_renderFilters();
        
        // Brands
        $html .=    $this->_renderBrandsCombo();
        
        $html .= '</div>';
        
        return $html;
    }
    
    protected function _renderTextFilter()
    {
        $html = '';
        
        return $html;
    }
    
    protected function _renderPriceRangeFilter()
    {
        $html = '';
        
        return $html;
    }
    
    protected function _renderBrandsComboText()
    {
        $html = 
                '<div id="showcase-sidebar-brands-combo-text" class="title">'.
                    strtoupper(lang::trans('brands')).
                '</div>';
        
        return $html;
    }
    
}