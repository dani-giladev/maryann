<?php

namespace modules\ecommerce\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Articles reviews by code
 *
 * @author Dani Gilabert
 */
class articleReviewsByArticlecode extends couchCommonView
{
    
    public function __construct($model)
    {
        $this->setName('ecommerce-articleReviewsByArticlecode');
        $this->setType($model->type);
        $this->setKeys(array('doc.articleCode'));
        $this->setModel($model);
    }
}