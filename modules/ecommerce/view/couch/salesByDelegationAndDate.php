<?php

namespace modules\ecommerce\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Sales by delegation and date
 *
 * @author Dani Gilabert
 */
class salesByDelegationAndDate extends couchCommonView
{
    
    public function __construct($model)
    {
        $this->setName('ecommerce-salesByDelegationAndDate');
        $this->setType($model->type);
        $this->setKeys(array('doc.delegation', 'doc.date'));
        $this->setModel($model);
    }
}