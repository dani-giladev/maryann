<?php

namespace modules\ecommerce\frontend\view\shoppingcart;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce as ecommerceController;
use modules\ecommerce\frontend\controller\article as articleController;
use modules\ecommerce\frontend\controller\brand as brandController;

// Views
use modules\ecommerce\frontend\view\ecommerce;

/**
 * Shoppingcart view
 *
 * @author Dani Gilabert
 * 
 */
class shoppingcart extends ecommerce
{      
    
    public function getWebpageName()
    {
        return 'shoppingcart';
    }
 
    public function getDevelopmentHeadStyleSheetsPaths()
    {
        $ecommerce_styles = $this->_getHeadEcommerceStyleSheetsPaths();
        
        $styles = array(
            '/res/css/jquery/fancybox/jquery.fancybox.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-buttons.css',
            //'/res/css/jquery/fancybox/helpers/jquery.fancybox-thumbs.css',
            
            '/modules/ecommerce/frontend/res/css/shoppingcart/shoppingcart.css',
            '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/shoppingcart/shoppingcart.css',
            '/modules/ecommerce/frontend/res/css/common/final-steps.css',
            '/modules/ecommerce/frontend/res/css/common/action-buttons.css'
        );
        
        $ret = array_merge($ecommerce_styles, $styles);
        
        return $ret;
    }
    
    public function getDevelopmentHeadScriptsPaths()
    {
        $ecommerce_scripts = $this->_getHeadEcommerceScriptsPaths();

        $scripts = array(
            '/res/js/jquery/jquery.validate-1.9.0.min.js',
            '/res/js/jquery/fancybox/jquery.fancybox.js',
            
            '/modules/ecommerce/frontend/res/js/shoppingcart.js'
        );

        $ret = array_merge($ecommerce_scripts, $scripts);      
        
        return $ret;
    }
    
    protected function _addJavascriptVars()
    {
        // Javascript vars and messages
        $html = $this->_renderHeadEcommerceJavascriptVars();
        
        $html .= $this->_renderAddToCartDialogWarningScriptsMessages();
        
        return $html;
    }
    
    public function renderStartContent()
    {
        $html = 
                '<div id="shoppingcart">'.
                    '<div id="shoppingcart-center">'.
                
                        // Show a basic dialog
                        '<div id="basic-dialog" style="display:none"></div>'. 
                
                        // Show a dialog when we increase the spinner cart and there isn't stock enough
                        '<div id="addtocart-amount-spinner-dialog-warning" style="display:none"></div>'.
                
                        '<div id="shoppingcart-content">'.
                '';   
        
        return $html;
    } 
    
    public function renderEndContent()
    {
        $html = 
                        '</div>'.
                    '</div>'.
                '</div>'.
                '';   
        
        return $html;
    }
    
    public function renderHeaderTitles()
    {
        $html = 
                '<div id="shoppingcart-content-header">'.
                    '<table id="shoppingcart-content-header-table" class="title">'.
                        '<tr>'.
                            '<td class="shoppingcart-content-articles-table-column-article '. 
                                       'shoppingcart-content-articles-table-column-article-header">'.
                                strtoupper(lang::trans('articles')).
                            '</td>'.
                            '<td class="shoppingcart-content-articles-table-column-amount '. 
                                       'shoppingcart-content-articles-table-column-amount-header">'.
                                strtoupper(lang::trans('amount')).'&nbsp;/&nbsp;'.strtoupper(lang::trans('price')).
                            '</td>'.
                            '<td class="shoppingcart-content-articles-table-column-price '. 
                                       'shoppingcart-content-articles-table-column-price-header">'.
                                strtoupper(lang::trans('total')).
                            '</td>'.
                        '</tr>'.
                    '</table>'.
                '</div>'.
                '';     
                
        return $html;
    }
    
