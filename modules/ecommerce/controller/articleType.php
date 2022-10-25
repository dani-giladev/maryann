<?php

namespace modules\ecommerce\controller;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\controller\ecommerce;

use modules\ecommerce\model\articleType as articleTypeModel;

/**
 * E-commerce article type controller
 *
 * @author Dani Gilabert
 * 
 */
class articleType extends ecommerce
{
    
    protected function _getArticleTypeModel($id = null)
    {
        return new articleTypeModel($id);
    }
    
    protected function _getRawData($stale = 'update_after')
    {
        $model = $this->_getArticleTypeModel();
        $model_type = $model->type;

        // Get data
        $ret = $model->getDataView($model_type, $model_type, $stale);
        return $ret;
    }
    
    public function getAvailableArticleTypes($public = false, $stale = 'update_after')
    {
        $ret = null;
        $list = $this->_getRawData($stale);
        
        if(isset($list))
        {
            $model = $this->_getArticleTypeModel();
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
    
    public function getArticleTypeByCode($code, $public = false)
    {
        $ret = null;
        $model = $this->_getArticleTypeModel();           
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

}