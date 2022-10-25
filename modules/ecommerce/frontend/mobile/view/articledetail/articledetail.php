<?php

namespace modules\ecommerce\frontend\mobile\view\articledetail;

// Controllers
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\articledetail\articledetail as articledetailView;
use modules\ecommerce\frontend\mobile\view\articledetail\relatedArticles as mobileRelatedArticlesView;

/**
 * Article detail mobile webpage view
 *
 * @author Dani Gilabert
 * 
 */
class articledetail extends articledetailView
{   
    
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/res/css/jquery/jquery.jcarousel/jquery.jcarousel-type1.css',
            //'/res/css/jquery/jquery.rondellcarousel/jquery.rondell-1.1.0.min.css',
            '/res/css/jquery/fancybox/jquery.fancybox.css',
            '/res/css/jquery/fancybox/helpers/jquery.fancybox-buttons.css',
            '/res/css/jquery/fancybox/helpers/jquery.fancybox-thumbs.css',
            '/res/css/jquery/rateyo/jquery.rateyo-2.2.0.min.css',
            
            // Related articles
            '/modules/ecommerce/frontend/mobile/res/css/showcase/showcase-content-articles.css', 
            '/modules/ecommerce/frontend/mobile/res/skins/'.$this->_skin.'/showcase/showcase-content-articles.css', 
            '/modules/ecommerce/frontend/mobile/res/css/articledetail/related-articles.css',
            
            // Reviews
            '/modules/ecommerce/frontend/mobile/res/css/articledetail/reviews.css',
                
