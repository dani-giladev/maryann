<?php

namespace modules\ecommerce\model;

// Controllers
use modules\admin\controller\language as adminLang;

// Models
use core\model\controller\model;

/**
 * E-commerce article model
 *
 * @author Dani Gilabert
 * 
 */
class article extends model
{
    protected $_properties = array(
        
        // Main
        'type' => array('type' => 'string', 'value' => 'ecommerce-article'),
        'code' => array('type' => 'string'),
        'delegations' => array('type' => 'string'),
        'articleType' => array('type' => 'string'),
        'articleTypeName' => array('type' => 'string'),        
        'brand' => array('type' => 'string'),
        'brandName' => array('type' => 'string'),
        'gamma' => array('type' => 'string'),
        'gammaName' => array('type' => 'string'),        
        'family' => array('type' => 'string'),
        'familyName' => array('type' => 'string'),
        'articleCode2GroupDisplays' => array('type' => 'string'),
        'articleName2GroupDisplays' => array('type' => 'string'),
        
        // Properties
        'properties' => array('type' => 'array'),      
        'outstanding' => array('type' => 'boolean'),
        'novelty' => array('type' => 'boolean'), 
        'pack' => array('type' => 'boolean'), 
        'christmas' => array('type' => 'boolean'), 
        
        // Categories
        'categories' => array('type' => 'string'),
        
        // Descriptions     
        'titles' => array('type' => 'array'),
        'displays' => array('type' => 'array'),  
        'shortDescriptions' => array('type' => 'array'), 
        'metaDescriptions' => array('type' => 'array'), 
        'descriptions' => array('type' => 'array'),
        'applications' => array('type' => 'array'),
        'activeIngredients' => array('type' => 'array'),
        'compositions' => array('type' => 'array'),
        'prospects' => array('type' => 'array'),
        'dataSheets' => array('type' => 'array'),
        
        // Images
        'images' => array('type' => 'array'), 
        
        // Prices
        'saleRate' => array('type' => 'string'),
        'saleRateName' => array('type' => 'string'),
        'costPrice' => array('type' => 'float'),
        'margin' => array('type' => 'float'),
        'useMargin' => array('type' => 'string'),
        'basePriceForCostPrice0' => array('type' => 'float'),
        'includedVat' => array('type' => 'boolean'),
        'recommendedRetailPrice' => array('type' => 'float'),
        'discount' => array('type' => 'float'),
        'useDiscount' => array('type' => 'string'),
        'finalRetailPrice' => array('type' => 'float'),
        'minMarginWarning' => array('type' => 'float'),
        'secondUnitDiscount' => array('type' => 'float'),
        
        // Stock
        'infinityStock' => array('type' => 'boolean'),
        'stock' => array('type' => 'integer'),
        'visibleIfNoStock' => array('type' => 'boolean'),        
        
        // ERP
        'erp' => array('type' => 'array'),
        'syncStock' => array('type' => 'boolean'),
        'syncCostPrice' => array('type' => 'boolean'),
        'syncMargin' => array('type' => 'boolean'),        
        
        // Availability
        'available' => array('type' => 'boolean'),      
        'startDate' => array('type' => 'date'),
        'endDate' => array('type' => 'date'),
        
        // SEO
        'canonical' => array('type' => 'string'),
        'canonicalName' => array('type' => 'string'),
        'keywords' => array('type' => 'array'),          
        'gtin' => array('type' => 'string'),
        'googleShopping' => array('type' => 'boolean'),   
        
        // Reviews
        'validated' => array('type' => 'boolean'),
        'spellcheck' => array('type' => 'boolean'),
        'checkedByPharmacist' => array('type' => 'boolean'),
//        'translatedToEnglish' => array('type' => 'boolean'),
        'checkedPackagingDate' => array('type' => 'date'),
        
        // Misc
        'notes' => array('type' => 'string'),
        'cloned' => array('type' => 'boolean'),
        
        // Dynamic properties (for manipulating on backend)
        'name' => array('type' => 'string'),
        'anyStock' => array('type' => 'boolean'),
        'inErp' => array('type' => 'boolean'),
        'forSale' => array('type' => 'boolean'),
        'forSaleAndVisible' => array('type' => 'boolean'),
        'anyGtin' => array('type' => 'boolean'),
        'checkedPackaging' => array('type' => 'boolean'),
        'anyImage' => array('type' => 'boolean')
        
    );
    
    protected $_id_COMPOSITION = array('type', 'code');
    protected $_publication_mode = 'OTHER_DOCUMENT';
    
    public function __construct($id = null)
    {
        parent::__construct();
        
        // Define properties dinamically
        $language_controller = new adminLang();
        $available_langs = $language_controller->getLanguages();
        foreach ($available_langs as $lang)
        {
            $property_name = 'url'.ucfirst($lang->code);
            $this->_properties[$property_name] = array('type' => 'string');
        }  
        
        $this->loadData($id);
    }

}