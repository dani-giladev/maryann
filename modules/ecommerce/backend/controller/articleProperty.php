<?php

namespace modules\ecommerce\backend\controller;

// Controllers
use core\helpers\controller\helpers;
use core\ajax\controller\ajax;
use core\backend\controller\backend;
use core\backend\controller\maintenance\type1 as maintenance;
use modules\ecommerce\controller\articleProperty as articlePropertyController;

/**
 * Backend E-commerce article property controller
 *
 * @author Dani Gilabert
 * 
 */
class articleProperty extends backend
{
    protected $_article_property_controller;
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'ecommerce';
        $this->_article_property_controller = new articlePropertyController();
    }  
    
    public function saveRecord($data)
    {
        // Saving
        $core_maintenance = new maintenance();
        $core_maintenance->saveRecord($data);
        
        // Refresh views
        $this->_article_property_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_article_property_controller->resetFrontendVars();
    } 
    
    public function deleteRecord($data)
    {
        // Deleting
        $core_maintenance = new maintenance();
        $core_maintenance->deleteRecord($data);
        
        // Refresh views
        $this->_article_property_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_article_property_controller->resetFrontendVars();
    }
    
    public function getValues($data)
    {
        $arr = array();
        $code = $data->code;         
        
        if ($code !== '')
        {
            $model = $this->_article_property_controller->getArticlePropertyByCode($code);
            if (isset($model))
            {
                $values = $model->values;
                if (isset($values))
                {
                    foreach ($values as $v)
                    {
                        $item = array();
                        $item['code'] = $v->code;
                        $item['name'] = $v->name;
                        $arr[] = $item;            
                    }                     
                }
            }                
        }
        
        $ret = helpers::objectize($arr);
        ajax::sendData($ret);
    }
    
}