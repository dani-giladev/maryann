<?php

namespace modules\cms\model;

use core\model\controller\model;

/**
 * CMS website model
 *
 * @author Dani Gilabert
 * 
 */
class website extends model
{
    protected $_properties = array(
        'type' => array('type' => 'string', 'value' => 'cms-website'),
        'code' => array('type' => 'string'),
        'name' => array('type' => 'string'),
        'description' => array('type' => 'string'),
        'available' => array('type' => 'boolean'),
        'delegation' => array('type' => 'string'),
        'delegationName' => array('type' => 'string'),
        'websiteType' => array('type' => 'string'),
        'websiteTypeName' => array('type' => 'string'),
        'domain' => array('type' => 'string'),
        'languages' => array('type' => 'string'),
        'logo' => array('type' => 'string'),
        'facebook' => array('type' => 'string'),
        'twitter' => array('type' => 'string'),
        'googleplus' => array('type' => 'string'),
        'legalNotice' => array('type' => 'array'),
        'privacyPolicies' => array('type' => 'array'),
        'cookiesPolicies' => array('type' => 'array'),
        'conditionsOfSale' => array('type' => 'array'),
        'titles' => array('type' => 'array'),
        'descriptions' => array('type' => 'array'),
        'keywords' => array('type' => 'array'),
        'robots' => array('type' => 'string'),
        'phone' => array('type' => 'string'),
        'email' => array('type' => 'string'),
        'schedules' => array('type' => 'array'),
        'udata' => array('type' => 'array'),
        'googleAnalytics' => array('type' => 'string')
    );
    
    protected $_id_COMPOSITION = array('type', 'code', 'delegation');
    
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}