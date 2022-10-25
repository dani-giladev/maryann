<?php

namespace modules\marketing\controller;

// Controllers
use modules\marketing\controller\marketing;

// Models
use modules\marketing\model\voucher as voucherModel;

/**
 * Marketing voucher controller
 *
 * @author Dani Gilabert
 * 
 */
class voucher extends marketing
{
    
    protected function _getVoucherModel($id = null)
    {
        return new voucherModel($id);
    }
    
    public function getVoucherByCode($code)
    {
        $model = $this->_getVoucherModel();           
        $model_type = $model->type;
        $id = $model_type.'-'.strtolower($code);
        $storage = $model->loadData($id);     
        if (is_null($storage)) return null;  
        return $model;
    }
    
    public function isValid($voucher)
    {
        // Is it available?
        if (!$voucher->available)
        {
            return false;
        }

        // Is start and en date matching?
        if (!$this->_isStartEndDatesMatching($voucher))
        {
            return false;
        }
        
        return true;
    }
    
    protected function _isStartEndDatesMatching($voucher)
    {
        $now = strtotime(date('d-m-Y'));
        
        if (isset($voucher->startDate) && !empty($voucher->startDate))
        {
            $startDate = strtotime($voucher->startDate);
            if ($now < $startDate) return false;
        }
        
        if (isset($voucher->endDate) && !empty($voucher->endDate))
        {
            $endDate = strtotime($voucher->endDate);
            if ($now > $endDate) return false;
        }
        
        return true;
    }

}