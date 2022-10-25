<?php

namespace modules\ecommerce\frontend\view\showcase;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce as ecommerceController;
use modules\ecommerce\frontend\controller\gamma;
use modules\ecommerce\frontend\controller\articleProperty;

/**
 * Showcase view (sidebar)
 *
 * @author Dani Gilabert
 * 
 */
class sidebar
{     
    protected $_ecommerce_controller;
    protected $_gamma_controller;
    protected $_article_property_controller;
    
    public $param = null;
    public $category = null;
    public $brand = null;
    public $html_subcategories_menu = null;
    public $brands = null;
    public $min_price_filter = null;
    public $max_price_filter = null;
    public $price_bounds_filter = null;
    public $outstanding_filter = null;
    public $novelty_filter = null;
    public $pack_filter = null;
    public $christmas_filter = null;
    public $available_categories_filter = null;
    public $checked_categories_filter = null;
    public $brands_filter = null;
    public $checked_brands_filter = null;
    public $available_gamma_filter = null;
    public $checked_gamma_filter = null;
    public $available_article_properties_filter = null;
    public $checked_article_properties_filter = null;
    public $available_basic_filter = null;
    public $any_articles = true;
    
    public function __construct()
    {
        $this->_ecommerce_controller = new ecommerceController();
        $this->_gamma_controller = new gamma();
        $this->_article_property_controller = new articleProperty();
    }      
    
    protected function _renderStartSidebar()
    {
        $html = 
                '<aside>'.
                    '<div id="showcase-sidebar">'.
                '';
        
        return $html;
    }      
    
    protected function _renderEndSidebar()
    {
        $html = 
                        '</div>'.
                '</aside>'.
                ''; 
        
        return $html;
    }
    
    public function renderSidebar()
    {
        // Start render
        $html = $this->_renderStartSidebar();
        
        // Content
        $html .= $this->_renderContent();
        
        // End render
        $html .= $this->_renderEndSidebar();
        
        return $html;       
    }     
    
    protected function _renderContent()
    {
        $html = '';
        
        // Building categories menu
        $html .= $this->_renderArticleSubcategoriesMenu(); 
        
        // Building brands and filters
        $html .= $this->_renderBrandsAndFilters();
        
        return $html;
    }    
    
    protected function _renderBrandsAndFilters()
    {
        $html = '';
        
        // Building brands combo
        $html .= '<div id="showcase-sidebar-brands-and-filters">';
        
        // Brands
        $html .=    $this->_renderBrandsCombo();
        
        // Filters
        $html .=    $this->_renderFilters();
        
        $html .= '</div>';
        
        return $html;
    }
    
    protected function _renderArticleSubcategoriesMenu()
    {
        // Building subcategories menu
        $html = '';
        
        if (!isset($this->html_subcategories_menu) || empty($this->html_subcategories_menu))
        {
            return $html;
        }
        
        $sub = (isset($this->category))? 'Sub-' : '';
        $html .=
                '<div id="showcase-sidebar-categories-menu-container">'.
                    '<div id="showcase-sidebar-categories-menu-text">'.
                        '<b>'.strtoupper($sub.lang::trans('categories')).'</b>'.
                    '</div>'.  
                    '<ul id="showcase-sidebar-categories-menu">'.
                        $this->html_subcategories_menu.
                    '</ul>'.
                '</div>'.  
                ''; 
        
        return $html;  
    }
    
    protected function _renderBrandsComboText()
    {
        $html = 
                '<div id="showcase-sidebar-brands-combo-text">'.
                    '<b>'.strtoupper(lang::trans('brands')).'</b>'.
                '</div>';
        
        return $html;
    }
    
    protected function _renderBrandsCombo()
    {
        // Building brands combo
        $current_lang = lang::getCurrentLanguage();
        $html = '';
        
        $html .= 
                '<div id="showcase-sidebar-brands-combo-container">'.
                $this->_renderBrandsComboText().
                '<select '.
                    'id="showcase-sidebar-brands-combo" '.
//                    'onChange="onChangeBrandCombo(this.options[this.selectedIndex].value)"'.
                    'onChange="window.location.href=this.value"'.
                ' >';          
        
        if (!isset($this->brand))
        {
            $html .= '<option value="'.''.'" selected >'.lang::trans('select_brand').'</option>';            
        }
        
        $sorted_brands = helpers::sortArrayByField($this->brands, 'name');
        foreach ($sorted_brands as $value)
        {
            if (!$value->available || (isset($value->visible) && !$value->visible))
            {
                continue;
            }
            $url = $this->_ecommerce_controller->getUrl(array($current_lang, lang::trans('url-brands'), $value->code));            
            $selected = '';
            if (isset($this->brand) && $this->brand == $value->code)
            {
                $selected = 'selected ';
            }            
            $html .= '<option value="'.$url.'" '.$selected.'>'.$value->name.'</option>';
        }                  
        $html .=
                    '</select>'.
                '</div>'.  
                '';    
        
        return $html;  
    }
    
