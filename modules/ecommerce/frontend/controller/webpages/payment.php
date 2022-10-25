<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use core\config\controller\config;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\personaldata;
use modules\ecommerce\frontend\controller\payment as paymentController;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;

// Views
use modules\ecommerce\frontend\view\payment as view;
use modules\ecommerce\frontend\view\finalSteps as finalStepsView;
use modules\ecommerce\frontend\view\actionButtons as actionButtonsView;

/**
 * Payment form webpage
 *
 * @author Dani Gilabert
 * 
 */
class payment extends paymentController
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
        $this->_action_buttons_view = new actionButtonsView();
    }
    
    public function init($data)
    {
        // If shoppingcart or personal data are empty, render showcase
        $personaldata = new personaldata();
        if ($this->_shoppingcart_controller->isEmpty() || $this->_shoppingcart_controller->isFinalTotalPriceInsufficient() || $personaldata->isEmpty())
        {
            $this->goToShowcaseWebpage();
            return;
        }
        
        // Set order code
        $this->_ordering_controller->createNewOrderCode();
        
        // Set failed msg
        $this->_setPaymentFailedMsg($data);
        
        // Set payment ways options
        $this->_setPaymentWaysOptions();
        
        // Render this page
        $this->renderPage();
    }      
    
    private function _setPaymentFailedMsg($data)
    {
        if (isset($data->payment_failed_msg))
        {
            $this->_view->payment_failed_msg = $data->payment_failed_msg;
        }
        else
        {
            // Is redsys request?
            if ($this->_redsys_controller->isRedsysRequest($data))
            {
                $url_ko_result = $this->_redsys_controller->getUrlKOResult($data);
                $this->_view->payment_failed_msg = $this->_beautyPaymentFailedMsg($url_ko_result['msg']);
            }            
        }
    }  
    
    private function _setPaymentWaysOptions()
    {
        $payment_ways_params =  config::getConfigParam(array("ecommerce", "payment_ways"))->value;
        if (isset($payment_ways_params->credit_card) && $payment_ways_params->credit_card)
        {
            $this->_view->credit_card_options = $this->_getCardParams('card');
        }
        if (isset($payment_ways_params->click_to_pay) && $payment_ways_params->click_to_pay)
        {
            if ($this->_user_controller->isLoggedUser() && !empty($this->getCardToken()))
            {
                $this->_view->clicktopay_options = $this->_getCardParams('clicktopay');
            }
        }
        if (isset($payment_ways_params->iupay) && $payment_ways_params->iupay)
        {
            $this->_view->iupay_options = $this->_getCardParams('iupay');
        }
        if (isset($payment_ways_params->paypal) && $payment_ways_params->paypal)
        {
            $this->_view->paypal_options = $this->_getPaypalParams();
        }
    }   
    
    protected function _getTitle()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('payment');
    }           
    
    protected function _getDescription()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('payment');
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
                                array('text' => lang::trans('confirmation'), 'url' => $this->getUrl(array($current_lang, 'validation'))),            
                                array('text' => lang::trans('payment'), 'url' => '')
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
        $html .= $this->_final_steps_view->renderFinalStepsMenu(4);
        
        // Render form
        $html .= $this->_view->renderForm();
        
        // Render action buttons
        $html .= $this->_action_buttons_view->renderActionButtons($continue_shopping_button = array('visible' => true,
                                                                                                    'type' => 'button',
                                                                                                    'text' => lang::trans('continue_shopping'),
                                                                                                    'onClick' => 'window.location.href=\''.$this->getUrl(array($current_lang, 'showcase')).'\''),
                                                                  $ordering_button = array('visible' => false));
        return $html;
    }
    
    public function validate($data)
    {
        // Validate data
        $ret_validation = $this->_validate($data);
        if (!$ret_validation['success'])
        {
            echo json_encode($ret_validation);
            return;
        }
        
        // Return result
        echo json_encode(array(
            'success' => true,
            'msg' => ''
        ));
    }
    
    public function ordering($data)
    {
        // Ordering
        $code = $this->_ordering_controller->ordering();
        
        // Return result
        echo json_encode(array(
            'success' => true,
            'msg' => '',
            'redirectUrl' => $this->getUrl(array(lang::getCurrentLanguage(), 'bond'), array('code' => $code))
        ));
    }
    
}