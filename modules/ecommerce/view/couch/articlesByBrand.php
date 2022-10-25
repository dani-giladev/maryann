<?php

namespace modules\ecommerce\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Articles by brand
 *
 * @author Dani Gilabert
 * 
 */
class articlesByBrand extends couchCommonView
{
    
    public function __construct($model, $prefix)
    {
        $name = $prefix.'-articlesByBrand';
        $type = $prefix.'-article';
        $keys = array('doc.brand');
        
        $this->setName($name);
        $this->setType($type);
        $this->setKeys($keys);
        $this->setModel($model);
    }
}