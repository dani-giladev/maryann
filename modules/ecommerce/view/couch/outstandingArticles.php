<?php

namespace modules\ecommerce\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Outstanding articles
 *
 * @author Dani Gilabert
 * 
 */
class outstandingArticles extends couchCommonView
{
    
    public function __construct($model, $prefix)
    {
        $name = $prefix.'-outstandingArticles';
        $type = $prefix.'-article';
        $keys = array('doc.outstanding');
        
        $this->setName($name);
        $this->setType($type);
        $this->setKeys($keys);
        $this->setModel($model);
    }
}