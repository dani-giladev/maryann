<?php

namespace modules\admin\controller;

// Controllers
use core\config\controller\config;

/**
 * Admin controller
 *
 * @author Dani Gilabert
 * 
 */
class admin
{

    public function __construct()
    {
        $this->module_id = 'admin';
    }

    public function isDevelopment()
    {
        return config::getConfigParam(array("application", "development"))->value;
    }
    
}