<?php

namespace modules\ecommerce\backend\controller;

// Controllers
use core\backend\controller\backend;
use core\backend\controller\maintenance\type1 as maintenance;
use modules\ecommerce\controller\gamma as ecommerceGammaController;

/**
 * Backend E-commerce gamma controller
 *
 * @author Dani Gilabert
 * 
 */
class gamma extends backend
{
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'ecommerce';
        $this->_gamma_controller = new ecommerceGammaController();
    }
    
    public function saveRecord($data)
    {
        // Saving
        $core_maintenance = new maintenance();
        $core_maintenance->saveRecord($data);
        
        // Refresh gamma views
        $this->_gamma_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_gamma_controller->resetFrontendVars();
    }
    
    public function saveAdditionalData($data)
    {
        // Saving
        $core_maintenance = new maintenance();
        $core_maintenance->saveRecord($data);
        
        // Refresh gamma views
        $this->_gamma_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_gamma_controller->resetFrontendVars();
    }
    
    public function publishRecord($data)
    {
        // Publishing
        $core_maintenance = new maintenance();
        $core_maintenance->publishRecord($data);
        
        // Refresh gamma views
        $this->_gamma_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_gamma_controller->resetFrontendVars();
    }
    
    public function deleteRecord($data)
    {
        // Deleting
        $core_maintenance = new maintenance();
        $core_maintenance->deleteRecord($data);
        
        // Refresh gamma views
        $this->_gamma_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_gamma_controller->resetFrontendVars();
    }
    
    public function cloneRecord($data)
    {
        // Cloning
        $core_maintenance = new maintenance();
        $core_maintenance->cloneRecord($data);
        
        // Refresh gamma views
        $this->_gamma_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_gamma_controller->resetFrontendVars();
    }
    
}