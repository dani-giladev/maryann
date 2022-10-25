<?php

namespace modules\ecommerce\frontend\view\menu;

// Controllers
use core\device\controller\device;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\menu\menu as menuController;
use modules\ecommerce\frontend\controller\article;

// Views
use modules\ecommerce\frontend\view\menu\menu as menuView;

/**
 * E-commerce frontend main menu view
 *
 * @author Dani Gilabert
 * 
 */
class main extends menuView
{ 
    protected $_show_shoppingcart_button = true;
    protected $_show_ordering_button = true;
    protected $_outstanding_articles = null;
    
    protected $_menu_controller;
    protected $_article_controller;
    
    private $_firstlevel_category = array();
    private $_max_length_first_level_text = 0;
    
    public function __construct($categories = null,
                                $show_shoppingcart_button = true, 
                                $show_ordering_button = true)
    {
        parent::__construct();
        $this->_show_shoppingcart_button = $show_shoppingcart_button;
        $this->_show_ordering_button = $show_ordering_button;
        
        $this->_menu_controller = new menuController($categories);
        $this->_article_controller = new article();
    }
    
    public function setOutstandingArticles($outstanding_articles)
    {
        $this->_outstanding_articles = $outstanding_articles;
    }
    
    public function renderMainMenu($categories_tree)
    {
        // Start render menus
        $html = 
                '<div id="menu-main">'.
                    '<div id="menu-main-center" class="page-center fadeout">'.
                '';
        
        // Build menus for categories
        $html .= 
                '<table id="menu-main-firstlevel-table" border="0" cellpadding="0" cellspacing="0">'.
                    '<tr>'.
                '';
        
        // Calculate the max length of first level title
        //$this->_setMaxLengthOfFirstLevelText($categories_tree);
        $this->_max_length_first_level_text = 10;
        
        foreach ($categories_tree as $key => $branch) {
            $html .= $this->_renderFirstLevelMenuForCategories($branch);
        }
        $html .=                                 
                    '</tr>'.
                '</table>'.
                '';

        // End render menus
        $html .= 
                    '</div>'.
                '</div>'.
                ''; 
        
        return $html;
    }
    
    private function _setMaxLengthOfFirstLevelText($categories_tree)
    {
        foreach ($categories_tree as $key => $branch) {
            if (!$branch->_data->available) continue;
            $text = $this->_getText($branch->_data);
            $length_text = strlen($text);
            if ($length_text > $this->_max_length_first_level_text)
            {
                $this->_max_length_first_level_text = $length_text;
            }
        }        
    }
    
    private function _fillText($text)
    {
        $fill2right = true;
//        $char = "&nbsp;";
        $char = ".";
        $aux_text = $text; 
        $number_of_chars_to_fill = 0;
        $color = "transparent";
//        $color = "red";
        
        do
        {
            $length_text = strlen($aux_text);
            if ($length_text >= $this->_max_length_first_level_text)
            {
                break;
            }
            
            if ($fill2right)
            {
                $aux_text = $aux_text.$char;
                $number_of_chars_to_fill++;
            }
            else 
            {
                $aux_text = $char.$aux_text;
            }
            $fill2right = !$fill2right;            
            
        } while (true);
        
        $tag = '<span style="color:'.$color.'">'.str_repeat($char, $number_of_chars_to_fill).'</span>';
        $ret = $tag.$text.$tag;
        
        return $ret;
    }
    
    private function _renderFirstLevelMenuForCategories($branch)
    {
        $html = '';
        
        // Is available?
        if (!$branch->_data->available) return $html;
        
        // Is empty?
        if (isset($branch->_data->empty) && $branch->_data->empty) return $html;

        // Get text
        $text = $this->_getText($branch->_data);
        $text = $this->_fillText($text);
        
        // Get url
        $url = $this->_getUrl($branch->_data);
        
        // Set the fisrt level parent category
        $this->_firstlevel_category = array(
            'text' => $text,
            'url' => $url
        );
        
        // Second level?
        $_content_tag = '';
        if (isset($branch->children) && !empty($branch->children))
        {
            $html_content = $this->_renderSecondLevelMenuForCategories($branch->_data->code);
            $_content_tag = '_content="'.htmlentities($html_content).'" ';            
        }  

        $html .= '<td class="menu-main-firstlevel-column">';   
            
        $html .= 
                '<a'.((device::isTouchDevice() && !empty($_content_tag))? '' : (' href="'.$url.'"')).' '.
                    'class="'.
                        'menu-main-firstlevel'.
//                        ($branch->_data->code === 'med'? 'menu-main-firstlevel-special ' : '').
                    '" '.
                    $_content_tag.
                    '_is_for_categories="true" '.
                    '_has_tooltip="true" '.
                '>'.
                    '<div class="menu-main-firstlevel-text'.
                        ((device::isTouchDevice() && !empty($_content_tag))? ' menu-main-firstlevel-touch ' : ' ').
                    '">'.
                        $text.
                    '</div>'.
                '</a>'.
                '';   
        
        $html .= '</td>';   
            
        return $html;     
    }

