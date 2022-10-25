<?php

namespace modules\admin\model;

// Controllers
use core\config\controller\config;
use core\helpers\controller\helpers;
use core\model\controller\model;

/**
 * Admin user model
 *
 * @author Dani Gilabert
 * 
 */
class user extends model
{
    protected $_properties = array(
        'code' => array('type' => 'string'),
        'password' => array('type' => 'password'),
        'firstName' => array('type' => 'string'),
        'lastName' => array('type' => 'string'),
        'available' => array('type' => 'boolean'),
        'group' => array('type' => 'string'),
        'groupName' => array('type' => 'string'),
        'loginLang' => array('type' => 'string'),
        'superUser' => array('type' => 'boolean', 'defaultValue' => false),
        'delegations' => array('type' => 'string'),
        'clubs' => array('type' => 'string'),
        'type' => array('type' => 'string', 'value' => 'admin-user')
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    public function __construct($id = null)
    {
        $this->_properties['type'] = array('type' => 'string', 'value' => 'admin-user');
        $this->_properties['delegations'] = array('type' => 'string');
        
        parent::__construct($id);
        $this->loadData($id);
    }

    public function hidePassword()
    {
        $this->password = "finger"; // Just to hide the password when weh store the object in cookies
    }
    
    public function getFullName()
    {
        $full_name = $this->firstName.' '.$this->lastName;
        return $full_name;
    }
    
    public function anyPermission()
    {
        $permissions = $this->getPermissions();
        if (!isset($permissions))
        {
            return false;
        }
        
        foreach ($permissions as $values)
        {
            if ($values->granted === 'all')
            {
                return true;
            }
            elseif ($values->granted === 'none') 
            {
                continue;
            }
            else
            {
                // Custom
                if (!isset($values->custom))
                {
                    continue;
                }
                foreach ($values->custom as $values)
                {                
                    if ($values->visualize)
                    {
                        return true;
                    }
                }
            }
        }        
        
        return false;
        
    }
    
    public function getVisibleModules()
    {
        $arr = null;
        
        $permissions = $this->getPermissions();
        if (!isset($permissions))
        {
            return $arr;
        }       
        
        foreach ($permissions as $key => $values)
        {
            if ($values->granted === 'all')
            {
                $arr[] = $key;
            }
            elseif ($values->granted === 'none') 
            {
                continue;
            }
            else
            {
                // Custom
                if (!isset($values->custom))
                {
                    continue;
                }
                foreach ($values->custom as $values)
                {                
                    if ($values->visualize)
                    {
                        $arr[] = $key;
                        break;
                    }
                }
            }
        }        

        $ret = helpers::objectize($arr);
        return $ret;
    }
    
    public function getVisibleItemsMenu($module_id)
    {
        $arr = null;
        
        $permissions = $this->getPermissions();
        if (!isset($permissions->$module_id) || !isset($permissions->$module_id->custom))
        {
            return $arr;
        }       
        
        foreach ($permissions->$module_id->custom as $key => $values)
        {
            if ($permissions->$module_id->granted === 'all')
            {
                $arr[] = $key;
                continue;
            }
            elseif ($permissions->$module_id->granted === 'none')
            {
                break;
            }       

            if ($values->visualize)
            {
                $arr[] = $key;
            }            
        }        
        
        $ret = helpers::objectize($arr);
        return $ret;                
    }
    
    public function getPermissions()
    {
        $arr = null;
        
        $modules = config::getInitParam(array("modules"))->value;  
        if ($this->superUser)
        {
            foreach ($modules as $module_id)
            {
                $arr[$module_id]['granted'] = 'all';
                $arr[$module_id]['custom'] = 'IamSuperUser'; 
            }      
            $ret = helpers::objectize($arr);
            return $ret;
        }
            
        $id = $this->id;
        $group = $this->group;
        if (!isset($id) || !isset($group))
        {
            return $arr;
        }
        
        // Find group
        $model = new userGroup();
        $model_type = $model->type;
        $id = $model_type.'-'.strtolower($this->group);
        $model->loadData($id);     
        if(!$model->exists() || !$model->available)
        {
            return $arr;
        }   
            
        $permissions = $model->permissions;
        
        foreach ($modules as $module_id)
        {
            $granted_property = 'grantedPermission_'.$module_id;
            $granted_permission_module = $model->$granted_property; 
            if (isset($granted_permission_module))
            {
                $arr[$module_id]['granted'] = $granted_permission_module;
                if (isset($permissions->$module_id))
                {
                    $arr[$module_id]['custom'] = $permissions->$module_id;
                }
            }    
        }    

        $ret = helpers::objectize($arr);
        return $ret;
    }    
}