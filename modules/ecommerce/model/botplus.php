<?php

namespace modules\ecommerce\model;

use core\botplus\model\botplus as botplusModel;

/**
 * E-commerce botplus model
 *
 * @author Dani Gilabert
 * 
 */
class botplus extends botplusModel
{
    
    public function __construct($id = null)
    {
        $this->_properties['type'] = array('type' => 'string', 'value' => 'ecommerce-botplus');
        
        parent::__construct($id);
    }

}