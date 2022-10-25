<?php

namespace modules\reporting\model;

use modules\ecommerce\model\article;

/**
 * Reporting farmatic article model
 *
 * @author Dani Gilabert
 * 
 */
class farmaticArticle extends article
{
    
    public function __construct($id = null)
    {
        $this->_properties['type'] = array('type' => 'string', 'value' => 'reporting-farmaticarticle');
        $this->_properties['efp'] = array('type' => 'boolean');
        $this->_properties['pvp'] = array('type' => 'float');
        $this->_properties['inDb'] = array('type' => 'boolean');
        $this->_properties['stockInDb'] = array('type' => 'integer');
        $this->_properties['forSale'] = array('type' => 'boolean');
        $this->_properties['forSaleAndVisible'] = array('type' => 'boolean');
        
        parent::__construct();
        $this->loadData($id);
    }
    
}