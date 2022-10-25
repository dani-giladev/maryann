<?php

namespace modules\ecommerce\frontend\view\menu;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce as ecommerceController;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\frontend\controller\brand;
use modules\ecommerce\frontend\controller\gamma;

/**
 * E-commerce frontend articles searcher view
 *
 * @author Dani Gilabert
 * 
 */
class searcher
{ 
    public $brands = array();
    public $gammas = array();
    public $categories = array();
    
    protected $_ecommerce_controller;
    protected $_article_controller;
    protected $_brand_controller;
    protected $_gamma_controller;
    protected $_rel_external = '';
    
    public function __construct()
    {
        $this->_ecommerce_controller = new ecommerceController();
        $this->_article_controller = new article();
        $this->_brand_controller = new brand();
        $this->_gamma_controller = new gamma();
    }

    public function renderSearcher()
    {
        $html = 
                '<div '.
                    'id="searcher" '.
                    '_has_tooltip="true" '.
                '>'.
                    '<input '.
                        'id="searcher-input" '.
                        'type="text" '.
                    '>'.
                    '<div '.
                        'id="searcher-img" '.
                    '/>'.           
                '</div>'.
                '';        
        
        return $html;     
    }
    
    public function renderSearchResult($needles, $articles, $brands, $categories)
    {
        $html = '';
            
        // Start render
        $html .= '<div id="searcher-result" class="scrollable">';
        
        if (empty($needles) || (empty($articles) && empty($brands) && empty($categories)))
        {
            $html .=
                    '<div class="searcher-no-results">'.
                        'No es troben resultats'.
                    '</div>';
            // End render
            $html .= '</div>';
        
            return $html;
        }
        
        // Title
        $html .= 
                '<table class="title" border="0" cellpadding="0" cellspacing="0">'.
                    '<tr>'.
                '';
        if (!empty($articles))
        {
            $html .= '<td class="searcher-result-table-column searcher-result-table-column-articles';
            if (!empty($brands) || !empty($categories))
            {
                $html .= ' searcher-result-table-column-delimiter-padding';
            }
            $html .= '">';            
            $html .= 
                        strtoupper(lang::trans('articles')).
                    '</td>'.
                    '';       
        }
        if (!empty($brands))
        {
            $html .= '<td class="searcher-result-table-column searcher-result-table-column-brands';
            $html .= '">';            
            $html .= 
                        strtoupper(lang::trans('brands')).
                    '</td>'.
                    '';  
        }
        if (!empty($categories))
        {
            $html .= 
                        '<td class="searcher-result-table-column searcher-result-table-column-categories">'.
                            strtoupper(lang::trans('categories')).
                        '</td>'.
                    '';            
        }
        $html .= 
                    '</tr>'.
                '</table>'.
                '';
        
        // Content
        $html .= 
                '<table class="searcher-result-table-content" border="0" cellpadding="0" cellspacing="0">'.
                    '<tr>'.
                '';
                
        // Render articles
        if (!empty($articles))
        {
            $html .= '<td class="searcher-result-table-column searcher-result-table-column-articles';
            if (!empty($brands) || !empty($categories))
            {
                $html .= ' searcher-result-table-column-delimiter searcher-result-table-column-delimiter-padding';
            }
            $html .= '">';
            $html .= $this->_renderArticles($needles, $articles);
            $html .= '</td>';           
        }
        
        // Render brands
        if (!empty($brands))
        {
            $html .= '<td class="searcher-result-table-column searcher-result-table-column-brands';
            if (!empty($categories))
            {
                $html .= ' searcher-result-table-column-delimiter';
            }  
            $html .= '">';
            $html .= $this->_renderBrands($needles, $brands);
            $html .= '</td>';
        }
        
        // Render categories
        if (!empty($categories))
        {
            $html .= '<td class="searcher-result-table-column searcher-result-table-column-categories">';
            $html .= $this->_renderCategories($needles, $categories);
            $html .= '</td>';
        }
        
        // End content
        $html .= 
                    '</tr>'.
                '</table>'.
                '';
        
        // End render
        $html .= '</div>';
        
        return $html;
    }
    
