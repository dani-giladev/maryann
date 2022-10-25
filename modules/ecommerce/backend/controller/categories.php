<?php

namespace modules\ecommerce\backend\controller;

// Controllers
use core\config\controller\config;
use core\helpers\controller\helpers;
use core\backend\controller\backend;
use core\backend\controller\maintenance\typeTree as maintenance;
use core\backend\controller\maintenance\data;
use core\ajax\controller\ajax;
use modules\admin\controller\language as adminLang;
use modules\seo\controller\url as seoUrl;
use modules\ecommerce\controller\categories as ecommerceCategoriesController;
use modules\ecommerce\frontend\controller\lang as ecommerceLang;

/**
 * Backend E-commerce categories controller
 *
 * @author Dani Gilabert
 * 
 */
class categories extends backend
{
    private $_available_langs; 
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'ecommerce';
        $this->_categories_controller = new ecommerceCategoriesController();
        
        // Set available admin languages
        $language_controller = new adminLang();
        $this->_available_langs = $language_controller->getLanguages(true);        
    }
    
    public function saveTree($data)
    {
        $core_maintenance = new maintenance();
        $dc = new data($data);
        
        // Clean data. Only records
        $dc->clean();        

        // Get model
        $model = $dc->getModel();
        if ($model === false)
        {
            $msg = "The model is not defined";
            ajax::fuckYou($msg);
            return;                
        }    
        $type = $model->type;
        $code = $model->code;
        $record_id = $type.'-'.$code;
        $model->loadData($record_id); 

        // Get cleaned data
        $cleaned_data = $dc->getData();
        
        // Get tree
        $tree = array();
        $decoded_tree = json_decode($cleaned_data->tree);
        if (empty($decoded_tree))
        {
            ajax::fuckYou('Uppss! The tree is empty!');
            return;
        }
        $tree[] = $decoded_tree;
        
        // Update data tree
        $this->_updateTree($tree[0]->children);
        
        // Clean true
        $core_maintenance->cleanTree($tree[0]->children);
        
        // Get data of each category
        $categories = array();
        $core_maintenance->addCategories($tree[0]->children, $categories);
        
        // Get tree of each category
        $subcategories = array();
        $core_maintenance->addSubcategories($tree[0]->children, $subcategories);
        
        // Get bread scrumbs of each category
        $breadcrumbs = array();
        $parents = array();
        $core_maintenance->addBreadcrumbs($tree[0]->children, $breadcrumbs, $parents);
        
        // Redirect old urls
        $this->_redirectOldUrls($model->categories, $categories);
        
        // Set properties
        $model->tree = $tree;
        $model->categories = $categories;
        $model->subcategories = $subcategories;
        $model->breadcrumbs = $breadcrumbs;
        
        // Save
        $model->save();
        
        if ($dc->getPublish())
        {
            // Publish
            $model->publish();            
        }

        ajax::ohYeah();
        
        // Reset frontend session vars
        $this->_categories_controller->resetFrontendVars();
    }
    
    public function publishTree($data)
    {
        // Publishing
        $core_maintenance = new maintenance();
        $core_maintenance->publishTree($data);
        
        // Reset frontend session vars
        $this->_categories_controller->resetFrontendVars();
    }
    
    private function _updateTree(&$tree)
    {
        // Init vars to update urls
        $urls_parent = array();
        foreach ($this->_available_langs as $lang)
        {
            $urls_parent[$lang->code] = '';

        }        
        
        $this->_updateUrl($tree, $urls_parent);
    }
    
    private function _updateUrl(&$tree, $urls_parent)
    {
        foreach ($tree as $key => $value)
        {
            $any_children = false;
            $urls_parent_for_children = array();
            
            foreach ($this->_available_langs as $lang)
            {
                $url_property = 'url'.ucfirst($lang->code);
                if (isset($tree[$key]->_data->$url_property) && !empty($tree[$key]->_data->$url_property))
                {
                    $url = $tree[$key]->_data->$url_property;
                }
                else 
                {
                    $title = $this->_getTitle($value->_data, $lang->code);
                    $url = $urls_parent[$lang->code];
                    if (!empty($url))
                    {
                        $url .= '-';
                    }
                    $url .= helpers::slugify($title);
                    $tree[$key]->_data->$url_property = $url;
                }
                
                if (isset($value->children) && !empty($value->children))
                {
                    $any_children = true;
                    $urls_parent_for_children[$lang->code] = $url;
                }
            }
            
            if ($any_children)
            {
                self::_updateUrl($tree[$key]->children, $urls_parent_for_children);
            }
        }
    }
    
    private function _getTitle($data, $lang)
    {
        $title = '';
        
        $title_key = 'titles-'.$lang;
        if (isset($data->$title_key) && !empty($data->$title_key))
        {
            $title = $data->$title_key;
        }
        else
        {
            $default_language =  config::getConfigParam(array("application", "default_language"))->value;
            $title_key = 'titles-'.$default_language;
            if (isset($data->$title_key) && !empty($data->$title_key))
            {
                $title = $data->$title_key;
            }
        }
        
        return $title;
    }
    
    private function _redirectOldUrls($old_categories, $new_categories)
    {
        $new_categories = (object) $new_categories;
        $seo_url_controller = new seoUrl();
        $any_updated_url = false;
        
        foreach ($old_categories as $old_category)
        {
            $old_category_code = $old_category->code;
            $new_category_matched = null;
            foreach ($new_categories as $new_category)
            {
                $new_category_code = $new_category->code;
                if ($new_category_code === $old_category_code)
                {
                    $new_category_matched = $new_category;
                    break;
                }
            }              
            
            if (is_null($new_category_matched))
            {
                // Blocking all old urls (create new urls)
                foreach ($this->_available_langs as $lang)
                {
                    $url_property = 'url'.ucfirst($lang->code);
                    $old_url = "/".$lang->code."/".ecommerceLang::trans('url-categories', $lang->code)."/".$old_category->$url_property;
                    $url_doc = $seo_url_controller->getUrl($old_url);
                    $model = $seo_url_controller->getUrlModel();
                    if (empty($url_doc))
                    {
                        $model->code = date("YmdHis")."-".rand(100, 999);
                    }
                    else
                    {
                        $model->code = $url_doc->code;
                        $model->setNewId();
                    }
                    $model->url = $old_url;
                    $model->action = "blocking";
                    $model->save();
                    $any_updated_url = true;
                }                
                continue;
            }
            
            // Check if it's a redirection
            foreach ($this->_available_langs as $lang)
            {
                $url_property = 'url'.ucfirst($lang->code);
                if ($old_category->$url_property !== $new_category_matched->$url_property)
                {
                    // Redirection
                    $old_url = "/".$lang->code."/".ecommerceLang::trans('url-categories', $lang->code)."/".$old_category->$url_property;
                    $url_doc = $seo_url_controller->getUrl($old_url);
                    $model = $seo_url_controller->getUrlModel();
                    if (empty($url_doc))
                    {
                        $model->code = date("YmdHis")."-".rand(100, 999);
                    }
                    else
                    {
                        $model->code = $url_doc->code;
                        $model->setNewId();
                    }
                    $model->url = $old_url;
                    $model->action = "redirection";
                    $model->useAction = "redirect2Category";
                    $model->redirect2Category = $new_category_matched->code;
                    $model->save();
                    $any_updated_url = true;
                }
            } 
        }
        
        if ($any_updated_url)
        {
            // Refresh url views
            $seo_url_controller->updateViews();
        }
    }
}