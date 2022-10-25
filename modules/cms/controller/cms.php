<?php

namespace modules\cms\controller;

// Controllers
use core\config\controller\config;

/**
 * CMS controller
 *
 * @author Dani Gilabert
 */
class cms
{

    public function __construct()
    {
        $this->module_id = 'cms';
    }

    public function isDevelopment()
    {
        return config::getConfigParam(array("application", "development"))->value;
    }
    
}