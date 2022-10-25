<?php

namespace modules\ecommerce\frontend\mobile\view\menu;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;
use modules\ecommerce\frontend\view\menu\searcher as searcherView;

/**
 * E-commerce frontend articles searcher mobile view
 *
 * @author Dani Gilabert
 * 
 */
class searcher extends searcherView
{ 
    protected $_ecommerce_view;
    
    public function __construct()
    {
        parent::__construct();
        $this->_ecommerce_view = new ecommerceView();
        $this->_rel_external = $this->_ecommerce_view->getRelExternalTag();
    }
    
    public function renderSearchResult($needles, $articles, $brands, $categories)
    {
        $ret = array();
                
        // Render articles
        if (!empty($articles))
        {
            $ret = $this->_renderArticles($needles, $articles);
        }
        
        if (empty($ret))
        {
            $ret[] = 
                    '<div class="searcher-no-results">'.
                        'No es troben resultats'.
                    '</div>'.
                    '';            
        }
        
        return $ret;
    }
    
    protected function _renderArticles($needles, $articles)
    {
        $ret = array();
        
        foreach ($articles as $article) 
        {
            $html = $this->_renderArticle($needles, $article);
            
            // Add article
            $ret[] = $html;
        }
        
        return $ret;
    }
    
    protected function _renderArticleImage($article)
    {
        $html = '';
        
        return $html;
    }
    
}