    protected function _renderFilters()
    {
        $html = '';
        
        if (!$this->any_articles)
        {
            return $html;
        }
        
        // Title filters
        $html .= $this->_renderTextFilter();   
        
        // Building price range filter
        $html .= $this->_renderPriceRangeFilter();
        
        // Start basic filters
        $html .= $this->_renderStartBasicDelimiterFilter();
        
        // Building outstanding articles filters
        $html .= $this->_renderOutstandingArticlesFilter(); 
        
        // Building novelty articles filters
        $html .= $this->_renderNoveltyArticlesFilter(); 
        
        // Building pack articles filters
        $html .= $this->_renderPackArticlesFilter(); 
        
         // ENABLE CHRISTMAS
        // Building christmas pack articles filters
        //$html .= $this->_renderChristmasArticlesFilter();
        
        // Final basic filters
        $html .= $this->_renderFinalBasicDelimiterFilter();
        
        // Building categories filters
        $html .= $this->_renderCategoriesFilter();  
        
        // Building brands filters
        $html .= $this->_renderBrandsFilter();  
        
        // Building gamma filters
        $html .= $this->_renderGammaFilter();
        
        // Building article properties filters
        $html .= $this->_renderArticlePropertiesFilter();
        
        return $html;  
    }
    
    protected function _renderTextFilter()
    {
        // Title filters
        $html =      
                '<div id="showcase-sidebar-text-filter">'.
                    strtoupper(lang::trans('filters')).
                '</div>'.     
                '<div class="showcase-sidebar-dots">'.'</div>'. 
                ''; 
        
        return $html;  
    }
    
    protected function _renderPriceRangeFilter()
    {
        $html = '';
        
        if (!isset($this->price_bounds_filter) || empty($this->price_bounds_filter) || $this->price_bounds_filter['min'] == $this->price_bounds_filter['max'])
        {
            return $html;
        }
        
        if (
                (isset($this->outstanding_filter) && $this->outstanding_filter) || 
                (isset($this->novelty_filter) && $this->novelty_filter) || 
                (isset($this->pack_filter) && $this->pack_filter) /*|| 
                (isset($this->christmas_filter) && $this->christmas_filter)*/ // ENABLE CHRISTMAS
        )
        {
            $min = 1;
            $max = 150;
        }
        else
        {
            $min = $this->price_bounds_filter['min'];
            $max = $this->price_bounds_filter['max'];
        }
        
        // Building price range filter
        $html .= 
                '<div id="showcase-sidebar-price-range-filter-container">'.
                    '<div id="showcase-sidebar-price-range-filter-label">'.
                        lang::trans('price').' :'.
                    '</div>'. 
                    '<div id="showcase-sidebar-price-range-filter-value" class="price">'.'</div>'.
                    '<div '.
                        'id="showcase-sidebar-slider-price-range" '.
                        '_min="'.$min.'" '.
                        '_max="'.$max.'" '.
                        '_min_value="'.(isset($this->min_price_filter)? $this->min_price_filter : $min).'" '.
                        '_max_value="'.(isset($this->max_price_filter)? $this->max_price_filter : $max).'" '.
                    '>'.
                    '</div>'.     
                '</div>'.     
                '';
        
        $html .= 
                '<div class="showcase-sidebar-dots">'.'</div>'.     
                '';         
        
        return $html;
    }
    
