<?php

namespace modules\admin\model;

// Controllers
use core\config\controller\config;
use core\model\controller\model;

/**
 * Admin user group model
 *
 * @author Dani Gilabert
 * 
 */
class userGroup extends model
{
    protected $_properties = array(
        'code' => array('type' => 'string'),
        'name' => array('type' => 'string'),
        'description' => array('type' => 'string'),
        'available' => array('type' => 'boolean'),
        'permissions' => array('type' => 'array'),
        'type' => array('type' => 'string', 'value' => 'admin-usergroup')
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    public function __construct($id = null)
    {
        $this->_properties['type'] = array('type' => 'string', 'value' => 'admin-usergroup');
        
        parent::__construct($id);
        
        // Define properties dinamically
        $modules = config::getInitParam(array("modules"))->value;  
        foreach ($modules as $module_id)
        {
            $property_name = 'grantedPermission_'.$module_id;
            $this->_properties[$property_name] = array('type' => 'string');          
        }        
        
        // Load data
        $this->loadData($id);
    }

}