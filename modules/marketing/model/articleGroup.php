<?php

namespace modules\marketing\model;

use core\model\controller\model;

/**
 * Marketing article group model
 *
 * @author Dani Gilabert
 * 
 */
class articleGroup extends model
{
    protected $_properties = array(
        
        // Main
        'type' => array('type' => 'string', 'value' => 'marketing-articlegroup'),
        'code' => array('type' => 'string'),
        'name' => array('type' => 'string'),

        // Group by
        'articleTypes' => array('type' => 'array'),
        'brands' => array('type' => 'array'),
        'gammas' => array('type' => 'array'),
        'articles' => array('type' => 'array')
        
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}