    protected function _renderOutstandingArticlesFilter()
    {
        $html = '';
        
        if (!isset($this->available_basic_filter['outstanding']) || !$this->available_basic_filter['outstanding'])
        {
            return $html;
        }
        
        $checked = (isset($this->outstanding_filter) && $this->outstanding_filter);
        $disabled = (isset($this->param) && $this->param === 'specialoffers');
        
        // Building outstanding articles filters
        $html .= 
                '<div class="showcase-sidebar-basic-filter">'.
                    '<label class="'.
                                'checkbox-label'.
                                (($disabled)? ' cursor-default' : '').
                                '">'.
                        '<input '.
                            'class="'.
                                'checkbox '.
                                'checkbox-multi-items'.
                                (($disabled)? ' cursor-default' : '').
                                '" '.
                            'type="checkbox" '.
                            'id="showcase-sidebar-outstanding-filter" '.
                            'onClick="onClickOutstandingArticlesCheckboxFilter()" '.
                            (($checked)? 'checked ' : '').
                            (($disabled)? 'disabled ' : '').
                        '/>'.
                        '&nbsp&nbsp'.'<b>'.lang::trans('special_offers').'</b>'.
                    '</label>'.                
                '</div>'.
                ''; 
        
        return $html;
    }
    
    protected function _renderNoveltyArticlesFilter()
    {
        $html = '';
        
        if (!isset($this->available_basic_filter['novelty']) || !$this->available_basic_filter['novelty'])
        {
            return $html;
        }
        
        $checked = (isset($this->novelty_filter) && $this->novelty_filter);
        $disabled = (isset($this->param) && $this->param === 'novelties');
        
        // Building novelty articles filters
        $html .= 
                '<div class="showcase-sidebar-basic-filter">'.
                    '<label class="'.
                                'checkbox-label'.
                                (($disabled)? ' cursor-default' : '').
                                '">'.
                        '<input '.
                            'class="'.
                                'checkbox '.
                                'checkbox-multi-items'.
                                (($disabled)? ' cursor-default' : '').
                                '" '.
                            'type="checkbox" '.
                            'id="showcase-sidebar-novelty-filter" '.
                            'onClick="onClickNoveltyArticlesCheckboxFilter()" '.
                            (($checked)? 'checked ' : '').
                            (($disabled)? 'disabled ' : '').
                        '/>'.
                        '&nbsp&nbsp'.'<b>'.lang::trans('novelties').'</b>'.
                    '</label>'.                
                '</div>'.
                ''; 
        
        return $html;
    }
    
    protected function _renderPackArticlesFilter()
    {
        $html = '';
        
        if (!isset($this->available_basic_filter['pack']) || !$this->available_basic_filter['pack'])
        {
            return $html;
        }
        
        $checked = (isset($this->pack_filter) && $this->pack_filter);
        $disabled = (isset($this->param) && $this->param === 'packs');
        
        // Building pack articles filters
        $html .= 
                '<div class="showcase-sidebar-basic-filter">'.
                    '<label class="'.
                                'checkbox-label'.
                                (($disabled)? ' cursor-default' : '').
                                '">'.
                        '<input '.
                            'class="'.
                                'checkbox '.
                                'checkbox-multi-items'.
                                (($disabled)? ' cursor-default' : '').
                                '" '.
                            'type="checkbox" '.
                            'id="showcase-sidebar-pack-filter" '.
                            'onClick="onClickPackArticlesCheckboxFilter()" '.
                            (($checked)? 'checked ' : '').
                            (($disabled)? 'disabled ' : '').
                        '/>'.
                        '&nbsp&nbsp'.'<b>'.'Packs'.'</b>'.
                    '</label>'.                
                '</div>'.
                ''; 
        
        return $html;
    }
    
    protected function _renderChristmasArticlesFilter()
    {
        $html = '';
        
        if (!isset($this->available_basic_filter['christmas']) || !$this->available_basic_filter['christmas'])
        {
            return $html;
        }
        
        $checked = (isset($this->christmas_filter) && $this->christmas_filter);
        $disabled = (isset($this->param) && $this->param === 'christmas');
        
        // Building christmas pack articles filters
        $html .= 
                '<div class="showcase-sidebar-basic-filter">'.
                    '<label class="'.
                                'checkbox-label'.
                                (($disabled)? ' cursor-default' : '').
                                '">'.
                        '<input '.
                            'class="'.
                                'checkbox '.
                                'checkbox-multi-items'.
                                (($disabled)? ' cursor-default' : '').
                                '" '.
                            'type="checkbox" '.
                            'id="showcase-sidebar-christmas-filter" '.
                            'onClick="onClickChristmasArticlesCheckboxFilter()" '.
                            (($checked)? 'checked ' : '').
                            (($disabled)? 'disabled ' : '').
                        '/>'.
                        '&nbsp&nbsp'.'<b>'.lang::trans('special_christmas').'</b>'.
                    '</label>'.                
                '</div>'.
                ''; 
        
        return $html;
    }
    
