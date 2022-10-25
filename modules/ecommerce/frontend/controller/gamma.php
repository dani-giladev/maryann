<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\config\controller\config;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\controller\gamma as ecommerceGamma;

/**
 * Gamma controller
 *
 * @author Dani Gilabert
 * 
 */
class gamma extends ecommerceGamma
{

    public function getTitle($gamma, $forced_lang = null)
    {
        $title = $this->_getText($gamma, 'titles', $forced_lang);
        return (empty($title))? '?' : $title;
    }
    
    public function getDescription($gamma)
    {
        return $this->_getText($gamma, 'descriptions');
    }   

    private function _getText($gamma, $property, $forced_lang = null)
    {
        $lang = (is_null($forced_lang))? (lang::getCurrentLanguage()) : $forced_lang;
        
        if(!isset($gamma->$property) ||
            !isset($gamma->$property->$lang) ||
            empty($gamma->$property->$lang)) 
        {
            if (is_null($forced_lang))
            {
                $default_language =  config::getConfigParam(array("application", "default_language"))->value;
                return $this->_getText($gamma, $property, $default_language);
            }
            return ($property === 'titles')? $gamma->name : '';
        }
        
        return $gamma->$property->$lang;
    }
    
}