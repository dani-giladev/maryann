<?php

namespace modules\ecommerce\controller;

// Controllers
use modules\ecommerce\controller\ecommerce;
use modules\ecommerce\frontend\controller\session as frontendSession;

// Models
use modules\ecommerce\model\categories as categoriesModel;

/**
 * E-commerce categories (tree) controller
 *
 * @author Dani Gilabert
 * 
 */
class categories extends ecommerce
{
    
    protected function _getCategoriesModel($id = null)
    {
        return new categoriesModel($id);
    }
    
    public function getCategoriesTree($public = false)
    {
        $model = $this->_getCategoriesModel();
        $model_type = $model->type;
        $code = $model->code;        
        $id = $model_type.'-'.$code;

        if ($model->isPublicationEnabled() && $model->getPublicationMode() === 'OTHER_DOCUMENT')
        {
            $id = ($public) ? ('public-'.$id) : $id;
        }
        
        $storage = $model->loadData($id);     
        if (is_null($storage)) return null;
        
        $ret = $storage;
        unset($ret->public);
        
        return $ret;
    }
    
    public function getCategoryByUrl($lang, $url, $public = true, $categories = null)
    {
        $auxiliar_category = null;
        
        if (!isset($categories))
        {
            $categories = $this->getCategoriesTree($public);  
        }
        
        if (!isset($categories)) return null;
        
        $url_property = 'url'.ucfirst($lang);
        foreach ($categories->categories as $key => $value) {
            if (isset($value->$url_property) &&
                !empty($value->$url_property) &&
                $value->$url_property === $url)
            {
                return $value;
            }
            if ($key === $url)
            {
                $auxiliar_category = $value;
            }
        }
        
        return $auxiliar_category;
    }
    
    public function resetFrontendVars()
    {
        // Reset frontend session vars
        $session_controller = new frontendSession();
        $session_controller->resetCategories();
    }

}