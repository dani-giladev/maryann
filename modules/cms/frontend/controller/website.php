<?php

namespace modules\cms\frontend\controller;

// Controllers
use modules\cms\frontend\controller\webpage;
use modules\cms\frontend\controller\session;

/**
 * CMS frontend website controller
 *
 * @author Dani Gilabert
 * 
 */
class website extends webpage
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getWebsite()
    {
        return session::getSessionVar('cms-website');
    }
    
    public function setWebsite($value)
    {
        session::setSessionVar('cms-website', $value);
    }
    
}