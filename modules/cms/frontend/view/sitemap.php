<?php

namespace modules\cms\frontend\view;

// Controllers
use core\config\controller\config;

/**
 * CMS frontend sitemap view
 *
 * @author Dani Gilabert
 * 
 */
class sitemap
{
    protected $_protocol;
    protected $_changefreq;
    
    public function __construct($changefreq)
    {
        $url = config::getConfigParam(array("application", "url"))->value;
        $url_pieces = explode('://', $url);
        $this->_protocol = $url_pieces[0].'://';
        $this->_changefreq = $changefreq;
    }
    
    public function getSitemap($website, $available_langs)
    {
        $domain = $this->_protocol.$website->domain;
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($available_langs as $lang_code => $lang_name)
        {
            $xml .= 
                    '<sitemap>'.
                        '<loc>'.
                            $domain.'/sitemap-'.$lang_code.'.xml'.
                        '</loc>'.
                        $this->_getLastmod().
                    '</sitemap>'.
                    '';
        }
        $xml .= '</sitemapindex>';
        
        return $xml;
    }
    
    protected function _getPriority($priority)
    {
        $xml = 
                '<priority>'.
                    $priority.
                '</priority>';       
        return $xml;
    }
    
    protected function _getLastmod($date = null)
    {
        if (is_null($date))
        {
            //$date = date('Y-m-d').'T'.date('H:i:s').'+00:00';
            $date = date('Y-m-d');
        }
        
        $xml = 
                '<lastmod>'.
                    $date.
                '</lastmod>';       
        return $xml;
    }
    
    protected function _getChangefreq($freq = null)
    {
        if (is_null($freq))
        {
            $freq = $this->_changefreq;
        }
        
        $xml = 
                '<changefreq>'.
                    $freq.
                '</changefreq>';       
        return $xml;
    }
}