<?php

namespace modules\ecommerce\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Articles by URL
 *
 * @author Dani Gilabert
 * 
 */
class articlesByUrl extends couchCommonView
{
    
    public function __construct($model, $prefix, $lang)
    {
        $name = $prefix.'-articlesByUrl'.ucfirst($lang);
        $type = $prefix.'-article';
        $keys = array('doc.url'.ucfirst($lang)); 
        
        $this->setName($name);
        $this->setType($type);
        $this->setKeys($keys);
        $this->setModel($model);
    }
}