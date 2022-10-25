<?php

namespace modules\cms\frontend\controller;

// Controllers
use core\config\controller\config;
use core\url\controller\url;
use core\device\controller\device;
use core\debug\controller\debug;
use modules\cms\controller\cms;
use modules\cms\frontend\controller\lang;

/**
 * CMS frontend webpage controller
 *
 * @author Dani Gilabert
 * 
 */
class webpage extends cms
{
    protected $_available_langs = array();
    protected $_current_url_without_params = null;
    protected $_current_url_params = null;
    
    public function renderPage()
    {
        // Set current url
        $this->_setCurrentUrlWithoutParams(url::getCurrentUrlWithoutParams());
        $this->_setCurrentUrlParams(url::getParams(true));
        
        // Start rendering
        echo $this->_renderStartPage();
        
        // Head
        echo $this->_view->renderStartHead();
        echo $this->_renderHead();
        echo $this->_view->renderEndHead();
        
        // Start body
        echo $this->_view->renderStartBody();
        
        // Body content
        if (device::isMobileVersion())
        {
           echo $this->_renderBodyForMobileVersion();
        }
        else
        {
           echo $this->_renderBodyForClassicVersion();
        }
        
        // End body
        echo $this->_view->renderEndBody();
        
        // End rendering
        echo $this->_view->renderEndPage();
    }    
    
    protected function _renderStartPage()
    {
        $current_lang = lang::getCurrentLanguage();  
        return $this->_view->renderStartPage($current_lang);
    }
    
    protected function _renderHead()
    {
        $title = $this->_getTitle();
        $description = $this->_getDescription();
        $keywords = $this->_getKeywords();
        $robots = $this->_getRobots();
        $favicon_path = $this->_getFaviconPath();
        $copyright = $this->_getCopyRight();
        $hreflangs = $this->_getAlternativeLanguages();
        $canonical_url = $this->_getCanonicalUrl();
        $pagination = $this->_getPagination();
        $version = $this->getVersion();
        
        return $this->_view->renderHead($title, $description, $keywords, $robots, $favicon_path, $copyright, $hreflangs, $canonical_url, $pagination, $version);
    }    
    
    protected function _renderHeader()
    {
        $website = $this->getWebsite();
        $available_langs = $this->_getAvailableLanguages();
        return $this->_view->renderHeader($website, $available_langs);
    }     
    
    protected function _renderBodyForClassicVersion()
    {
        // Header
        echo $this->_view->renderStartHeader();
        echo $this->_renderHeader();
        echo $this->_view->renderEndHeader();

        // Nav
        echo $this->_view->renderStartMenu();
        echo $this->_renderMenu();      
        echo $this->_view->renderEndMenu();
        
        // Content
        echo $this->_view->renderStartContentWrapper();
        echo $this->_view->renderStartContent();
        echo $this->_renderContent();
        echo $this->_view->renderEndContent();
        echo $this->_view->renderEndContentWrapper();

        // Additional extra content
        echo $this->_renderAdditionalContent();

        // Footer
        echo $this->_view->renderStartFooter();
        echo $this->_renderFooter(); 
        echo $this->_view->renderEndFooter();

        // Render debug result
        debug::render();        
    }   
    
    protected function _renderBodyForMobileVersion()
    {
        // Start page
        echo $this->_view->renderStartHeader();
        echo $this->_renderHeader();

        // Breadcrumbs
        echo $this->_renderBreadcrumbs();

        // Content
        echo $this->_renderContent();

        // Additional extra content
        echo $this->_renderAdditionalContent();

        // Footer
        echo $this->_renderFooter(); 

        // Render debug result
        debug::render();

        // End page
        echo $this->_view->renderEndHeader();

        // Nav (more additional pages)
         echo $this->_renderMenu();        
    }
    
    protected function _renderMenu()
    {
        return $this->_view->renderMenu();
    }       
    
    protected function _renderBreadcrumbs()
    {
        return '';
    } 
    
    protected function _renderFooter()
    {
        $website = $this->getWebsite();
        
        return $this->_view->renderFooter($website); 
    }
    