    protected function _geShoppingcartData($shoppingcart_value)
    {
        $current_lang = lang::getCurrentLanguage();
        $ecommerce_controller = new ecommerceController();
        $article_controller = new articleController();
        $brand_controller = new brandController();
        
        // Build article detail url
        $article_detail_url = $article_controller->getArticleUrl($shoppingcart_value->article);
            
        // Image
        $image_path = '';
        $images = $article_controller->getImages($shoppingcart_value->article, false);
        if (!empty($images))
        {
            $image_path = $images[0];
        }
        
        // Title
        $article_title = $article_controller->getTitle($shoppingcart_value->article);
        $article_title_anchor = '<a href="'.$article_detail_url.'"'.$this->_rel_external.'>'.$article_title.'</a>';
        
        // Display
        $display = $article_controller->getDisplay($shoppingcart_value->article);
            
        // Brand
        $brand = $brand_controller->getBrandByCode($shoppingcart_value->article->brand, true);
        $brand_code = $brand_controller->getBrandCode($brand);
        $brand_name = $brand_controller->getBrandName($brand);
        $brand_url = $ecommerce_controller->getUrl(array($current_lang, lang::trans('url-brands'), $brand_code));
        
        $min_amount_to_add = 0;
        $max_amount_to_add = 0;
        $in_stock = 0;
        $amount = 0;
        $stock_enable = !(isset($shoppingcart_value->article->infinityStock) && $shoppingcart_value->article->infinityStock);
        if ($stock_enable)
        {
            $in_stock = $shoppingcart_value->article->stock;
//                     $html .= 
//                                        '<label class="shoppingcart-content-articles-table-column-amount-stock-label">'.
//                                            'STOCK'.':&nbsp;'.
//                                        '</label>'.
//                                        '<span class="shoppingcart-content-articles-table-column-amount-stock-value">'.
//                                            $shoppingcart_value->article->stock.
//                                        '</span>'.
//                                        '';

            $min_amount_to_add = ($in_stock == 0)? 0 : 1;
            $max_amount_to_add = $shoppingcart_value->amount + ($in_stock - $shoppingcart_value->amount); 
            $amount = $shoppingcart_value->amount;
        }
        else
        {
            $min_amount_to_add = 1;
            $max_amount_to_add = 100; 
            $amount = $shoppingcart_value->amount;
        }
        
        return array(
            'article_detail_url' => $article_detail_url,
            'image_path' => $image_path,
            'article_title' => $article_title,
            'article_title_anchor' => $article_title_anchor,
            'display' => $display,
            'brand_url' => $brand_url,
            'brand_name' => $brand_name,
            'ref' => $shoppingcart_value->article->code,
            'amount' => $amount,
            'article_code' => $shoppingcart_value->article->code,
            'stock_enable' => $stock_enable,
            'in_stock' => $in_stock,
            'min_amount_to_add' => $min_amount_to_add,
            'max_amount_to_add' => $max_amount_to_add,
            'rendered_price' => $this->renderPriceFormat($shoppingcart_value->price).'&euro;',
            'rendered_total_price' => $this->renderPriceFormat(($shoppingcart_value->price * $amount)).'&euro;'
        );
    }

    public function renderArticles($shoppingcart)
    { 
        $html =                 
                '<div id="shoppingcart-content-articles">'.
                    '<table id="shoppingcart-content-articles-table" border="0" cellpadding="0" cellspacing="0">'.
                '';
        
        foreach ($shoppingcart as $shoppingcart_value) 
        {
            $html .= $this->_renderArticleRows($shoppingcart_value);
        }
                
        $html .= 
                    '</table>'.
                '</div>'.
                '';          
        
        return $html;
    }
    
    protected function _renderArticleRows($shoppingcart_value)
    {
        $data = $this->_geShoppingcartData($shoppingcart_value);
        $html = $this->_renderArticleRow($data);
        return $html;
    }
    
