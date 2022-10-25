<?php

namespace modules\cms\model;

use core\model\controller\model;

/**
 * CMS webpage model
 *
 * @author Dani Gilabert
 * 
 */
class webpage extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'cms-webpage'),
        'code' => array('type' => 'string'),
        'name' => array('type' => 'string'),
        'description' => array('type' => 'string'),
        'available' => array('type' => 'boolean'),
        'delegation' => array('type' => 'string'),
        'delegationName' => array('type' => 'string'),
        'website' => array('type' => 'string'),
        'websiteName' => array('type' => 'string'),
        'slider' => array('type' => 'array'),
        'banners' => array('type' => 'array')
    );
    
    protected $_id_COMPOSITION = array('type', 'code', 'delegation', 'website');
    
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}