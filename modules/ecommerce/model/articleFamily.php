<?php

namespace modules\ecommerce\model;

use core\model\model\basic as basicModel;

/**
 * E-commerce article family model
 *
 * @author Dani Gilabert
 * 
 */
class articleFamily extends basicModel
{
    
    public function __construct($id = null)
    {
        $this->_properties['type'] = array('type' => 'string', 'value' => 'ecommerce-articlefamily');
        
        parent::__construct($id);
    }

}