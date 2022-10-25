<?php

namespace modules\ecommerce\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Search articles starting with a letter .....
 *
 * @author Dani Gilabert
 */
class articlesStartingWith extends couchCommonView
{
    
    public function __construct($model)
    {
        $this->setName('ecommerce-articlesStartingWith');
        $this->setType($model->type);
        $this->setKeys(array('doc.name'));
        $this->setModel($model);
    }
}