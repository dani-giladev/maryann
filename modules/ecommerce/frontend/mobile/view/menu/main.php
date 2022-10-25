<?php

namespace modules\ecommerce\frontend\mobile\view\menu;

// Controller
use modules\ecommerce\frontend\controller\lang;

// Views
use modules\ecommerce\frontend\view\menu\main as mainView;

/**
 * E-commerce frontend mobilemain menu view
 *
 * @author Dani Gilabert
 * 
 */
class main extends mainView
{ 
    public $available_langs;
    
    public function renderMainMenu($categories_tree)
    {
        $html = $this->_renderMainMenuPages($categories_tree);
        
        return $html;
    }
    
    private function _renderMainMenuPages($categories_tree)
    {
        $html = '';
        $title = lang::trans("main_menu");
        $counter = 1;
        
        $pages = $this->_renderMainMenuPage($categories_tree, $title, $counter);
        foreach ($pages as $page) {
            $html .= $page;
        }
        
        return $html;
    }

    public function renderMenu($tree)
    {
        $html = '';
        $title = strtoupper('Sub-'.lang::trans('categories'));
        $counter = 1;
        
        $pages = $this->_renderMainMenuPage($tree, $title, $counter, "subcategories");
        foreach ($pages as $page) {
            $html .= $page;
        }
        
        return $html;
    }
    
    private function _renderMainMenuPage($tree, $title, &$counter, $menu_name = 'categories', $url_view_all = null)
    {
        $pages = array();
        $back = lang::trans("back");
        
        // Start page
        $html = '<div data-dom-cache="false" data-role="page" id="menu-'.$menu_name.'-'.$counter.'" class="page">';
            
        // Header
        $html .= <<<HTML
    <div data-role="header">
        <a data-role="button" data-rel="back" data-icon="arrow-l" data-iconpos="left" class="ui-btn-left">
            {$back}
        </a>
        <h1 >{$title}</h1>
    </div>
HTML;
        
        // Content
        $html .= <<<HTML
    <div data-role="content">
        <ul data-role="listview">
HTML;
        
        // Add view all
        if (!is_null($url_view_all))
        {
            $html .= 
                    '<li data-icon="false">'.
                        '<a href="'.$url_view_all.'" rel="external" class="url-external-viewall">'.
                            strtoupper(lang::trans('view_all')).' '.$title.
                        '</a>'.
                    '</li>';
        }
        else
        {
            if ($menu_name === 'categories')
            {
                // First time (Show special menus)
                $special_menus = array_reverse($this->_ecommerce_controller->getSpecialMenuData());
                foreach ($special_menus as $special_menu)
                {
                    $html .= 
                        '<li data-icon="false">'.
                            '<a href="'.$special_menu['url'].'" rel="external" class="url-external">'.
                                mb_strtoupper($special_menu['text']).
                            '</a>'.
                        '</li>';
                }                    
            }        
        }
        
        foreach ($tree as $branch)
        {
            // Is available?
            if (!$branch->_data->available) continue;
        
            // Is empty?
            if (isset($branch->_data->empty) && $branch->_data->empty) continue;
            
            // Get text
            $text = $this->_getText($branch->_data);
        
            // Get url
            $url = $this->_getUrl($branch->_data);

            // Any children?
            $any_children = false;
            if (isset($branch->children) && !empty($branch->children))
            {
                $counter++;
                $original_counter = $counter;
                $children_pages = $this->_renderMainMenuPage($branch->children, $text, $counter, $menu_name, $url);
                if (!empty($children_pages))
                {
                    $any_children = true;
                    $pages = array_merge($pages, $children_pages);
                }
            }
            
            $html .= '<li'.($any_children? '' : ' data-icon="false"').'>';
            if (!$any_children)
            {
                //$html .= '<a href="'.$url.'" rel="external" class="url-external">';
                $html .= '<a href="'.$url.'" rel="external">';
            }
            else
            {
                $html .= '<a href="#menu-'.$menu_name.'-'.$original_counter.'">';
            }
            $html .=  
                            $text.
                        '</a>'.
                    '</li>';            
        }
        
        $html .= <<<HTML
        </ul>
    </div>
HTML;
        
        // End page
        $html .= '</div>'.PHP_EOL;
        
        $pages[] = $html;
        
        return $pages;
    }
    
    public function renderLanguageSelectionPage()
    {
        $back = lang::trans("back");
        $title = lang::trans("select_language");
            
        // Start page
        $html = '<div data-dom-cache="false" data-role="page" id="menu-languages" class="page">';
            
        // Header
        $html .= <<<HTML
    <div data-role="header">
        <a data-role="button" data-rel="back" data-icon="arrow-l" data-iconpos="left" class="ui-btn-left">
            {$back}
        </a>
        <h1 >{$title}</h1>
    </div>
HTML;
        
        // Content
        $html .= <<<HTML
    <div data-role="content">
        <ul data-role="listview" data-inset="true">
HTML;
                         
        foreach ($this->available_langs as $lang_code => $values)
        {
            $html .= 
                    '<li data-lang="'.$lang_code.'">'.
                        '<a href="'.$values['url'].'" rel="external">'.
                            $values['name'].
                        "</a>".                    
                    '</li>';
        }
        
        $html .= <<<HTML
        </ul>
    </div>
HTML;
        
        // End page
        $html .= '</div>'.PHP_EOL;
        
        return $html;
    }
    
}