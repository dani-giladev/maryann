<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\shoppingcart as shoppingcartController;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;
use modules\ecommerce\frontend\controller\personaldata;
use modules\ecommerce\frontend\controller\voucher;

// Views
use modules\ecommerce\frontend\view\shoppingcart\shoppingcart as view;
use modules\ecommerce\frontend\view\shoppingcart\tooltip as shoppingcartTooltipView;
use modules\ecommerce\frontend\view\finalSteps as finalStepsView;
use modules\ecommerce\frontend\view\actionButtons as actionButtonsView;

/**
 * Shoppingcart webpage
 *
 * @author Dani Gilabert
 * 
 */
class shoppingcart extends shoppingcartController
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
        // Render this page
        $this->renderPage();
    }    
    
    protected function _getTitle()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('order_summary');
    }           
    
    protected function _getDescription()
    {
        $website = $this->getWebsite();
        return $website->name.' - '.lang::trans('order_summary');
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
        $this->_categories = $main_menu->getCategoriesTree();
        
        // Render breadcrumbs menu
        $breadcrumbs = array(array('text' => lang::trans('order_summary'), 'url' => ''));
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
        // Get shopping cart content
        $html = '';
        $current_lang = lang::getCurrentLanguage();
        $total_amount = $this->getTotalAmount();
        
        // Render final steps menu
        $html .= $this->_final_steps_view->renderFinalStepsMenu(1);
        
        if ($total_amount > 0)
        {
            $shoppingcart = $this->getShoppingcart();
            $total_price = $this->getTotalPrice();
            $shipping_cost = $this->getShippingCost();
            $free_shipping_cost_from = $this->getFreeShippingCostFrom();
            $voucher = $this->_voucher_controller->getVoucher();
            $voucher_discount = $this->getVoucherDiscount();
            $second_unit_discount = $this->get2ndUnitDiscount();
            $final_total_price = $this->getFinalTotalPrice();
            
            $html .= $this->_view->renderHeaderTitles();
            $html .= $this->_view->renderArticles($shoppingcart);
            $html .= $this->_view->renderTotals($total_price, $shipping_cost, $free_shipping_cost_from, $voucher, $voucher_discount, $second_unit_discount, $final_total_price);
            $html .= $this->_view->renderVoucher($voucher);
            
            // Render action buttons
            $html .= $this->_action_buttons_view->renderActionButtons($continue_shopping_button = array('visible' => true,
                                                                                                        'type' => 'button',
                                                                                                        'text' => lang::trans('continue_shopping'),
                                                                                                        'onClick' => 'window.location.href=\''.$this->getUrl(array($current_lang, 'showcase')).'\''),
                                                                      $ordering_button = array('visible' => true,
                                                                                               'type' => 'button',
                                                                                               'text' => lang::trans('continue'),
                                                                                               'onClick' => 'validate()'));
        }
        else
        {
            $html .= $this->_view->renderEmptyCart();  
            
            // Render action buttons
            $html .= $this->_action_buttons_view->renderActionButtons($continue_shopping_button = array('visible' => true,
                                                                                                        'type' => 'button',
                                                                                                        'text' => lang::trans('continue_shopping'),
                                                                                                        'onClick' => 'window.location.href=\''.$this->getUrl(array($current_lang, 'showcase')).'\''),
                                                                      $ordering_button = array('visible' => false));
        }
        
        return $html;
    }
    
    public function removeFromShoppingcart($data)
    {
        $this->removeArticle($data->code);
        
        // Happy end
        $shoppingcart_menu_option_view = new shoppingcartTooltipView(false, true);
        $ret['content'] = $this->_renderContent();
        $ret['shoppingcartAmount'] = $shoppingcart_menu_option_view->renderShoppingcartMenuAmount();
        $ret['shoppingcartTotalPrice'] = $shoppingcart_menu_option_view->renderShoppingcartMenuTotalPrice();
        $ret['shoppingcartTooltip'] = $shoppingcart_menu_option_view->renderShoppingcartTooltip();
        $ret = json_encode($ret);
        echo $ret;        
    }
    
    public function removeAllFromShoppingcart()
    {
        $this->flush();
        
        // Happy end
        $shoppingcart_menu_option_view = new shoppingcartTooltipView(false, true);
        $ret['content'] = $this->_renderContent();
        $ret['shoppingcartAmount'] = $shoppingcart_menu_option_view->renderShoppingcartMenuAmount();
        $ret['shoppingcartTotalPrice'] = $shoppingcart_menu_option_view->renderShoppingcartMenuTotalPrice();
        $ret['shoppingcartTooltip'] = $shoppingcart_menu_option_view->renderShoppingcartTooltip();
        $ret = json_encode($ret);
        echo $ret;        
    }
    
    public function onChangeArticleAmount($data)
    {
        $this->setArticleAmount($data->code, $data->amount);
        
        // Happy end
        $shoppingcart_menu_option_view = new shoppingcartTooltipView(false, true);
        $ret['totalArticlePrice'] = $this->_view->renderPriceFormat($this->getTotalArticlePrice($data->code, $data->amount)).'&euro;';
        $ret['totalPrice'] = $this->_view->renderTotals(
                $this->getTotalPrice(), 
                $this->getShippingCost(), 
                $this->getFreeShippingCostFrom(), 
                $this->_voucher_controller->getVoucher(), 
                $this->getVoucherDiscount(), 
                $this->get2ndUnitDiscount(), 
                $this->getFinalTotalPrice());
        $ret['shoppingcartAmount'] = $shoppingcart_menu_option_view->renderShoppingcartMenuAmount();
        $ret['shoppingcartTotalPrice'] = $shoppingcart_menu_option_view->renderShoppingcartMenuTotalPrice();
        $ret['shoppingcartTooltip'] = $shoppingcart_menu_option_view->renderShoppingcartTooltip();
        $ret = json_encode($ret);
        echo $ret;  
    }
    
    public function confirmVoucher($data)
    {
        $current_lang = lang::getCurrentLanguage();
        $vouchercode = strtoupper($data->vouchercode);

        // Get the voucher and check if it's valid
        $voucher_is_valid = true;
        if (empty($vouchercode))
        {
            $voucher_is_valid = false;
        }
        if ($voucher_is_valid)
        {
            $voucher_controller = new voucher();
            $model = $voucher_controller->getVoucherByCode($vouchercode);
            if (!is_null($model))
            {
                $voucher = (object) $model->getStorage();
            }
            else
            {
                $voucher_is_valid = false;
            }
        }
        if ($voucher_is_valid)
        {
            if (!$voucher_controller->isValid($voucher))
            {
                $voucher_is_valid = false;
            }
        }
        if (!$voucher_is_valid)
        {
            $ret['success'] = false;
            $ret['msg'] = lang::trans('voucher_is_not_valid');
            echo json_encode($ret); 
            return;
        }
        
        // Set voucher
        if ($voucher->voucherType === 'free-shippingcost')
        {
            $free_shipping_cost_from = $this->getShippingCost() * -1;
            $voucher->value = $free_shipping_cost_from;
        }
        $voucher_controller->setVoucher($voucher);
            
        // Happy end
        $ret['success'] = true;
        $ret['msg'] = '';
        
        if (isset($voucher->messages->$current_lang) && !empty($voucher->messages->$current_lang))
        {
            $msg = $voucher->messages->$current_lang;
            $ret['htmlMsg'] = $this->_view->renderWindowAfterConfirmVoucher($msg);
        }  
        
        echo json_encode($ret);          
    }
    
    public function validate($data)
    {
        $current_lang = lang::getCurrentLanguage();
        
        // Check if the session is expired
        if ($this->isEmpty())
        {
            echo json_encode(array(
                'success' => false,
                'msg' => lang::trans('expired_session'),
                'redirectUrl' => $this->getUrl(array($current_lang, 'showcase'), array('start'))
            ));
            return;
        }         
        
        // Check if the final total price is insufficient
        if ($this->isFinalTotalPriceInsufficient())
        {
            echo json_encode(array(
                'success' => false,
                'msg' => lang::trans('minimum_purchase_amount_to_order_is').
                         " : <b>".$this->getMinimumFinalTotalPrice().'€</b>',
                'redirectUrl' => ''
            ));
            return;
        }
        
        // Happy end (redirect to..)
        $personaldata = new personaldata();
        $next_webpage = ($personaldata->isEmpty())? 'personaldata' : 'validation';
        $redirect_url = $this->getUrl(array($current_lang, $next_webpage));
        
        echo json_encode(array(
            'success' => true,
            'msg' => '',
            'redirectUrl' => $redirect_url
        ));
    }
    
}