    protected function _getTitle()
    {
        $website = $this->getWebsite();
        $current_lang = lang::getCurrentLanguage();
        
        $ret = $website->name;
        
        if (isset($website->titles->$current_lang))
        {
            $ret .= ' - '.$website->titles->$current_lang;
        }  
        
        return $ret;
    }           
    
    protected function _getDescription()
    {
        $website = $this->getWebsite();
        $current_lang = lang::getCurrentLanguage();
        return (isset($website->descriptions->$current_lang)) ? $website->descriptions->$current_lang : '';
    }
    
    protected function _getKeywords()
    {
        $website = $this->getWebsite();
        $current_lang = lang::getCurrentLanguage();
        return (isset($website->keywords->$current_lang)) ? $website->keywords->$current_lang : '';
    }        
    
    protected function _getRobots()
    {
        $website = $this->getWebsite();
        return $website->robots;
    }     
    
    protected function _getFaviconPath()
    {
        return '/'.config::getProjectPath().'/ico/favicon.ico';
    }    
    
    protected function _getCopyRight()
    {
        $website = $this->getWebsite();
        return $website->name.' '.$website->domain;
    }
    
    protected function _renderAdditionalContent()
    {
        return '';
    }
    
    protected function _getCanonicalUrl()
    {
        return '';
    }
    
    protected function _getPagination()
    {
        return '';
    }
    
    public function getVersion()
    {
        return 1;
    }
    
    public function getUrl($url_pieces = array(), $params = array())
    {
        $url = url::getProtocol().url::getServerName();
        
        if (!empty($url_pieces))
        {
            foreach ($url_pieces as $value) {
                $url .= '/'.$value;
            }            
        }
        
        if (!empty($params))
        {
            $url .= '?';
            $first_time = true;
            foreach ($params as $key => $value) {
                if (!$first_time)
                {
                    $url .= '&';
                }
                $first_time = false;
                if (is_numeric($key))
                {
                    $url .= $value;
                }
                else
                {
                    $url .= $key.'='.$value;
                }
                
            }            
        }        
        
        return $url;
    }
    
    public function getLogoPath($website)
    {
        $logo_path= '';
        
        if (isset($website->logo) && !empty($website->logo))
        {
            $filemanager_path = config::getFilemanagerPath();
            $logo_path = '/'.$filemanager_path.'/'.$website->logo;
        }  
        
        return $logo_path;
    }
    
    protected function _getAvailableLanguages()
    {
        if (!empty($this->_available_langs))
        {
            return $this->_available_langs;
        }
        
        $website = $this->getWebsite();
        $langs = lang::getAvailableLanguages($website);
        
        foreach ($langs as $lang_code => $lang_name) {
            $url = $this->translateCurrentUrl($lang_code);
            $this->_available_langs[$lang_code]['code'] = $lang_code;
            $this->_available_langs[$lang_code]['name'] = $lang_name;
            $this->_available_langs[$lang_code]['url'] = $url;
        }
        
        return $this->_available_langs;
    }
    
    protected function _getAlternativeLanguages()
    {
        $ret = array();
        $website = $this->getWebsite();
        $available_langs = $this->_getAvailableLanguages();
        $hreflangs = lang::getAlternativeLanguages($website);
        
        foreach ($hreflangs as $lang_code => $lang_name) {
            $ret[$lang_code] = $available_langs[$lang_code];
        }
        
        return $ret;
    }
    
    protected function _getCurrentUrlWithoutParams()
    {
        if (isset($this->_current_url_without_params)) return $this->_current_url_without_params;
        $value = session::getSessionVar('cms-currenturlwithoutparams');
        return (isset($value) && !empty($value))? $value : '';
    }
    
    protected function _setCurrentUrlWithoutParams($value)
    {
        session::setSessionVar('cms-currenturlwithoutparams', $value);
        $this->_current_url_without_params = $value;
    }
    
    protected function _getCurrentUrlParams()
    {
        if (isset($this->_current_url_params)) return $this->_current_url_params;
        $value = session::getSessionVar('cms-currenturlparams');
        return (isset($value) && !empty($value))? $value : array();
    }
    
    protected function _setCurrentUrlParams($value)
    {
        session::setSessionVar('cms-currenturlparams', $value);
        $this->_current_url_params = $value;
    }
    
}