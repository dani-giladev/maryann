<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\config\controller\config;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\controller\brand as ecommerceBrand;

/**
 * Brand controller
 *
 * @author Dani Gilabert
 * 
 */
class brand extends ecommerceBrand
{

    public function getDescription($brand)
    {
        return $this->_getText($brand, 'descriptions');
    }   

    private function _getText($brand, $property, $forced_lang = null)
    {
        $lang = (is_null($forced_lang))? (lang::getCurrentLanguage()) : $forced_lang;
        
        if(!isset($brand->$property) ||
            !isset($brand->$property->$lang) ||
            empty($brand->$property->$lang)) 
        {
            if (is_null($forced_lang))
            {
                $default_language =  config::getConfigParam(array("application", "default_language"))->value;
                return $this->_getText($brand, $property, $default_language);
            }
            return '';
        }
        
        return $brand->$property->$lang;
    } 
    
    public function getBrandCode($brand)
    {
        if ((isset($brand->visible) && !$brand->visible))
        {
            return $brand->laboratory;
        }
        else
        {
            return $brand->code;
        }
    } 
    
    public function getBrandName($brand)
    {
        if ((isset($brand->visible) && !$brand->visible))
        {
            return $brand->laboratoryName;
        }
        else
        {
            return $brand->name;
        }
    }
    
}