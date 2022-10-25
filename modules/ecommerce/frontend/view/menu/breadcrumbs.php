<?php

namespace modules\ecommerce\frontend\view\menu;

// Controllers
use core\device\controller\device;
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\menu\menu as menuView;
use modules\ecommerce\frontend\view\shoppingcart\tooltip;

/**
 * E-commerce frontend breadcrumbs view
 *
 * @author Dani Gilabert
 * 
 */
class breadcrumbs extends menuView
{ 
    public $last_item_text = null;
    public $show_shoppingcart = true;
    public $show_shoppingcart_button = true;
    public $show_ordering_button = true;
    public $webpage = '';
    
    protected $_rel_external;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->_rel_external = device::isMobileVersion()? ' rel="external"' : '';
    }
    
    public function renderBreadcrumbsMenu($html_breadcrumbs)
    {        
        // Start
        $html = 
                '<div id="menu-breadcrumbs">'.
                    '<div id="menu-breadcrumbs-center" class="page-center">'.
                        '<table id="menu-breadcrumbs-table" cellpadding="0" cellspacing="0">'.
                            '<tr>'.
                '';       

        // The breadscrumbs
        $html .= 
                '<td class="menu-breadcrumbs-column-breadcrumb">'.
                    // Home start image
                    '<div id="menu-breadcrumbs-wrapper-start-img-wrapper">'.
                        $this->_renderStartImage().
                    '</div>'.     
                    // Breadcrums
                    '<div id="menu-breadcrumbs-wrapper-text">'.
                        $html_breadcrumbs.
                    '</div>'.    
                '</td>'.
                '';    

        // Shopping cart option menu
        if ($this->show_shoppingcart)
        {
            $shoppingcart_menu_option_view = new tooltip($this->show_shoppingcart_button, $this->show_ordering_button);
            $html .= 
                    '<td class="menu-breadcrumbs-column-shoppingcart">'.
                        $shoppingcart_menu_option_view->renderShoppingcartMenu().
                    '</td>'.
                    '';            
        }
        
        // End
        $html .=           
                            '</tr>'.
                        '</table>'.
                    '</div>'.
                '</div>'.
                ''; 
        
        return $html;
    }

    public function renderCategories($breadcrumbs, $last_item_is_only_text = true)
    {
        $html = $this->_renderStart();
        
        if (!isset($breadcrumbs)) return $html;
        
        $count = count($breadcrumbs);
        foreach ($breadcrumbs as $key => $value) {
            
            $html .= $this->_renderDelimiter();
            
            // Get text
            $text = $this->_getText($value);

            if ($key >= ($count - 1))
            {
                $this->last_item_text = $text;
            }
            
            // Build breadcrumb
            $only_text = false;
            if ($key < ($count - 1) || !$last_item_is_only_text)
            {
                // Get url
                $url = $this->_getUrl($value);
                $breadcrumb = '<a href="'.$url.'"'.$this->_rel_external.'><i>'.$text.'</i></a>';
            }
            else
            {
                // Only text
                $only_text = true;
                $breadcrumb = '<i>'.$text.'</i>';
            }
            $html .= $this->_renderBreadcrumb($breadcrumb, $only_text);
        }          
        
        return $html;   
    }

    public function renderCategoriesAndArticle($breadcrumbs, $article_name)
    {
        $html = $this->renderCategories($breadcrumbs, false);
        $html .= $this->_renderDelimiter();
        
        $html_content = '<div id="menu-breadcrumbs-breadcrumb-article-tooltip-wrapper">'.$article_name.'</div>';
        $_content_tag = '_content="'.htmlentities($html_content).'" ';  
        
        $breadcrumb = 
                '<i '.
                    'id="menu-breadcrumbs-breadcrumb-article-tooltip" '.
                    $_content_tag.
                '>'.
                    $article_name.
                '</i>';
        $html .= $this->_renderBreadcrumb($breadcrumb, true);
        return $html;   
    }
    
    public function renderBreadcrumbs($breadcrumbs)
    {
        $html = $this->_renderStart();
        
        $count = count($breadcrumbs);
        foreach ($breadcrumbs as $key => $values) {
            
            $html .= $this->_renderDelimiter();
            
            // Get text
            $text = $values['text'];

            if ($key >= ($count - 1))
            {
                $this->last_item_text = $text;
            }
            
            // Build breadcrumb
            $only_text = false;
            if (isset($values['url']) && !empty($values['url']))
            {
                // Get href, target, etc
                $breadcrumb = '<a href="'.$values['url'].'"'.$this->_rel_external.'><i>'.$text.'</i></a>';
            }
            else
            {
                // Only text
                $only_text = true;
                $breadcrumb = '<i>'.$text.'</i>';
            }
            $html .= $this->_renderBreadcrumb($breadcrumb, $only_text);            
        }
        
        return $html;
    }
    
    private function _renderStartImage()
    {
        $current_lang = lang::getCurrentLanguage();

        $webpage = ($this->webpage === 'showcase')? 'home' : 'showcase';
        $new_url = $this->_ecommerce_controller->getUrl(array($current_lang, $webpage));
        
        $html = 
                '<a href="'.$new_url.'"'.$this->_rel_external.'>'.
                    '<img id="menu-breadcrumbs-wrapper-start-img" src="/modules/ecommerce/frontend/res/img/home-1-30x25.png" >'.
                '</a>'.
                '';
        
        return $html;
    }
    
    private function _renderStart()
    {
        $current_lang = lang::getCurrentLanguage();

        $webpage = ($this->webpage === 'showcase')? 'home' : 'showcase';
        $new_url = $this->_ecommerce_controller->getUrl(array($current_lang, $webpage));
        
        $html = 
                '<a href="'.$new_url.'"'.$this->_rel_external.' class="menu-breadcrumbs-start-text">'.
                    '<i>'.lang::trans('start').'</i>'.
                '</a>';
        
        return $html;
    }
    
    private function _renderBreadcrumb($breadcrumb, $only_text = false)
    {
        
        $html = 
                '<span '.
                    'class="'.
                        'menu-breadcrumbs-breadcrumb'.
                        (($only_text)? ' menu-breadcrumbs-breadcrumb-only-text' : '').
                        '" >'.
                    $breadcrumb.
                '</span>';
        
        return $html;
    }
    
    private function _renderDelimiter()
    {
        $html = 
                '<span class="menu-breadcrumbs-delimiter" >'.
                    '&nbsp;&nbsp;>&nbsp;&nbsp;'.
                '</span>';
        
        return $html;
    }
    
    public function renderBrandText($brand_name)
    {
        $html = $this->_renderStart();
        
        $html .= $this->_renderDelimiter();
        
        $html .= 
                '<span class="menu-breadcrumbs-brand-text" >'.
                    lang::trans('brand').' '.
                '</span>';
        $html .= 
                '<span class="menu-breadcrumbs-brand-name" >'.
                    strtoupper($brand_name).
                '</span>';
        
        return $html;   
    }
    
    public function renderArticleText($article_name)
    {
        $html = $this->_renderStart();
        
        $html .= $this->_renderDelimiter();
        
        $html .= 
                '<span class="menu-breadcrumbs-brand-text" >'.
                    lang::trans('article').' : '.
                '</span>';
        $html .= 
                '<span class="menu-breadcrumbs-brand-name" >'.
                    $article_name.
                '</span>';
        
        return $html;   
    }
    
    public function renderText($text)
    {
        $html = 
                '<span class="menu-breadcrumbs-text" >'.
                    $text.
                '</span>';
        
        return $html;   
    }
    
}
