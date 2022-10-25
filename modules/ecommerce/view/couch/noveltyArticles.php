<?php

namespace modules\ecommerce\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Novelty articles
 *
 * @author Dani Gilabert
 * 
 */
class noveltyArticles extends couchCommonView
{
    
    public function __construct($model, $prefix)
    {
        $name = $prefix.'-noveltyArticles';
        $type = $prefix.'-article';
        $keys = array('doc.novelty');
        
        $this->setName($name);
        $this->setType($type);
        $this->setKeys($keys);
        $this->setModel($model);
    }
}