    protected function _renderArticleRow($data)
    {
        // Start article content column
        $html = 
                    '<tr>'.
                        '<td class="shoppingcart-content-articles-table-column '. 
                                   'shoppingcart-content-articles-table-column-article '. 
                                   'shoppingcart-content-articles-table-column-article-content">'.
                    '';

        // Start article content
        $html .=            
                            '<table border="0" cellpadding="0" cellspacing="0">'.
                                '<tr>'.
                                    '<td>'.
                                        '<a href="'.$data['article_detail_url'].'"'.$this->_rel_external.'>'.
                                            '<img class="shoppingcart-content-articles-table-column-article-content-img" '. 
                                                'src="'.$data['image_path'].'" />'.
                                        '</a>'.
                                    '</td>'.
                                    '<td>'.
                                        '<div class="shoppingcart-content-articles-table-column-article-content-container-text">'.
                '';

        // Title
        $html .=            
                                        '<div class="shoppingcart-content-articles-table-column-article-content-title article-title">'.
                                            $data['article_title_anchor'].
                                        '</div>'.
                    '';

        // Display
        if (!empty($data['display']))
        {
            $html .=  
                                        '<div class="shoppingcart-content-articles-table-column-article-content-display article-display">'.
                                            $data['display'].
                                        '</div>';            
        } 

        // Brand
        $html .= 
                                        '<div class="shoppingcart-content-articles-table-column-article-content-brand">'.
                                            '<a href="'.$data['brand_url'].'" class="brand"'.$this->_rel_external.'>'.
                                                $data['brand_name'].
                                            '</a>'.
                                        '</div>';

        // Ref.
        $html .= $this->_renderArticleRef($data);

        // End article content
        $html .=            
                                        '</div>'.
                                    '</td>'.
                                '</tr>'.
                            '</table>'.
                        '</td>'.
                '';


        // Amount column
        $html .= $this->_renderAmountColumn($data);

        // Total price and trash column     
        $html .=                
                '<td class="shoppingcart-content-articles-table-column '. 
                           'shoppingcart-content-articles-table-column-price '. 
                           'shoppingcart-content-articles-table-column-price-content">'.
                    // Total price
                    '<label id="shoppingcart-content-articles-table-column-price-content-price-'.$data['article_code'].'" class="price">'.
                        $this->_renderArticleTotalPrice($data).
                    '</label>'.
                    // Trash
                    '<img class="shoppingcart-content-articles-table-column-price-content-trash" '. 
                        'src="/modules/ecommerce/frontend/res/img/trash1-black.png" '. 
                        'onClick="removeFromShoppingcart(\''.$data['article_code'].'\')" '. 
                    '/>'.
                '</td>'.
            '</tr>'.
            '';        
        
        return $html;
    }
    
    protected function _renderArticleTotalPrice($data)
    {
        return $data['rendered_total_price'];
    }
    
    protected function _renderArticleRef($data)
    {
        // Ref.
        $html =
                '<div class="shoppingcart-content-articles-table-column-article-content-ref label-ref">'.
                    'Ref. '.$data['ref'].
                '</div>';        
        
        return $html;
    }
    
    protected function _renderAmountColumn($data)
    {
        // Start amount column
        $html =            
                '<td class="shoppingcart-content-articles-table-column '. 
                           'shoppingcart-content-articles-table-column-amount '. 
                           'shoppingcart-content-articles-table-column-amount-content">'.
                    '<div class="shoppingcart-content-articles-table-column-amount-stock">'.
                    '';

        $html .= 
                    '</div>'.                       
                    // Amount (spinner) and price
                    '<div class="shoppingcart-content-articles-table-column-amount-content-div">'.                    
                        '<div class="shoppingcart-content-articles-table-column-amount-content-spinner-div">'.                    
                            '<input '.
                                    'class="shoppingcart-content-articles-table-column-amount-content-spinner" '.
                                    'name="shoppingcart-content-articles-table-column-amount-content-spinner-name" '.
                                    'value="'.$data['amount'].'" '.
                                    '_article_code="'.$data['article_code'].'" '.
                                    '_article_title="'.$data['article_title'].'" '.
                                    '_enable="'.$data['stock_enable'].'" '.
                                    '_stock="'.$data['in_stock'].'" '.
                                    '_min="'.$data['min_amount_to_add'].'" '.
                                    '_max="'.$data['max_amount_to_add'].'" '.
                            '>'.
                        '</div>'.
                        '<div class="shoppingcart-content-articles-table-column-amount-content-x">'.
                            'X'.
                        '</div>'.
                        '<div class="shoppingcart-content-articles-table-column-amount-content-price price">'.
                            $data['rendered_price'].
                        '</div>'.
                    '</div>'.
                '</td>';         
        
        return $html;      
    }
    
