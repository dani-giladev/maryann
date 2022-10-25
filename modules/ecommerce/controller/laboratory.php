<?php

namespace modules\ecommerce\controller;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\controller\ecommerce;
use modules\ecommerce\frontend\controller\session as frontendSession;

// Models
use modules\ecommerce\model\laboratory as laboratoryModel;

/**
 * E-commerce laboratory controller
 *
 * @author Dani Gilabert
 * 
 */
class laboratory extends ecommerce
{
    protected function _getLaboratoryModel($id = null)
    {
        return new laboratoryModel($id);
    }
    
    protected function _getRawData($stale = 'update_after')
    {
        $model = $this->_getLaboratoryModel();
        $model_type = $model->type;

        // Get data
        $ret = $model->getDataView($model_type, $model_type, $stale);
        return $ret;
    }
    
    public function getLaboratories($public = false, $stale = 'update_after')
    {
        $ret = null;
        $list = $this->_getRawData($stale);
        
        if (isset($list))
        {
            $model = $this->_getLaboratoryModel();
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
                $arr[] = $object;
            }   
            $ret = helpers::objectize($arr);
        }
        
        return $ret;
    }
    
    public function getLaboratoryByCode($code, $public = false)
    {
        $ret = null;
        $model = $this->_getLaboratoryModel();           
        $model_type = $model->type;
        $id = $model_type.'-'.strtolower($code);
        $storage = $model->loadData($id);
        if (is_null($storage)) return null;        
        
        if ($public && $model->isPublicationEnabled() && $model->getPublicationMode() === 'SAME_DOCUMENT')
        {
            $ret = $model->public;
            if (!isset($ret)) return $ret;
        }
        else
        {
            $ret = $model;
        }
        
        return $ret;
    }
    
    public function updateViews($update_main_view = true)
    {
        if ($update_main_view)
        {
            $model = $this->_getLaboratoryModel();
            
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
        $session_controller->resetBrands();
    }
    
}