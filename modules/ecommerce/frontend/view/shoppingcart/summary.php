<?php

namespace modules\ecommerce\frontend\view\shoppingcart;

// Controllers
use modules\ecommerce\frontend\controller\ecommerce as ecommerceController;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\frontend\controller\brand;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Shopping cart summary view
 *
 * @author Dani Gilabert
 * 
 */
class summary
{
    public $is_email = false;
    
    protected $_ecommerce_controller;
    protected $_ecommerce_view;
    protected $_article_controller;
    protected $_brand_controller;
    
    public function __construct()
    {
        $this->_ecommerce_controller = new ecommerceController();
        $this->_ecommerce_view = new ecommerceView();
        $this->_article_controller = new article();
        $this->_brand_controller = new brand();
    }
    
    public function renderArticles($shoppingcart)
    { 
        // Start
        $html = '<div id="shoppingcart-summary">';
        
        // Header
        if (!$this->is_email)
        {
            $html .=
                    '<div id="shoppingcart-summary-header">'.
                        '<table id="shoppingcart-summary-header-table" class="title" border="0" cellpadding="0" cellspacing="0">'.
                            '<tr>'.
                                '<td class="shoppingcart-summary-articles-table-column-article '.
                                           'shoppingcart-summary-articles-table-column-article-header">'.
                                    strtoupper(lang::trans('articles')).
                                '</td>'.
                                '<td class="shoppingcart-summary-articles-table-column-amount '.
                                           'shoppingcart-summary-articles-table-column-amount-header">'.
                                    strtoupper(lang::trans('amount')).
                                '</td>'.
                                '<td class="shoppingcart-summary-articles-table-column-price '.
                                           'shoppingcart-summary-articles-table-column-price-header">'.
                                    strtoupper(lang::trans('total')).
                                '</td>'.
                            '</tr>'.
                        '</table>'.
                    '</div>'.
                    '';              
        } 
        
        // Article table
        $html .= 
                '<div id="shoppingcart-summary-articles">'.
                    '<table id="shoppingcart-summary-articles-table" border="0" cellpadding="0" cellspacing="0">'.
                '';        
        
        foreach ($shoppingcart as $shoppingcart_value)
        {
            // Start article content column
            $html .= 
                        '<tr>'.
                            '<td class="shoppingcart-summary-articles-table-column '.
                                       'shoppingcart-summary-articles-table-column-article '.
                                       'shoppingcart-summary-articles-table-column-article-content">'.
                        '';
            // Image
            $image_path = '';
            $images = $this->_article_controller->getImages($shoppingcart_value->article, false);
            if (!empty($images))
            {
                $image_path = $images[0];
            }
            
            // Start article content
            $html .=            
                    '<table border="0" cellpadding="0" cellspacing="0">'.
                        '<tr>'.
                            '<td>'.
                                '<img class="shoppingcart-summary-articles-table-column-article-content-img" '.
                                    'src="'.$image_path.'" />'.
                            '</td>'.
                            '<td>'.
                                '<div class="shoppingcart-summary-articles-table-column-article-content-container-text">'.
                    '';
            
            // Title
            $article_title = $this->_article_controller->getTitle($shoppingcart_value->article);
            $html .=            
                        '<div class="shoppingcart-summary-articles-table-column-article-content-title article-title">'.
                            $article_title.
                        '</div>';
        
            // Display
            $display = $this->_article_controller->getDisplay($shoppingcart_value->article);
            if (!empty($display))
            {
                $html .=  
                        '<div class="shoppingcart-summary-articles-table-column-article-content-display article-display">'.
                            $display.
                        '</div>';            
            } 
        
            // Brand
            $brand = $this->_brand_controller->getBrandByCode($shoppingcart_value->article->brand, true);
            $brand_name = $this->_brand_controller->getBrandName($brand);
            $html .= 
                        '<div class="shoppingcart-summary-articles-table-column-article-content-brand brand">'.
                            //$shoppingcart_value->article->brandName.
                            $brand_name.
                        '</div>';
            
            // Ref.
            $html .=
                        '<div class="shoppingcart-summary-articles-table-column-article-content-ref label-ref">'.
                            'Ref. '.$shoppingcart_value->article->code.
                        '</div>';
            
            // End article content
            $html .=            
                                    '</div>'.
                                '</td>'.
                            '</tr>'.
                        '</table>'.
                    '</td>'.
                    '';
            
            // Amount and price columns
            $html .=            
                    '<td class="shoppingcart-summary-articles-table-column '. 
                               'shoppingcart-summary-articles-table-column-amount '. 
                               'shoppingcart-summary-articles-table-column-amount-content">'.
                        // Amount x Price
                        '<div class="shoppingcart-summary-articles-table-column-amount-content-div price">'.   
                            $shoppingcart_value->amount.'&nbsp;X&nbsp;'.$this->_ecommerce_view->renderPriceFormat($shoppingcart_value->price).'&euro;'. 
                        '</div>'.
                    '</td>'.
                    '<td class="shoppingcart-summary-articles-table-column '. 
                               'shoppingcart-summary-articles-table-column-price '. 
                               'shoppingcart-summary-articles-table-column-price-content '.
                               'price">'.
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
                
        // End
        $html .= '</div>';
        
        return $html;
    }
    
    public function renderTotals($total_price, $shipping_cost, $voucher, $voucher_discount, $second_unit_discount, $final_total_price)
    {
        $html =         
                '<div id="shoppingcart-summary-totals-container">'.
                    '<div id="shoppingcart-summary-totals">'.
                        '<table class="shoppingcart-summary-totals-table shoppingcart-summary-totals-table-details">'.
                
                            // Total price of articles
                            '<tr>'.
                                '<td class="shoppingcart-summary-totals-table-column '. 
                                           'shoppingcart-summary-totals-table-column-label">'.
                                    lang::trans("total_price").' ('.lang::trans("tax_included").') : '.
                                '</td>'.
                                '<td class="shoppingcart-summary-totals-table-column '. 
                                           'shoppingcart-summary-totals-table-column-value '.
                                           'shoppingcart-summary-totals-table-column-shippingcost '.
                                           'price">'.
                                    $this->_ecommerce_view->renderPriceFormat($total_price).'&euro;'.                                
                                '</td>'.
                            '</tr>'.

                            // Shipping cost
                            '<tr>'.
                                '<td class="shoppingcart-summary-totals-table-column '. 
                                           'shoppingcart-summary-totals-table-column-label">'.
                                    lang::trans('shipping_cost').' :'.
                                '</td>'.
                                '<td class="shoppingcart-summary-totals-table-column '. 
                                           'shoppingcart-summary-totals-table-column-value '.
                                           'shoppingcart-summary-totals-table-column-shippingcost '.
                                           'price">'.
                                    $this->_ecommerce_view->renderPriceFormat($shipping_cost).'&euro;'.                                
                                '</td>'.
                            '</tr>'.
                '';
        
        if ($voucher_discount !== 0)
        {
            $html .= 
                            // Voucher
                            '<tr>'.
                                '<td class="shoppingcart-summary-totals-table-column '. 
                                           'shoppingcart-summary-totals-table-column-label">'.
                                    lang::trans('voucher').' :'.
                                '</td>'.
                                '<td class="shoppingcart-summary-totals-table-column '. 
                                           'shoppingcart-summary-totals-table-column-value '.
                                           'shoppingcart-summary-totals-table-column-shippingcost '.
                                           'price">'.
                                    $this->_ecommerce_view->renderPriceFormat($voucher_discount).'&euro;'.  
                                    ((isset($voucher->voucherType) && $voucher->voucherType === 'percentage-over-total')? (' <font size=2>('.$voucher->value.'%)</font>') : '').
                                '</td>'.
                            '</tr>'.
                    '';            
        }
        
        if ($second_unit_discount !== 0)
        {
            $html .= 
                            // 2nd unit discount
                            '<tr>'.
                                '<td class="shoppingcart-summary-totals-table-column '. 
                                           'shoppingcart-summary-totals-table-column-label">'.
                                    lang::trans('discount_for_2nd_unit').' :'.
                                '</td>'.
                                '<td class="shoppingcart-summary-totals-table-column '. 
                                           'shoppingcart-summary-totals-table-column-value '.
                                           'shoppingcart-summary-totals-table-column-shippingcost '.
                                           'price">'.
                                    $this->_ecommerce_view->renderPriceFormat($second_unit_discount).'&euro;'.  
                                '</td>'.
                            '</tr>'.
                    '';            
        }
            
        $html .= 
                        '</table>'.
                        '<table class="shoppingcart-summary-totals-table" border="0" cellpadding="0" cellspacing="0">'.
                            // Final total price
                            '<tr>'.
                                '<td class="shoppingcart-summary-totals-table-column '. 
                                           'shoppingcart-summary-totals-table-column-label">'.
                                    lang::trans("final_total_price").' ('.lang::trans("tax_included").') : '.
                                '</td>'.
                                '<td class="shoppingcart-summary-totals-table-column '. 
                                           'shoppingcart-summary-totals-table-column-value '.
                                           'price">'.
                                    $this->_ecommerce_view->renderPriceFormat($final_total_price).'&euro;'.                                
                                '</td>'.
                            '</tr>'.
                        '</table>'.
                    '</div>'.
                '</div>'.
                '';    
        
        return $html;  
    }    
    
}