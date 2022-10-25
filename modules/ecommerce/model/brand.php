<?php

namespace modules\ecommerce\model;

use core\model\controller\model;

/**
 * E-commerce brand model
 *
 * @author Dani Gilabert
 * 
 */
class brand extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'ecommerce-articlebrand'),
        'code' => array('type' => 'string'),
        'name' => array('type' => 'string'),
        'description' => array('type' => 'string'),
        'available' => array('type' => 'boolean'),
        'visible' => array('type' => 'boolean'),
        'outstanding' => array('type' => 'boolean'),
        'empty' => array('type' => 'boolean'),
//        'brandType' => array('type' => 'string'),
//        'brandTypeName' => array('type' => 'string'),
        'image' => array('type' => 'string'),
        'seoDescriptions' => array('type' => 'array'),
        'keywords' => array('type' => 'array'),
        'medicines' => array('type' => 'boolean'),
        'laboratory' => array('type' => 'string'),
        'laboratoryName' => array('type' => 'string'),
        //'titles' => array('type' => 'array'),
        'descriptions' => array('type' => 'array'),
        'notes' => array('type' => 'string')
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}