<?php

namespace modules\ecommerce\model;

use core\model\model\basic as basicModel;

/**
 * E-commerce article type model
 *
 * @author Dani Gilabert
 * 
 */
class articleType extends basicModel
{
    
    public function __construct($id = null)
    {
        $this->_properties['type'] = array('type' => 'string', 'value' => 'ecommerce-articletype');
        $this->_properties['vat'] = array('type' => 'float');
        
        parent::__construct($id);
    }

}