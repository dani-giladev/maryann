<?php

namespace modules\seo\model;

use core\model\controller\model;

/**
 * SEO url model
 *
 * @author Dani Gilabert
 * 
 */
class url extends model
{
    protected $_properties = array(
        
        // Main
        'type' => array('type' => 'string', 'value' => 'seo-url'),
        'code' => array('type' => 'string'),
        'url' => array('type' => 'string'),
        'action' => array('type' => 'string'),
        'useAction' => array('type' => 'string'),
        'redirect2Url' => array('type' => 'string'),
        'redirect2Article' => array('type' => 'string'),
        'redirect2Category' => array('type' => 'string'),
        'redirect2Brand' => array('type' => 'string')
        
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}