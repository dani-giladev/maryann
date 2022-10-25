<?php

namespace modules\ecommerce\frontend\view\shoppingcart;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\shoppingcart as shoppingcartController;
//use modules\ecommerce\frontend\controller\personaldata;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\frontend\controller\brand;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Tooltip view for shopping cart
 *
 * @author Dani Gilabert
 * 
 */
class tooltip
{
    protected $_ecommerce_controller;
    protected $_ecommerce_view;
    protected $_shoppingcart_controller;
    protected $_show_shoppingcart_button;
    protected $_show_ordering_button;
    protected $_article_controller;
    protected $_brand_controller;
    
    public function __construct($show_shoppingcart_button = false, 
                                $show_ordering_button = true)
    {
        $this->_ecommerce_controller = new ecommerce();
        $this->_ecommerce_view = new ecommerceView();
        $this->_shoppingcart_controller = new shoppingcartController();
        $this->_show_shoppingcart_button = $show_shoppingcart_button;
        $this->_show_ordering_button = $show_ordering_button;
        $this->_article_controller = new article();
        $this->_brand_controller = new brand();
    }
    
    public function renderShoppingcartMenu()
    {
        $current_lang = lang::getCurrentLanguage();
        $html_content = $this->renderShoppingcartTooltip();
        $_content_tag = '_content="'.htmlentities($html_content).'" ';            
        $shoppingcart_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'shoppingcart'));
        $html = 
                '<a href="'.$shoppingcart_url.'">'.
                    '<div '.
                        'id="shoppingcart-menu-option" '.
                        '_has_tooltip="true" '.
                        $_content_tag.
                    '>'.
                        '<table border="0" cellpadding="0" cellspacing="0">'.
                            '<tr>'.
                                '<td>'.
                                    '<div '.
                                        'id="shoppingcart-menu-option-image" '.
                                        $_content_tag.
                                    '/>'.
                                '</td>'.
                                '<td>'. 
                                    $this->renderShoppingcartMenuAmount().
                                '</td>'.
                                '<td>'. 
                                    $this->renderShoppingcartMenuTotalPrice().
                                '</td>'.
                            '</tr>'.
                        '</table>'.                    
                    '</div>'.
                '</a>'.
                '';        
        
        return $html;     
    }
    
    public function renderShoppingcartMenuAmount()
    {
        $total_amount = $this->_shoppingcart_controller->getTotalAmount();
        
        $html = 
                '<div id="shoppingcart-menu-option-amount-wrapper">'.
                    '<div id="shoppingcart-menu-option-amount">'.
                        '<div id="shoppingcart-menu-option-amount-text">'.
                            $total_amount.
                        '</div>'.  
                    '</div>'.  
                '</div>'.
            '';
        
        return $html;   
    }

    public function renderShoppingcartMenuTotalPrice()
    {
        $total_price = $this->_shoppingcart_controller->getFinalTotalPrice();          
        
        $html = 
                '<div id="shoppingcart-menu-option-totalprice-wrapper">'.
                    '<div id="shoppingcart-menu-option-totalprice">'.
                        $this->_ecommerce_view->renderPriceFormat($total_price).'&euro;'.
                    '</div>'.    
                '</div>'.
            '';
        
        return $html;   
    }
    
    public function renderShoppingcartTooltip()
    {
        $html = '';
        $current_lang = lang::getCurrentLanguage();
        
        $shoppingcart = $this->_shoppingcart_controller->getShoppingcart();
        $total_amount = $this->_shoppingcart_controller->getTotalAmount();
        $total_price = $this->_shoppingcart_controller->getTotalPrice();
        $shipping_cost = $this->_shoppingcart_controller->getShippingCost();
        $voucher_discount = $this->_shoppingcart_controller->getVoucherDiscount();
        $second_unit_discount = $this->_shoppingcart_controller->get2ndUnitDiscount();
        $final_total_price = $this->_shoppingcart_controller->getFinalTotalPrice();           
        $shoppingcart_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'shoppingcart'));
        // Get ordering url
        //$personaldata = new personaldata();
        //$next_webpage = ($personaldata->isEmpty())? 'personaldata' : 'validation';
        $next_webpage = 'shoppingcart';
        $ordering_url = $this->_ecommerce_controller->getUrl(array($current_lang, $next_webpage));        
        
        // Start render
        $main_content_id = ($total_amount > 0)? 'shoppingcart-menu-option-menu-option-tp' : 'shoppingcart-menu-option-menu-option-tp-reduced';
        $html .= '<div id="'.$main_content_id.'" class="scrollable" >';
        
        if ($total_amount > 0)
        {
            $html .= $this->_renderTooltipArticles($shoppingcart);
            $html .= $this->_renderTooltipTotals($total_price, $shipping_cost, $voucher_discount, $second_unit_discount, $final_total_price);            
        }
        else
        {
            // Empty text
            $html .=         
                    '<div id="shoppingcart-menu-option-menu-option-tp-empty-text">'.
                        lang::trans('shoppingcart_is_empty').
                    '</div>'.
                    '';             
        }
        
        // Buttons
        $show_shoppingcart_button = $this->_show_shoppingcart_button;
        $show_ordering_button = ($this->_show_ordering_button && $total_amount > 0);
        if ($show_shoppingcart_button || $show_ordering_button)
        {
            $html .= '<div id="shoppingcart-menu-option-menu-option-tp-buttons">';
        }
        // Add to cart button
        if ($show_shoppingcart_button)
        {
            $button_position_class = ($show_ordering_button)? 'shoppingcart-menu-option-menu-option-tp-button-left-position' : 'shoppingcart-menu-option-menu-option-tp-button-center-position';
            $html .= 
                    '<button '.
                        'type="button" '.                        
                        'class='.
                            '"'.
                            'button '.
                            'button-view-shoppingcart '.
                            'shoppingcart-menu-option-menu-option-tp-button '.
                            $button_position_class.
                            '" '.
                        'onClick="window.location.href=\''.$shoppingcart_url.'\'" '.
                    '>'.
                        lang::trans('view_shoppingcart').
                    '</button>'.
                    '';            
        }
        // Ordering button
        if ($show_ordering_button)
        {
            $button_position_class = ($show_shoppingcart_button)? 'shoppingcart-menu-option-menu-option-tp-button-right-position' : 'shoppingcart-menu-option-menu-option-tp-button-center-position';            
            $html .= 
                    '<button '.
                        'type="button" '.                        
                        'class='.
                            '"'.
                            'button '.
                            'button-ordering '.
                            'shoppingcart-menu-option-menu-option-tp-button '.
                            $button_position_class.
                            '" '.
                        'onClick="window.location.href=\''.$ordering_url.'\'" '.
                    '>'.
                        lang::trans('ordering').
                    '</button>'.
                    '';              
        }
        if ($show_shoppingcart_button || $show_ordering_button)
        {
            $html .= '</div>';
        }        
        
        // End render
        $html .= '</div>';
        
        return $html;   
    }
    
    private function _renderTooltipArticles($shoppingcart)
    { 
        $html =                 
                '<div id="shoppingcart-menu-option-tooltip">'.
                    '<table id="shoppingcart-menu-option-tooltip-table" border="0" cellpadding="0" cellspacing="0">'.
                '';        
        
        foreach ($shoppingcart as $shoppingcart_value) {
            $html .= 
                        '<tr>'.
                            '<td class="shoppingcart-menu-option-tooltip-table-column '.
                                       'shoppingcart-menu-option-tooltip-table-column-article '.
                                       'shoppingcart-menu-option-tooltip-table-column-article-content">'.
                        '';
            // Image
            $image_path = '';
            $images = $this->_article_controller->getImages($shoppingcart_value->article, false);
            if (!empty($images))
            {
                $image_path = $images[0];
            }   
            $html .=            '<img class="shoppingcart-menu-option-tooltip-table-column-article-content-img" '.
                                    'src="'.$image_path.'" />';
            
            // Brand
            $brand = $this->_brand_controller->getBrandByCode($shoppingcart_value->article->brand, true);
            $brand_name = $this->_brand_controller->getBrandName($brand);
            $html .=            '<div class="shoppingcart-menu-option-tooltip-table-column-article-content-brand brand">'.
                                    $brand_name.
                                '</div>';
                    
            // Title
            $article_title = $this->_article_controller->getTitle($shoppingcart_value->article);          
            $html .=            '<div class="shoppingcart-menu-option-tooltip-table-column-article-content-title article-title">'.
                                    $article_title.
                                '</div>'.
                            '</td>'.
                            '<td class="shoppingcart-menu-option-tooltip-table-column '. 
                                       'shoppingcart-menu-option-tooltip-table-column-amount '. 
                                       'shoppingcart-menu-option-tooltip-table-column-amount-content">'.
                                // Amount x Price
                                '<div class="shoppingcart-menu-option-tooltip-table-column-amount-content-div price">'.   
                                    $shoppingcart_value->amount.'&nbsp;X&nbsp;'.$this->_ecommerce_view->renderPriceFormat($shoppingcart_value->price).'&euro;'. 
                                '</div>'.
                            '</td>'.
                            '<td class="shoppingcart-menu-option-tooltip-table-column '. 
                                       'shoppingcart-menu-option-tooltip-table-column-price '. 
                                       'shoppingcart-menu-option-tooltip-table-column-price-content '.
                                       'price '.
                                        '">'.
                                // Total
                                $this->_ecommerce_view->renderPriceFormat(($shoppingcart_value->price * $shoppingcart_value->amount)).'&euro;'.
                            '</td>'.
                        '</tr>'.
                        '';
        }
                
        $html .= 
                    '</table>'.
                '</div>'.
                '';           
        return $html;
    }
    
    private function _renderTooltipTotals($total_price, $shipping_cost, $voucher_discount, $second_unit_discount, $final_total_price)
    {
        $html =         
                '<div id="shoppingcart-menu-option-tooltip-totals">'.
                    '<table class="shoppingcart-menu-option-tooltip-totals-table shoppingcart-menu-option-tooltip-totals-table-details" border="0" cellpadding="0" cellspacing="0">'.
                        
                        // Total price of articles
                        '<tr>'.
                            '<td class="shoppingcart-menu-option-tooltip-totals-label">'.
                                lang::trans("total_price").' ('.lang::trans("tax_included").') : '.
                            '</td>'.
                            '<td class="shoppingcart-menu-option-tooltip-totals-value '.
                                       'shoppingcart-menu-option-tooltip-totals-shippingcost '.
                                       'price '. 
                                       '">'.
                                $this->_ecommerce_view->renderPriceFormat($total_price).'&euro;'.
                            '</td>'.
                        '</tr>'.
                
                        // Shipping cost
                        '<tr>'.
                            '<td class="shoppingcart-menu-option-tooltip-totals-label">'.
                                lang::trans("shipping_cost").' :'.
                            '</td>'.
                            '<td class="shoppingcart-menu-option-tooltip-totals-value '.
                                       'shoppingcart-menu-option-tooltip-totals-shippingcost '.
                                       'price '. 
                                       '">'.
                                $this->_ecommerce_view->renderPriceFormat($shipping_cost).'&euro;'.
                            '</td>'.
                        '</tr>'.
                '';
        
        if ($voucher_discount !== 0)
        {
            $html .= 
                        // Voucher
                        '<tr>'.
                            '<td class="shoppingcart-menu-option-tooltip-totals-label">'.
                                lang::trans("voucher").' :'.
                            '</td>'.
                            '<td class="shoppingcart-menu-option-tooltip-totals-value '.
                                       'shoppingcart-menu-option-tooltip-totals-shippingcost '.
                                       'price '. 
                                       '">'.
                                $this->_ecommerce_view->renderPriceFormat($voucher_discount).'&euro;'.
                            '</td>'.
                        '</tr>'.
                    '';            
        }
        
        if ($second_unit_discount !== 0)
        {
            $html .= 
                        // 2nd unit discount
                        '<tr>'.
                            '<td class="shoppingcart-menu-option-tooltip-totals-label">'.
                                lang::trans("discount_for_2nd_unit").' :'.
                            '</td>'.
                            '<td class="shoppingcart-menu-option-tooltip-totals-value '.
                                       'shoppingcart-menu-option-tooltip-totals-shippingcost '.
                                       'price '. 
                                       '">'.
                                $this->_ecommerce_view->renderPriceFormat($second_unit_discount).'&euro;'.
                            '</td>'.
                        '</tr>'.
                    '';            
        }
            
        $html .= 
                   '</table>'.
                   '<table class="shoppingcart-menu-option-tooltip-totals-table" border="0" cellpadding="0" cellspacing="0">'.
                        // Final total price        
                        '<tr>'.
                            '<td class="shoppingcart-menu-option-tooltip-totals-label">'.
                                lang::trans("final_total_price").' ('.lang::trans("tax_included").') : '.
                            '</td>'.
                            '<td class="shoppingcart-menu-option-tooltip-totals-value '.
                                       'price '. 
                                       '">'.
                                $this->_ecommerce_view->renderPriceFormat($final_total_price).'&euro;'.
                            '</td>'.
                        '</tr>'.
                    '</table>'.
                '</div>'.
            '';    
         
        return $html;  
    }    
    
}