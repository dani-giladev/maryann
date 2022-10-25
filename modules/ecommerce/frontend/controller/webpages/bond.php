<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use core\redsys\controller\redsys;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;
// Test
//use modules\ecommerce\frontend\controller\mailing\orderConfirmation as orderConfirmationMailer;

// Models
use modules\ecommerce\model\sale as saleModel;

// Views
use modules\ecommerce\frontend\view\bond as view;
use modules\ecommerce\frontend\view\actionButtons as actionButtonsView;

/**
 * Bond page webpage
 *
 * @author Dani Gilabert
 * 
 */
class bond extends ecommerce
{
    protected $_redsys_controller;
    protected $_view;
    protected $_action_buttons_view;
    protected $_categories;

    public function __construct()
    {
        parent::__construct();
        $this->_redsys_controller = new redsys();
        $this->_view = new view();
        $this->_action_buttons_view = new actionButtonsView(true);
    }
    
    public function init($data)
    {
        // If code is not defined, render showcase
        if (!isset($data->code))
        {
            $this->goToError404Webpage();
            return;            
        }
        
        // Is redsys request?
        if ($this->_redsys_controller->isRedsysRequest($data))
        {
            $url_ok_result = $this->_redsys_controller->getUrlOKResult($data, $data->code);
            if (!$url_ok_result['success'])
            {
                $this->_goTo('payment', array(
                    'payment_failed_msg' => $this->_beautyPaymentFailedMsg($url_ok_result['msg'])
                ));
                return;
            }
        } 
            
        // Check order x times
        if (!$this->_checkOrder($data->code))
        {
            $this->goToError404Webpage();
            return;             
        }
        
//        // Test
//        $order_confirmation_mailer = new orderConfirmationMailer($this->_sale_data);
//        echo $order_confirmation_mailer->renderPage(); 
//        return;
        
        // Render this page
        $this->renderPage();  
    }
    
    private function _checkOrder($code, $times = 5)
    {
        for ($i=1; $i<=$times; $i++)
        {
            $id = 'ecommerce-sale-'.strtolower($code);
            $this->_sale_data = new saleModel($id);
            if($this->_sale_data->exists())
            {
                return true;  
            }
            
            sleep(1);
        }
        
        return false;
    }
    
    protected function _getTitle()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('order_confirmation');
    }           
    
    protected function _getDescription()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('order_confirmation');
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
        $main_menu = new mainMenu(null, true, false);
        $html .= $main_menu->renderMainMenu();
        $this->_categories = $main_menu->getCategoriesTree();
        
        // Render breadcrumbs menu
        $breadcrumbs = array(
                                array('text' => lang::trans('order_confirmation'), 'url' => '')
                            );
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'breadcrumbs' => $breadcrumbs, 
            'categories' => $this->_categories,
            'show_shoppingcart' => false
        ));          
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    }
    
    protected function _renderContent()
    {
        $current_lang = lang::getCurrentLanguage();
        $html = '';
        
        // Render content
        $this->_view->sale_data = $this->_sale_data;
        $html .= $this->_view->renderBondContent();
        
        // Render action buttons
        $html .= $this->_action_buttons_view->renderActionButtons($continue_shopping_button = array('visible' => true,
                                                                                                    'type' => 'button',
                                                                                                    'text' => lang::trans('continue_shopping'),
                                                                                                    'onClick' => 'window.location.href=\''.$this->getUrl(array($current_lang, 'showcase')).'\''),
                
                                                                  $ordering_button = array('visible' => false),
                
                                                                  $home_button = array('visible' => true,
                                                                                       'type' => 'button',
                                                                                       'text' => lang::trans('start'),
                                                                                       'onClick' => 'window.location.href=\''.$this->getUrl(array($current_lang, 'home')).'\'')                
        );
        
        return $html;
    }
    
    private function _goTo($webpage, $params)
    {
        $current_lang = lang::getCurrentLanguage();
        $url = $this->getUrl(array($current_lang, $webpage), $params);
        header('Location: '.$url);         
    }
    
    protected function _beautyPaymentFailedMsg($msg)
    {
        return 
            lang::trans('payment_failed_description').'<br><br>'.
            '<font color=\'red\'>'.
                $msg.
            '</font>';        
    }
    
}