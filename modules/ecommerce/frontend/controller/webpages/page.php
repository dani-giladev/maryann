<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use core\config\controller\config;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;

// Views
use modules\ecommerce\frontend\view\page as view;

/**
 * Empty webpage controller (dynamic content)
 *
 * @author Dani Gilabert
 * 
 */
class page extends ecommerce
{
    protected $_view;
    protected $_html = '';
    protected $_name = '';

    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
    }
    
    public function init($data)
    {
        // Get the page code
        if (!isset($data->code))
        {
            $this->goToShowcaseWebpage();
            return;            
        }
        
        $website = $this->getWebsite();
        $current_lang = lang::getCurrentLanguage();
        
        
        // Set name (title)
        $is_website_property = true;
        if ($data->code == 'legal-notice')
        {
            $website_property = 'legalNotice';
            $this->_name = lang::trans('legal_notice');
        }
        elseif ($data->code == 'privacy-policy')
        {
            $website_property = 'privacyPolicies';
            $this->_name = lang::trans('privacy_policy');
        } 
        elseif ($data->code == 'cookies-policy')
        {
            $website_property = 'cookiesPolicies';
            $this->_name = lang::trans('cookies_policy');
        } 
        elseif ($data->code == 'conditions-of-sale')
        {
            $website_property = 'conditionsOfSale';
            $this->_name = lang::trans('conditions_of_sale');
        }
        else
        {
            $is_website_property = false;
            if ($data->code == 'faq')
            {
                $this->_name = lang::trans('faq');
            }
            elseif ($data->code == 'shipments')
            {
                $this->_name = lang::trans('shipments');
            }
            elseif ($data->code == 'devolutions')
            {
                $this->_name = lang::trans('devolutions');
            }
            elseif ($data->code == 'sale-of-medicines')
            {
                $this->_name = lang::trans('sale_of_medicines');
            }
            else
            {
                $this->goToError404Webpage();
                return;                 
            }
        }
        
        // Set content
        if ($is_website_property)
        {
            if (isset($website->$website_property->$current_lang) && !empty($website->$website_property->$current_lang))
            {
                $html = $website->$website_property->$current_lang;
            }
            else
            {
                $default_language =  config::getConfigParam(array("application", "default_language"))->value;
                $html = $website->$website_property->$default_language;
            }            
        }
        else
        {
            $base_path = config::getConfigParam(array("application", "base_path"))->value;
            $html_path = $base_path.'/'.config::getProjectPath()."/html/info/$data->code.html";
            $html = file_get_contents($html_path);
        }
        
        // Replace tags in content
        $URL_CONDITIONS_OF_SALE = $this->getUrl(array($current_lang, 'page'), array('code' => 'conditions-of-sale'));
        $MINIMUM_PURCHASE_AMOUNT = config::getConfigParam(array("ecommerce", "minimum_purchase_amount"))->value;
        $SHIPPING_COST = config::getConfigParam(array("ecommerce", "shipping_cost"))->value;
        $FREE_SHIPPING_COST_FROM = config::getConfigParam(array("ecommerce", "free_shipping_cost_from"))->value;
        $DEVOLUTION_COST = $SHIPPING_COST * 2;
        // Replace!
        $html = str_replace(array(
            '$URL_CONDITIONS_OF_SALE',
            '$MINIMUM_PURCHASE_AMOUNT',
            '$SHIPPING_COST',
            '$FREE_SHIPPING_COST_FROM',
            '$DEVOLUTION_COST'
        ), array(
            $URL_CONDITIONS_OF_SALE,
            $MINIMUM_PURCHASE_AMOUNT,
            $this->_view->renderPriceFormat($SHIPPING_COST),
            $this->_view->renderPriceFormat($FREE_SHIPPING_COST_FROM, true),
            $this->_view->renderPriceFormat($DEVOLUTION_COST),
        ), $html);
        
        // Set final content
        $this->_html = $html;
        
        // Render this page
        $this->renderPage();
    }
    
    protected function _getTitle()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.$this->_name;
    }           
    
    protected function _getDescription()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.$this->_name;
    }           
    
    protected function _getKeywords()
    {
        return '';
    }           
    
    protected function _getRobots()
    {
        return 'noindex, nofollow';
    }
    
    protected function _renderMenu()
    {
        $html = '';
        
        // Render main menu
        $main_menu = new mainMenu(null, false, true);
        $html .= $main_menu->renderMainMenu();
        
        // Render breadcrumbs menu
        $breadcrumbs = array(array('text' => $this->_name, 'url' => ''));
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'breadcrumbs' => $breadcrumbs
        ));           
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    }
    
    protected function _renderContent()
    {
        $html = '';
        
        $html .= $this->_view->renderContent($this->_html);
        
        return $html;
    }
    
}