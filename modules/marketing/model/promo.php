<?php

namespace modules\marketing\model;

use core\model\controller\model;

/**
 * Marketing promo model
 *
 * @author Dani Gilabert
 * 
 */
class promo extends model
{
    protected $_properties = array(
        
        // Main
        'type' => array('type' => 'string', 'value' => 'marketing-promo'),
        'code' => array('type' => 'string'),
        'name' => array('type' => 'string'),
        'available' => array('type' => 'boolean'),
        'visibleMenu' => array('type' => 'boolean'),
        'articleGroup' => array('type' => 'string'),
        'articleGroupName' => array('type' => 'string'),
        'titles' => array('type' => 'array')
        
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}