    protected function _renderStartBasicDelimiterFilter()
    {
        $html = '';
        
        $html .= 
                '<div class="showcase-sidebar-basic-filter-start">'.'</div>'.     
                '';
        
        return $html;
    }
    
    protected function _renderFinalBasicDelimiterFilter()
    {
        $html = '';
        
        if (
                (!isset($this->available_basic_filter['outstanding']) || !$this->available_basic_filter['outstanding']) &&
                (!isset($this->available_basic_filter['novelty']) || !$this->available_basic_filter['novelty']) &&
                (!isset($this->available_basic_filter['pack']) || !$this->available_basic_filter['pack']) /*&&
                (!isset($this->available_basic_filter['christmas']) || !$this->available_basic_filter['christmas'])*/ // ENABLE CHRISTMAS
        )
        {
            return $html;
        }
        
        $html .= 
                '<div class="showcase-sidebar-dots showcase-sidebar-basic-filter-end">'.'</div>'.     
                '';
        
        return $html;
    }
    
    protected function _renderCategoriesFilter()
    {
        $html = '';
        
        if (!isset($this->available_categories_filter) || empty($this->available_categories_filter))
        {
            return $html;
        }
        
        $current_lang = lang::getCurrentLanguage();
        
        // Building categories filters
        $html .= 
                '<div id="showcase-sidebar-categories-filter-container">'.
                    '<div id="showcase-sidebar-categories-filter-text">'.
                        '<b>'.lang::trans('categories').'</b>'.
                    '</div>'.  
                    '<div id="showcase-sidebar-categories-filter" class="scrollable scrollable-onbackground">';
        foreach ($this->available_categories_filter as $value) {
            $titles = 'titles-'.$current_lang;
            $text = (isset($value->$titles)) ? $value->$titles : '';
            $checked = (in_array($value->code, $this->checked_categories_filter))? 'checked ' : '';            
            $html .= 
                    '<label class="checkbox-label">'.
                        '<input class="checkbox checkbox-multi-items" '.
                            'type="checkbox" '.
                            'name="showcase-sidebar-categories-filter" '.
                            'code="'.$value->code.'" '.
                            'onClick="onClickCategoriesCheckboxFilter()" '.
                            $checked.
                        '/>'.
                        '&nbsp&nbsp'.$text.
                    '</label>';                
        }
        $html .=
                    '</div>'.
                '</div>'.  
                ''; 
        
        $html .= 
                '<div class="showcase-sidebar-dots">'.'</div>'.     
                '';        
        
        return $html;       
    }
    
    protected function _renderBrandsFilter()
    {
        $html = '';
        
        if (!isset($this->brands_filter) || empty($this->brands_filter) || isset($this->brand))
        {
            return $html;
        }
        
        $sorted_brands_filter = helpers::sortArrayByField($this->brands_filter, 'name');
        
        // Building brands filters
        $html .= 
                '<div class="showcase-sidebar-filter-container">'.
                    '<div class="showcase-sidebar-filter-text">'.
                        '<b>'.lang::trans('brands').'</b>'.
                    '</div>'.  
                    '<div class="showcase-sidebar-filter scrollable scrollable-onbackground">';
        foreach ($sorted_brands_filter as $value) {
            $checked = (in_array($value->code, $this->checked_brands_filter))? 'checked ' : '';
            $html .= 
                    '<label class="checkbox-label">'.
                        '<input class="checkbox checkbox-multi-items showcase-sidebar-filter-checkbox" '.
                            'type="checkbox" '.
                            'name="showcase-sidebar-brands-filter" '.
                            'code="'.$value->code.'" '.
                            'onClick="onClickBrandsCheckboxFilter()" '.
                            $checked.
                        '/>'.
                        '&nbsp&nbsp'.
                        '<span class="showcase-sidebar-filter-label">'.
                            $value->name.
                        '</span>'.
                    '</label>';                
        }
        $html .=
                    '</div>'.
                '</div>'.  
                ''; 
        
        $html .= 
                '<div class="showcase-sidebar-dots">'.'</div>'.     
                '';        
        
        return $html;       
    }
    
