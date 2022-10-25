<?php

namespace modules\marketing\controller;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\session as frontendSession;
use modules\marketing\controller\marketing;

// Models
use modules\marketing\model\articleGroup as articleGroupModel;

/**
 * Marketing article group controller
 *
 * @author Dani Gilabert
 * 
 */
class articleGroup extends marketing
{
    
    public function getArticleGroupModel($id = null)
    {
        return new articleGroupModel($id);
    }
    
    protected function _getRawData($stale = 'update_after')
    {
        $model = $this->getArticleGroupModel();
        $model_type = $model->type;

        // Get data
        $ret = $model->getDataView($model_type, $model_type, $stale);
        return $ret;
    }
    
    public function getArticleGroups($public = false, $stale = 'update_after')
    {
        $ret = null;
        $list = $this->_getRawData($stale);
        
        if (isset($list))
        {
            $model = $this->getArticleGroupModel();
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
                $arr[$object->code] = $object;
            }   
            $ret = helpers::objectize($arr);
        }
        
        return $ret;
    }
    
    public function getArticleGroupByCode($code)
    {
        $model = $this->getArticleGroupModel();           
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
            $model = $this->getArticleGroupModel();
            
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
        $session_controller->resetArticleGroups();
    }

}