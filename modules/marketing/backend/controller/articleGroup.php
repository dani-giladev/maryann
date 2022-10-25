<?php

namespace modules\marketing\backend\controller;

// Controllers
use core\backend\controller\backend;
use core\backend\controller\maintenance\type1 as maintenance;
use modules\marketing\controller\articleGroup as marketingArticleGroupController;

/**
 * Backend Marketing article group controller
 *
 * @author Dani Gilabert
 * 
 */
class articleGroup extends backend
{
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'marketing';
        $this->_article_group_controller = new marketingArticleGroupController();
    }
    
    public function saveRecord($data)
    {
        $core_maintenance = new maintenance();
        
        // Saving
        $core_maintenance->saveRecord($data);
        
        // Refresh articleGroup views
        $this->_article_group_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_article_group_controller->resetFrontendVars();
    }
    
}