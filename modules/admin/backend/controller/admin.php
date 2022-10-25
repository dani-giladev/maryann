<?php

namespace modules\admin\backend\controller;

// Controllers
use core\backend\controller\backend;

/**
 * Backend admin controller
 *
 * @author Dani Gilabert
 * 
 */
class admin extends backend
{

    public function __construct()
    {
        $this->module_id = 'admin';
    }
    
}