<?php

namespace modules\cms\frontend\controller;

// Controllers
use core\device\controller\device;
use modules\cms\controller\cms;
use modules\cms\frontend\controller\lang;

/**
 * CMS frontend builder controller
 *
 * @author Dani Gilabert
 * 
 */
class builder extends cms
{
    private $_data = null;
    private $_website = null;
    private $_base_controller = null;
    private $_splitted_url = array();
    private $_parsed_url = array();
    private $_lang = null;
    private $_webpage = null;
    private $_method = 'init';
    private $_controller_path = null;
    private $_webpage_controller = null;
    private $_errors = array();
    
    public function init($data)
    {
        // The data
        $this->_data = $data;
        
        // The website
        $this->_setWebsite();
        
        // Split url (PATH INFO)
        $this->_splitUrl();
        
        // The lang
        $this->_setLang();
        
        // Parse url (get the webpage and url key)
        $this->_parseUrl();
        
        // Check webpage
        $this->_checkWebpage();
        
        // Render webpage
        $this->_renderWebpage();
    }
    
    private function _setWebsite()
    {
        $this->_website = $this->_data->website;
        if(!$this->_website->available)
        {
            die("The public website is not available.");
        }
        
        $controller_path = 'modules\\'.$this->_website->websiteType.'\frontend\controller\\'.$this->_website->websiteType;
        $this->_base_controller = new $controller_path;        
        $this->_base_controller->setWebsite($this->_website);
    }
    
    private function _splitUrl()
    {
        $path_info = (isset($_SERVER['REDIRECT_URL']))? $_SERVER['REDIRECT_URL'] : '';
        if (strlen($path_info) > 1)
        {
            $path_info = substr($path_info, 1); // Remove the first slash
            $this->_splitted_url = preg_split("/\//", $path_info);
            if (count($this->_splitted_url) > 3)
            {
                $this->_addError("The url is invalid. Too many paths.");
            }
        }          
    }
    
    private function _setLang()
    {
        $available_langs = lang::getAvailableLanguages($this->_website); 
        if (!isset($available_langs))
        {
            die("The website doesn't have assigned any language.");
        }
        lang::setAvailableLanguages($available_langs); 
        
        if (!empty($this->_splitted_url))
        {
            $lang = $this->_splitted_url[0];
            if (!array_key_exists($lang, $available_langs))
            {
                $this->_addError("The language <i>".$lang."</i> is not available.");
                $lang = lang::getCurrentLanguage($this->_website);
            }            
        }            
        else
        {
            $lang = lang::getCurrentLanguage($this->_website);
        }
        
        if (!array_key_exists($lang, $available_langs))
        {
            die("The language <i>".$lang."</i> is not available.");
        }
        
        $this->_lang = $lang;
        lang::setCurrentLanguage($this->_lang);
    }
    
    private function _parseUrl()
    {
        $this->_parsed_url = $this->_base_controller->parseUrl($this->_splitted_url);
        
        if (!empty($this->_parsed_url['params']))
        {
            foreach ($this->_parsed_url['params'] as $key => $value) {
                $this->_data->$key = $value;
            }
        }
    }
    
    private function _checkWebpage()
    {
        if (empty($this->_parsed_url))
        {
            $this->_addError("The webpage doesn't exist.");
        }
        
        if ($this->_anyErrors())
        {
            $this->_webpage = "error404";
            $this->_webpage_controller = $this->_getWebpageController();            
        }
        else
        {
            $this->_webpage = $this->_parsed_url['webpage'];
            $this->_webpage_controller = $this->_getWebpageController();
            if ($this->_webpage_controller === false)
            {    
                $this->_webpage = "error404";
                $this->_webpage_controller = $this->_getWebpageController();         
            }             
        }
        
        if ($this->_webpage_controller === false)
        {    
            die("Error: Method <i>".$this->_getMethod()."</i> doesn't exist in ".$this->_getControllerPath());        
        }         
         
        // Set webpage
        $this->_base_controller->setWebpage($this->_webpage);
    }
    
    private function _renderWebpage()
    {
        // Bootstrap in order to render the webpage
        if ($this->_webpage_controller === false)
        {    
            die("Error: Method <i>".$this->_getMethod()."</i> doesn't exist in ".$this->_getControllerPath());        
        }         
        
        // Render webpage
        $method = $this->_getMethod();
        $this->_webpage_controller->$method($this->_data);        
    }
    
    private function _addError($error)
    {
        array_push($this->_errors, $error);
    }
    
    private function _anyErrors()
    {
        return (!empty($this->_errors));
    }
    
    private function _getWebpageController()
    {
        if (device::isMobileVersion())
        {
            $controller_path = 'modules\\'.$this->_website->websiteType.'\frontend\mobile\controller\\'.$this->_webpage;
            $this->_setControllerPath($controller_path);
            $method = $this->_getMethod();
            if (method_exists($controller_path, $method))
            {    
                return new $controller_path;            
            } 
            device::setIsMobileVersion(false);
        }
        
        $controller_path = 'modules\\'.$this->_website->websiteType.'\frontend\controller\webpages\\'.$this->_webpage;
        $this->_setControllerPath($controller_path);
        $method = $this->_getMethod();
        if (method_exists($controller_path, $method))
        {    
            return new $controller_path;            
        }        
        
        return false;
    }
    
    private function _getMethod()
    {
        return $this->_method;
    }
    
    private function _setMethod($method)
    {
        $this->_method = $method;
    }
    
    private function _getControllerPath()
    {
        return $this->_controller_path;
    }
    
    private function _setControllerPath($path)
    {
        $this->_controller_path = $path;
    }
}