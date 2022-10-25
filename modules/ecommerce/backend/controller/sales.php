<?php

namespace modules\ecommerce\backend\controller;

// Controllers
use core\ajax\controller\ajax;
use core\backend\controller\maintenance\type1 as maintenance;
use core\backend\controller\maintenance\data;
use modules\ecommerce\controller\sales as ecommerceSalesController;
use modules\ecommerce\frontend\controller\ecommerce as ecommerceFrontendController;

/**
 * Backend E-commerce controller for sales
 *
 * @author Dani Gilabert
 * 
 */
class sales extends maintenance
{
    protected $_ecommerce_sales_controller;
    protected $_ecommerce_frontend_controller;

    public function __construct()
    {
        $this->module_id = 'ecommerce';
        $this->_ecommerce_sales_controller = new ecommerceSalesController();
        $this->_ecommerce_frontend_controller = new ecommerceFrontendController();
    }
    
    public function getRecords($data, $send_data = true)
    {
        $dc = new data($data);
        
        // Get model
        $model = $dc->getModel();
        if ($model === false)
        {
            $msg = "The model is not defined";
            ajax::fuckYou($msg);
            return;                
        }    
        //$model_type = $model->type;

        // Get data from specific view
        $stale = $dc->getStale();
        $object = $this->_ecommerce_sales_controller->getData($model, $stale, $data->delegation, $data->startDate, $data->endDate);
        
        // Filtering
        $dc->filtering($object);
        
        // Add data
        $dc->addData($object);
        
        // Discard fields
        $dc->discardFields($object);

        $ret = $object;
        ajax::sendData($ret);  
    }
    
    public function cancelRecord($data)
    {
        $dc = new data($data);
        
        // Get model
        $record_id = $dc->getRecordId();
        $model = $dc->getModel($record_id);
        if ($model === false)
        {
            $msg = "The model is not defined";
            ajax::fuckYou($msg);
            return;                
        }

        // Check if exist
        if(!$model->exists())
        {
            $msg = "The record or file with id: '".$record_id."' does not exists";
            ajax::fuckYou($msg);
            return;
        }
        
        // Cancel or delete
        if (strtoupper($data->cancellation_reason) == '>DELETE')
        {
            // Delete
            $model->delete();            
        }
        else
        {
            // Cancel
            $model->cancelled = true;
            $model->cancellationReason = $data->cancellation_reason;
            $model->save();            
        }
        
        // Call the view to refresh the data with stale=false
        $object = $this->_ecommerce_sales_controller->getData($model, false, $data->delegation, '2000-01-01', '2000-01-01');        
        
        ajax::ohYeah();
    }
    
    public function getUrl($data)
    {
        $code = $data->code;         
        $current_lang = $this->_ecommerce_frontend_controller->getCurrentLanguage();
        $url = $this->_ecommerce_frontend_controller->getUrl(array($current_lang, 'bond'), array('code' => $code));
        ajax::ohYeah($url);
    }
}