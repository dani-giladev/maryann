<?php

namespace modules\marketing\backend\controller;

// Controllers
use core\backend\controller\backend;
use core\backend\controller\maintenance\type1 as maintenance;
use modules\marketing\controller\promo as marketingPromoController;

/**
 * Backend Marketing promo controller
 *
 * @author Dani Gilabert
 * 
 */
class promo extends backend
{
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'marketing';
        $this->_promo_controller = new marketingPromoController();
    }
    
    public function saveRecord($data)
    {
        $core_maintenance = new maintenance();
        
        // Saving
        $core_maintenance->saveRecord($data);
        
        // Refresh promo views
        $this->_promo_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_promo_controller->resetFrontendVars();
    }
    
}