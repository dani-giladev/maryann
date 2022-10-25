<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use modules\cms\frontend\controller\lang as cmsLang;

/**
 * Overrides CMS lang class
 *
 * @author Dani Gilabert
 * 
 */
class lang extends cmsLang
{
    public function __construct()
    {
        parent::__construct();
    }
    
    protected static function _getLangPath($lang)
    {
        $lang_path = 'modules/ecommerce/frontend/res/lang/'.$lang.'.php';
        return $lang_path;
    }
}