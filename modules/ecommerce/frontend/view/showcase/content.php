<?php

namespace modules\ecommerce\frontend\view\showcase;

// Controllers
use core\config\controller\config;
use core\url\controller\url;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce as ecommerceController;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\frontend\controller\brand;
use modules\ecommerce\frontend\controller\gamma;
use modules\ecommerce\frontend\controller\stock;
use modules\ecommerce\frontend\controller\personaldata;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Showcase view (content)
 *
 * @author Dani Gilabert
 * 
 */
class content
{  
    protected $_ecommerce_controller;
    protected $_ecommerce_view;
    protected $_article_controller;
    protected $_brand_controller;
    protected $_gamma_controller;
    protected $_rel_external;
    
    public $title = '';
    public $articles = array();
    public $brands = array();
    public $gammas = array();
    public $category = null;
    public $categories = array();
    public $articles_per_page = 15;
    public $current_page = 1;
    public $total_pages = 0;
    public $sortby = 'a-z';
    public $show_addtocart_area = true;
    public $search_filter = null;
    public $subcategories_menu_is_rendered;
    public $columns = 3;
    public $current_url_without_params = '';
    public $current_url_params = array();
    
    public function __construct()
    {
        $this->_ecommerce_controller = new ecommerceController();
        $this->_ecommerce_view = new ecommerceView();
        $this->_article_controller = new article();
        $this->_brand_controller = new brand();
        $this->_gamma_controller = new gamma();
        
        $this->_rel_external = $this->_ecommerce_view->getRelExternalTag();
    }       
    
    public function renderStart()
    {
        $html = 
                '<section>'.
                    '<div id="showcase-content">'.
                
                    // Show a dialog when we increase the spinner cart and there isn't stock enough
                    '<div id="addtocart-amount-spinner-dialog-warning" style="display:none"></div>'.         
                '';     
        
        return $html;            
    }
    
    public function renderEnd()
    {
        $html = 
                    '</div>'.
                '</section>'.
                '';     
        
        return $html;            
    }      
    
    public function renderContent()
    {
        $html = '';
        $html .= $this->_renderTitle();
        $html .= $this->_renderDescription();
        $html .= $this->_renderMenu();
        $html .= $this->renderArticles();
        $html .= $this->_renderFooter();
        $html .= $this->_renderSearchResult();
        
        return $html;            
    } 
    
    protected function _renderTitle()
    {
        $title = mb_strtoupper($this->title);
        if (isset($this->search_filter))
        {
            $title .= 
                    ' '.strtolower(lang::trans('by')).
                    '<span style="letter-spacing: 1px; font-weight: normal;">'.
                        ' <b>"</b>'.$this->search_filter.'<b>"</b>'.
                    '</span>'.
                    '';
        }
        
        $html = 
                '<h1 id="showcase-content-title" class="title">'.
                    $title.
                '</h1>'.
                '';     
        
        return $html;            
    }
    
    protected function _renderDescription()
    {
        $html = '';
        
        if (!isset($this->category) || empty($this->category) || empty($this->categories))
        {
            return $html;    
        }
        
        $category = $this->category;
        $current_lang = lang::getCurrentLanguage();
        
        // Description
        $prop = 'longDescription1'.ucfirst($current_lang);
        if (!isset($this->categories->$category) || !isset($this->categories->$category->$prop) || empty($this->categories->$category->$prop))
        {
            return $html;    
        }
        $description = $this->categories->$category->$prop;
        
        // Image
        $image = null;
        $prop = 'image1';
        if (isset($this->categories->$category) && isset($this->categories->$category->$prop) && !empty($this->categories->$category->$prop))
        {
            $filemanager_path = config::getFilemanagerPath();
            $image = '/'.$filemanager_path.'/'.$this->categories->$category->$prop;
        }
        
        $html .= 
                '<div id="showcase-content-description-wrapper">'.
                    '<table id="showcase-content-description-table" border="0" cellpadding="0" cellspacing="0">'.
                        '<tr>';
        if (isset($image))
        {           
            $html .= 
                            '<td>'.
                                '<a '.
                                    'href="'.$image.'" '.
                                    'title="'.$this->title.'" '.
                                    'class="fancybox" '.
                                '>'.                    
                                    '<img '.
                                        'id="showcase-content-description-img" '.
                                        'src="'.$image.'" '.
                                    '/>'.
                                '</a>'.
                            '</td>';
        }
        $html .= 
                            '<td id="showcase-content-description-desc">'.
                                $description.
                            '</td>'.
                        '</tr>'.
                    '</table>'.
                '</div>'.
                '<div id="showcase-content-description-delimiter"></div>'.
                '';     
        
        return $html;            
    }
    