            '/modules/ecommerce/frontend/mobile/res/css/articledetail/article-detail.css',
            '/modules/ecommerce/frontend/mobile/res/skins/'.$this->_skin.'/articledetail/article-detail.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/action-buttons.css',
            '/modules/ecommerce/frontend/mobile/res/css/common/medicines-info.css' 
        );   
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }
    
    public function getDevelopmentHeadScriptsPaths()
    {
        $ecommerce_scripts = $this->_getHeadEcommerceScriptsPaths();

        $scripts = array(
            '/res/js/jquery/jquery.validate-1.9.0.min.js',
            '/res/js/jquery/jquery.jcarousel/jquery.jcarousel-0.3.1.js',
            '/res/js/jquery/jquery.jcarousel/jquery.jcarousel-type1.js',       
            '/res/js/jquery/fancybox/jquery.fancybox.js',
            '/res/js/jquery/fancybox/helpers/jquery.fancybox-buttons.js',
            '/res/js/jquery/fancybox/helpers/jquery.fancybox-media.js',
            '/res/js/jquery/rateyo/jquery.rateyo-2.2.0.min.js',
            
            '/modules/ecommerce/frontend/res/js/articledetail.js'
        );

        $ret = array_merge($ecommerce_scripts, $scripts);      
        
        return $ret;
    }
    
    public function renderContent()
    {
        // Article
        $html = $this->_renderArticle();
        
        // Related articles
        $html .= $this->_renderRelatedArticles();
        
        return $html;
    } 
    
    protected function _getRelatedArticlesView()
    {
        $related_articles_view = new mobileRelatedArticlesView();
        $related_articles_view->columns = 1; 
        return $related_articles_view;
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
    
    protected function _renderIntroduction()
    {
        $data = $this->_getIntroductionData();
        
        // Start introduction
        $html = '<div id="article-detail-introduction">';      
        
        // Rating average
        $html .=  $this->_renderRatingAverage($data, true);
                
        // Add to cart option
        $html .= $this->_renderAddToCart($data, true);
        
        // Gamma, title, display and brand, ...
        $html .= $this->_renderMaindata($data);
        
        // Ref.
        //$html .=  $this->_renderRef($data);
                
        // Line delimiter
        $html .= '<div class="article-detail-introduction-line-delimiter"></div>';
        
        // Short description
        $html .=  $this->_renderShortDescription($data);
        
        // End introduction
        $html .=  '</div>';
        
        return $html;
    }
    
    protected function _renderSpinnerAmount($article, $amounts_to_add)
    {
        $html = '';
        
        return $html;
    }
    
    protected function _renderTabs()
    {
        $data = $this->_getTabsData();
        $html = '';
        
        // Exit if nothing to render
        if (!$data['success'])
        {
            return $html;
        }
        
        // Start tabs
        $html .= '<div id="article-detail-tabs-wrapper" data-role="collapsibleset">';
        
        $prefix_collapsed = '<div data-role="collapsible" data-collapsed-icon="arrow-r" data-expanded-icon="arrow-d"><h3>';
        if (!empty($data['description']))
        {
            $html .= $prefix_collapsed.lang::trans('description').'</h3>'.$data['description'].'</div>';
        }
        if (!empty($data['application']))
        {
            $html .= $prefix_collapsed.lang::trans('application').'</h3>'.$data['application'].'</div>';
        }
        if (!empty($data['active_ingredients']))
        {
            $html .= $prefix_collapsed.lang::trans('active_ingredients').'</h3>'.$data['active_ingredients'].'</div>';
        }
        if (!empty($data['composition']))
        {
            $html .= $prefix_collapsed.lang::trans('composition').'</h3>'.$data['composition'].'</div>';
        }
        if (!empty($data['botplus_epigraphs']))
        {
            $html .= $prefix_collapsed.lang::trans('more_info').'</h3>'.$data['botplus_epigraphs'].'</div>';
        }
        if (!empty($data['botplus_messages']))
        {
            $html .= $prefix_collapsed.lang::trans('warnings').'</h3>'.$data['botplus_messages'].'</div>';
        }
        if (!empty($data['prospect']))
        {
            $is_pdf = (strpos(strtoupper($data['prospect']), '.PDF'));
            if ($is_pdf)
            {
                $html .= 
                        '<a href="'.$data['prospect'].'" target="_blank" class="ui-btn ui-icon-arrow-r ui-btn-icon-left article-detail-tab-iframe-button">';
            }
            else
            {
                $html .= 
                        '<a href="#" class="ui-btn ui-icon-arrow-r ui-btn-icon-left article-detail-tab-iframe-button" '.
                            'onClick="ecommerce.showIFrame('.
                                '\''.htmlentities($this->_getIFrameTag($data['prospect'], "article-detail-tab-iframe")).'\''.
                           ')" '.                    
                            '>';                
            }
            $html .= lang::trans('prospect').'</a>';
        }
        if (!empty($data['datasheet']))
        {
            $is_pdf = (strpos(strtoupper($data['datasheet']), '.PDF'));
            if ($is_pdf)
            {
                $html .= 
                        '<a href="'.$data['datasheet'].'" target="_blank" class="ui-btn ui-icon-arrow-r ui-btn-icon-left article-detail-tab-iframe-button">';
            }
            else
            {
                $html .= 
                        '<a href="#" class="ui-btn ui-icon-arrow-r ui-btn-icon-left article-detail-tab-iframe-button" '.
                            'onClick="ecommerce.showIFrame('.
                                '\''.htmlentities($this->_getIFrameTag($data['datasheet'], "article-detail-tab-iframe")).'\''.
                           ')" '.                    
                            '>';                
            }
            $html .= lang::trans('datasheet').'</a>';
        }
        if (!empty($data['brand']))
        {
            $html .= $prefix_collapsed.$data['brand_title'].'</h3>'.$data['brand'].'</div>';
        }
        if (!empty($data['gamma']))
        {
            //$html .= $prefix_collapsed.lang::trans('gamma').'</h3>'.$data['gamma'].'</div>';
            $html .= $prefix_collapsed.$data['gama_title'].'</h3>'.$data['gamma'].'</div>';
        }
        if (!empty($data['reviews']))
        {
            $html .= $prefix_collapsed.lang::trans('reviews').' ('.count($this->current_reviews).')'.'</h3>'.$data['reviews'].'</div>';
        }

        // End tabs
        $html .= '</div></div>';     
    
        return $html;
    }
    
}