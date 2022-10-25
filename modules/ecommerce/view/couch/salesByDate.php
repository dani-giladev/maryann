<?php

namespace modules\ecommerce\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Sales by date
 *
 * @author Dani Gilabert
 */
class salesByDate extends couchCommonView
{
    
    public function __construct($model)
    {
        $this->setName('ecommerce-salesByDate');
        $this->setType($model->type);
        $this->setKeys(array('doc.date'));
        $this->setModel($model);
    }
}