    protected function _renderArticles($needles, $articles)
    {
        $html = '';
        
        foreach ($articles as $article) 
        {            
            $html .= $this->_renderArticle($needles, $article);
        }
        
        return $html;
    }
    
    protected function _renderArticle($needles, $article)
    {
        $html = '';
        
        // Start article
        $html .= $this->_renderArticleStart($article);

        // Image
        $html .= $this->_renderArticleImage($article);

        // Text
        $html .= $this->_renderArticleText($needles, $article);

        // End article
        $html .= $this->_renderArticleEnd();
        
        return $html;
    }
    
    protected function _renderArticleStart($article)
    {
        $html = '';
        
        // Build article detail url
        $article_detail_url = $this->_article_controller->getArticleUrl($article);
        
        // Start article
        $html .= 
                '<div class="searcher-result-article">'.
                    '<a href="'.$article_detail_url.'"'.$this->_rel_external.'>'.
                        '<table class="searcher-result-article-table" border="0" cellpadding="0" cellspacing="0">'.
                            '<tr>'.
                '';        
        
        return $html;
    }
    
    protected function _renderArticleEnd()
    {
        $html = '';
       
        // End article
        $html .= '</tr></table></a></div>';  
        
        return $html;
    }
    
    protected function _renderArticleImage($article)
    {
        $html = '';
        
        // Image
        $image_path = '';
        $images = $this->_article_controller->getImages($article, false);
        if (!empty($images))
        {
            $image_path = $images[0];
        }
        $html .= 
                '<td>'.
                    '<img class="searcher-result-article-img" '.
                        'src="'.$image_path.'" />'.
                '</td>';
        
        return $html;
    }
    
    protected function _renderArticleText($needles, $article)
    {
        $html = '';
        
        // Text
        $html .= 
                '<td>'.
                    '<div class="searcher-result-article-text-wrapper">'.
                '';

        // Title
        $article_title = $this->getArticleTitle($article);
        $highlighted_article_title = $this->_getHighlightedText($needles, $article_title);
        $html .=            
                '<div class="searcher-result-article-title">'.
                    $highlighted_article_title.
                '</div>';

        // Description
        $article_description = $article->code.' - '.strip_tags($this->_article_controller->getShortDescription($article));        
        $max_characters = 120;
        if (strlen($article_description) > $max_characters)
        {
            $article_description = substr($article_description, 0, $max_characters).'...';
        }
        $highlighted_article_description = $this->_getHighlightedText($needles, $article_description, true);
        $html .=            
                '<div class="searcher-result-article-description">'.
                    $highlighted_article_description.
                '</div>';        

        // End text
        $html .= '</div></td>';    
        
        return $html;
    }
    
    private function _renderBrands($needles, $brands)
    {
        $html = '';
        $current_lang = lang::getCurrentLanguage();
        
        foreach ($brands as $brand) 
        {
            $url = $this->_ecommerce_controller->getUrl(array($current_lang, lang::trans('url-brands'), $brand->code));
            
            $brand_name = $brand->name;
            $highlighted_brand_name = $this->_getHighlightedText($needles, $brand_name);
            $html .=
                    '<div class="searcher-result-brand">'.
                        '<a href="'.$url.'" class="brand"'.$this->_rel_external.'>'.
                            $highlighted_brand_name.
                        '</a>'.
                    '</div>'.
                    '';            
        }
        
        return $html;
    }
    
