<?php

namespace modules\cms\backend\controller;

// Controllers
use modules\cms\controller\cms;
use core\helpers\controller\helpers;
use core\ajax\controller\ajax;

// Models
use modules\cms\model\webpage as webpageModel;

/**
 * CMS backend webpage controller
 *
 * @author Dani Gilabert
 * 
 */
class webpage extends cms
{
    
    public function getSlider($param)
    {
        $id = $param->record_id;
        $ret = $this->_getProperty($id, "slider");
        ajax::sendData($ret);
    }
    
    public function getBanners($param)
    {
        $id = $param->record_id;
        $ret = $this->_getProperty($id, "banners");
        ajax::sendData($ret);
    }
    
    private function _getProperty($id, $property)
    {
        $arr = array();
        
        if ($id !== '')
        {
            $model = new webpageModel($id);
            if ($model->exists())
            {
                $values = $model->$property;
                
                if (isset($values))
                {
                    $arr = $values;                    
                }                    
            }                
        }
        
        $ret = helpers::objectize($arr);
        
        return $ret;
    }
    
}