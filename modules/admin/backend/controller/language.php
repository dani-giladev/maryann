<?php

namespace modules\admin\backend\controller;

// Controllers
use core\ajax\controller\ajax;
use modules\admin\backend\controller\admin;
use modules\admin\controller\language as adminLanguageController;

/**
 * Backend admin language controller
 *
 * @author Dani Gilabert
 * 
 */
class language extends admin
{
    protected $_admin_language_controller;
    
    public function __construct()
    {
        parent::__construct();
        $this->_admin_language_controller = new adminLanguageController();
    } 
    
    public function getLanguages($data)
    {
        $ret = $this->_admin_language_controller->getLanguages(true);
        ajax::sendData($ret);
    }   
    
}