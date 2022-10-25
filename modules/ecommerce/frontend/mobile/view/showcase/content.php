<?php

namespace modules\ecommerce\frontend\mobile\view\showcase;

// Controllers
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\showcase\content as showcaseContentView;

/**
 * Showcase mobile view (content)
 *
 * @author Dani Gilabert
 * 
 */
class content extends showcaseContentView
{           
    
    public function renderStart()
    {
        $html = '';
        
        $html .= $this->_renderTitle();
        $html .= $this->_renderDescription();
        $html .= $this->_renderMenu();
        
        return $html;            
    }
    
    public function renderContent()
    {
        $html = '<div id="showcase-content">';
        
        $html .= $this->renderArticles();
        $html .= $this->_renderFooter();
        $html .= $this->_renderSearchResult(true);   
        
        return $html;            
    }
    
    public function renderEnd()
    {
        $html = '</div>';     
        
        return $html;            
    } 
    
    protected function _renderMenu()
    {
        $html = '<div id="showcase-content-menu">';
        
        // Filter + sub-categories
        $html .= $this->_renderMenuButtons();      
        
        // Sort articles by
        $html .= $this->_renderSortbyCombo();
        
        $html .= '</div>';
        
        return $html;            
    }
    
    protected function _renderMenuButtons()
    {
        $html = 
                '<table id="showcase-content-menu-buttons-table" border="0" cellpadding="0" cellspacing="0">'.
                    '<tr>';
        if ($this->subcategories_menu_is_rendered)
        {
            $html .=
                    '<td class="showcase-content-menu-rightpadding showcase-content-menu-50">';
            
            $class= "";// ' class="ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-notext ui-icon-gear"';
        }
        else
        {
            $html .=
                    '<td>';
            $class= "";
        }
        $html .=
                            '<a data-role="button" href="#showcase-sidebar"'.$class.'>'.
                                strtoupper(lang::trans('filters')).
                            '</a>'.
                        '</td>';
        if ($this->subcategories_menu_is_rendered)
        {
            $html .= 
                        '<td class="showcase-content-menu-leftpadding showcase-content-menu-50">'.
                            '<a data-role="button" href="#menu-subcategories-1">'.
                                strtoupper('Sub-'.lang::trans('categories')).
                            '</a>'.
                        '</td>';                    
        }
        $html .= 
                    '</tr>'.
                '</table>';                
        
        return $html;
    }
    
    protected function _renderSortbyComboText()
    {
        $html = '';
        
        return $html;     
    }
    
    protected function _renderArticle($article)
    {
        // Build article detail url
        $article_detail_url = $this->_article_controller->getArticleUrl($article);
        
        $html = 
            '<a href="'.$article_detail_url.'" rel="external" class="showcase-content-article-anchor" >'.
                '<table class="showcase-content-article-table" border="0" cellpadding="0" cellspacing="0">'.
                    '<tr>'.
                        '<td class="showcase-content-article-column-img">'.
                            $this->_renderImageArea($article, $article_detail_url).
                        '</td>'.
                        '<td class="showcase-content-article-column-content">'.
                            $this->_renderTextArea($article, $article_detail_url).
                            $this->_renderPricesArea($article);
        
        if (!$this->_anyStock($article))
        {
            $html .= $this->_renderNoStock();
        }
        
        $html .= 
                        '</td>'.
                    '</tr>'.
                '</table>'.
            '</a>'.
            '';

        return $html;
    }
    
    protected function _renderNoStock() {
        $html = 
                '<div class="showcase-content-article-no-stock">'.
                    'NO STOCK'.
                '</div>';
        
        return $html;
    }
    
    protected function _renderBadges($article)
    {
        $html = '';           
        
        if (isset($article->secondUnitDiscount) && $article->secondUnitDiscount > 0)
        {
                $html .= 
                        '<div class="showcase-content-article-badge-2ndunitto-wrapper">'.
                            '<div class="showcase-content-article-badge-2ndunitto-text2">'.
                                ' '.$article->secondUnitDiscount.'% '.
                            '</div>'.        
                            '<div class="showcase-content-article-badge-2ndunitto-text3">'.
                                strtolower(lang::trans('in_the_2nd_unit')).
                            '</div>'. 
                        '</div>'.
                        '';        
        }
        
        return $html;
    }
    
    protected function _renderTextArea($article, $article_detail_url)
    {
        $data = $this->_getTextAreaData($article);
        
        // Start
        $html = '<div class="showcase-content-article-text-wrapper" >';
        
        // Title and Gamma
        $html .= 
                '<div class="showcase-content-article-gamma gamma">'.
                    $data['gamma_name'].
                '</div>'.                
                '<a href="'.$article_detail_url.'" rel="external" class="showcase-content-article-title">'.
                    $data['title'].
                '</a>'.                
                '';
         
        // Display
        $html .= 
                '<div class="showcase-content-article-display article-display">'.
                    (!empty($data['display'])? $data['display'] : '').
                '</div>'.                
                ''; 
                
        // Brand
        $html .= 
                '<a href="'.$data['brand_url'].'" rel="external" class="showcase-content-article-brand brand">'.
                    $data['brand_name'].
                '</a>'.                
                '';
        
        // End
        $html .= '</div>';
        
        return $html;
    }
    
    protected function _renderPricesArea($article)
    {
        $data = $this->_getPricesAreaData($article);
        
        $html = '<div class="showcase-content-article-prices">';
        
        if (!is_null($data['rendered_strikethrough_price']))
        {
            $html .= 
                    '<div class="showcase-content-article-prices-strikethrough-price strikethrough-price">'.
                        $data['rendered_strikethrough_price'].
                    '</div>';              
        }
        
        if (!is_null($data['rendered_price']))
        {
            $html .= 
                    '<div class="showcase-content-article-prices-new-price price">'.
                        $data['rendered_price'].
                    '</div>';          
        }
        
        $html .= '</div>';                
                
        return $html;
    }
    
    public function renderAddToShoppingcartWidgets($article)
    {
        $html = '';
        
        return $html;
    }
    
    protected function _renderTotalArticlesFooter()
    {
        $html = '';
        
        return $html;     
    }
    
    protected function _renderArticlesPerPageCombo()
    {
        $html = '';
        
        return $html;     
    }
 
}