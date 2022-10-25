<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\config\controller\config;
use core\url\controller\url;
use core\globals\controller\globals;
use modules\cms\frontend\controller\webpage;
use modules\ecommerce\frontend\controller\session;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\controller\article;
use modules\ecommerce\controller\categories;
use modules\ecommerce\frontend\controller\webpages\error404 as error404Webpage;
use modules\marketing\controller\promo as marketingPromo;
use modules\marketing\controller\articleGroup as marketingArticleGroup;

/**
 * Common controller
 *
 * @author Dani Gilabert
 * 
 */
class ecommerce extends webpage
{
    protected $_website;
    protected $_webpage;
    
    public function getWebsite()
    {
        if (isset($this->_website))
        {
            return $this->_website;
        }
        
        $value = session::getSessionVar('ecommerce-website');
        $this->_website = (isset($value) && !empty($value))? $value : null;
        return $this->_website;
    }
    
    public function setWebsite($value)
    {
        session::setSessionVar('ecommerce-website', $value);
        $this->_website = $value;
    }
    
    public function getWebpage()
    {
        if (isset($this->_webpage))
        {
            return $this->_webpage;
        }
        
        $value = session::getSessionVar('ecommerce-webpage');
        $this->_webpage = (isset($value) && !empty($value))? $value : null;
        return $this->_webpage;
    }
    
    public function setWebpage($value)
    {
        session::setSessionVar('ecommerce-webpage', $value);
        $this->_webpage = $value;
    }
    
    public function parseUrl($splitted_url = array())
    {
        $ret = array();
        
        // No pieces
        if (empty($splitted_url) || !isset($splitted_url[1]))
        {
            $ret['webpage'] = 'home';
            $ret['params'] = array();
            return $ret;
        }        
        
        // Get the second piece
        // The first piece is the language
        $second_piece = lang::getKey(strtolower($splitted_url[1]));
        if ($second_piece !== false)
        {
            $second_piece = strtolower($second_piece);
            if ($second_piece === 'url-articles' || $second_piece === 'url-categories' || $second_piece === 'url-brands')
            {
                // Return at home if there is only 2 pieces
                if (!isset($splitted_url[2]))
                {
                    if ($second_piece === 'url-brands')
                    {
                        $ret['webpage'] = 'brands';
                    }
                    else
                    {
                        $ret['webpage'] = 'home';
                    }
                        
                    $ret['params'] = array();
                    return $ret;
                }
                
                if ($second_piece === 'url-articles')
                {
                    $ret['webpage'] = 'articledetail';
                    $ret['params'] = array('article_url' => $splitted_url[2]);
                    return $ret;                    
                }
                elseif ($second_piece === 'url-categories')
                {
                    $ret['webpage'] = 'showcase';
                    $ret['params'] = array('category' => $splitted_url[2]);
                    return $ret;
                } 
                else
                {
                    $ret['webpage'] = 'showcase';
                    $ret['params'] = array('brand' => $splitted_url[2]);
                    return $ret;
                }               
            }
        }

        $ret['webpage'] = $splitted_url[1];
        if (!isset($splitted_url[2]))
        {
            $ret['params'] = array();
        }
        else
        {
            $ret['params'] = array('undefined' => $splitted_url[2]);
        }
        return $ret;
    }
    
