<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\shoppingcart;
use modules\ecommerce\frontend\controller\personaldata as personaldataController;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;

// Views
use modules\ecommerce\frontend\view\personaldata as view;
use modules\ecommerce\frontend\view\finalSteps as finalStepsView;
use modules\ecommerce\frontend\view\actionButtons as actionButtonsView;

/**
 * Personal (Customer) data form webpage
 *
 * @author Dani Gilabert
 * 
 */
class personaldata extends personaldataController
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
    
    public function init()
    {
        // If shoppingcart is empty, render showcase
        $shoppingcart = new shoppingcart();
        if ($shoppingcart->isEmpty() || $shoppingcart->isFinalTotalPriceInsufficient())
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
        return $website->name.' - '.lang::trans('shipping_data');
    }           
    
    protected function _getDescription()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('shipping_data');
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
                                array('text' => lang::trans('shipping_data'), 'url' => '')
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
        $html .= $this->_final_steps_view->renderFinalStepsMenu(2);
        
        // Render form
        $html .= $this->_view->renderStartForm();
        $html .= $this->_view->renderForm();
        
        // Render action buttons
        $html .= $this->_action_buttons_view->renderActionButtons($continue_shopping_button = array('visible' => true,
                                                                                                    'type' => 'button',
                                                                                                    'text' => lang::trans('continue_shopping'),
                                                                                                    'onClick' => 'window.location.href=\''.$this->getUrl(array($current_lang, 'showcase')).'\''),
                                                                  $ordering_button = array('visible' => true,
                                                                                           'type' => 'submit',
                                                                                           'text' => lang::trans('continue'),
                                                                                           'onClick' => ''));
        // End render form
        $html .= $this->_view->renderEndForm();
        
        return $html;
    }
    
    public function validate($data)
    {
        $success = true;
        $msg = '';
        $redirect_url = '';
        $current_lang = lang::getCurrentLanguage();
        
        $firstname = $data->firstname;
        $lastname = $data->lastname;
        $email = $data->email;
        $phone = $data->phone;
        $company = $data->company;
        $address = $data->address;
        $postalcode = $data->postalcode;
        $city = $data->city;
        $country = $data->country;
        $comments = $data->comments;
        
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
            $this->setFirstName($firstname);
            $this->setLastName($lastname);
            $this->setEmail($email);
            $this->setPhone($phone);
            $this->setCompany($company);
            $this->setAddress($address);
            $this->setPostalcode($postalcode);
            $this->setCity($city);
            $this->setCountry($country);
            $this->setComments($comments);
            $redirect_url = $this->getUrl(array($current_lang, 'validation'));
        }
        
        $ret['success'] = $success;
        $ret['msg'] = $msg;
        $ret['redirectUrl'] = $redirect_url;
        $ret = json_encode($ret);
        echo $ret;          
    }
    
}