    protected function _renderMenu()
    {
        $html = '<div id="showcase-content-menu">';
        
        // Total articles
        $html .= $this->_renderTotalArticlesMenu();      
        
        // Sort articles by
        $html .= $this->_renderSortbyCombo();
        
        $html .= '</div>';
        
        return $html;            
    }
    
    protected function _renderTotalArticlesMenu()
    {
        $total_articles = count($this->articles);
        
        $html =
                '<div>'.
                    '<b>'.
                        lang::trans('total_articles').' '. $total_articles.
                    '</b>'.
                '</div>'.
            '';
        
        return $html;  
    }
    
    protected function _renderSortbyCombo()
    {
        $total_articles = count($this->articles);
        
        if ($total_articles <= 0)
        {
            return '';
        }
        
        $html = 
                '<div id="showcase-content-menu-sortby-wrapper">'.
                    '<select '.
                        'id="showcase-content-menu-sortby-combo" '.
//                        'onChange="onChangeSortby(this.options[this.selectedIndex].value)"'.
//                        'onChange="onChangeSortby(\'this.value\')"'.
                        'onChange="onChangeSortby()"'.
                    ' >'.
                '';   
        
        for ($i=0; $i<=3; $i++)
        {
            switch ($i)
            {
                case 0:
                    $sortby = 'a-z';
                    break;
                case 1:
                    $sortby = 'z-a';
                    break;
                case 2:
                    $sortby = 'cheaper-first';
                    break;
                case 3:
                    $sortby = 'more-expensive-first';
                    break;
                default:
                    $sortby = 'a-z';
                    break;
            }
            
            $selected = '';
            if ($sortby === $this->sortby)
            {
                $selected = 'selected ';
            }            
            $html .= '<option value="'.$sortby.'" '.$selected.'>'.lang::trans('sortby-'.$sortby).'</option>';            
        }   
        
        $html .=
                    '</select>'.
                
        $this->_renderSortbyComboText().

                '</div>'.  
            '';
        
        return $html;  
    }
    
    protected function _renderSortbyComboText()
    {
        $html =
                '<div id="showcase-content-menu-sortby-text">'.
                    lang::trans('sortby').
                '</div>'.
                '';   
        
        return $html;      
    }

    public function renderArticles()
    {    
        $html = '';      
        $articles = $this->articles;
        $articles_counter = 0;
        $articles_rendered_counter = 0;
        
        if (empty($articles))
        {
            return $html;
        }
        
        $first_article = (($this->current_page - 1) * $this->articles_per_page) + 1;

        $html .= '<table class="showcase-content-articles-table" border="0">';
        $column = 1;
        foreach ($articles as $article) 
        {
            // Check paging
            $articles_counter++;
            if ($articles_counter < $first_article)
            {
                continue;
            }
            if ($articles_rendered_counter >= $this->articles_per_page)
            {
                break;
            }

            // Continue rendering
            if ($column == 1)
            {
                $html .= '<tr>';
            }

            $html .= '<td class="showcase-content-article-column">';
            $html .= '<div class="showcase-content-article-wrapper fadeout">';

            // Render the article
            $html .= '<div class="showcase-content-article">';
            $html .= $this->_renderArticle($article);
            $html .= '</div>';

            $articles_rendered_counter++;

            // Render delimiter on bottom of column
            $html .= '<div class="showcase-content-article-column-delimiter-bottom"></div>';

            $html .= '</div>';
            $html .= '</td>';

            $column ++;
            if ($column > $this->columns)
            {
                $html .= '</tr>';
                $column = 1;
            }                
        }

        // Add the lacked columns and close the table
        if ($column != 1)
        {
            for ($i = $column; $i <= $this->columns; $i++)
            {
                $html .= 
                        '<td class="showcase-content-article-column">'.
                        '</td>';
            }
            $html .= '</tr>';
        }                 
        $html .= '</table>';
        
        return $html;
    }
    