    private function _renderSecondLevelMenuForCategories($category)
    {
        // Start tooltip
        $html =  '<div class="menu-main-tooltip scrollable">';

        // Add scripts
        $html .=
                '<script type="text/javascript">'.
                    '$("#menu-main-tooltip-column-subcategories").menu();'.
                '</script>'.
                ''; 
        
        // Get html of outstanding articles
        $outstanding_articles = array();
        if (isset($this->_outstanding_articles) && !empty($this->_outstanding_articles))
        {
            foreach ($this->_outstanding_articles as $article)
            {
                if (count($outstanding_articles) > 6)
                {
                    break;
                }
                $categories = $article->categories;
                $pos = strpos($categories, $category);
                if ($pos === false)
                {
                    continue;
                }
                
                $outstanding_articles[] = $article;
            }
        }
        
        // Title
        $html .= 
                '<table class="menu-main-tooltip-table title" border="0" cellpadding="0" cellspacing="0">'.
                    '<tr>'.
                        '<td class="menu-main-tooltip-table-column menu-main-tooltip-table-column-categories">'.
                            strtoupper(lang::trans('categories')).
                        '</td>'.
                '';
        if (!empty($outstanding_articles))
        {
            $html .= 
                        '<td class="menu-main-tooltip-table-column menu-main-tooltip-table-column-specialoffers">'.
                            strtoupper(lang::trans('special_offers')).
                        '</td>'.
                    '';            
        }
        $html .= 
                    '</tr>'.
                '</table>'.
                '';
        
        $html .= 
                '<table class="menu-main-tooltip-table" border="0" cellpadding="0" cellspacing="0">'.
                    '<tr>'.
                '';
        
        // Categories
        $html .= 
                        '<td class="menu-main-tooltip-table-column menu-main-tooltip-table-column-categories">'.
                '';
        
        if (device::isTouchDevice())
        {
            $html .= 
                    '<a href="'.$this->_firstlevel_category['url'].'" class="menu-main-tooltip-touch-view-all-firstlevel-category">'.
                        strtoupper(lang::trans('view_all')).' '.$this->_firstlevel_category['text'].
                        '<div class="menu-main-tooltip-touch-view-all-firstlevel-category-delimiter"></div>'.
                    '</a>'.
                    '';            
        }
        
        $html .= 
                            '<ul id="menu-main-tooltip-column-subcategories">'.
                                $this->_menu_controller->renderArticleSubcategoriesMenu($category).
                            '</ul>'.
                        '</td>'.
            '';
        
        // Special offers
        if (!empty($outstanding_articles))
        {
            $html .= 
                        '<td class="menu-main-tooltip-table-column menu-main-tooltip-table-column-specialoffers">'.
                            '<table class="menu-main-tooltip-specialoffers-table" border="0" cellpadding="0" cellspacing="0">'.
                                          
                    '';
            foreach ($outstanding_articles as $article)
            {
                // Build article detail url
                $article_detail_url = $this->_article_controller->getArticleUrl($article);                
                $article_title = $this->_article_controller->getTitle($article);
                $article_display = $this->_article_controller->getDisplay($article);
                if (!empty($article_display))
                {
                    $article_title .= ' - '.$article_display;
                }
                $html .= 
                                '<tr>'.       
                                    '<td class="menu-main-tooltip-specialoffers-column-delimiter">'.
                                    '</td>'.  
                                    '<td class="menu-main-tooltip-specialoffers-column-article-text">'.
                                        '<a href="'.$article_detail_url.'">'.
                                            $article_title.
                                        '</a>'.
                                    '</td>'.
                                '</tr>'.
                    '';               
            }
            
            // Image of last article
            $image_path = '';
            $images = $this->_article_controller->getImages($article, false);
            if (!empty($images))
            {
                $image_path = $images[0];
            }   
            $html .= 
                    '<tr>'.
                        '<td class="menu-main-tooltip-specialoffers-column-delimiter">'.
                        '</td>'.                      
                        '<td align="center">'.
                            '<a href="'.$article_detail_url.'">'.
                                '<img '.
                                    'class="menu-main-tooltip-specialoffers-column-article-img" '.
                                    'src="'.$image_path.'" '.           
                                '/>'.
                            '</a>'.                     
                        '</td>'.
                    '</tr>';  
            
            $html .=                
                            '</table>'.                    
                        '</td>'.
                    '';            
        }            
        
        $html .= 
                    '</tr>'.
                '</table>'.
                '';        
        
        
        // End tooltip
        $html .= '</div>';
        
        return $html;     
    }

}