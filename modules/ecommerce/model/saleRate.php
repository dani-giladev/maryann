<?php

namespace modules\ecommerce\model;

use core\model\model\basic as basicModel;

/**
 * E-commerce sale rate model
 *
 * @author Dani Gilabert
 * 
 */
class saleRate extends basicModel
{
    
    public function __construct($id = null)
    {
        $this->_properties['type'] = array('type' => 'string', 'value' => 'ecommerce-salerate');
        $this->_properties['profitMargin'] = array('type' => 'float');
        $this->_properties['discount'] = array('type' => 'float');
        $this->_properties['hideDiscount'] = array('type' => 'boolean');
        $this->_properties['hideDiscountBadge'] = array('type' => 'boolean');
        
        parent::__construct($id);
    }

}