    public function renderTotals($total_price, $shipping_cost, $free_shipping_cost_from, $voucher, $voucher_discount, $second_unit_discount, $final_total_price)
    {
        $html =         
                '<div id="shoppingcart-content-totals-container">'.
                    '<div id="shoppingcart-content-totals">'.
                        '<table class="shoppingcart-content-totals-table" border="0" cellpadding="0" cellspacing="0">'.
                            
                            // Total price of articles
                            '<tr>'.
                                '<td class="shoppingcart-content-totals-table-column '. 
                                           'shoppingcart-content-totals-label'.
                                           '">'.
                                    lang::trans("total_price").' ('.lang::trans("tax_included").') : '.
                                '</td>'.
                                '<td class="shoppingcart-content-totals-table-column '. 
                                           'shoppingcart-content-totals-value '.
                                           'shoppingcart-content-totals-shippingcost '.
                                           'price'.
                                           '">'.
                                    $this->renderPriceFormat($total_price).'&euro;'.
                                '</td>'.
                                '<td class="shoppingcart-content-totals-table-column '. 
                                           'shoppingcart-content-totals-trash'.
                                           '">'.
                                '</td>'.
                            '</tr>'.

                            // Shipping cost
                            '<tr>'.
                                '<td class="shoppingcart-content-totals-table-column-shippingcost '. 
                                           'shoppingcart-content-totals-label'.
                                           '">'.
                                    lang::trans("shipping_cost").' :'.
                                '</td>'.
                                '<td class="shoppingcart-content-totals-table-column-shippingcost '. 
                                           'shoppingcart-content-totals-value '.
                                           'shoppingcart-content-totals-shippingcost '.
                                           'price'.
                                           '">'.
                                    $this->renderPriceFormat($shipping_cost).'&euro;'.
                                '</td>'.
                            '</tr>'.
                            '<tr>'.
                                '<td class="shoppingcart-content-totals-table-column-info '. 
                                           '">'.
                                    lang::trans("free_shipping_cost_from").' '.$free_shipping_cost_from.'&euro;'.
                                '</td>'.
                            '</tr>'.
                '';
        
        if ($voucher_discount !== 0)
        {
            $html .= 
                            // Voucher
                            '<tr>'.
                                '<td class="shoppingcart-content-totals-table-column '. 
                                           'shoppingcart-content-totals-label'.
                                           '">'.
                                    lang::trans('voucher').' :'.
                                '</td>'.
                                '<td class="shoppingcart-content-totals-table-column '. 
                                           'shoppingcart-content-totals-value '.
                                           'shoppingcart-content-totals-shippingcost '.
                                           'price'.
                                           '">'.
                                    $this->renderPriceFormat($voucher_discount).'&euro;'.
                                    (($voucher->voucherType === 'percentage-over-total')? (' <font size=2>('.$voucher->value.'%)</font>') : '').
                                '</td>'.
                            '</tr>'.
                    '';            
        }
        
        if ($second_unit_discount !== 0)
        {
            $html .= 
                            // 2nd unit discount
                            '<tr>'.
                                '<td class="shoppingcart-content-totals-table-column '. 
                                           'shoppingcart-content-totals-label'.
                                           '">'.
                                    lang::trans('discount_for_2nd_unit').' :'.
                                '</td>'.
                                '<td class="shoppingcart-content-totals-table-column '. 
                                           'shoppingcart-content-totals-value '.
                                           'shoppingcart-content-totals-shippingcost '.
                                           'price'.
                                           '">'.
                                    $this->renderPriceFormat($second_unit_discount).'&euro;'.
                                '</td>'.
                            '</tr>'.
                    '';            
        }
            
        $html .= 
                        '</table>'.
                        '<table class="shoppingcart-content-totals-table" border="0" cellpadding="0" cellspacing="0">'.
                            // Final total price
                            '<tr>'.
                                '<td class="shoppingcart-content-totals-table-column '. 
                                           'shoppingcart-content-totals-label'.
                                           '">'.
                                    lang::trans("final_total_price").' ('.lang::trans("tax_included").') : '.
                                '</td>'.
                                '<td class="shoppingcart-content-totals-table-column '. 
                                           'shoppingcart-content-totals-value '.
                                           'price'.
                                           '">'.
                                    $this->renderPriceFormat($final_total_price).'&euro;'.
                                '</td>'.
                                '<td class="shoppingcart-content-totals-table-column '. 
                                           'shoppingcart-content-totals-trash'.
                                           '">'.
                                    // Trash to remove all articles
                                    '<img id="shoppingcart-content-totals-trash-img" '. 
                                        'src="/modules/ecommerce/frontend/res/img/trash1-black.png" '. 
                                        'onClick="removeAllFromShoppingcart()" '.  
                                    '/>'.
                                '</td>'.
                            '</tr>'.
                        '</table>'.
                    '</div>'.
                '</div>'.
                '';    
        
        return $html;  
    }
    
