<?php

namespace modules\ecommerce\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Articles by type
 *
 * @author Dani Gilabert
 * 
 */
class articlesByArticleType extends couchCommonView
{
    
    public function __construct($model, $prefix)
    {
        $name = $prefix.'-articlesByArticleType';
        $type = $prefix.'-article';
        $keys = array('doc.articleType');
        
        $this->setName($name);
        $this->setType($type);
        $this->setKeys($keys);
        $this->setModel($model);
    }
}