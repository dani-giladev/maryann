<?php

namespace modules\ecommerce\model;

use core\model\controller\model;

/**
 * E-commerce laboratory model
 *
 * @author Dani Gilabert
 * 
 */
class laboratory extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'ecommerce-laboratory'),
        'code' => array('type' => 'string'),
        'name' => array('type' => 'string'),
        'description' => array('type' => 'string'),
        'available' => array('type' => 'boolean'),
        'outstanding' => array('type' => 'boolean'),
        'empty' => array('type' => 'boolean'),
        'image' => array('type' => 'string'),
        'descriptions' => array('type' => 'array'),
        'keywords' => array('type' => 'array'),
        'medicines' => array('type' => 'boolean'),
        'notes' => array('type' => 'string')
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}