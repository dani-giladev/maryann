<?php

namespace modules\cms\backend\controller;

// Controllers
use core\ajax\controller\ajax;
use core\backend\controller\lang;
use core\backend\controller\maintenance\type1 as maintenance;
use modules\cms\controller\website as cmsWebsite;
use modules\ecommerce\frontend\controller\session as ecommerceFrontendSession;
use modules\bookingengine\frontend\controller\session as bookingengineFrontendSession;

/**
 * CMS backend website controller
 *
 * @author Dani Gilabert
 * 
 */
class website extends cmsWebsite
{
    
    public function saveRecord($data)
    {
        // Check if domain already exists
        if (!empty($data->domain))
        {
            $website = $this->getWebsiteByDomain($data->domain, false, true);
            $is_new_record = filter_var($data->is_new_record, FILTER_VALIDATE_BOOLEAN);

            if($is_new_record )
            {
                if (!empty($website))
                {
                    ajax::fuckYou(lang::trans("domain_already_exists", __NAMESPACE__));
                    return;
                }
            }
            else
            {
                if (!empty($website) && $data->code != $website->code)
                {
                    ajax::fuckYou(lang::trans("domain_already_exists", __NAMESPACE__));
                    return;
                }            
            }            
        }
        
        // Save the record
        $maintenance_controller = new maintenance();
        $maintenance_controller->saveRecord($data);
        
        // Refresh some views (with stale = false)
        $website = $this->getWebsiteByDomain("", false, false);        
    }
    
    public function resetFrontendData($data)
    {
        $websiteType = $data->websiteType;
        
        if ($websiteType === 'bookingengine')
        {
            $frontend_session_controller = new bookingengineFrontendSession();
        }
        else
        {
            $frontend_session_controller = new ecommerceFrontendSession();
        }
        
        $frontend_session_controller->resetAll();
        
        ajax::ohYeah();
    }
}
