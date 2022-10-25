<?php

namespace modules\cms\frontend\controller;

// Controllers
use core\config\controller\config as config;
use core\frontend\controller\lang as coreFrontendLang;
use modules\admin\controller\language as adminLang;
use modules\cms\frontend\controller\session;

/**
 * CMS frontend lang controller
 *
 * @author Dani Gilabert
 * 
 */
class lang extends coreFrontendLang
{
    protected static $_current_lang = null;
    
    protected static function _getLangPath($lang)
    {
        $lang_path = 'modules/cms/frontend/res/lang/'.$lang.'.php';
        return $lang_path;
    }
    
    public static function trans($id, $lang = null)
    {
        if (is_null($lang))
        {
            $lang = self::getCurrentLanguage();
        }
        
        return self::_trans($id, $lang);
    }
    
    public static function getKey($value, $lang = null)
    {
        if (is_null($lang))
        {
            $lang = self::getCurrentLanguage();
        }
        
        return self::_getKey($value, $lang);
    }
    
    public static function getCurrentLanguage($website = null)
    {
        if (isset(self::$_current_lang) && !empty(self::$_current_lang))
        {
            return self::$_current_lang;
        }
        
        $session_lang = session::getSessionVar('cms-lang');
        if (isset($session_lang) && !empty($session_lang))
        {
            self::$_current_lang = $session_lang;
            return $session_lang;
        }

        $available_languages = self::getAvailableLanguages($website);
        if (isset($available_languages))
        {
            $browser_language = self::getBrowserDefaultLanguage();
            if (isset($browser_language) && 
                isset($available_languages[$browser_language]))
            {
                $ret = $browser_language;
            }
            else
            {
                // Return the first key of $available_languages
                $ret = current(array_keys($available_languages));
            }  
            self::setCurrentLanguage($ret);
            return $ret;
        }
        
        $ret = config::getConfigParam(array("cms", "default_language"))->value;      
        self::setCurrentLanguage($ret);    
        return $ret;
    }
    
    public static function setCurrentLanguage($lang)
    {
        self::$_current_lang = $lang;
        session::setSessionVar('cms-lang', $lang);
    }
    
    public static function getAvailableLanguages($website)
    {
        $ret = null;
        
        // Get session langs
        $available_langs = session::getSessionVar('cms-available-langs');
        if(isset($available_langs) && !empty($available_langs))
        {
            return $available_langs;
        }
        
        if (!isset($website)) return $ret;
        
        // Get available languages
        $lang_controller = new adminLang();
        $available_languages = $lang_controller->getLanguages(true);
        
        $cms_lang_pieces = explode('|', $website->languages);
        foreach ($cms_lang_pieces as $cms_lang)
        {
            foreach ($available_languages as $lang_values)
            {
                if ($lang_values->code === $cms_lang)
                {
                    $ret[$cms_lang] = $lang_values->name;
                    break;
                }
            }
        }
        
        return $ret;
    }
    
    public static function setAvailableLanguages($available_langs)
    {
        session::setSessionVar('cms-available-langs', $available_langs);
    }
    
    public static function isCurrentLanguage($lang, $website = null)
    {
        $current_lang = self::getCurrentLanguage($website);
        return ($lang == $current_lang);
    }
    
    public static function getAlternativeLanguages($website = null)
    {
        $ret = array();
        
        $current_lang = self::getCurrentLanguage();
        
        $available_languages = self::getAvailableLanguages($website);
        foreach ($available_languages as $lang_code => $values) {
            if ($lang_code === $current_lang)
            {
                continue;
            }
            $ret[$lang_code] = $values;
        }
        
        return $ret;
    }
}
