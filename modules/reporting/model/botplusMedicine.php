<?php

namespace modules\reporting\model;

use core\model\controller\model;

/**
 * Reporting botplus medicine report model
 *
 * @author Dani Gilabert
 * 
 */
class botplusMedicine extends model
{
    protected $_properties = array(
        
        // Main
        'type' => array('type' => 'string', 'value' => 'reporting-botplusmedicine'),
        'code' => array('type' => 'string'),
        
        'ESPENOM' => array('type' => 'string'),
        'ESPEDES' => array('type' => 'string'),
        'CODESTADO' => array('type' => 'string'),
        'DESCRIPCION' => array('type' => 'string'),
        'CODVIA' => array('type' => 'string'),
        'DESCRIPCION_VIA' => array('type' => 'string'),
        'FFARCOD' => array('type' => 'string'),
        'FFARDESC' => array('type' => 'string'),
        'ESPUNIE1' => array('type' => 'string'),
        'ESPUNIE' => array('type' => 'string'),
        'ESPELABEU' => array('type' => 'string'),
        'ESPEIVAEU' => array('type' => 'string'),
        'LABCOD' => array('type' => 'string'),
        'LABNOM' => array('type' => 'string'),
        'CODCONJUNTO' => array('type' => 'string'),
        'NOMBRE' => array('type' => 'string'),
        'ESPFEA' => array('type' => 'string'),
        'ESPEDESHAS' => array('type' => 'string'),
        'ESPFEE' => array('type' => 'string'),
        'GTVMPCOD' => array('type' => 'string'),
        'GTVMPDES' => array('type' => 'string'),
        
        'COMPOSICION' => array('type' => 'array'),
        'DATOSFARMACEUTICOS' => array('type' => 'array'),
        
        // Dynamic properties (for manipulating on backend)
        'COMP1' => array('type' => 'string'),
        'COMP2' => array('type' => 'string'),
        'COMP3' => array('type' => 'string'),
        'COMP4' => array('type' => 'string'),
        'COMP5' => array('type' => 'string'),
        'COMP6' => array('type' => 'string'),
        'COMP7' => array('type' => 'string'),
        'COMP8' => array('type' => 'string'),
        'COMP9' => array('type' => 'string'),
        'COMP10' => array('type' => 'string'),
        
        'DF1' => array('type' => 'string'),
        'DF2' => array('type' => 'string'),
        'DF3' => array('type' => 'string'),
        'DF4' => array('type' => 'string'),
        'DF5' => array('type' => 'string'),
        'DF6' => array('type' => 'string'),
        'DF7' => array('type' => 'string'),
        'DF8' => array('type' => 'string'),
        'DF9' => array('type' => 'string'),
        'DF10' => array('type' => 'string')
        
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->loadData($id);
    }
    
}