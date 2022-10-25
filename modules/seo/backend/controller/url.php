<?php

namespace modules\seo\backend\controller;

// Controllers
use core\ajax\controller\ajax;
use core\backend\controller\backend;
use core\backend\controller\maintenance\type1 as maintenance;
use modules\seo\controller\url as seoUrlController;

/**
 * Backend SEO url controller
 *
 * @author Dani Gilabert
 * 
 */
class url extends backend
{
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'seo';
        $this->_url_controller = new seoUrlController();
    }
    
    public function saveRecord($data)
    {
        $core_maintenance = new maintenance();
        $dc = $core_maintenance->getDc($data);
        
        if ($dc->getIsNewRecord())
        {
            $url_doc = $this->_url_controller->getUrl($data->url);
            if (!empty($url_doc))
            {
                $msg = "Url ".$this->trans('already_exists', 'core');
                ajax::fuckYou($msg);
                return;
            }
        }
        
        // Saving
        $core_maintenance->saveRecord($data);
        
        // Refresh url views
        $this->_url_controller->updateViews(false);
    }
    
}