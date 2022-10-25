<?php

namespace modules\ecommerce\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Outstanding articles by categories
 *
 * @author Dani Gilabert
 * 
 */
class outstandingArticlesByCategories extends couchCommonView
{
    
    public function __construct($model, $prefix)
    {
        $name = $prefix.'-outstandingArticlesByCategories';
        $type = $prefix.'-article';
        $keys = array('doc.outstanding, doc.categories');
        
        $this->setName($name);
        $this->setType($type);
        $this->setKeys($keys);
        $this->setModel($model);
    }
}