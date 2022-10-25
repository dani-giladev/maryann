<?php

namespace modules\reporting\backend\controller;

// Controllers
use core\backend\controller\backend;

/**
 * Backend common controller for reporting
 *
 * @author Dani Gilabert
 * 
 */
class reporting extends backend
{
    protected $_view;
    protected $_delegation = 'farmacia';
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'reporting';
    }  
    
}