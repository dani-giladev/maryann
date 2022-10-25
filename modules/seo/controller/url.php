<?php

namespace modules\seo\controller;

// Controllers
use modules\seo\controller\seo;

// Models
use modules\seo\model\url as urlModel;

// Views
use modules\seo\view\couch\urlsByUrl as urlsByUrlView;

/**
 * SEO url controller
 *
 * @author Dani Gilabert
 * 
 */
class url extends seo
{
    
    public function getUrlModel($id = null)
    {
        return new urlModel($id);
    }
    
    public function getUrl($url, $stale = true)
    {
        $ret = array();
        
        $model = $this->getUrlModel();
        $view = new urlsByUrlView($model);
        $params = array(
            "key" => array($url)
        );
        $object = $view->getDataView($params, $stale);
        
        if (!empty($object->rows))
        {
            $ret = $object->rows[0]->value;
        }   
        
        return $ret;
    }
    
    public function updateViews($update_main_view = true)
    {
        if ($update_main_view)
        {
            $model = $this->getUrlModel();
            
            // Refresh the data view
            $model_type = $model->type;
            $update = $model->updateDataView($model_type, $model_type);
            if (!$update)
            {
                return;
            }
        }
                
        // Update the 'urlsByUrl' view
        $url = $this->getUrl("", false);
    }

}