    public function translateCurrentUrl($lang)
    {       
        $params_piece = '';
        $params = url::getParams();
        if (isset($params) && !empty($params))
        {
            $params_piece = '?'.$params;
        }
        
        $path_info = (isset($_SERVER['REDIRECT_URL']))? $_SERVER['REDIRECT_URL'] : '';
        if (strlen($path_info) === 0)
        {
            // No pieces
            $ret = url::getProtocol().url::getServerName().'/'.$lang.$params_piece;
            return $ret;
        }
        
        $path_info = substr($path_info, 1); // Remove the first slash
        $splitted_url = preg_split("/\//", $path_info);
        
        // Only one piece? (only de lang?)
        // The first piece is the language
        if (!isset($splitted_url[1]))
        {
            $ret = url::getProtocol().url::getServerName().'/'.$lang.$params_piece;
            return $ret;
        }
        
        // Get the second piece
        $second_piece = $splitted_url[1];
        $second_piece_key = lang::getKey(strtolower($splitted_url[1]));
        if ($second_piece_key !== false)
        {
            $second_piece_key = strtolower($second_piece_key);
            if ($second_piece_key === 'url-articles' || $second_piece_key === 'url-categories' || $second_piece_key === 'url-brands')
            {
                $second_piece = lang::trans($second_piece_key, $lang);
            }            
        }
        
        // Only two pieces?
        if (!isset($splitted_url[2]))
        {
            $ret = url::getProtocol().url::getServerName().'/'.$lang.'/'.$second_piece.$params_piece;
            return $ret;
        }
        
        // Get the third piece
        $third_piece = $splitted_url[2];
        if ($second_piece_key == 'url-articles' || $second_piece_key === 'url-categories')
        {
            $current_lang = lang::getCurrentLanguage();
            $url_property = 'url'.ucfirst($lang);
            if ($second_piece_key == 'url-articles')
            {
//                $controller = new article();
//                $article = $controller->getArticleByUrl($current_lang, $splitted_url[2]);
                $article = $this->_getArticleByUrl($current_lang, $splitted_url[2]);
                if (!empty($article))
                {
                    $third_piece = $article->$url_property;                
                }                 
            }
            else
            {
//                $controller = new categories();
//                $category = $controller->getCategoryByUrl($current_lang, $splitted_url[2]);
                $category = $this->_getCategoryByUrl($current_lang, $splitted_url[2]);
                if (!empty($category))
                {
                    if (isset($category->$url_property) &&
                        !empty($category->$url_property))
                    {
                        $third_piece = $category->$url_property;
                    }
                    else
                    {
                        $third_piece = $category->code; 
                    }
                }
            }
        } 
        
        $ret = url::getProtocol().url::getServerName().'/'.$lang.'/'.$second_piece.'/'.$third_piece.$params_piece;
        return $ret;
    }
    
    public function getCurrentLanguage()
    {
        return lang::getCurrentLanguage();
    }
    
    public function goToShowcaseWebpage()
    {
        $current_lang = lang::getCurrentLanguage();
        $url = $this->getUrl(array($current_lang, 'showcase'));
        header('Location: '.$url); 
    }
    
    public function goToError404Webpage()
    {
        $error404 = new error404Webpage();
        $error404->init();
    }
    
    public function getSkin()
    {
        if (isset($_REQUEST['skin']))
        {
            $skin = $_REQUEST['skin'];
            $this->setSkin($skin);
            return $skin;
        }
        
        $skin = session::getSessionVar('ecommerce-skin');
        if (isset($skin) && !empty($skin))
        {
            return $skin;
        }
        
        return config::getConfigParam(array("ecommerce", "skin"))->value;
    }
    
    public function setSkin($value)
    {
        session::setSessionVar('ecommerce-skin', $value);
    }
    
    protected function _getPathVersion($is_mobile = false)
    {
        if ($is_mobile)
        {
            return 'modules/ecommerce/frontend/mobile/res/version';
        }
        else
        {
            return 'modules/ecommerce/frontend/res/version';
        }
    }
    
    public function getVersion($is_mobile = false)
    {
        $path = $this->_getPathVersion($is_mobile);
        if (!file_exists($path))
        {
            /*
            $base_path = config::getConfigParam(array("application", "base_path"))->value;
            $path = $base_path.'/'.$path;
            if (!file_exists($path))
            {
                return '';
            }*/
            return '';
        }
        
        return file_get_contents($path);
    }
    
    public function setVersion($value, $is_mobile = false)
    {
        $path = $this->_getPathVersion($is_mobile);
        if (!file_exists($path))
        {
            return;
        }
        return file_put_contents($path, $value);
    }
    
