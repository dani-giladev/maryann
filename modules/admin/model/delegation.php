<?php

namespace modules\admin\model;

use core\model\controller\model;

/**
 * Admin delegation model
 *
 * @author Dani Gilabert
 * 
 */
class delegation extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'admin-delegation'),
        'code' => array('type' => 'string'),
        'name' => array('type' => 'string'),
        'description' => array('type' => 'string'),
        'available' => array('type' => 'boolean'),
        'phone' => array('type' => 'string'),
        'email' => array('type' => 'string')
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}