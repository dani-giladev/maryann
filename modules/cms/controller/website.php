<?php

namespace modules\cms\controller;

// Controllers
use modules\cms\controller\cms;
use core\helpers\controller\helpers;
use core\globals\controller\globals;

// Models
use modules\cms\model\website as websiteModel;

// Views
use modules\cms\view\couch\websitesByDomain;

/**
 * CMS website controller
 *
 * @author Dani Gilabert
 * 
 */
class website extends cms
{
    
    public function getWebsites($stale = 'update_after')
    {
        $model = new websiteModel();
        $model_type = $model->type;

        // Get data
        $ret = $model->getDataView($model_type, $model_type, $stale);
        return $ret;
    }
    
    public function getAvailableWebsites($public = false, $stale = 'update_after')
    {
        $ret = null;
        $list = $this->getWebsites($stale);
        if(isset($list))
        {
            $model = new websiteModel();
            $is_publication_enabled = $model->isPublicationEnabled();   
            $publication_mode = $model->getPublicationMode();  
            $arr = array();
            foreach($list->rows as $row_key => $row_values)
            {
                $object = $row_values->value;
                if ($public && $is_publication_enabled && $publication_mode === 'SAME_DOCUMENT')
                {
                    $object = $object->public;
                    if(!isset($object))
                    {
                        continue;
                    }
                }
                if(!$object->available)
                {
                    continue;
                }
                $arr[] = $object;
            }   
            $ret = helpers::objectize($arr);
        }
        return $ret;
    }
    
    public function getWebsiteByDomain($domain, $public = true, $stale = true, $frontend = false)
    {
        if ($frontend)
        {
            $globalvar = globals::getGlobalVar('website-by-domain');
            if (isset($globalvar) && isset($globalvar[$domain]) && !empty($globalvar[$domain]))
            {
                return $globalvar[$domain];
            }         
        }
        
        $ret = array();
        $model = new websiteModel();
        $view = new websitesByDomain($model);
        $params = array(
            "key" => array($domain)
        );    
        $object = $view->getDataView($params, $stale);
        
        if (!empty($object->rows))
        {
            if ($public && $model->isPublicationEnabled() && $model->getPublicationMode() === 'SAME_DOCUMENT')
            {
                if (isset($object->rows[0]->value->public))
                {
                    $ret = $object->rows[0]->value->public;
                }
            }
            else
            {
                $ret = $object->rows[0]->value;
            }
        }
        
        if ($frontend)
        {
            $globalvar[$domain] = $ret;
            globals::setGlobalVar('website-by-domain', $globalvar);
        }
        
        return $ret;
    }    
    
    public function getWebsiteByCode($code, $delegation, $public = false)
    {
        $ret = null;
        $model = new websiteModel();
        $model_type = $model->type;
        $id = strtolower($model_type.'-'.$code.'-'.$delegation); 
        
        $storage = $model->loadData($id);     
        if (is_null($storage))
        {
            return $ret;
        }
        
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