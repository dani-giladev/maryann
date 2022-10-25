<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\helpers\controller\helpers;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\availability;

/**
 * Brands controller
 *
 * @author Dani Gilabert
 * 
 */
class brands extends ecommerce
{
    
    protected function _getBrandsClassifiedByLetter()
    {
        $availability = new availability();
        $brands = $availability->getBrands();
        
        if (!isset($brands) || empty($brands))
        {
            return array();
        }
        
        $brands_sorted_by_letter = array();
        foreach ($brands as $brand)
        {
            if (strlen($brand->name) <= 0)
            {
                continue;
            }
            
            if (
                    !$brand->available || 
                    (isset($brand->visible) && !$brand->visible) || 
                    (isset($brand->empty) && $brand->empty)
            )
            {
                continue;
            }
            
            if (isset($brand->outstanding) && $brand->outstanding)
            {
                $letter = 'OUTSTANDING';
                $brands_sorted_by_letter[$letter][] = $brand;
            }
            
            $letter = strtoupper(substr($brand->name, 0, 1));
            $brands_sorted_by_letter[$letter][] = $brand;
        }        
        ksort($brands_sorted_by_letter);
        
        foreach ($brands_sorted_by_letter as $letter => $brands_by_letter)
        {
            $brands_sorted_by_letter[$letter] = helpers::sortArrayByField($brands_by_letter, 'name');
        }
        
        return $brands_sorted_by_letter;
    }
    
}