    protected function _renderArticle($article)
    {
        // Build article detail url
        $article_detail_url = $this->_article_controller->getArticleUrl($article);
        
        // Start table
        $html = '<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">';
        
        // Image area
        $html .= 
                '<tr><td align="center">'.
                        $this->_renderImageArea($article, $article_detail_url).
                '</td></tr>';
        
        // Text area
        $html .= 
                '<tr><td align="center">'.
                    $this->_renderTextArea($article, $article_detail_url).
                '</td></tr>';
        
        // Prices area
        $html .= 
                '<tr><td align="center">'.
                    $this->_renderPricesArea($article).
                '</td></tr>';
        
        // Add to cart area
        if ($this->show_addtocart_area)
        {
            $html .= 
                    '<tr><td align="center">'.
                        $this->_renderAddToShoppingcartArea($article).
                    '</td></tr>';            
        }
        
        // Article footer
        $html .= '<tr><td align="center">';
        $html .= '<div class="showcase-content-article-footer">'.'</div>';
        $html .= '</td></tr>';
        
        // End table
        $html .= '</table>';

        return $html;
    }
    
    protected function _renderImageArea($article, $article_detail_url)
    {
        $image_path = '';
        $images = $this->_article_controller->getImages($article, false);
        if (!empty($images))
        {
            $image_path = $images[0];
        }
        
        $html = 
                '<a href="'.$article_detail_url.'"'.$this->_rel_external.'>'.
                    '<div class="showcase-content-article-img-wrapper" >'.
                        '<img '.
                            'class="showcase-content-article-img" '.
                            'src="'.$image_path.'" '.
                            'alt="'.$this->_getComposedTitle($article).'" '.
                        '/>'.
                        
                        $this->_renderBadges($article).
                            
                    '</div>'.
                '</a>'. 
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
                        '<div class="showcase-content-article-badge-wrapper showcase-content-article-badge-christmas-wrapper">'.
                            '<img '.
                                'class="showcase-content-article-badge-img" '.
                                'src="'."/modules/ecommerce/frontend/res/img/christmas/christmas-ball-gold.png".'" '.
                            '/>'.                     
                        '</div>'.
                        '';        
        }
        else*/if (isset($article->outstanding) && $article->outstanding)
        {
                $html .= 
                        '<div class="showcase-content-article-badge-wrapper showcase-content-article-badge-outstanding-wrapper">'.
                            '<img '.
                                'class="showcase-content-article-badge-img" '.
                                'src="'."/modules/ecommerce/frontend/res/img/discount.png".'" '.
                            '/>'.                        
                            '<span class="showcase-content-article-badge-text showcase-content-article-badge-outstanding-text">'.
                                strtoupper(lang::trans('special_offer')).
                            '</span>'.                        
                        '</div>'.
                        '';        
        }  
        elseif (isset($article->novelty) && $article->novelty)
        {
                $html .= 
                        '<div class="showcase-content-article-badge-wrapper showcase-content-article-badge-novelty-wrapper">'.
                            '<img '.
                                'class="showcase-content-article-badge-img" '.
                                //'src="'."/modules/ecommerce/frontend/res/img/new.png".'" '.
                                'src="'."/modules/ecommerce/frontend/res/img/black-circle-1.png".'" '.
                            '/>'.                        
                            '<span class="showcase-content-article-badge-text showcase-content-article-badge-novelty-text">'.
                                strtoupper(lang::trans('new')).
                            '</span>'.                        
                        '</div>'.
                        '';                
        }             
        elseif (isset($article->pack) && $article->pack)
        {
                $html .= 
                        '<div class="showcase-content-article-badge-wrapper showcase-content-article-badge-pack-wrapper">'.
                            '<img '.
                                'class="showcase-content-article-badge-img" '.
                                //'src="'."/modules/ecommerce/frontend/res/img/pack.png".'" '.
                                'src="'."/modules/ecommerce/frontend/res/img/black-circle-1.png".'" '.
                            '/>'.                        
                            '<span class="showcase-content-article-badge-text showcase-content-article-badge-pack-text">'.
                                'PACK'.
                            '</span>'.                        
                        '</div>'.
                        '';        
        }             
        
