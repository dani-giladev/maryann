<?php

namespace modules\ecommerce\model;

use core\model\controller\model;

/**
 * E-commerce gamma model
 *
 * @author Dani Gilabert
 * 
 */
class gamma extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'ecommerce-articlegamma'),
        'code' => array('type' => 'string'),
        'brand' => array('type' => 'string'),
        'brandName' => array('type' => 'string'),
        'name' => array('type' => 'string'),
        'description' => array('type' => 'string'),
        'available' => array('type' => 'boolean'),
        'visible' => array('type' => 'boolean'),
        'not_visible_in_article' => array('type' => 'boolean'),
        'discard_in_composition_of_article_title' => array('type' => 'boolean'),
        'titles' => array('type' => 'array'),
        'descriptions' => array('type' => 'array'),
        'discount' => array('type' => 'float')
    );
    
    protected $_id_COMPOSITION = array('type', 'code', 'brand');
    
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}