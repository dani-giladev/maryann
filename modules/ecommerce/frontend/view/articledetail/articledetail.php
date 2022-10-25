<?php

namespace modules\ecommerce\frontend\view\articledetail;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\rate;
use modules\ecommerce\frontend\controller\stock;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\frontend\controller\brand;
use modules\ecommerce\frontend\controller\gamma;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;
use modules\ecommerce\frontend\view\articledetail\relatedArticles as relatedArticlesView;
use modules\ecommerce\frontend\view\articledetail\reviews as reviewsView;
use modules\ecommerce\frontend\view\medicinesInfo as medicinesInfoView;

/**
 * Article detail view
 *
 * @author Dani Gilabert
 * 
 */
class articledetail extends ecommerceView
{ 
    public $brands = array();
    public $gammas = array();
    public $brand = null;
    public $gamma = null;    
    public $canonical_article = null;
    public $related_articles = array();
    public $current_reviews = array();
    public $articles_grouped_by_display = array();
    public $tabdata = array();
    public $botplusdata = array();
    public $is_article_available = true;
    
    protected $_article;
    protected $_article_controller;
    protected $_brand_controller;
    protected $_gamma_controller;

    public function __construct($article = null)
    {
        parent::__construct();
        
        $this->_article_controller = new article();
        $this->_brand_controller = new brand();
        $this->_gamma_controller = new gamma();
        
        if (!is_null($article))
        {
            $this->_article = $article;
        }
    }
    
    public function getWebpageName()
    {
        return 'articledetail';
    }         
    
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
            '/modules/ecommerce/frontend/res/css/showcase/showcase-content-articles.css', 
            '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/showcase/showcase-content-articles.css', 
            '/modules/ecommerce/frontend/res/css/articledetail/related-articles.css',
            
            // Reviews
            '/modules/ecommerce/frontend/res/css/articledetail/reviews.css',
                
            '/modules/ecommerce/frontend/res/css/articledetail/article-detail.css',
            '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/articledetail/article-detail.css',
            '/modules/ecommerce/frontend/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/res/css/shoppingcart/window-after-add-to-shoppingcart.css',
            '/modules/ecommerce/frontend/res/css/common/action-buttons.css', 
            '/modules/ecommerce/frontend/res/css/common/medicines-info.css'
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
            '/res/js/jquery/fancybox/helpers/jquery.fancybox-thumbs.js',
            '/res/js/jquery/rateyo/jquery.rateyo-2.2.0.min.js',

