<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\config\controller\config;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\controller\articleProperty as ecommerceArticleProperty;

/**
 * Article property controller
 *
 * @author Dani Gilabert
 * 
 */
class articleProperty extends ecommerceArticleProperty
{

    public function getText($property, $forced_lang = null)
    {
        $lang = (is_null($forced_lang))? (lang::getCurrentLanguage()) : $forced_lang;
        
        if(!isset($property) ||
            !isset($property->$lang) ||
            empty($property->$lang)) 
        {
            if (is_null($forced_lang))
            {
                $default_language =  config::getConfigParam(array("application", "default_language"))->value;
                return $this->getText($property, $default_language);
            }
            return '';
        }
        
        return $property->$lang;
    }
    
}