<?php

namespace modules\ecommerce\frontend\mobile\view;

// Controllers
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\brands as brandsView;

/**
 * Brands mobile webpage view
 *
 * @author Dani Gilabert
 * 
 */
class brands extends brandsView
{   
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/mobile/res/css/brands.css',
            '/modules/ecommerce/frontend/mobile/res/skins/'.$this->_skin.'/brands.css'
        );   
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }  
    
    protected function _renderTabs()
    {
        $html = '';
        
        // Start tabs
        $html .= '<div id="brands-content-tabs" data-role="collapsibleset">';
        
        $prefix_collapsed = '<div data-role="collapsible" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d"><h3>';
        
        // Add outstandings brands
        if (isset($this->brands['OUTSTANDING']))
        {
            $html .= 
                    $prefix_collapsed.
                        strtoupper(lang::trans('outstanding_brands')).
                    '</h3>'.
                    $this->renderBrandsByLetter($this->brands['OUTSTANDING']).
                    '</div>';             
        }
        
        // Add brands by letter
        foreach ($this->brands as $letter => $brands_by_letter)
        {
            if ($letter === 'OUTSTANDING')
            {
                continue;
            }
            $html .= 
                    $prefix_collapsed.
                        $letter.
                    '</h3>'.
                    $this->renderBrandsByLetter($brands_by_letter).
                    '</div>';            
        }
        
        // End tabs
        $html .= '</div>';
        
        return $html;
    }
    
    public function renderBrandsByLetter($brands_by_letter)
    {
        $html = '';
        
        $html .= '<div class="brands-content-by-letter">';
        
        $brands_counter = 0;

        foreach ($brands_by_letter as $brand)
        {
            $html .= $this->_renderBrandContent($brand);
            
            $brands_counter++;
            
            if (!is_null($this->max_brands) && $brands_counter >= $this->max_brands)
            {
                break;
            }
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
}