            '/modules/ecommerce/frontend/res/js/articledetail.js'
        );

        $ret = array_merge($ecommerce_scripts, $scripts);      
        
        return $ret;
    }
    
    protected function _addJavascriptVars()
    {
        // Javascript vars and messages
        $html = $this->_renderHeadEcommerceJavascriptVars();
        $html .= $this->_renderAddToCartDialogWarningScriptsMessages();
        
        $html .= 
                '<script type="text/javascript">'.PHP_EOL.
                    'var article_code = "'.$this->_article->code.'";'.PHP_EOL.
                    'var msg_processing_your_request = "'.lang::trans('processing_your_request').'";'.PHP_EOL.
                    'var msg_required_field = "'.lang::trans('required_field').'";'.PHP_EOL.
                    'var msg_rate_required = "'.lang::trans('rate_required').'";'.PHP_EOL.
                '</script>'.PHP_EOL.
                '';    
        
        return $html;
    } 
    
    public function renderStartContent()
    {
        $html = 
                '<div>'.
                    // Show a dialog when we increase the spinner cart and there isn't stock enough
                    '<div id="addtocart-amount-spinner-dialog-warning" style="display:none"></div>'.
                '';   
        
        return $html;
    } 
    
    public function renderEndContent()
    {
        $html = 
                '</div>'.
                '';   
        
        return $html;
    }
    
    public function renderContent()
    {
        // Article
        $html = $this->_renderArticle();
        
        // Related articles
        $html .= $this->_renderRelatedArticles();
        
        return $html;
    } 
    
    protected function _renderArticle()
    {
        // Start
        $html = 
                '<div id="article-detail-wrapper">'.
                    '<div id="article-detail-wrapper-center">'.
                        '<div itemscope itemtype="http://schema.org/Product">'.
                '';
        
        // Presentation
        $html .= $this->_renderPresentation();
        
        // Tabs info
        $html .= $this->_renderTabs();
        
        // End 
        $html .=  
                        '</div>'.    
                    '</div>'.
                    
                    $this->_renderMedicinesInfo().
                
                '</div>';
        
        return $html;
    } 
    
    private function _renderPresentation()
    {
        $html = '';
        
        // Start presentation
        $html .=  '<div id="article-detail-presentation">';
        
        // Images
        $html .= $this->_renderImages();
        
        // Introduction
        $html .= $this->_renderIntroduction();
        
        // End presentation
        $html .=  '</div>';
        
        return $html;
    }
    
    protected function _renderImages()
    {
        $article = $this->_article;
        $html = '';
        
        // Title
        $composed_title = $this->getComposedTitle($article);
        
        // Images
        $images = $this->_article_controller->getImages($article);
        $counter = count($images);
        if (!empty($images))
        {
            foreach ($images as $path) {
                $html .= '<meta itemprop="image" content="'.$path.'" />';
            }            
        }
        $html .= 
                '<div id="article-detail-carousel-wrapper-parent">'.
                    '<div id="article-detail-carousel-wrapper">'.
                        '<div '. 
                            'id ="article-detail-carousel" '.
                            'class="jcarousel-type1" '.
                            '_items="'.$counter.'" '.
                        '>'.
                            '<ul>';
        if (!empty($images))
        {
            foreach ($images as $path) {
                $html .= $this->_getImgTagLi($path, $composed_title);                        
            }
        }
        else
        {
            $path = '';
            $html .= $this->_getImgTagLi($path, $composed_title);           
        }
        $html .=
                            '</ul>'.
                        '</div>'.
                        '<a href="#" '.
                            'id ="article-detail-carousel-control-prev" '.
                            'class="jcarousel-type1-control-prev" >'.
                            '&lsaquo;'.
                        '</a>'.
                        '<a href="#" '.
                            'id ="article-detail-carousel-control-next" '.
                            'class="jcarousel-type1-control-next" >'.
                            '&rsaquo;'.
                        '</a>'.
                        '<p '.
                            'id ="article-detail-carousel-pagination" '.
                            'class="jcarousel-type1-pagination" >'.
                        '</p>'.
                    '</div>'.
                    '';
        
        $html .=    $this->_renderBadges($article).
                '</div>'.
                '';              
        
        return $html;
    }
    
    protected function _renderBadges($article)
    {
        $html = '';   
        
         // ENABLE CHRISTMAS
        /*if (isset($article->christmas) && $article->christmas)
        {
                $html .= 
                        '<div class="article-detail-carousel-badge-wrapper article-detail-carousel-badge-christmas-wrapper">'.
                            '<img '.
                                'class="article-detail-carousel-badge-img" '.
                                'src="'."/modules/ecommerce/frontend/res/img/christmas/christmas-ball-gold.png".'" '.
                            '/>'.                     
                        '</div>'.
                        '';        
        } 
        else*/if (isset($article->outstanding) && $article->outstanding)
        {
                $html .= 
                        '<div class="article-detail-carousel-badge-wrapper article-detail-carousel-badge-outstanding-wrapper">'.
                            '<img '.
                                'class="article-detail-carousel-badge-img" '.
                                'src="'."/modules/ecommerce/frontend/res/img/discount.png".'" '.
                            '/>'.                        
                            '<span class="article-detail-carousel-badge-text article-detail-carousel-badge-outstanding-text">'.
                                strtoupper(lang::trans('special_offer')).
                            '</span>'.                        
                        '</div>'.
                        '';        
        }  
        elseif (isset($article->novelty) && $article->novelty)
        {
                $html .= 
                        '<div class="article-detail-carousel-badge-wrapper article-detail-carousel-badge-novelty-wrapper">'.
                            '<img '.
                                'class="article-detail-carousel-badge-img" '.
                                //'src="'."/modules/ecommerce/frontend/res/img/new.png".'" '.
                                'src="'."/modules/ecommerce/frontend/res/img/black-circle-1.png".'" '.
                            '/>'.                        
                            '<span class="article-detail-carousel-badge-text article-detail-carousel-badge-novelty-text">'.
                                strtoupper(lang::trans('new')).
                            '</span>'.                        
                        '</div>'.
                        '';                
        }                 
        elseif (isset($article->pack) && $article->pack)
        {
                $html .= 
                        '<div class="article-detail-carousel-badge-wrapper article-detail-carousel-badge-pack-wrapper">'.
                            '<img '.
                                'class="article-detail-carousel-badge-img" '.
                                //'src="'."/modules/ecommerce/frontend/res/img/pack.png".'" '.
                                'src="'."/modules/ecommerce/frontend/res/img/black-circle-1.png".'" '.
                            '/>'.                        
                            '<span class="article-detail-carousel-badge-text article-detail-carousel-badge-pack-text">'.
                                'PACK'.
                            '</span>'.                        
                        '</div>'.
                        '';        
        }                
        
        if (isset($article->secondUnitDiscount) && $article->secondUnitDiscount > 0)
        {
                $html .= 
                        '<div class="article-detail-carousel-badge-2ndunitto-wrapper">'.
                            '<div class="article-detail-carousel-badge-2ndunitto-text1">'.
                                mb_strtoupper(lang::trans('promotion')).'!'.
                            '</div>'. 
                            '<div class="article-detail-carousel-badge-2ndunitto-text2">'.
                                ' '.$article->secondUnitDiscount.'% '.
                            '</div>'.        
                            '<div class="article-detail-carousel-badge-2ndunitto-text3">'.
                                strtolower(lang::trans('in_the_second_unit')).
                            '</div>'. 
                        '</div>'.
                        '';        
        }
        
        return $html;
    }
    
    private function _getImgTagLi($path, $title)
    {
        $img = 
            '<img '.                       
                'border="0" '.
                'class="article-detail-carousel-img" '. 
                'src="'.$path.'" '.
                'alt="'.$title.'" '.
            '>';
        $a = 
            '<a '.
                'href="'.$path.'" '.
                'title="'.$title.'" '.
                'class="fancybox"'.$this->_rel_external.
            '>'.
                $img.
            '</a>';               
        
        return  '<li>'.$a.'</li>';
    }
    
    protected function _getIntroductionData()
    {
        $article = $this->_article;
        $current_lang = lang::getCurrentLanguage(); 
        
        // Set values to paint
        $brand_name = $this->_brand_controller->getBrandName($this->brand);
        $title = $this->_article_controller->getTitle($article);
        $display = $this->_article_controller->getDisplay($article);
        if (isset($this->gamma) && $this->gamma->visible &&
           (!isset($this->gamma->not_visible_in_article) || !$this->gamma->not_visible_in_article)
        )
        {
            $gamma_name = $this->_gamma_controller->getTitle($this->gamma);
            if (strtolower($title) === strtolower($gamma_name) ||
                strtolower($brand_name) === strtolower($gamma_name))
            {
                $gamma_name = '';
            } 
        }
        else
        {
            $gamma_name = "";
        }
        
        // Title
        $composed_title = $this->getComposedTitle($article);
        
        // Brand
        $brand_code = $this->_brand_controller->getBrandCode($this->brand);
        $brand_url = $this->_ecommerce_controller->getUrl(array($current_lang, lang::trans('url-brands'), $brand_code));
        
        // Prices
        $article_url = $this->_article_controller->getArticleUrl($article);
        $is_novelty = (isset($article->novelty) && $article->novelty);
        $price = 0;
        $strikethrough_price = 0;
        $rate = new rate();
        $prices = $rate->getArticlePrices($article);
        if (isset($prices))
        {
            $price = $prices->finalRetailPrice;
            $discount = $prices->discount;
            if ($discount > 0 && !$prices->hideDiscount)
            {
                $strikethrough_price = $prices->retailPrice;
            }  
        }
        
        $rendered_strikethrough_price = null;
        if ($strikethrough_price > 0)
        {
            $rendered_strikethrough_price = $this->renderPriceFormat($strikethrough_price).'&euro;';
        }
        
        $rendered_price = null;
        $udata_price_content = null;
        $rendered_discount = null;
        if ($price > 0)
        {
            $rendered_price = $this->renderPriceFormat($price);
            $udata_price_content = number_format(round($price, 2), 2, ".", "");

            // Discount image
            $hide_discount_badge = $prices->hideDiscountBadge;
            if ($strikethrough_price > 0 && !$hide_discount_badge)
            {
                $rendered_discount = '-'.$discount.'%';             
            }              
        }
        
        // Stock
        $any_stock = $this->_article_controller->anyStock($article);        
        
        // Short description
        $short_description = $this->_article_controller->getShortDescription($article, null, $this->canonical_article);
        
        // Rating average
        $total_reviews = count($this->current_reviews);
        $rating = null;
        if ($total_reviews > 0)
        {
            $rating = $this->_article_controller->getRatingAverageReviews($this->current_reviews);            
        }
        
        return array(
            'gamma_name' => $gamma_name,
            'title' => $title,
            'composed_title' => $composed_title,
            'display' => $display,
            'brand_url' => $brand_url,
            'brand_name' => $brand_name,
            'article_url' => $article_url,
            'is_novelty' => $is_novelty,
            'rendered_strikethrough_price' => $rendered_strikethrough_price,
            'rendered_price' => $rendered_price,
            'udata_price_content' => $udata_price_content,
            'rendered_discount' => $rendered_discount,
            'any_stock' => $any_stock,
            'article' => $article,
            'short_description' => $short_description,
            'total_reviews' => $total_reviews,
            'rating' => $rating
        );
    }
    
    protected function _renderMaindata($data)
    {
        $html = '';
        
        // Gamma
        if (!empty($data['gamma_name']))
        {
            $html .= 
                    '<div id="article-detail-introduction-gamma" class="gamma">'.
                        $data['gamma_name'].
                    '</div>';             
        }
        
        // Title
        $html .=  
                '<h1 id="article-detail-introduction-title" class="article-title" itemprop="name" content="'.$data['composed_title'].'">'.
                    $data['title'].
                '</h1>';
        
        // Display
        if (!empty($data['display']))
        {
            $html .=  
                    '<div id="article-detail-introduction-display" class="article-display">'.
                        $data['display'].
                    '</div>';            
        }
        
        // Brand
        $html .=  
                '<div id="article-detail-introduction-brand-wrapper">'.
                    '<a href="'.$data['brand_url'].'" class="brand"'.$this->_rel_external.'>'.
                        '<h2 id="article-detail-introduction-brand" itemprop="brand">'.
                            $data['brand_name'].
                        '</h2>'.
                    '</a>'.                   
                '</div>';
        
        // Available formats (articles grouped by display)
        $html .= $this->_renderAvailableFormats();
        
        // Prices
        $html .= 
                '<div id="article-detail-introduction-prices">'.
                    '<span '.
                        'itemprop="offers" itemscope itemtype="http://schema.org/Offer"'
                    .'>'.
                        '<meta itemprop="url" content="'.$data['article_url'].'" />';
        if ($data['is_novelty'])
        {
            $html .= '<meta itemprop="itemCondition" content="new" /> ';
        }     
        if (!is_null($data['rendered_strikethrough_price']))
        {
            $html .=  
                    '<div id="article-detail-introduction-strikethrough-price" class="strikethrough-price">'.
                        $data['rendered_strikethrough_price'].
                    '</div>';            
        }   
        if (!is_null($data['rendered_price']))
        {
            $html .=  
                    '<div id="article-detail-introduction-price" class="price">'.
                        '<span itemprop="price" content="'.$data['udata_price_content'].'">'.$data['rendered_price'].'</span>'.
                        '<span itemprop="priceCurrency" content="EUR">&euro;</span>'.
                    '</div>';            
        }
        if (!is_null($data['rendered_discount']))
        {
            $html .= 
                    '<div class="article-detail-introduction-price-discount">'.
                        '<img '.
                            'class="article-detail-introduction-price-discount-img" '.
                            'src="'."/modules/ecommerce/frontend/res/img/discount.png".'" '.
                        '/>'.                        
                        '<span class="article-detail-introduction-price-discount-text">'.
                            $data['rendered_discount'].
                        '</span>'.                        
                    '</div>';                   
        }
        
        // Stock
        $html .= '<link itemprop="availability" href="http://schema.org/'.($data['any_stock']? 'InStock' : 'OutOfStock').'" />';
        
        // End prices
        $html .= '</span>';
        $html .= '</div>';
        
        return $html;
    }
    
    protected function _renderAddToCart($data, $is_mobile = false)
    {
        $html = '';
                
        // Add to cart option
        if (!is_null($data['rendered_price']) && $data['any_stock'] && $this->brand->available && $this->is_article_available)
        {
            $html .= '<div id="article-detail-introduction-addtocart">';
            $html .=    $this->renderAddToShoppingcartWidgets($data['article'], $is_mobile);
            $html .= '</div>';
        }
        else
        {
            $html .= 
                    '<div id="article-detail-no-stock">'.
                        '- '.lang::trans('no_stock').' -'.
                    '</div>';
        }
        
        return $html;
    }
    
    protected function _renderRef($data)
    {
        $html = '';
                
        // Ref.
        $html .=  
                '<div id="article-detail-introduction-ref" class="label-ref">'.
                    'Ref. '.$data['article']->code.
                '</div>';
        
        return $html;
    }
    
    protected function _renderShortDescription($data)
    {
        $html = '';
                
        // Short description
        $html .=  
                '<h3 id="article-detail-introduction-short-description" itemprop="description">'.
                    $data['short_description'].
                '</h3>';    
        
        return $html;
    }
    
    protected function _renderRatingAverage($data, $is_mobile = false)
    {
        $html = '';
              
        // Rating average
        $total_reviews = $data['total_reviews'];
        $rating = $data['rating'];
        if ($total_reviews > 0)
        {
            $total_reviews_text = $is_mobile? "" : $total_reviews;
            $review_text = $is_mobile? "" : (' '.(($total_reviews === 1)? strtolower(lang::trans('review')) : strtolower(lang::trans('reviews'))));
            $rating_value_text = $is_mobile? "" : (str_replace('.', ',', $rating));
            $html .= 
                    '<div id="article-detail-introduction-reviews" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">'.
                        '<div id="article-detail-introduction-reviews-total">'.
                            '<span itemprop="reviewCount" content="'.$total_reviews.'">'.$total_reviews_text.'</span>'.
                            $review_text.
                        '</div>'.
                        '<div id="article-detail-introduction-reviews-star-rating" _rating="'.$rating.'"></div>'.
                        '<div id="article-detail-introduction-reviews-rating-average" itemprop="ratingValue" content="'.$rating.'">'.$rating_value_text.'</div>'.
                        //'<meta itemprop="worstRating" content="1" />'.
                        //'<meta itemprop="bestRating" content="5" />'.
                    '</div>';            
        }
        
        return $html;
    }
    
    protected function _renderIntroduction()
    {
        $data = $this->_getIntroductionData();
        
        // Start introduction
        $html =  
                '<div id="article-detail-introduction">'.
                    '<div id="article-detail-introduction-1">';      
        
        // Gamma, title, display and brand, ...
        $html .= $this->_renderMaindata($data);
                
        // Add to cart option
        $html .= $this->_renderAddToCart($data);
                
        // Line delimiter
        $html .= '<div class="article-detail-introduction-line-delimiter"></div>';
        
        // End introduction 1
        $html .= '</div>';
        
        // Ref.
        $html .=  $this->_renderRef($data);
        
        // Short description
        $html .=  $this->_renderShortDescription($data);
        
        // Rating average
        $html .=  $this->_renderRatingAverage($data);
        
        // End introduction
        $html .=  '</div>';
        
        return $html;
    }
    
    public function renderAddToShoppingcartWidgets($article, $is_mobile = false)
    {
        $html = '';
        $stock_controller = new stock();
        $amounts_to_add = $stock_controller->getAmountToAdd($article);
        $data_icon = $is_mobile? 'data-icon="plus" ' : "";
        
        // Button
        $html .= 
                    '<div id="article-detail-introduction-addtocart-button-container">'.
                        '<button '.
                            'id="article-detail-introduction-addtocart-button" '.
                            'type="button" '.$data_icon.
                            'class="'.
                                   'button '.
                                   'button-addtocart'.
                                    '" '.
                            'onClick="addIndividualArticleToShoppingcart('.
                                                           '\''.$article->code.'\''.
                                                      ')" '.
                            '_disabled="'.(($amounts_to_add->minAmountToAdd <= 0)? true : false).'" '.
                        '>';
        if (!$is_mobile)
        {
            $html .= 
                            '<img '.
                                'class="article-detail-introduction-addtocart-button-img" '.
                                'src="/modules/ecommerce/frontend/res/img/shoppingcarts/shoppingcart5-white.png" '.
                            '/>';                    
        }
        $html .= 
                            '<div class="article-detail-introduction-addtocart-button-text" >'.                 
                                lang::trans('add_to_cart').
                            '</div>'.            
                        '</button>'.
                    '</div>'.
                    '';
        
        $html .= $this->_renderSpinnerAmount($article, $amounts_to_add);
            
        return $html;
    }
    
    protected function _renderSpinnerAmount($article, $amounts_to_add)
    {
        // Spinner (amount)
        $html = 
                    '<div id="article-detail-introduction-addtocart-amount-stock">'.
                        '<div id="article-detail-introduction-addtocart-stock">'.
                    '';
        
        $stock_enable = !(isset($article->infinityStock) && $article->infinityStock);
//        if ($stock_enable)
//        {
//             $html .= 
//                            '<label id="article-detail-introduction-addtocart-stock-label">'.
//                                'STOCK'.':&nbsp;'.
//                            '</label>'.
//                            '<span id="article-detail-introduction-addtocart-stock-value">'.
//                                $article->stock.
//                            '</span>'.
//                            '';
//        }        
        
        // Title
        $article_title = $this->_article_controller->getTitle($article);
        
        $html .= 
                        '</div>'.
                        '<div id="article-detail-introduction-addtocart-amount">'.
                            '<input '.
                                    'id="article-detail-introduction-addtocart-amount-spinner" '.
                                    '_article_code="'.$article->code.'" '.
                                    '_article_title="'.$article_title.'" '.
                                    '_enable="'.$stock_enable.'" '.
                                    '_stock="'.$article->stock.'" '.
                                    '_min="'.$amounts_to_add->minAmountToAdd.'" '.
                                    '_max="'.$amounts_to_add->maxAmountToAdd.'" '.
                                    'value="'.$amounts_to_add->minAmountToAdd.'" '.                    
                            '>'.
                        '</div>'.
                    '</div>'.
                    '';
            
        return $html;
        
    }
    
    private function _renderBotplusAccordion($botplus_key)
    {
        $html = '';
        
        if (empty($this->botplusdata) || empty($this->botplusdata[$botplus_key]))
        {
            return $html;
        }
        
        $content = '';
        $current_lang = lang::getCurrentLanguage(); 
        
        foreach ($this->botplusdata[$botplus_key] as $key => $value)
        {
            if (!$value->enabled)
            {
                continue;
            }
            
            if (property_exists($value, $current_lang) && isset($value->$current_lang))
            {
                $name = $value->$current_lang->name;
                if (empty($name))
                {
                    $name = $value->es->name;
                }
                $text = $value->$current_lang->text;
                if (empty($text))
                {
                    $text = $value->es->text;
                }
            }
            else
            {
                $name = $value->es->name;
                $text = $value->es->text;
            }
            
            $content .= '<h3>'.$name.'</h3>';
            $content .= '<div>'.$text.'</div>';
        }
        if (empty($content))
        {
            return $html;
        }
        
        $html .= '<div id="article-detail-tab-accordion-'.$botplus_key.'">';
        $html .= $content;
        $html .= '</div>';
    
        return $html;
    }

    protected function _getTabsData()
    {
        $description = $this->tabdata['description'];
        $application = $this->tabdata['application'];
        $active_ingredients = $this->tabdata['active_ingredients'];
        $composition = $this->tabdata['composition'];
        $prospect = $this->tabdata['prospect'];
        $datasheet = $this->tabdata['datasheet'];
        $brand = $this->tabdata['brand'];
        $brand_title = $this->tabdata['brand_title'];
        $gamma = $this->tabdata['gamma'];
        $gama_title = $this->tabdata['gama_title'];
        
        // Botplus data
        // Add epigraphs of botplus in description tab whether this is in blank
        $botplus_epigraphs = $this->_renderBotplusAccordion('epigraphs');
        if (empty($description))
        {
            $description = $botplus_epigraphs;
            $botplus_epigraphs = "";
        }
        $botplus_messages = $this->_renderBotplusAccordion('messages');
        
        // Reviews
        $reviews_view = new reviewsView($this->current_reviews);
        $reviews = $reviews_view->render();
        
        // Exit if nothing to render
        $success = !(empty($description) && empty($application) && empty($composition) && empty($prospect) && empty($datasheet) && empty($brand) && empty($gamma) && empty($reviews) && empty($botplus_epigraphs) && empty($botplus_messages));
        
        return array(
            'success' => $success,
            'description' => $description,
            'application' => $application,
            'active_ingredients' => $active_ingredients,
            'composition' => $composition,
            'prospect' => $prospect,
            'datasheet' => $datasheet,
            'brand' => $brand,
            'brand_title' => $brand_title,
            'gamma' => $gamma,
            'gama_title' => $gama_title,
            'reviews' => $reviews,
            'botplus_epigraphs' => $botplus_epigraphs,
            'botplus_messages' => $botplus_messages
        );
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
        $html .= 
                '<div id="article-detail-tabs-wrapper">'.
                    '<div id="article-detail-tabs">'.
                        '<ul>';
        
        if (!empty($data['description']))
        {
            $html .= '<li><a href="#article-detail-tab-description">'.lang::trans('description').'</a></li>';
        }
        if (!empty($data['application']))
        {
            $html .= '<li><a href="#article-detail-tab-application">'.lang::trans('application').'</a></li>';
        }
        if (!empty($data['active_ingredients']))
        {
            $html .= '<li><a href="#article-detail-tab-activeingredients">'.lang::trans('active_ingredients').'</a></li>';
        }
        if (!empty($data['composition']))
        {
            $html .= '<li><a href="#article-detail-tab-composition">'.lang::trans('composition').'</a></li>';
        }
        if (!empty($data['botplus_epigraphs']))
        {
            $html .= '<li><a href="#article-detail-tab-epigraphs">'.lang::trans('more_info').'</a></li>';
        }
        if (!empty($data['botplus_messages']))
        {
            $html .= '<li><a href="#article-detail-tab-messages">'.lang::trans('warnings').'</a></li>';
        }
        if (!empty($data['prospect']))
        {
            $html .= '<li><a href="#article-detail-tab-prospect">'.lang::trans('prospect').'</a></li>';
        }
        if (!empty($data['datasheet']))
        {
            $html .= '<li><a href="#article-detail-tab-datasheet">'.lang::trans('datasheet').'</a></li>';
        }
        if (!empty($data['brand']))
        {
            $html .= '<li><a href="#article-detail-tab-brand">'.$data['brand_title'].'</a></li>';
        }
        if (!empty($data['gamma']))
        {
            //$html .= '<li><a href="#article-detail-tab-gamma">'.lang::trans('gamma').'</a></li>';
            $html .= '<li><a href="#article-detail-tab-gamma">'.$data['gama_title'].'</a></li>';
        }
        if (!empty($data['reviews']))
        {
            $html .= '<li><a href="#article-detail-tab-reviews">'.lang::trans('reviews').' ('.count($this->current_reviews).')'.'</a></li>';
        }
        
        $html .= '</ul>';
            
        if (!empty($data['description']))
        {
            $html .= 
                    '<div id="article-detail-tab-description">'.
                        '<div class="article-detail-tab" scrollable">'.
                            '<h4 id="article-detail-tab-description-content">'.
                                $data['description'].
                            '</h4>'.
                        '</div>'.
                    '</div>';
        }
        if (!empty($data['application']))
        {
            $html .= 
                    '<div id="article-detail-tab-application">'.
                        '<div class="article-detail-tab scrollable">'.
                            $data['application'].
                        '</div>'.
                    '</div>';
        }
        if (!empty($data['active_ingredients']))
        {
            $html .= 
                    '<div id="article-detail-tab-activeingredients">'.
                        '<div class="article-detail-tab scrollable">'.
                            $data['active_ingredients'].
                        '</div>'.
                    '</div>';
        }
        if (!empty($data['composition']))
        {
            $html .= 
                    '<div id="article-detail-tab-composition">'.
                        '<div class="article-detail-tab scrollable">'.
                            $data['composition'].
                        '</div>'.
                    '</div>';
        }
        if (!empty($data['botplus_epigraphs']))
        {
            $html .= 
                    '<div id="article-detail-tab-epigraphs">'.
                        '<div class="article-detail-tab" scrollable">'.
                            $data['botplus_epigraphs'].
                        '</div>'.
                    '</div>';
        }
        if (!empty($data['botplus_messages']))
        {
            $html .= 
                    '<div id="article-detail-tab-messages">'.
                        '<div class="article-detail-tab" scrollable">'.
                            $data['botplus_messages'].
                        '</div>'.
                    '</div>';
        }
        if (!empty($data['prospect']))
        {
            $html .= 
                    '<div id="article-detail-tab-prospect">'.
                        $this->_getIFrameTag($data['prospect'], "article-detail-tab article-detail-tab-iframe scrollable").
                    '</div>';
        }
        if (!empty($data['datasheet']))
        {
            $html .= 
                    '<div id="article-detail-tab-datasheet">'.
                        $this->_getIFrameTag($data['datasheet'], "article-detail-tab article-detail-tab-iframe scrollable").
                    '</div>';
        }
        if (!empty($data['brand']))
        {
            $html .= 
                    '<div id="article-detail-tab-brand">'.
                        '<div class="article-detail-tab scrollable">'.
                            $data['brand'].
                        '</div>'.
                    '</div>';
        } 
        if (!empty($data['gamma']))
        {
            $html .= 
                    '<div id="article-detail-tab-gamma">'.
                        '<div class="article-detail-tab scrollable">'.
                            $data['gamma'].
                        '</div>'.
                    '</div>';
        }          
        if (!empty($data['reviews']))
        {
            $html .= 
                    '<div id="article-detail-tab-reviews">'.
                        '<div class="article-detail-tab scrollable">'.
                            $data['reviews'].
                        '</div>'.
                    '</div>';
        }       

        // End tabs
        $html .= '</div></div>';
        
        return $html;
    }
    
    protected function _getIFrameTag($path, $class = "")
    {
        $is_pdf = (strpos(strtoupper($path), '.PDF'));
        $src = $is_pdf? $path.'#view=FitH' : $path;
        $tag = $is_pdf? 'embed' : 'iframe';
        $type = $is_pdf? ' type="application/pdf"' : '';
        $class = empty($class)? "" : (' class="'.$class.'"');

        return '<'.$tag.' src="'.$src.'"'.$type.$class.'></'.$tag.'>';
    }
    
    protected function _renderMedicinesInfo()
    {
        $html = '';
        
        if ($this->_article->articleType !== '2')
        {
            return $html;
        }
        
        $medicines_info_view = new medicinesInfoView();
        $medicines_info_view->is_individual_article = true;
        $html .= $medicines_info_view->render();
        
        return $html; 
    }

    protected function _renderRelatedArticles()
    {
        $html = '';
        $articles = $this->related_articles;
        
        if (empty($articles))
        {
            return $html;
        }
    
        // Start
        $html .= 
                '<div id="article-detail-related-articles-wrapper">'.
                    '<div id="article-detail-related-articles-wrapper-center" class="page-center">'.   
                '';  
        
        $html .= 
                '<div id="article-detail-related-articles-title" class="title">'.
                    lang::trans('you_may_also_like').'....'.
                '</div>'.
                '';
        
        $html .= '<div id="article-detail-related-articles-content">';
        
        $related_articles_view = $this->_getRelatedArticlesView();
        $related_articles_view->articles = $articles;
        $related_articles_view->brands = $this->brands;
        $related_articles_view->gammas = $this->gammas;
        
        $html .=  $related_articles_view->renderArticles();
        $html .= '</div>';
                
        // End
        $html .= '</div></div>';
        
        return $html;
    }
    
    protected function _getRelatedArticlesView()
    {
        $related_articles_view = new relatedArticlesView();
        return $related_articles_view;
    }
    
    public function getComposedTitle($article)
    {
        // Brand
        if (isset($this->brand))
        {
            $brand_name = $this->_brand_controller->getBrandName($this->brand);  
        }
        else
        {
            $brand_name = '';
        }
        
        // Gamma
        if (isset($this->gamma) && 
            $this->gamma->visible &&
           (!isset($this->gamma->discard_in_composition_of_article_title) || !$this->gamma->discard_in_composition_of_article_title)
        )
        {
            $gamma_name = $this->_gamma_controller->getTitle($this->gamma);
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

    private function _renderAvailableFormats()
    {
        $html = '';
        $articles = $this->articles_grouped_by_display;
        
        if (count((array) $articles) <= 1)
        {
            return $html;
        }
        
        $html .= 
                '<div id="article-detail-introduction-availableformats-combo-wrapper">'.
                    '<div id="article-detail-introduction-availableformats-text">'.
                        lang::trans('available_formats').
                    '</div>'.                
                    '<select '.
                        'id="article-detail-introduction-availableformats-combo" '.
                        'onChange="window.location.href=this.value"'.
                    ' >'.
                '';   
        
        // Sort combo values by display
        $combo_values = array();
        foreach ($articles as $article)
        {
            $article->url = $this->_article_controller->getArticleUrl($article);
            $article->display = $this->_article_controller->getDisplay($article);
            $combo_values[] = $article;
        }
        $sorted_combo_values = helpers::sortArrayByField($combo_values, 'display');
        
        // Render combo values
        foreach ($sorted_combo_values as $values)
        {
            $selected = '';
            if ($values->code === $this->_article->code)
            {
                $selected = 'selected ';
            }
            $html .= 
                    '<option value="'.$values->url.'" '.$selected.'>'.
                        $values->display.
                    '</option>';            
        }   
        
        $html .=
                    '</select>'.
                '</div>'.  
            '';

        $html .=  '<div id="article-detail-introduction-availableformats-delimiter"></div>';        
    
        return $html;
    }
}