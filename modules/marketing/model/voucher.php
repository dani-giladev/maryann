<?php

namespace modules\marketing\model;

use core\model\model\basic as basicModel;

/**
 * Marketing voucher model
 *
 * @author Dani Gilabert
 * 
 */
class voucher extends basicModel
{
    protected $_properties = array(
        
        // Main
        'type' => array('type' => 'string', 'value' => 'marketing-voucher'),
        'code' => array('type' => 'string'),
        
        // Properties
        'name' => array('type' => 'string'),
        'voucherType' => array('type' => 'string'),
        'voucherTypeName' => array('type' => 'string'),
        'value' => array('type' => 'float'),
        
        // Availability
        'available' => array('type' => 'boolean'),      
        'startDate' => array('type' => 'date'),
        'endDate' => array('type' => 'date'),
        
        // Text
        'messages' => array('type' => 'array')
        
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}