<?php

namespace modules\ecommerce\model;

use core\model\controller\model;

/**
 * E-commerce brand type model
 *
 * @author Dani Gilabert
 * 
 */
class brandType extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'ecommerce-brandtype'),
        'code' => array('type' => 'string'),
        'name' => array('type' => 'string'),
        'description' => array('type' => 'string'),
        'available' => array('type' => 'boolean')
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }

}