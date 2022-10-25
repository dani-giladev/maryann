<?php

namespace modules\admin\model;

// Controllers
use core\model\controller\model;

/**
 * Admin language model
 *
 * @author Dani Gilabert
 * 
 */
class language extends model
{
    protected $_properties = array(
        'code' => array('type' => 'string'),
        'name' => array('type' => 'string'),
        'available' => array('type' => 'boolean'),
        'order' => array('type' => 'integer')
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    public function __construct($id = null)
    {
        $this->_properties['type'] = array('type' => 'string', 'value' => 'admin-language');
        
        parent::__construct($id);
        $this->loadData($id);
    }

}