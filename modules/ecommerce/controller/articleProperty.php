<?php

namespace modules\ecommerce\controller;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\controller\ecommerce;
use modules\ecommerce\frontend\controller\session as frontendSession;

use modules\ecommerce\model\articleProperty as articlePropertyModel;

/**
 * E-commerce article property controller
 *
 * @author Dani Gilabert
 * 
 */
class articleProperty extends ecommerce
{
    
    protected function _getArticlePropertyModel($id = null)
    {
        return new articlePropertyModel($id);
    }
    
    protected function _getRawData($stale = 'update_after')
    {
        $model = $this->_getArticlePropertyModel();
        $model_type = $model->type;

        // Get data
        $ret = $model->getDataView($model_type, $model_type, $stale);
        return $ret;
    }
    
    public function getAvailableArticleProperties($public = false, $stale = 'update_after')
    {
        $ret = null;
        $list = $this->_getRawData($stale);
        
        if (isset($list))
        {
            $model = $this->_getArticlePropertyModel();
            $is_publication_enabled = $model->isPublicationEnabled();   
            $publication_mode = $model->getPublicationMode();   
            $arr = array();
            foreach($list->rows as $row_key => $row_values)
            {
                $object = $row_values->value;
                if ($public && $is_publication_enabled && $publication_mode === 'SAME_DOCUMENT')
                {
                    $object = $object->public;
                    if (!isset($object)) continue;   
                }
                if(!$object->available) continue;
                $arr[] = $object;
            }   
            $ret = helpers::objectize($arr);
        }
        
        return $ret;
    }
    
    public function getArticlePropertyByCode($code)
    {
        $model = $this->_getArticlePropertyModel();           
        $model_type = $model->type;
        $id = $model_type.'-'.strtolower($code);
        $storage = $model->loadData($id);     
        if (is_null($storage)) return null;  
        return $model;
    }
    
    public function updateViews($update_main_view = true)
    {
        if ($update_main_view)
        {
            $model = $this->_getArticlePropertyModel();
            
            // Refresh the data view
            $model_type = $model->type;
            $update = $model->updateDataView($model_type, $model_type);
            if (!$update)
            {
                return;
            }
        }
    }
    
    public function resetFrontendVars()
    {
        // Reset frontend session vars
        $session_controller = new frontendSession();
        $session_controller->resetArticleProperties();
    }

}