    protected function _renderGammaFilter()
    {
        $html = '';
        
        if (!isset($this->available_gamma_filter) || empty($this->available_gamma_filter))
        {
            return $html;
        }
        
        // Sorting by title
        $gamma_filter = array();
        foreach ($this->available_gamma_filter as $value) {
            $gamma_title = $this->_gamma_controller->getTitle($value);
            $value->title = $gamma_title;
            $gamma_filter[] = $value;
        }
        $sorted_gamma_filter = helpers::sortArrayByField($gamma_filter, 'title');
        
        // Building gamma filters
        $html .= 
                '<div class="showcase-sidebar-filter-container">'.
                    '<div class="showcase-sidebar-filter-text">'.
                        '<b>'.lang::trans('gamma').'</b>'.
                    '</div>'.  
                    '<div class="showcase-sidebar-filter scrollable scrollable-onbackground">';
        foreach ($sorted_gamma_filter as $value) {
            $checked = (in_array($value->code, $this->checked_gamma_filter))? 'checked ' : '';
            
            $html .= 
                    '<label class="checkbox-label">'.
                        '<input class="checkbox checkbox-multi-items showcase-sidebar-filter-checkbox" '.
                            'type="checkbox" '.
                            'name="showcase-sidebar-gamma-filter" '.
                            'code="'.$value->code.'" '.
                            'onClick="onClickGammaCheckboxFilter()" '.
                            $checked.
                        '/>'.
                        '&nbsp&nbsp'.
                        '<span class="showcase-sidebar-filter-label">'.
                            $value->title.
                        '</span>'.
                    '</label>';                
        }
        $html .=
                    '</div>'.
                '</div>'.  
                ''; 
        
        $html .= 
                '<div class="showcase-sidebar-dots">'.'</div>'.     
                '';        
        
        return $html;       
    }
    
    protected function _renderArticlePropertiesFilter()
    {
        $html = '';
        
        if (!isset($this->available_article_properties_filter) || empty($this->available_article_properties_filter))
        {
            return $html;
        }
        
        $checked_filters = $this->checked_article_properties_filter;
        
        foreach ($this->available_article_properties_filter as $code => $article_property_values)
        {
            $prop_title = $this->_article_property_controller->getText($article_property_values['property']->titles);
            
            // Building filters
            $html .= 
                    '<div class="showcase-sidebar-filter-container">'.
                        '<div class="showcase-sidebar-filter-text">'.
                            '<b>'.$prop_title.'</b>'.
                        '</div>'.  
                        '<div class="showcase-sidebar-filter scrollable scrollable-onbackground">';    
            
            foreach ($article_property_values['amounts'] as $amount => $amount_values)
            {
                // Sorting by text
                $values = array();
                foreach ($amount_values['values'] as $value_code => $thevalue) {
                    $text = $this->_article_property_controller->getText($thevalue->texts);
                    $thevalue->text = $text;
                    $values[] = $thevalue;
                }
                $sorted_values = helpers::sortArrayByField($values, 'text');                
                
                foreach ($sorted_values as $thevalue) 
                {
                    $value_code = $thevalue->code;
                    $checked = (isset($checked_filters[$code]['amounts'][$amount]['values'][$value_code]))? 'checked ' : '';
                    $text = $thevalue->text;

                    $html .= 
                            '<label class="checkbox-label">'.
                                '<input class="checkbox checkbox-multi-items showcase-sidebar-filter-checkbox" '.
                                    'type="checkbox" '.
                                    'name="showcase-sidebar-articleproperties-filter" '.
                                    'code="'.$code.'" '.
                                    'amount="'.$amount.'" '.
                                    'value_code="'.$value_code.'" '.
                                    'onClick="onClickArticlePropertiesCheckboxFilter()" '.
                                    $checked.
                                '/>'.
                                '&nbsp&nbsp'.
                                '<span class="showcase-sidebar-filter-label">'.
                                    (empty($amount)? '' : ($amount.' ')).$text.
                                '</span>'.
                            '</label>';                           
                }
            }
                
            $html .=
                        '</div>'.
                    '</div>'.  
                '<div class="showcase-sidebar-dots">'.'</div>'.     
                '';                  
        }
        
        return $html;       
    }
    
}