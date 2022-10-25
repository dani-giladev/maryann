<?php

namespace modules\ecommerce\controller;

// Controllers
use core\config\controller\config;

/**
 * E-commerce controller
 *
 * @author Dani Gilabert
 */
class ecommerce
{

    public function __construct()
    {
        $this->module_id = 'ecommerce';
    }

    public function isDevelopment()
    {
        return config::getConfigParam(array("application", "development"))->value;
    }
    
}