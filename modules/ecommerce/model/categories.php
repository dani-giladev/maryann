<?php

namespace modules\ecommerce\model;

use core\model\controller\model;

/**
 * E-commerce categories model
 *
 * @author Dani Gilabert
 * 
 */
class categories extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'ecommerce-articlecategories'),
        'code' => array('type' => 'string', 'value' => 'tree'),
        'tree' => array('type' => 'array'),
        'categories' => array('type' => 'array'),
        'subcategories' => array('type' => 'array'),
        'breadcrumbs' => array('type' => 'array')
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    protected $_publication_mode = 'OTHER_DOCUMENT';
    
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }

}