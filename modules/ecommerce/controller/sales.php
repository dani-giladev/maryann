<?php

namespace modules\ecommerce\controller;

// Controllers
use modules\ecommerce\controller\ecommerce;

// Views
use modules\ecommerce\view\couch\salesByDate;
use modules\ecommerce\view\couch\salesByDelegationAndDate;

/**
 * E-commerce controller for sales
 *
 * @author Dani Gilabert
 * 
 */
class sales extends ecommerce
{

    public function getData($model, $stale, $delegation, $start_date, $end_date) 
    {
        if (empty($delegation) || $delegation == '_all')
        {
            $view = new salesByDate($model);
            $params = array(
                "startkey" => array($start_date),
                "endkey" => array($end_date)
            );            
        }
        else
        {
            $view = new salesByDelegationAndDate($model);
            $params = array(
                "startkey" => array($delegation, $start_date),
                "endkey" => array($delegation, $end_date)
            );            
        }
        
        $ret = $view->getDataView($params, $stale);
        return $ret;
    }
    
}