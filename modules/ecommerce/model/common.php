<?php

namespace modules\ecommerce\model;

use core\model\controller\model;

/**
 * E-commerce common model
 *
 * @author Dani Gilabert
 * 
 */
class common extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'ecommerce-common'),
        'code' => array('type' => 'string', 'value' => 'common'),
        
        'date_time_last_checking_changes_in_farmartic_article_codes' => array('type' => 'string')
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}