        if (isset($article->secondUnitDiscount) && $article->secondUnitDiscount > 0)
        {
                $html .= 
                        '<div class="showcase-content-article-badge-2ndunitto-wrapper">'.
                            '<div class="showcase-content-article-badge-2ndunitto-text1">'.
                                mb_strtoupper(lang::trans('promotion')).'!'.
                            '</div>'. 
                            '<div class="showcase-content-article-badge-2ndunitto-text2">'.
                                ' '.$article->secondUnitDiscount.'% '.
                            '</div>'.        
                            '<div class="showcase-content-article-badge-2ndunitto-text3">'.
                                strtolower(lang::trans('in_the_second_unit')).
                            '</div>'. 
                        '</div>'.
                        '';        
        }
        
        return $html;
    }
    
    protected function _getTextAreaData($article)
    {
        $current_lang = lang::getCurrentLanguage();
                
        // Brand
        if (isset($this->brands[$article->brand]))
        {
            $brand_code = $this->_brand_controller->getBrandCode($this->brands[$article->brand]);
            $brand_name = $this->_brand_controller->getBrandName($this->brands[$article->brand]);            
            $brand_url = $this->_ecommerce_controller->getUrl(array($current_lang, lang::trans('url-brands'), $brand_code));
        }
        else
        {
            $brand_code = '';
            $brand_name = '';
            $brand_url = '';            
        }
        
        // Title and Gamma
        $title = $this->_article_controller->getTitle($article);
        if (!empty($article->gamma) && !empty($brand_code) && 
            isset($this->gammas[$article->gamma][$brand_code]) && 
            $this->gammas[$article->gamma][$brand_code]->visible &&
            (!isset($this->gammas[$article->gamma][$brand_code]->not_visible_in_article) || !$this->gammas[$article->gamma][$brand_code]->not_visible_in_article)
        )
        {
            $gamma_name = $this->_gamma_controller->getTitle($this->gammas[$article->gamma][$brand_code]);
            if (strtolower($title) === strtolower($gamma_name) ||
                strtolower($brand_name) === strtolower($gamma_name))
            {
                $gamma_name = '';
            }            
        }
        else
        {
            $gamma_name = '';
        }
         
        // Display
        $display = $this->_article_controller->getDisplay($article);
        
        return array(
            'brand_url' => $brand_url,
            'brand_code' => $brand_code,
            'brand_name' => $brand_name,
            'gamma_name' => $gamma_name,
            'title' => $title,
            'display' => $display
        );
    }
    
    protected function _renderTextArea($article, $article_detail_url)
    {
        $data = $this->_getTextAreaData($article);
        
        // Start
        $html = '<div class="showcase-content-article-text-wrapper" >';
                
        // Brand
        $html .= 
                '<a href="'.$data['brand_url'].'" class="showcase-content-article-brand brand"'.$this->_rel_external.'>'.
                    $data['brand_name'].
                '</a>'.                
                '';
        
        // Title and Gamma
        $html .= 
                '<div class="showcase-content-article-gamma gamma">'.
                    $data['gamma_name'].
                '</div>'.                
                '<a href="'.$article_detail_url.'" class="showcase-content-article-title"'.$this->_rel_external.'>'.
                    $data['title'].
                '</a>'.                
                '';
         
        // Display
        $html .= 
                '<div class="showcase-content-article-display article-display">'.
                    (!empty($data['display'])? $data['display'] : '').
                '</div>'.                
                ''; 
        
        // End
        $html .= '</div>';
        
        return $html;
    }
    
    protected function _getPricesAreaData($article)
    {
        $rendered_price = null;
        $rendered_strikethrough_price = null;
        $rendered_discount = null;
        
        if (isset($article->prices) && $article->prices->finalRetailPrice > 0)
        {
            $price = $article->prices->finalRetailPrice;
            $discount = $article->prices->discount;
            $strikethrough_price = 0;
            if ($discount > 0 && !$article->prices->hideDiscount)
            {
                $strikethrough_price = $article->prices->retailPrice;
            }
            
            // Price without discount
            if ($strikethrough_price > 0)
            {
                $rendered_strikethrough_price = $this->_ecommerce_view->renderPriceFormat($strikethrough_price).'&euro;';
            }
            // New price
            $rendered_price = $this->_ecommerce_view->renderPriceFormat($price).'&euro;';
            
            // Discount image
            $hide_discount_badge = $article->prices->hideDiscountBadge;
            if ($strikethrough_price > 0 && !$hide_discount_badge)
            {
                $rendered_discount = '-'.$discount.'%';
            }            
        }
        
        return array(
            'rendered_price' => $rendered_price,
            'rendered_strikethrough_price' => $rendered_strikethrough_price,
            'rendered_discount' => $rendered_discount
        );
        
    }
    
    protected function _renderPricesArea($article)
    {
        $data = $this->_getPricesAreaData($article);
        
        $html = 
                '<div class="showcase-content-article-prices-wrapper">'. 
                    '<div class="showcase-content-article-prices">';
        
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
        
        if (!is_null($data['rendered_discount']))
        {
            $html .= 
                    '<div class="showcase-content-article-prices-discount">'.
                        '<img '.
                            'class="showcase-content-article-prices-discount-img" '.
                            'src="'."/modules/ecommerce/frontend/res/img/discount.png".'" '.
                        '/>'.                        
                        '<span class="showcase-content-article-prices-discount-text">'.
                            $data['rendered_discount'].
                        '</span>'.                        
                    '</div>'.
                    '';        
        }
        
        $html .=    '</div>'.
                '</div>';                
                
        return $html;
    }
    
    protected function _anyStock($article) {
        $stock_controller = new stock();
        return $stock_controller->anyStock($article);
    }
    
    protected function _renderNoStock() {
        $html = 
                '<div class="showcase-content-article-no-stock">'.
                    '- '.lang::trans('no_stock').' -'.
                '</div>';
        
        return $html;
    }
    
    private function _renderAddToShoppingcartArea($article)
    {
        $html = '';
        
        $html .= '<div class="showcase-content-article-addtocart-wrapper">';
                    
        // No stock?
        if (!$this->_anyStock($article))
        {
            $html .= $this->_renderNoStock();
        }
        else
        {
            $html .= '<div class="showcase-content-article-addtocart">';
            if (isset($article->prices) && $article->prices->finalRetailPrice > 0)
            {
                $html .= 
                        '<div id="showcase-content-article-addtocart-article-'.$article->code.'">'.
                            $this->renderAddToShoppingcartWidgets($article).
                        '</div>'.
                    '';
            }
            $html .= '</div>';             
        }
        
        $html .= '</div>';
                
        return $html;
    }
    
    public function renderAddToShoppingcartWidgets($article)
    {
        $html = '';
        $stock_controller = new stock();
        $amounts_to_add = $stock_controller->getAmountToAdd($article);
       
        $html .= 
                '<table class="showcase-content-article-addtocart-table" border="0" cellpadding="0" cellspacing="0">' .
                    '<tr>'.
                '';

        // Spinner (amount)
        $html .= 
                        '<td class="showcase-content-article-addtocart-column showcase-content-article-addtocart-column-amount-stock">'.
                            '<div class="showcase-content-article-addtocart-stock">'.
                        '';
        $stock_enable = !(isset($article->infinityStock) && $article->infinityStock);
//        if ($stock_enable)
//        {
//             $html .= 
//                                '<label class="showcase-content-article-addtocart-stock-label">'.
//                                    'STOCK'.':&nbsp;'.
//                                '</label>'.
//                                '<span class="showcase-content-article-addtocart-stock-value">'.
//                                    $article->stock.
//                                '</span>'.
//                                '';
//        }            
        
        // Title
        $article_title = $this->_article_controller->getTitle($article);
        
        $html .= 
                            '</div>'.
                            '<div class="showcase-content-article-addtocart-amount">'.
                                '<input '.
                                        'class="showcase-content-article-addtocart-amount-spinner" '.
                                        'name="showcase-content-article-addtocart-amount-spinner-name" '.
                                        '_article_code="'.$article->code.'" '.
                                        '_article_title="'.$article_title.'" '.
                                        '_enable="'.$stock_enable.'" '.
                                        '_stock="'.$article->stock.'" '.
                                        '_min="'.$amounts_to_add->minAmountToAdd.'" '.
                                        '_max="'.$amounts_to_add->maxAmountToAdd.'" '.
                                        'value="'.$amounts_to_add->minAmountToAdd.'" '.
                                '>'.
                            '</div>'.
                        '</td>'.
                        '';

        // Button
        $html .= 
                        '<td class="showcase-content-article-addtocart-column showcase-content-article-addtocart-column-button">'.
                            '<button '.
                                'type="button" '.
                                'name="showcase-content-article-addtocart-button-name" '.
                                'class="showcase-content-article-addtocart-button '.
                                       'button '.
                                       'button-addtocart" '.
                                'onClick="addToShoppingcart('.
                                                               '\''.$article->code.'\''.
                                                          ')" '.
                                '_article_code="'.$article->code.'" '.
                                '_disabled="'.(($amounts_to_add->minAmountToAdd <= 0)? true : false).'" '.
                            '>'.
                                '<img '.
                                    'class="showcase-content-article-addtocart-button-img" '.
                                    'src="/modules/ecommerce/frontend/res/img/shoppingcarts/shoppingcart5-white-15x15.png" '.
                                '/>'. 
                                '<div class="showcase-content-article-addtocart-button-text" >'.                 
                                    lang::trans('add').
                                '</div>'.
                            '</button>'.
                        '</td>'.
                        '';

        $html .= 
                    '</tr>'.
                '</table>';
            
        return $html;
    }
    
    private function _getComposedTitle($article)
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
    
    protected function _renderFooter()
    {
        $total_articles = count($this->articles);
        
        if ($total_articles <= 0)
        {
            return '';
        }
        
        $html = '<div id="showcase-content-footer">';
        
        // Total articles
        $html .= $this->_renderTotalArticlesFooter();
        
        // Paging
        $html .= $this->_renderPaging();
        
        // Articles per page combo
        $html .= $this->_renderArticlesPerPageCombo();
        
        $html .= '</div>';
        
        return $html;            
    }
    
    protected function _renderTotalArticlesFooter()
    {
        $total_articles = count($this->articles);
        
        $html =
                '<b>'.
                    lang::trans('total_articles').' '. $total_articles.
                '</b>'.
                '';
        
        return $html;  
    }
    
    protected function _renderPaging()
    {
        if ($this->total_pages === 0)
        {
            return '';
        }
        
        $html = '<div id="showcase-content-footer-paging-wrapper">';
        
        // Previous
        if ($this->current_page > 1)
        {
            $url = $this->_getUrlWithPage($this->current_page - 1);
            $html .= 
                    '<a '.
                        'href="'.$url.'"'.$this->_rel_external.' '.
                        'class="showcase-content-footer-paging-nextandprevious" '.
                    '>'.
                        '<'.
                    '</a>'.
                    '<span class="showcase-content-footer-paging-delimiter">&nbsp;&nbsp;&nbsp;</span>'.
                    '';    
        }
        
        $dots_are_already_drawed = false;
        
        for ($page=1; $page<=$this->total_pages; $page++)
        {
            $draw_dots = false;
            if ($this->total_pages > 10)
            {
                if ($page > 3 && $page <= ($this->total_pages - 3))
                {
                    $draw_dots = true;
                }
            }
            
            if ($page == $this->current_page)
            {
                $html .= 
                        '<span class="showcase-content-footer-paging-active">'.
                            $page.
                        '</span>';
                if ($dots_are_already_drawed && $draw_dots && $page < ($this->total_pages - 3))
                {
                    $html .= 
                            '<span class="showcase-content-footer-paging-delimiter">&nbsp;&nbsp;&nbsp;</span>'.
                            '...';
                    
                }
            }
            else
            {
                if ($draw_dots)
                {
                    if ($dots_are_already_drawed)
                    {
                        continue;
                    }
                    $html .= '...';
                    $dots_are_already_drawed = true;
                }
                else
                {
                    $url = $this->_getUrlWithPage($page);
                    $html .= 
                            '<a '.
                                'href="'.$url.'"'.$this->_rel_external.' '.
                                'class="showcase-content-footer-paging-noactive" '.
                            '>'.
                                $page.
                            '</a>'.
                            '';                  
                }
            }
            $html .= 
                    '<span class="showcase-content-footer-paging-delimiter">&nbsp;&nbsp;&nbsp;</span>'.
                    '';              
        }
        
        // Next
        if ($this->current_page < $this->total_pages)
        {
            $url = $this->_getUrlWithPage($this->current_page + 1);
            $html .= 
                    '<a '.
                        'href="'.$url.'"'.$this->_rel_external.' '.
                        'class="showcase-content-footer-paging-nextandprevious" '.
                    '>'.
                        '>'.
                    '</a>'.
                    '';                
        }    
            
        $html .=
                '</div>'.  
            '';
        
        return $html;
    }
    
    private function _getUrlWithPage($page) {
        
        if ($page > 1)
        {
            $url = url::updateParameters(array('page' => $page), array(), $this->current_url_without_params, $this->current_url_params);
        }
        else
        {
            $url = url::updateParameters(array(), array('page'), $this->current_url_without_params, $this->current_url_params);
        }
        
        return $url;
    }
    
    protected function _renderArticlesPerPageCombo()
    {
        $html = '';
        
        $html .= 
                '<div id="showcase-content-footer-articlesperpage-wrapper">'.
                    '<div id="showcase-content-footer-articlesperpage-text">'.
                        'Articles per pàgina'.
                    '</div>'.
                    '<select '.
                        'id="showcase-content-footer-articlesperpage-combo" '.
//                        'onChange="onChangeArticlesPerPage(\'this.value\')"'.
                        'onChange="onChangeArticlesPerPage()"'.
                    ' >';   
        
        for ($i=1; $i<=4; $i++)
        {
            $value = 15 * $i;
            
            $selected = '';
            if ($value == $this->articles_per_page)
            {
                $selected = 'selected ';
            }            
            $html .= '<option '.$selected.'>'.$value.'</option>';            
        }   
        
        $html .=
                    '</select>'.
                '</div>'.  
            '';
        
        return $html;  
    }

    protected function _renderSearchResult($is_mobile = false)
    {    
        $html = '';
        
        if (!isset($this->search_filter))
        {
            return $html;
        }
        
        // Set vars
        $current_lang = lang::getCurrentLanguage();
        $website = $this->_ecommerce_controller->getWebsite();
        $specialoffers_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'showcase'), array('specialoffers'));
        $brands_url = $this->_ecommerce_controller->getUrl(array($current_lang, lang::trans('url-brands')));
        $personaldata_controller = new personaldata();
        $email = $personaldata_controller->getEmail();
        
        // Start render
        $html .= '<div id="showcase-content-searchresult-wrapper">';
        
        if (empty($this->articles))
        {
            $html .= lang::trans('no_articles_found').' "<b>'.$this->search_filter.'</b>"';
        }        
        
        $html .= 
                '<div class="showcase-content-searchresult-title label-info"'.
                (!empty($this->articles)? ' style="margin-top:0px"' : '').
                '>'.
                    lang::trans('ask_us_if_you_cannot_find').
                '</div>'.
                lang::trans('you_can_contact_us_to_help_you').

                '<table class="showcase-content-searchresult-table" border="0" cellpadding="0" cellspacing="0">'.
                    '<tr>'.
                        '<td class="showcase-content-searchresult-table-column showcase-content-searchresult-table-column-title">'.
                            lang::trans('send_us_message').
                        '</td>';
        if (!$is_mobile)
        {
            $html .= 
                        '<td class="showcase-content-searchresult-table-column showcase-content-searchresult-table-column-title showcase-content-searchresult-table-column-padding-left">'.
                            lang::trans('you_can_also_contact_us').":".
                        '</td>';                    
        }
        $html .= 
                    '</tr>'.
                    '<tr>'.
                        '<td class="showcase-content-searchresult-table-column showcase-content-searchresult-table-column-content">'.
                            
                            '<div class="showcase-content-searchresult-form">'.
                
                                // Text
                                '<div class="label">'.lang::trans('text').'</div>'.
                                '<textarea '.
                                    'id="showcase-content-searchresult-form-text" '.
                                    'class="field" '.
                                '>'.''.'</textarea>'.
                
                                // Title
                                '<div class="label">'.lang::trans('your_email').'</div>'.
                                '<input '.
                                    'id="showcase-content-searchresult-form-email" '.
                                    'class="field" '.
                                    'type="text" '.
                                    'value="'.$email.'" '.
                                '>'.
        
                                // Button
                                '<button '.
                                    'type="button" '.
                                    'id="showcase-content-searchresult-form-button" '.                
                                    'class="'.
                                        'button '.
                                        'button-ordering'.
                                        '" '.
                                    'onclick="showcase.sendSearcherMsg();"'.
                                '>'.
                                    lang::trans('send_message').
                                '</button>'.
                                // Error message
                                '<div id="showcase-content-searchresult-form-error-msg">'.'</div>'.
                            '</div>'.
                
                        '</td>';
        if (!$is_mobile)
        {
            $html .= 
                        '<td class="showcase-content-searchresult-table-column showcase-content-searchresult-table-column-content showcase-content-searchresult-table-column-padding-left">'.
                            '<div class="showcase-content-searchresult-contact">'.
                                '<div id="showcase-content-searchresult-contact-phone-img"></div>'.
                                '<div id="showcase-content-searchresult-contact-phone-number">'.
                                    (isset($website->phone)? $website->phone : '').
                                '</div>'.
                                '<div id="showcase-content-searchresult-contact-email-img"></div>'.
                                '<div id="showcase-content-searchresult-contact-email-text">'.
                                    (isset($website->email)? $website->email : '').
                                '</div>'.  
                            '</div>'.             
                        '</td>';                    
        }
        $html .= 
                    '</tr>'.                
                '</table>'.

                '<div class="showcase-content-searchresult-title label-info">'.
                    lang::trans('search_tips').
                '</div>'.
                '<ul>'.
                    '<li>'.
                        lang::trans('search_for').' '.
                        '<a href="'.$brands_url.'"'.$this->_rel_external.'>'.
                            strtolower(lang::trans('brands')).
                        '</a>'.
                    '</li>'.
                    '<li>'.
                        lang::trans('use_related_words_or_synonyms').
                    '</li>'.
                    '<li>'.
                        lang::trans('move_you_by_categories').
                    '</li>'.
                    '<li>'.
                        lang::trans('you_can_also_consult_our').' '.
                        '<a href="'.$specialoffers_url.'"'.$this->_rel_external.'>'.
                            strtolower(lang::trans('special_offers')).
                        '</a>'.
                    '</li>'.                
            '';
        
        // End render
        $html .=                 
                '</div>';
        
        return $html;
    }
}