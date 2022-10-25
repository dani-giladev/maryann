<?php

namespace modules\ecommerce\frontend\controller\mailing;

// Controllers
use core\config\controller\config;
use modules\cms\frontend\controller\mail as cmsMail;
use modules\ecommerce\frontend\controller\session;

/**
 * Mail controller
 *
 * @author Dani Gilabert
 * 
 */
class mail extends cmsMail
{
    
    public function getWebsite()
    {
        return session::getSessionVar('ecommerce-website');
    }
    
    public function setWebsite($value)
    {
        session::setSessionVar('ecommerce-website', $value);
    }
    
    protected function _getMailAddressesParam($is_admin = false)
    {
        if ($is_admin)
        {
            return config::getConfigParam(array("ecommerce", "admin_mail_addresses"))->value;
        }
        else
        {
            return config::getConfigParam(array("ecommerce", "mail_addresses"))->value;
        }
    }
}