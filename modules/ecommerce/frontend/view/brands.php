<?php

namespace modules\ecommerce\frontend\view;

// Controllers
use core\config\controller\config;
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Brands webpage view
 *
 * @author Dani Gilabert
 * 
 */
class brands extends ecommerceView
{ 
    public $current_lang;
    public $brands = array();
    public $max_brands = null;
    public $show_brand_name_text = true;
    
    protected $_max_columns = 5;
    
    public function getWebpageName()
    {
        return 'brands';
    } 
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/modules/ecommerce/frontend/res/css/brands.css'
        );
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }  
    
    public function getDevelopmentHeadScriptsPaths()
    {
        $ecommerce_scripts = $this->_getHeadEcommerceScriptsPaths();

        $scripts = array(
            '/modules/ecommerce/frontend/res/js/brands.js'
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
    
    public function renderStartContent()
    {
        $html = 
                '<div id="brands-page">'.
                    '<div id="brands-page-center" class="page-center">'.
                '';   
        
        return $html;
    } 
    
    public function renderEndContent()
    {
        $html = 
                    '</div>'.
                '</div>'.
                '';   
        
        return $html;
    }    
    
    public function renderContent()
    {
        $html = '';
        
        if (!isset($this->brands))
        {
            return $html;
        }
        
        $html .= 
                '<h1 id="brands-content-title" class="title">'.
                    strtoupper(lang::trans('brands')).
                '</h1>'.
                '';
        
        $html .= $this->_renderTabs();
        
        return $html;
    }
    
    protected function _renderTabs()
    {
        $html = '';
        
        // Start tabs
        $html .= 
                '<div id="brands-content-tabs">'.
                    '<ul>';
        
        // Add outstandings brands
        if (isset($this->brands['OUTSTANDING']))
        {
            $html .= '<li><a href="#brands-content-tab-outstanding">'.'&#9733;'.'</a></li>';
        }
            
        // Add brands by letter
        foreach ($this->brands as $letter => $brands_by_letter)
        {
            if ($letter === 'OUTSTANDING')
            {
                continue;
            }
            $html .= '<li><a href="#brands-content-tab-'.$letter.'">'.$letter.'</a></li>';
        }
        
        $html .= '</ul>';
        
        // Outstandings brands
        if (isset($this->brands['OUTSTANDING']))
        {
            $html .= 
                '<div id="brands-content-tab-outstanding">'.
                    '<div class="brands-content-letter-title title brands-content-letter-outstanding-title">'.
                        strtoupper(lang::trans('outstanding_brands')).
                    '</div>'.
                    $this->renderBrandsByLetter($this->brands['OUTSTANDING']).
                '</div>';            
        }
            
        // Brands by letter
        foreach ($this->brands as $letter => $brands_by_letter)
        {
            if ($letter === 'OUTSTANDING')
            {
                continue;
            }
            $html .= 
                '<div id="brands-content-tab-'.$letter.'">'.
                    '<div class="brands-content-letter-title title">'.
                        $letter.
                    '</div>'.
                    $this->renderBrandsByLetter($brands_by_letter).
                '</div>';
        }

        // End tabs
        $html .= '</div>';
        
        return $html;
    }
    
    protected function _renderAllBrands()
    {
        $html = '';
        
        foreach ($this->brands as $letter => $brands_by_letter)
        {
            $html .= 
                    '<div class="brands-content-letter-title title">'.
                        $letter.
                    '</div>'.
                    '';
            
            $html .= $this->renderBrandsByLetter($brands_by_letter);
        }
        
        return $html;
    }
    
    public function renderBrandsByLetter($brands_by_letter)
    {
        $html = 
                '<table class="brands-content-table">'.
                    '<tr>';    
        
        $row = 0;
        $column = 0;
        $brands_counter = 0;

        $total_brands = count($brands_by_letter);
        foreach ($brands_by_letter as $brand)
        {
            $html .= $this->_renderColumn($brand);

            $column++;
            $brands_counter++;
            
            if ($column >= $total_brands)
            {
                break;
            }
            if (!is_null($this->max_brands) && $brands_counter >= $this->max_brands)
            {
                break;
            }
                
            if ($column >= $this->_max_columns)
            {
                $html .= '</tr><tr>';
                $row++;
                $column = 0;
            }
        }
        if ($column !== 0 && $column < $this->_max_columns)
        {
            for ($c = $column; $c < $this->_max_columns; $c++)
            {
                $html .= $this->_renderColumn(null);
            }
        }

        $html .= 
                    '</tr>'.
                '</table>'.
                ''; 
            
        return $html;
    }
    
    private function _renderColumn($brand)
    {
        $html =
                '<td class="brands-content-column">'.
                    $this->_renderBrandContent($brand).
                '</td>'.
                '';
        
        return $html;
    }
    
    protected function _renderBrandContent($brand)
    {
        $html = '';
        $rel_external = $this->getRelExternalTag();
        
        if (is_null($brand))
        {
            return $html;
        }
        
        $url = $this->_ecommerce_controller->getUrl(array($this->current_lang, lang::trans('url-brands'), $brand->code));
        
        $html .=
                '<div class="brands-content-brand-wrapper">'.
                    '<a href="'.$url.'"'.$rel_external.' class="brand">'.
                '';
        
        if ($this->show_brand_name_text)
        {
            $html .=
                        '<div class="brands-content-brand-text">'.
                            $brand->name.
                        '</div>'.
                '';            
        }
        
        if (isset($brand->image) && !empty($brand->image))
        {
            $filemanager_path = config::getFilemanagerPath();
            $image = '/'.$filemanager_path.'/'.$brand->image;
            $html .=
                            '<img '.
                                'class="brands-content-brand-img" '.
                                'src="'.$image.'" '.
                                'alt="'.$brand->name.'" '.
                            '/>';                    
        }

        $html .=
                    '</a>'.
                '</div>'.
                '';
        
        return $html;
    }
    
}