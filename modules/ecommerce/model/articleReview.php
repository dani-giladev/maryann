<?php

namespace modules\ecommerce\model;

use core\model\controller\model;

/**
 * Article review model for E-commerce
 *
 * @author Dani Gilabert
 * 
 */
class articleReview extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'ecommerce-articlereview'),
        'code' => array('type' => 'string'),
        'date' => array('type' => 'date'),
        'time' => array('type' => 'time'),
        'articleCode' => array('type' => 'string'),
        'articleName' => array('type' => 'string'),
        //'article' => array('type' => 'array'),
        'rating' => array('type' => 'integer'),
        'name' => array('type' => 'string'),
        'title' => array('type' => 'string'),
        'text' => array('type' => 'string'),
        'lang' => array('type' => 'string')
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
    public function getNewCode()
    {
        $code = date("YmdHis")."-".rand(100, 999);
        return $code;
    }
    
}