<?php

namespace modules\ecommerce\controller;

// Controllers
use modules\ecommerce\controller\ecommerce;

// Models
use modules\ecommerce\model\brandType as brandTypeModel;

/**
 * E-commerce brand type controller
 *
 * @author Dani Gilabert
 * 
 */
class brandType extends ecommerce
{
    protected function _getBrandTypeModel($id = null)
    {
        return new brandTypeModel($id);
    }

}