<?php

namespace modules\marketing\controller;

// Controllers
use core\config\controller\config;

/**
 * Marketing controller
 *
 * @author Dani Gilabert
 * 
 */
class marketing
{

    public function __construct()
    {
        $this->module_id = 'marketing';
    }

    public function isDevelopment()
    {
        return config::getConfigParam(array("application", "development"))->value;
    }
    
}