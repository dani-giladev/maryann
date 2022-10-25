<?php

namespace modules\ecommerce\backend\controller;

// Controllers
use core\backend\controller\backend;
use core\backend\controller\maintenance\type1 as maintenance;
use modules\ecommerce\controller\saleRate as ecommerceSaleRateController;

/**
 * Backend E-commerce sale rate controller
 *
 * @author Dani Gilabert
 * 
 */
class saleRate extends backend
{
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'ecommerce';
        $this->_sale_rate_controller = new ecommerceSaleRateController();
    }
    
    public function saveRecord($data)
    {
        // Saving
        $core_maintenance = new maintenance();
        $core_maintenance->saveRecord($data);
        
        // Refresh saleRate views
        $this->_sale_rate_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_sale_rate_controller->resetFrontendVars();
    }
    
    public function deleteRecord($data)
    {
        // Deleting
        $core_maintenance = new maintenance();
        $core_maintenance->deleteRecord($data);
        
        // Refresh saleRate views
        $this->_sale_rate_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_sale_rate_controller->resetFrontendVars();
    }
}