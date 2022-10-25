<?php

namespace modules\seo\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: urls by url
 *
 * @author Dani Gilabert
 * 
 */
class urlsByUrl extends couchCommonView
{
    public function __construct($model)
    {
        $this->setName('seo-urlsByUrl');
        $this->setType($model->type);
        $this->setKeys(array('doc.url'));
        $this->setModel($model);
    }
}