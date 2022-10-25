<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\config\controller\config;
use core\helpers\controller\helpers;
use modules\cms\controller\webpage;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\availability;

/**
 * Home controller
 *
 * @author Dani Gilabert
 * 
 */
class home extends ecommerce
{
    protected $_webpage = array();
    protected $_brands = array();
    protected $_gammas = array();
    
    private $_current_lang;
    private $_filemanager_path;
    private $_default_language;

    public function __construct()
    {
        parent::__construct();
        
        $this->_setWebpage();
        
        $this->_setBrands();
        $this->_setGammas();
        
        $this->_current_lang = lang::getCurrentLanguage();
        $this->_filemanager_path = config::getFilemanagerPath();
        $this->_default_language = config::getConfigParam(array("application", "default_language"))->value;
    }
    
    protected function _setWebpage()
    {
        $website = $this->getWebsite();
        $webpage_controller = new webpage();
        $website_model = $webpage_controller->getWebpageByCode('home', $website->delegation, $website->code);
        if (isset($website_model))
        {
            $this->_webpage = $website_model->getStorage();
        }
    }
    
    protected function _getOutstandingArticles()
    {
        $availability = new availability();
        $availability->setBrands($this->_getBrands());
        $articles = $availability->getOutstandingArticles();
        if (empty($articles))
        {
            return array();
        }
        
        $ret = helpers::shuffleObject($articles);
        
        return $ret;
    }
    
    protected function _getNoveltyArticles()
    {
        $availability = new availability();
        $availability->setBrands($this->_getBrands());
        $articles = $availability->getNoveltyArticles();
        if (empty($articles))
        {
            return array();
        }
        
        $ret = helpers::shuffleObject($articles);
        
        return $ret;
    }
    
    protected function _getBrands()
    {
        return $this->_brands;
    }
    
    protected function _getOutstandingBrands()
    {
        $ret = array();
        
        foreach ($this->_brands as $brand)
        {
            if (strlen($brand->name) <= 0)
            {
                continue;
            }
            
            if (
                    !$brand->available || 
                    (isset($brand->visible) && !$brand->visible) || 
                    (isset($brand->empty) && $brand->empty)
            )
            {
                continue;
            }
            
            if (!isset($brand->outstanding) || !$brand->outstanding)
            {
                continue;
            }
            
            if (!isset($brand->image) || empty($brand->image))
            {
                continue;
            }
            
            $ret[] = $brand;
        }
        
        return helpers::sortArrayByField($ret, 'name');
    }
    
    protected function _setBrands()
    {
        $availability = new availability();
        $this->_brands = $availability->getBrands();
    }
    
    protected function _getGammas()
    {
        return $this->_gammas;
    }
    
    protected function _setGammas()
    {
        $availability = new availability();
        $this->_gammas = $availability->getGammas();
    }
    
    protected function _getSlider()
    {
        $ret = array();
        
        if (empty($this->_webpage) || !isset($this->_webpage->slider) || empty($this->_webpage->slider))
        {
            return $ret;
        }
        
        foreach ($this->_webpage->slider as $values)
        {
            if (!$values->available)
            {
                continue;
            }
            
            $src = "/".$this->_filemanager_path."/".$this->_getPropertyValueByCurrentLang($values, 'image');
            $title = $this->_getPropertyValueByCurrentLang($values, 'title');
            $url = $this->_getPropertyValueByCurrentLang($values, 'url');
            
            if (empty($url) && !empty($values->promo))
            {
                $url = $this->getUrl(array($this->_current_lang, 'showcase'), array('promo' => $values->promo));
            }
            
            $ret[] = array(
                'src' => $src,
                'title' => $title,
                'url' => $url,
                'description' => ''
            );
        }
        
        return $ret;
    }
    
    protected function _getBanners()
    {
        $ret = array();

        if (empty($this->_webpage) || !isset($this->_webpage->banners) || empty($this->_webpage->banners))
        {
            return $ret;
        }
        
        foreach ($this->_webpage->banners as $row)
        {
            if (!$row->available || empty($row->columns))
            {
                continue;
            }
            
            $columns = array();
            foreach ($row->columns as $column)
            {
                $src = "/".$this->_filemanager_path."/".$this->_getPropertyValueByCurrentLang($column, 'image');
                $title = $this->_getPropertyValueByCurrentLang($column, 'title');
                $url = $this->_getPropertyValueByCurrentLang($column, 'url');
                $description = '';
                unset($column->image);
                unset($column->title);
                unset($column->url);
                $column->src = $src;
                $column->title = $title;
                
                if (empty($url) && !empty($column->promo))
                {
                    $url = $this->getUrl(array($this->_current_lang, 'showcase'), array('promo' => $column->promo));
                }
                
                $column->url = $url;
                $column->description = $description;
                
                $columns[] = $column;
            }
            
            unset($row->columns);
            $row->columns = $columns;
            
            $ret[] = $row;
        }
        
        return $ret;
    }
    
    private function _getPropertyValueByCurrentLang($values, $property)
    {
        $current_lang = $this->_current_lang;
        
        $ret = $values->$property->$current_lang;
        if (empty($ret))
        {
            $default_language = $this->_default_language;
            $ret = $values->$property->$default_language;
        }
        
        return $ret;
    }
    
}