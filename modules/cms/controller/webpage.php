<?php

namespace modules\cms\controller;

// Controllers
use modules\cms\controller\cms;

// Models
use modules\cms\model\webpage as webpageModel;

/**
 * CMS webpage controller
 *
 * @author Dani Gilabert
 * 
 */
class webpage extends cms
{
    
    public function getWebpageByCode($code, $delegation, $website, $public = false)
    {
        $ret = null;
        $model = new webpageModel();
        $model_type = $model->type;
        $id = $model_type.'-'.strtolower($code.'-'.$delegation.'-'.$website);
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