    protected function _getArticleByUrl($current_lang, $url)
    {
        $articles_by_url = globals::getGlobalVar('ecommerce-articles-by-url');
        if (isset($articles_by_url) && isset($articles_by_url[$current_lang]) && isset($articles_by_url[$current_lang][$url]))
        {
            return $articles_by_url[$current_lang][$url];
        }
        
        $controller = new article();
        $article = $controller->getArticleByUrl($current_lang, $url, true, true);        
        
        $articles_by_url[$current_lang][$url] = $article;
        globals::setGlobalVar('ecommerce-articles-by-url', $articles_by_url);
        
        return $article;
    }
    
    protected function _getCategoryByUrl($current_lang, $url)
    {
        $categories_by_url = globals::getGlobalVar('ecommerce-categories-by-url');
        if (isset($categories_by_url) && isset($categories_by_url[$current_lang]) && isset($categories_by_url[$current_lang][$url]))
        {
            return $categories_by_url[$current_lang][$url];
        }
        
        $categories = $this->getCategoriesTree();
        $controller = new categories();
        $category = $controller->getCategoryByUrl($current_lang, $url, true, $categories);
                
        $categories_by_url[$current_lang][$url] = $category;
        globals::setGlobalVar('ecommerce-categories-by-url', $categories_by_url);
        
        return $category;
    }
    
    public function getCategoriesTree()
    {
        $categories = globals::getGlobalVar('ecommerce-categories');
        if (isset($categories) && !empty($categories))
        {
            return $categories;
        }
        
        $controller = new categories();
        $categories = $controller->getCategoriesTree(true);
        globals::setGlobalVar('ecommerce-categories', $categories);
        
        return $categories;
    }
    
    public function getSpecialMenuData()
    {
        $ret = array();
        $current_lang = lang::getCurrentLanguage();
        
        $ret[] = array(
            'text' => lang::trans('brands'),
            'url' => $this->getUrl(array($current_lang, lang::trans('url-brands')))
        );
        
        // ENABLE CHRISTMAS
        /*$ret[] = array(
            'text' => lang::trans('special_christmas'),
            'url' => $this->getUrl(array($current_lang, 'showcase'), array('christmas'))
        );*/
        
        $promos = $this->_getPromos();
        if (isset($promos) && !empty($promos))
        {
            foreach ($promos as $promo)
            {
                if (!$promo->visibleMenu)
                {
                    continue;
                }
                $ret[] = array(
                    'text' => $promo->titles->$current_lang,
                    'url' => $this->getUrl(array($current_lang, 'showcase'), array('promo' => $promo->code))
                );                
            }
        }
        
        $ret[] = array(
            'text' => 'Packs',
            'url' => $this->getUrl(array($current_lang, 'showcase'), array('packs'))
        );
        
        $ret[] = array(
            'text' => lang::trans('special_offers'),
            'url' => $this->getUrl(array($current_lang, 'showcase'), array('specialoffers'))
        );
        
        $ret[] = array(
            'text' => lang::trans('novelties'),
            'url' => $this->getUrl(array($current_lang, 'showcase'), array('novelties'))
        );
        
        return $ret;
    }
    
    protected function _getPromos()
    {
        $promos = globals::getGlobalVar('marketing-promos');
        if (isset($promos))
        {
            return $promos;
        }
        
        $controller = new marketingPromo();
        $promos = $controller->getAvailablePromos(true, true);        
        
        globals::setGlobalVar('marketing-promos', $promos);
        
        return $promos;
    }
    
    protected function _getArticleGroups()
    {
        $articlegroups = globals::getGlobalVar('marketing-articlegroups');
        if (isset($articlegroups))
        {
            return $articlegroups;
        }
        
        $controller = new marketingArticleGroup();
        $articlegroups = $controller->getArticleGroups(true, true);        
        
        globals::setGlobalVar('marketing-articlegroups', $articlegroups);
        
        return $articlegroups;
    }
    
}