    private function _renderCategories($needles, $categories)
    {
        $html = '';
        $current_lang = lang::getCurrentLanguage();
        $url_property = 'url'.ucfirst($current_lang);
        $title_property = 'titles-'.$current_lang;
        
        foreach ($categories as $category_code => $category) 
        {
            if (isset($category->$title_property) &&
                !empty($category->$title_property))
            {
                $category_name = $category->$title_property;
            }
            else
            {
                $category_name = $category->name;
            }
            
            $highlighted_category_name = $this->_getHighlightedText($needles, $category_name);            
            
            // Mount breadcrumb
            if (!isset($this->categories->breadcrumbs) || !isset($this->categories->breadcrumbs->$category_code))
            {
                continue;
            }
            $breadcrumb = '';
            $is_first = true;
            foreach ($this->categories->breadcrumbs->$category_code as $value)
            {
                if (!isset($value->$url_property) || empty($value->$url_property))
                {
                    continue 2;
                }   
                
                $url_value = $value->$url_property;
                $url = $this->_ecommerce_controller->getUrl(array($current_lang, lang::trans('url-categories', $current_lang), $url_value));                

                if (!$is_first)
                {
                    $breadcrumb .= '   >   ';
                }
                
                $breadcrumb .= '<a href="'.$url.'"'.$this->_rel_external.'>';
                if ($value->code === $category_code)
                {
                    $breadcrumb .= '<b>'.$highlighted_category_name.'</b>';
                }
                else
                {
                    if (isset($value->$title_property) &&
                        !empty($value->$title_property))
                    {
                        $value_name = $value->$title_property;
                    }
                    else
                    {
                        $value_name = $value->name;
                    }
                    //$breadcrumb .= '<span class="searcher-result-category-breadcrumb">'.$value_name.'</span>';
                    $breadcrumb .= $value_name;
                }
                $breadcrumb .= '</a>';                
                
                $is_first = false;
            }

            $html .=
                    '<div class="searcher-result-category">'.
                        $breadcrumb.
                    '</div>'.
                    '';
        }
        
        return $html;
    }
    
    public function getArticleTitle($article)
    {
        // Brand
        if (isset($this->brands[$article->brand]))
        {
            $brand_name = $this->_brand_controller->getBrandName($this->brands[$article->brand]); 
        }
        else
        { 
            $brand_name = '';
        }
        
        // Gamma
        if (isset($this->gammas[$article->gamma][$article->brand]) && 
            $this->gammas[$article->gamma][$article->brand]->visible &&
           (!isset($this->gammas[$article->gamma][$article->brand]->discard_in_composition_of_article_title) || !$this->gammas[$article->gamma][$article->brand]->discard_in_composition_of_article_title)
        )
        {
            $gamma_name = $this->_gamma_controller->getTitle($this->gammas[$article->gamma][$article->brand]);
        }
        else
        {
            $gamma_name = ''; 
        }
        
        // Title
        $title = $this->_article_controller->getTitle($article);
        
        // Display
        $display = $this->_article_controller->getDisplay($article);
        
        // Build composed title
        $ret =  $this->_article_controller->getComposedTitle($brand_name, $gamma_name, $title, $display);
        
        return $ret;
    }
    
    protected function _getHighlightedText($needles, $text, $sensitive = false)
    {
        foreach ($needles as $needle)
        {
            if (empty($needle)) continue;
            $lower_text = mb_strtolower($text);
            $lower_needle = mb_strtolower($needle);
            $pos = strpos($lower_text, $lower_needle);
            if ($pos !== false)
            {
                // Replace text by {text}
                $replace = '{'.substr($text, $pos, strlen($needle)).'}';
                $text = substr_replace($text, $replace, $pos, strlen($needle));
            }
            else
            {
                if (!$sensitive)
                {
                    $normalized_text = helpers::normalizeSpecialChars($text);
                    $normalized_needle = helpers::normalizeSpecialChars($needle);
                    $lower_text = mb_strtolower($normalized_text);
                    $lower_needle = mb_strtolower($normalized_needle);                    
                    $pos = strpos($lower_text, $lower_needle);
                    if ($pos !== false)
                    {
                        // Replace text by {text}
                        $replace = '{'.substr($text, $pos, strlen($normalized_needle)).'}';
                        $text = substr_replace($text, $replace, $pos, strlen($normalized_needle));
                    }
                }                
            }
        }
        
        // Replace marks by spans
        $text = str_replace(
                array('{', '}'), 
                array('<span class="searcher-result-highlighted">', '</span>'), 
                $text
        );
        
        return $text;
    }
    
}