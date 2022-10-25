<?php

namespace modules\ecommerce\backend\controller;

// Controllers
use core\backend\controller\backend;
use core\backend\controller\maintenance\type1 as maintenance;
use modules\ecommerce\controller\brand as ecommerceBrandController;
use modules\ecommerce\controller\article as ecommerceArticleController;

/**
 * Backend E-commerce brand controller
 *
 * @author Dani Gilabert
 * 
 */
class brand extends backend
{
    protected $_brand_controller;
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'ecommerce';
        $this->_brand_controller = new ecommerceBrandController();
    }
    
    public function saveRecord($data)
    {
        $core_maintenance = new maintenance();
        $dc = $core_maintenance->getDc($data);
        
        if (!$dc->getIsNewRecord())
        {
            $old_brand = $this->_brand_controller->getBrandByCode($data->code);
        }
        
        // Saving
        $core_maintenance->saveRecord($data);
        
        // Refresh brand views
        $this->_brand_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_brand_controller->resetFrontendVars();
        
        // Update articles?
        if (!$dc->getIsNewRecord() && $old_brand->name !== $data->name)
        {
            $article_controller = new ecommerceArticleController();
            $articles = $article_controller->getArticlesByBrand($data->code, false, false);
            foreach ($articles as $article) {
                $article_model = $article_controller->getArticleModel($article->_id);
                $article_model->brandName = $data->name;
                $article_model->save(true);
                $article_model->publish(true);
            }
            
            // Refresh article views
            $article_controller->updateViews();

            // Reset frontend session vars
            $article_controller->resetFrontendVars();            
        }
    }
    
    public function saveAdditionalData($data)
    {
        // Saving
        $core_maintenance = new maintenance();
        $core_maintenance->saveRecord($data);
        
        // Refresh brand views
        $this->_brand_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_brand_controller->resetFrontendVars();
    }
    
    public function publishRecord($data)
    {
        // Publishing
        $core_maintenance = new maintenance();
        $core_maintenance->publishRecord($data);
        
        // Refresh brand views
        $this->_brand_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_brand_controller->resetFrontendVars();
    }
    
    public function deleteRecord($data)
    {
        // Deleting
        $core_maintenance = new maintenance();
        $core_maintenance->deleteRecord($data);
        
        // Refresh brand views
        $this->_brand_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_brand_controller->resetFrontendVars();
    }
    
    public function cloneRecord($data)
    {
        // Cloning
        $core_maintenance = new maintenance();
        $core_maintenance->cloneRecord($data);
        
        // Refresh brand views
        $this->_brand_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_brand_controller->resetFrontendVars();
    }
    
}