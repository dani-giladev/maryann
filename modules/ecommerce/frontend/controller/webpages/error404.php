<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\frontend\controller\brand;
use modules\seo\controller\url as seoUrl;

// Views
use modules\ecommerce\frontend\view\error404 as view;

/**
 * Error 404 webpage
 *
 * @author Dani Gilabert
 * 
 */
class error404 extends ecommerce
{
    protected $_view;
    private $_action;
    private $_current_lang;

    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
    }
    
    public function init()
    {
        $url = (isset($_SERVER['REDIRECT_URL']))? $_SERVER['REDIRECT_URL'] : '';
        $this->_current_lang = lang::getCurrentLanguage();
        
        $this->_action = '';
        if (!empty($url))
        {
            $seo_url_controller = new seoUrl();
            $url_doc = $seo_url_controller->getUrl($url);
            if (!empty($url_doc))
            {
                $this->_action = $url_doc->action;
                $use_action = $url_doc->useAction;
                if ($this->_action === 'redirection')
                {
                    if ($use_action === "redirect2Url")
                    {
                        $redirect_url = $this->getUrl().$url_doc->$use_action;
                    }
                    elseif ($use_action === "redirect2Article")
                    {
                        $article_code = $url_doc->$use_action;
                        $article_controller = new article();
                        $article = $article_controller->getArticleByCode($article_code, true);
                        if (!isset($article))
                        {
                            $this->_action = '';
                        }
                        else
                        {
                            $redirect_url = $article_controller->getArticleUrl($article);
                        }
                    }
                    elseif ($use_action === "redirect2Category")
                    {
                        $category_code = $url_doc->$use_action;
                        $url_by_category_code = $this->_getUrlByCategoryCode($category_code);
                        if (empty($url_by_category_code))
                        {
                            $this->_action = '';
                        }
                        else
                        {
                            $redirect_url = $url_by_category_code;
                        }
                    }
                    elseif ($use_action === "redirect2Brand")
                    {
                        $brand_code = $url_doc->$use_action;
                        $brand_controller = new brand();
                        $brand = $brand_controller->getBrandByCode($brand_code, true);
                        if (!isset($brand))
                        {
                            $this->_action = '';
                        }
                        else
                        {
                            $redirect_url = $this->getUrl(array($this->_current_lang, lang::trans('url-brands'), $brand_code));
                        }
                    }
                    else
                    {
                        $this->_action = '';
                    }
                }
            }
        }
        
        $server_protocol = $_SERVER["SERVER_PROTOCOL"];
        if ($this->_action === 'blocking')
        {
            header($server_protocol." 200 OK"); // HTTP/1.1 200 OK
            //$date = date('l, j F Y h:i:s \G\M\T');
            //$date = gmdate('l, j F Y h:i:s \G\M\T', time());
            //header('Date: '.$date); // Date: Tue, 25 May 2010 21:42:43 GMT
            header("X-Robots-Tag: noindex, nofollow", true); // X-Robots-Tag: noindex    
        }
        elseif ($this->_action === 'redirection')
        {
            header('Location: '.$redirect_url, true, 301);
            return;
        }
        else
        {
            header($server_protocol." 404 Not Found");
            header("Status: 404 Not Found");
            $_SERVER['REDIRECT_STATUS'] = "404";
        }

        $this->renderPage();
    }   
    
    private function _getUrlByCategoryCode($category_code)
    {
        $ret = '';
        
        if (!isset($category_code) || empty($category_code))
        {
            return $ret;
        }
        
        $categories_tree = $this->getCategoriesTree();
        if (!isset($categories_tree)) return $ret;
        if (!isset($categories_tree->categories)) return $ret;
        $categories = $categories_tree->categories;
        
        foreach ($categories as $key => $value)
        {
            if ($category_code === $key)
            {
                $url_property = 'url'.ucfirst($this->_current_lang);
                if (isset($value->$url_property) && 
                    !empty($value->$url_property))
                {
                    $url_value = $value->$url_property;
                    $ret = $this->getUrl(array($this->_current_lang, lang::trans('url-categories', $this->_current_lang), $url_value));
                }                
                break;
            }
        }

        return $ret;
    }  
    
    protected function _getTitle()
    {
        $website = $this->getWebsite();
        $action_text = empty($this->_action)? "" : (" ($this->_action)");
        return $website->name.' - '.lang::trans('error_404_title').$action_text;
    }           
    
    protected function _getDescription()
    {
        return lang::trans('error_404_description');
    }           
    
    protected function _getKeywords()
    {
        return '';
    }           
    
    protected function _getRobots()
    {
        return 'noindex, nofollow';
    }
    
    protected function _renderMenu()
    {
        $html = '';
        
        // Render main menu
        $main_menu = new mainMenu();
        $html .= $main_menu->renderMainMenu();
        
        // Render breadcrumbs menu
        $breadcrumbs = array(
                                array('text' => lang::trans('error_404_description'), 'url' => '')
                            );        
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'breadcrumbs' => $breadcrumbs
        ));         
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    }
    
    protected function _renderContent()
    {
        $html = '';
        
        $html .= $this->_view->renderContent();
        
        return $html;
    }
    
}