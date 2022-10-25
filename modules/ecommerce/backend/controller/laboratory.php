<?php

namespace modules\ecommerce\backend\controller;

// Controllers
use core\backend\controller\backend;
use core\backend\controller\maintenance\type1 as maintenance;
use modules\ecommerce\controller\laboratory as ecommerceLaboratoryController;

/**
 * Backend E-commerce laboratory controller
 *
 * @author Dani Gilabert
 * 
 */
class laboratory extends backend
{
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'ecommerce';
        $this->_laboratory_controller = new ecommerceLaboratoryController();
    }
    
    public function saveRecord($data)
    {
        // Saving
        $core_maintenance = new maintenance();
        $core_maintenance->saveRecord($data);
        
        // Refresh laboratory views
        $this->_laboratory_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_laboratory_controller->resetFrontendVars();
    }
    
    public function publishRecord($data)
    {
        // Publishing
        $core_maintenance = new maintenance();
        $core_maintenance->publishRecord($data);
        
        // Refresh laboratory views
        $this->_laboratory_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_laboratory_controller->resetFrontendVars();
    }
    
    public function deleteRecord($data)
    {
        // Deleting
        $core_maintenance = new maintenance();
        $core_maintenance->deleteRecord($data);
        
        // Refresh laboratory views
        $this->_laboratory_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_laboratory_controller->resetFrontendVars();
    }
    
    public function cloneRecord($data)
    {
        // Cloning
        $core_maintenance = new maintenance();
        $core_maintenance->cloneRecord($data);
        
        // Refresh laboratory views
        $this->_laboratory_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_laboratory_controller->resetFrontendVars();
    }
    
}