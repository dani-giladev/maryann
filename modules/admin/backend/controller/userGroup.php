<?php

namespace modules\admin\backend\controller;

// Controllers
use core\ajax\controller\ajax;
use core\config\controller\config;
use core\helpers\controller\helpers;
use core\backend\controller\backend;

// Models
use modules\admin\model\userGroup as userGroupModel;

/**
 * Backend admin user groups controller
 *
 * @author Dani Gilabert
 * 
 */
class userGroup extends backend
{
    
    public function getPermissions()
    {
        $arr = array();
        $modules = config::getInitParam(array("modules"))->value;  
        foreach ($modules as $module_id)
        {
            $lic = array();
            $lic['module_id'] = $module_id;
            $lic['module_name'] = $this->trans($module_id, 'core');
            $lic['permissions'] = $module_id;
            $arr[] = $lic;
        }
        $ret = helpers::objectize($arr);
        ajax::sendData($ret);
    }
    
    public function getPermissionsByModule($data)
    {
        $id = $data->group_id;
        $module_id = $data->module_id;
        
        $arr = array();

        $menu_file = 'modules/'.$module_id.'/backend/res/menu/menu.json';
        if(!file_exists($menu_file))
        {
            $ret = helpers::objectize($arr);
            ajax::sendData($ret);
            return;
        }             
        $object_file = json_decode(file_get_contents($menu_file));
        $items_menu = $this->_getLeafItemsMenu($object_file);
        
        if ($id !== '')
        {
            $model = new userGroupModel();
            $storage = $model->loadData($id);     
            if (!is_null($storage))
            {
                $permissions = $model->permissions;
            }                
        }
            
        foreach ($items_menu as $values)
        {
            $menu = array();
            $menu_id = $values->alias;
            $menu['menu_id'] = $menu_id;        
            $menu['menu_text'] = $this->trans($menu_id.'_menu', $module_id);
            
            if (isset($permissions->$module_id->$menu_id) )
            {
                $menu['visualize'] = $permissions->$module_id->$menu_id->visualize;
                $menu['update'] = $permissions->$module_id->$menu_id->update;
                $menu['delete'] = $permissions->$module_id->$menu_id->delete;
                $menu['publish'] = $permissions->$module_id->$menu_id->publish;         
            }
            else
            {
                $menu['visualize'] = false;
                $menu['update'] = false;
                $menu['delete'] = false; 
                $menu['publish'] = false;                   
            }

            $arr[] = $menu;            
        }        
        
        $ret = helpers::objectize($arr);
        ajax::sendData($ret);
    }
    
    private function _getLeafItemsMenu($menu)
    {
        $arr = array();

        foreach ($menu as $values)
        {
            if (isset($values->children))
            {
                $items = $this->_getLeafItemsMenu($values->children);
                if (!empty($items))
                {
                    foreach ($items as $item_key => $item_values)
                    {
                        $arr[] = $item_values;
                    }                       
                }            
            }
            else
            {
                $arr[] = $values;
            }
        }   
        
        return $arr;
    }
    
    public function savePermissions($data)
    {
        $model = new userGroupModel();
        $model_type = $model->type;
        $id = $model_type.'-'.strtolower($data->code);
        $storage = $model->loadData($id);
        
        if (is_null($storage))
        {
            $msg = "The record with code"." '".$data->code."' "."doesn't exists";
            ajax::fuckYou($msg);
            return;
        }

        $json_records = str_replace('&#34;', '"', $data->records);
        $records = json_decode($json_records);
        if(!isset($records) || empty($records))
        {
            $msg = "There aren't records to save (code"." '".$data->code."')";
            ajax::fuckYou($msg);
            return;
        }
        
        $custom_permissions = array();
        foreach ($records as $values)
        {
            $module_id = $values->module_id;
            foreach ($values->records as $rc_values)
            {
                $menu = array();
                $menu_id = $rc_values->menu_id;
                $menu['visualize'] = $rc_values->visualize;
                $menu['update'] = $rc_values->update;
                $menu['delete'] = $rc_values->delete;
                $menu['publish'] = $rc_values->publish;
                $custom_permissions[$module_id][$menu_id] = $menu;
            }            
        }          
        
        // Set property
        $model->permissions = $custom_permissions;
        
        // Save
        $model->save();

        if ($data->publish)
        {
            // Publish
            $model->publish();            
        }   
        
        ajax::ohYeah();        
    }
}
