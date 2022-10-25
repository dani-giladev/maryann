<?php

namespace modules\ecommerce\backend\controller;

// Controllers
use core\backend\controller\backend;
use core\backend\controller\maintenance\type1 as maintenance;
use modules\ecommerce\controller\articleType as ecommerceArticleTypeController;
use modules\ecommerce\controller\article as ecommerceArticleController;

/**
 * Backend E-commerce article type controller
 *
 * @author Dani Gilabert
 * 
 */
class articleType extends backend
{
    protected $_article_type_controller;
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'ecommerce';
        $this->_article_type_controller = new ecommerceArticleTypeController();
    }
    
    public function saveRecord($data)
    {
        $core_maintenance = new maintenance();
        $dc = $core_maintenance->getDc($data);
        
        if (!$dc->getIsNewRecord())
        {
            $old_article_type = $this->_article_type_controller->getArticleTypeByCode($data->code);
        }
        
        // Saving
        $core_maintenance->saveRecord($data);
        
        // Update articles?
        if (!$dc->getIsNewRecord() && $old_article_type->name !== $data->name)
        {
            $article_controller = new ecommerceArticleController();
            $articles = $article_controller->getArticlesByArticleType($data->code, false, false);
            foreach ($articles as $article) {
                $article_model = $article_controller->getArticleModel($article->_id);
                $article_model->articleTypeName = $data->name;
                $article_model->save(true);
                $article_model->publish(true);
            }
            
            // Refresh article views
            $article_controller->updateViews();

            // Reset frontend session vars
            $article_controller->resetFrontendVars(); 
        }
    }
    
}