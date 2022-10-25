<?php

namespace modules\ecommerce\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Articles by validated
 *
 * @author Dani Gilabert
 * 
 */
class articlesByValidated extends couchCommonView
{
    
    public function __construct($model, $prefix)
    {
        $name = $prefix.'-articlesByValidated';
        $type = $prefix.'-article';
        $keys = array('doc.validated');
        
        $this->setName($name);
        $this->setType($type);
        $this->setKeys($keys);
        $this->setModel($model);
    }
}