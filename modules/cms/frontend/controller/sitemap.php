<?php

namespace modules\cms\frontend\controller;

// Controllers
use core\config\controller\config;
use modules\cms\frontend\controller\lang;

// Views
use modules\cms\frontend\view\sitemap as view;

/**
 * CMS frontend sitemap controller
 *
 * @author Dani Gilabert
 * 
 */
class sitemap
{
    protected $_view = null;

    public function __construct($changefreq)
    {
        $this->_view = new view($changefreq);
    }

    public function rebuildSitemap($website) 
    {
        // Check the domain
        if (empty($website->domain))
        {
            echo 'The website domain is empty'.PHP_EOL;
            return false;
        }
        
        echo 'Building sitemap: '.$website->domain.PHP_EOL;
        
        // Get available langs
        $available_langs = lang::getAvailableLanguages($website); 
        if (!isset($available_langs))
        {
            echo "The website doesn't have assigned any language.".PHP_EOL;
            return false;
        }  
        
        // Create the folder if it doesn't exist
        $base_path = config::getConfigParam(array("application", "base_path"))->value;
        $sitemap_path = $base_path.'/'.config::getSitemapPath($website->domain);
        if(!file_exists($sitemap_path))
        {
            \mkdir($sitemap_path, 0755);
        }    

        // Delete all sitemap files
        echo 'Delete all sitemap files...'.PHP_EOL;
        $unlink = array_map('unlink', glob($sitemap_path."/*.xml"));
        if (is_array($unlink))
        {
            foreach ($unlink as $value) {
                if ($value === false)
                {
                    echo " Impossible unlink old files.".PHP_EOL;
                    return false;
                }
            }
        }
        // Building sitemap.xml
        echo 'Building sitemap.xml...'.PHP_EOL;
        $xml = $this->_view->getSitemap($website, $available_langs);
        $file_path = $sitemap_path.'/sitemap.xml';
        file_put_contents($file_path, $xml, LOCK_EX);   
        chmod($file_path, 0777);
        
        // Building sitemap for each language
        echo 'Building sitemap for each language...'.PHP_EOL;
        foreach ($available_langs as $lang_code => $lang_name)
        {
            $controlfile_path = $sitemap_path.'/sitemap-'.$lang_code.'-control.json';
            if (file_exists($controlfile_path))
            {
                $last_control = json_decode(file_get_contents($controlfile_path), true);
            }
            else
            {
                $last_control = array();
            }
                    
            $ret = $this->_view->getSitemapByLanguage($website, $lang_code, $last_control);
            $xml = $ret['xml'];
            $control = $ret['control'];
            
            // Save sitemap
            $file_path = $sitemap_path.'/sitemap-'.$lang_code.'.xml';
            file_put_contents($file_path, $xml, LOCK_EX);   
            chmod($file_path, 0777);            
            
            // Save control
            file_put_contents($controlfile_path, json_encode($control, JSON_PRETTY_PRINT), LOCK_EX);
            chmod($controlfile_path, 0777);    
        }        
        
    }
    
}