<?php

namespace modules\ecommerce\model;

use core\model\model\basic as basicModel;

/**
 * E-commerce article property model
 *
 * @author Dani Gilabert
 * 
 */
class articleProperty extends basicModel
{
    
    public function __construct($id = null)
    {
        $this->_properties['type'] = array('type' => 'string', 'value' => 'ecommerce-articleproperty');
        $this->_properties['titles'] = array('type' => 'array');
        $this->_properties['values'] = array('type' => 'array');
        
        parent::__construct($id);
    }

}