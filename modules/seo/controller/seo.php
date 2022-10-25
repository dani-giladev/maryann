<?php

namespace modules\seo\controller;

// Controllers
use core\config\controller\config;

/**
 * SEO controller
 *
 * @author Dani Gilabert
 * 
 */
class seo
{

    public function __construct()
    {
        $this->module_id = 'seo';
    }

    public function isDevelopment()
    {
        return config::getConfigParam(array("application", "development"))->value;
    }
    
}