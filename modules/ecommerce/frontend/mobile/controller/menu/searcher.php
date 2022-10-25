<?php

namespace modules\ecommerce\frontend\mobile\controller\menu;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\menu\searcher as searcherController;

// Views
use modules\ecommerce\frontend\mobile\view\menu\searcher as view;

/**
 * E-commerce frontend articles searcher mobile controller
 *
 * @author Dani Gilabert
 * 
 */
class searcher extends searcherController
{

    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
    }
    
    protected function renderSearchResult($needles, $articles, $brands, $categories) 
    {
        $ret = array();
        $nonutf8 = $this->_view->renderSearchResult($needles, $articles, $brands, $categories);
        foreach ($nonutf8 as $value) {
            $utf8 = helpers::removeNoUtf8Chars($value);
            $ret[] = $utf8;
        }
        return $ret;
    }
    
}