<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\shoppingcart;
use modules\ecommerce\frontend\controller\personaldata;
use modules\ecommerce\frontend\controller\voucher;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;

// Views
use modules\ecommerce\frontend\view\validation as view;
use modules\ecommerce\frontend\view\finalSteps as finalStepsView;
use modules\ecommerce\frontend\view\actionButtons as actionButtonsView;

/**
 * Validation form webpage
 *
 * @author Dani Gilabert
 * 
 */
class validation extends ecommerce
{
    protected $_view;
    protected $_final_steps_view;
    protected $_action_buttons_view;
    protected $_categories;

    public function __construct()
    {
        parent::__construct();
        $this->_view = new view();
        $this->_final_steps_view = new finalStepsView();
        $this->_final_steps_view->main_controller = new ecommerce();
        $this->_action_buttons_view = new actionButtonsView();
    }
    
    public function init()
    {
        // If shoppingcart or personal data are empty, render showcase
        $shoppingcart = new shoppingcart();
        $personaldata = new personaldata();
        if ($shoppingcart->isEmpty() || $shoppingcart->isFinalTotalPriceInsufficient() || $personaldata->isEmpty())
        {
            $this->goToShowcaseWebpage();
            return;
        }        
        
        // Render this page
        $this->renderPage();
    }    
    
    protected function _getTitle()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('confirmation');
    }           
    
    protected function _getDescription()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('confirmation');
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
        $current_lang = lang::getCurrentLanguage();
        
        // Render main menu
        $main_menu = new mainMenu(null, true, false);
        $html .= $main_menu->renderMainMenu();
        $this->_categories = $main_menu->getCategoriesTree();
        
        // Render breadcrumbs menu
        $breadcrumbs = array(
                                array('text' => lang::trans('order_summary'), 'url' => $this->getUrl(array($current_lang, 'shoppingcart'))),            
                                array('text' => lang::trans('shipping_data'), 'url' => $this->getUrl(array($current_lang, 'personaldata'))),            
                                array('text' => lang::trans('confirmation'), 'url' => '')
                            );
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'breadcrumbs' => $breadcrumbs, 
            'categories' => $this->_categories, 
            'show_shoppingcart_button' => false,
            'show_ordering_button' => false
        ));        
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    }
    
    protected function _renderContent()
    {
        $current_lang = lang::getCurrentLanguage();
        $html = '';
        
        // Render final steps menu
        $html .= $this->_final_steps_view->renderFinalStepsMenu(3);
        
        // Render view content
        $html .= $this->_renderViewContent();
        
        // Render action buttons
        $html .= $this->_action_buttons_view->renderActionButtons($continue_shopping_button = array('visible' => true,
                                                                                                    'type' => 'button',
                                                                                                    'text' => lang::trans('continue_shopping'),
                                                                                                    'onClick' => 'window.location.href=\''.$this->getUrl(array($current_lang, 'showcase')).'\''),
                                                                  $ordering_button = array('visible' => true,
                                                                                           'type' => 'button',
                                                                                           'text' => lang::trans('continue'),
                                                                                           'onClick' => 'validate()'));
        return $html;
    }
    
    private function _renderViewContent()
    {
        $html = '';
        
        $shoppingcart_controller = new shoppingcart();
        $personaldata_controller = new personaldata();
        $voucher_controller = new voucher();
        
        $sale_data = array(
            'firstName' => $personaldata_controller->getFirstName(),
            'lastName' => $personaldata_controller->getLastName(),
            'email' => $personaldata_controller->getEmail(),   
            'phone' => $personaldata_controller->getPhone(), 
            'company' => $personaldata_controller->getCompany(),
            'address' => $personaldata_controller->getAddress(),
            'postalCode' => $personaldata_controller->getPostalCode(),
            'city' => $personaldata_controller->getCity(),
            'country' => $personaldata_controller->getCountry(),
            'comments' => $personaldata_controller->getComments(),
            
            'shoppingcart' => $shoppingcart_controller->getShoppingcart(),
            'totalPrice' => $shoppingcart_controller->getTotalPrice(),
            'shippingCost' => $shoppingcart_controller->getShippingCost(),
            'voucher' => $voucher_controller->getVoucher(),
            'voucherDiscount' => $shoppingcart_controller->getVoucherDiscount(),
            'secondUnitDiscount' => $shoppingcart_controller->get2ndUnitDiscount(),
            'finalTotalPrice' => $shoppingcart_controller->getFinalTotalPrice()            
        );
        
        $this->_view->sale_data = (object) $sale_data;
        $html .= $this->_view->renderForm();
        
        return $html;
    }
    
    public function validate()
    {
        $success = true;
        $msg = '';
        $redirect_url = '';
        $current_lang = lang::getCurrentLanguage();
        
        $shoppingcart = new shoppingcart();
        if ($shoppingcart->isEmpty())
        {
            $success = false;
            $msg = lang::trans('expired_session');
            $redirect_url = $this->getUrl(array($current_lang, 'showcase'), array('start'));
        }
        
        // Happy end
        if ($success)
        {
            $redirect_url = $this->getUrl(array($current_lang, 'payment'));
        }
        
        // Return result
        $ret['success'] = $success;
        $ret['msg'] = $msg;
        $ret['redirectUrl'] = $redirect_url;  
        $ret = json_encode($ret);
        echo $ret;          
    }
    
}