    public function renderEmptyCart()
    {
        $html =         
                '<div id="shoppingcart-content-empty">'.
                    '<img id="shoppingcart-content-empty-img" src="/modules/ecommerce/frontend/res/img/shoppingcarts/shoppingcart5-black.png" />'.
                    '<div id="shoppingcart-content-empty-text">'.
                        lang::trans('shoppingcart_is_empty').
                    '</div>'.
                '</div>'.
                '';     
        
        return $html;
    }
    
    public function renderVoucher($voucher)
    {
        $voucher_code = (isset($voucher) && !empty((array) $voucher))? $voucher->code : '';
        
        $html_content = $this->_renderVoucherTooltip($voucher_code);
        $_content_tag = '_content="'.htmlentities($html_content).'" '; 
        
        if (empty($voucher_code))
        {
            $voucher_text = lang::trans('have_you_got_a_voucher');
        }
        else
        {
            $voucher_text = lang::trans('voucher').' : <b>'.$voucher_code.'</b>';
        }
        
        $html = 
                '<div id="shoppingcart-content-voucher-zone">'.
                    '<table id="shoppingcart-content-voucher-table" border="0" cellpadding="0" cellspacing="0">'.
                        '<tr>'.
                            '<td class="shoppingcart-content-voucher-table-column">'.
                                '<div id="shoppingcart-content-voucher-wrapper">'.
                                    '<a '.
                                        'id="shoppingcart-content-voucher" '.
                                        'class="label-info"'.$this->_rel_external.' '.
//                                        $_content_tag.
                                        'onClick="showVoucherDialog()" '.
                                    '>'.
                                        $voucher_text.
                                    '</a>'.
                                '</div>'.
                            '</td>'.
                        '</tr>'.
                    '</table>'.
                '</div>'.
                $html_content.
                '';
                
        return $html;
    }
    
    private function _renderVoucherTooltip($voucher_code)
    {
        $html = '';
        
        // Start render
        $html .= 
                '<div id="shoppingcart-content-voucher-tooltip">';
     
        // Voucher field
        $html .= '<div class="label">'.lang::trans('voucher').'</div>';
        $html .= 
                '<input '.
                    'id="shoppingcart-content-voucher-tooltip-input" '.
                    'class="field-voucher field" '.
                    'name="vouchercode" '.
                    'type="text" '.
                    'value="'.$voucher_code.'" '.
                '>';
        
        // Div
        $html .= '<div>'.'</div>';

        // Confirm button
        $html .= 
                '<button '.
                    'id="shoppingcart-content-voucher-tooltip-button" '.
                    'type="button" '.                        
                    'class="'.
                        'button '.
                        'button-ordering'.
                        '" '.
                    'onclick="" style="cursor:pointer" '.
                '>'.
                    lang::trans('confirm').
                '</button>'.
                '';
        
        // Error message
        $html .= '<div id="shoppingcart-content-voucher-tooltip-error-msg">'.'</div>';
            
        // End render
        $html .= '</div>';
        
        return $html;    
    }  
    
    public function renderWindowAfterConfirmVoucher($msg)
    {
        // Start render
        $html = '<div id="shoppingcart-content-voucher-message-wrapper" >';
        
        $html .= 
                '<div id="shoppingcart-content-voucher-message-title" >'.
                    lang::trans('voucher_activated').
                '</div>'.
                '';
        
        $html .= 
                '<div id="shoppingcart-content-voucher-message-msg" >'.
                    $msg.
                '</div>'.
                '';
        
        // End render
        $html .= '</div>';         
  
        return $html;
    }  
}