<?php

namespace modules\marketing\controller;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\session as frontendSession;
use modules\marketing\controller\marketing;

// Models
use modules\marketing\model\promo as promoModel;

/**
 * Marketing promo controller
 *
 * @author Dani Gilabert
 * 
 */
class promo extends marketing
{
    
    public function getPromoModel($id = null)
    {
        return new promoModel($id);
    }
    
    protected function _getRawData($stale = 'update_after')
    {
        $model = $this->getPromoModel();
        $model_type = $model->type;

        // Get data
        $ret = $model->getDataView($model_type, $model_type, $stale);
        return $ret;
    }
    
    public function getAvailablePromos($public = false, $stale = 'update_after')
    {
        $ret = null;
        $list = $this->_getRawData($stale);
        
        if (isset($list))
        {
            $model = $this->getPromoModel();
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
                $arr[$object->code] = $object;
            }   
            $ret = helpers::objectize($arr);
        }
        
        return $ret;
    }
    
    public function getPromoByCode($code)
    {
        $model = $this->getPromoModel();           
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
            $model = $this->getPromoModel();
            
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
        $session_controller->resetPromos();
    }

}