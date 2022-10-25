<?php

namespace modules\admin\controller;

// Controllers
use core\helpers\controller\helpers;
use core\globals\controller\globals;
use modules\admin\controller\admin;

// Models
use modules\admin\model\language as languageModel;

/**
 * Admin language controller
 *
 * @author Dani Gilabert
 * 
 */
class language extends admin
{
    
    public function getLanguageModel($id = null)
    {
        $model = new languageModel($id);
        return $model;        
    }
    
    public function getLanguages($available = null)
    {
        $globalvar_key = $this->_getVarKey($available);
        $available_langs = globals::getGlobalVar('admin-langs');
        if (isset($available_langs) && isset($available_langs[$globalvar_key]) && !empty($available_langs[$globalvar_key]))
        {
            return $available_langs[$globalvar_key];
        }
        
        $model = $this->getLanguageModel();
        $model_type = $model->type;
    
        // Get data
        $object = $model->getDataView($model_type, $model_type);
        
        // Filtering
        $ret = array();
        if (!empty($object->rows))
        {
            foreach($object->rows as $row_values)
            {
                if (
                        ($available === true && !$row_values->value->available) ||
                        ($available === false && $row_values->value->available)
                )
                {
                    continue;
                }  
                
                $item = array();
                $item['code'] = $row_values->value->code;
                $item['name'] = $row_values->value->name;
                $item['order'] = $row_values->value->order;
                $ret[] = $item;                
            }
        }  
        
        $ret = helpers::sortArrayByField($ret, 'order');
        $ret = helpers::objectize($ret);
        
        $available_langs[$globalvar_key] = $ret;
        globals::setGlobalVar('admin-langs', $available_langs);
        
        return $ret;
    }
    
    private function _getVarKey($available)
    {        
        if ($available === true)
        {
            $var_key = 'available';
        }
        elseif ($available === false)
        {
            $var_key = 'no-available';
        }
        else
        {
            $var_key = 'all';
        }
        
        return $var_key;
    }
    
}