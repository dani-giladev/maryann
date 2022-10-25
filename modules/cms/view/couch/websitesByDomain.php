<?php

namespace modules\cms\view\couch;

// Views
use core\model\view\couch as couchCommonView;

/**
 * Couch view: Websites by domain
 *
 * @author Dani Gilabert
 * 
 */
class websitesByDomain extends couchCommonView
{
    
    public function __construct($model)
    {
        $this->setName('cms-websitesByDomain');
        $this->setType($model->type);
        $this->setKeys(array('doc.domain'));
        $this->setModel($model);
    }
}