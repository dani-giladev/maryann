<?php

namespace modules\ecommerce\frontend\view\shoppingcart;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce as ecommerceController;
//use modules\ecommerce\frontend\controller\personaldata;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\frontend\controller\brand;

// Views
use modules\ecommerce\frontend\view\ecommerce as ecommerceView;

/**
 * Window view when we've just added an article to shoppingcart
 *
 * @author Dani Gilabert
 * 
 */
class windowAfterAddToShoppingcart
{      
    
    public function renderWindow($article, $amount = 1)
    {
        $ecommerce_view = new ecommerceView();
        $article_controller = new article();
        $brand_controller = new brand();
        
        // Start render
        $html = '<div id="window-after-add-to-shoppingcart" >';
        
        $html .= 
                '<div id="window-after-add-to-shoppingcart-title" >'.
                    strtoupper(lang::trans('you_have_just_added_to_shoppingcart')).' ...'.
                '</div>'.
                '';
        
        // Start article
        $html .= '<div id="window-after-add-to-shoppingcart-article" >';
        
        // Image
        $image_path = '';
        $images = $article_controller->getImages($article, false);
        if (!empty($images))
        {
            $image_path = $images[0];
        }
        $html .=            
                '<img id="window-after-add-to-shoppingcart-article-img" '.
                    'src="'.$image_path.'" />';  
        
        // Price
        if (isset($article->prices) && $article->prices->finalRetailPrice > 0)
        {   
            $price = $article->prices->finalRetailPrice;  
            $total_price = $amount * $price;  
            
            $html .= '<div id="window-after-add-to-shoppingcart-article-price-container">';
            $html .=             
                        '<div id="window-after-add-to-shoppingcart-article-price" class="price">'.
                            $ecommerce_view->renderPriceFormat($total_price).'&euro;'.
                        '</div>';            
            if ($amount > 1)
            {
                $html .= 
                        '<div id="window-after-add-to-shoppingcart-article-price-amount" class="price">'.
                            $amount.'&nbsp;'.'X'.'&nbsp;'.$ecommerce_view->renderPriceFormat($price).'&euro;'.
                        '</div>';
            }
            $html .= '</div>';
        }               
        
        // Start container text
        $html .= '<div id="window-after-add-to-shoppingcart-article-text-container" >';    
        
        // Title
        $title = $article_controller->getTitle($article);
        $html .= 
                '<div id="window-after-add-to-shoppingcart-article-title" class="article-title">'.
                    $title.
                '</div>'.
                '';
        
        // Display
        $display = $article_controller->getDisplay($article);
        if (!empty($display))
        {
            $html .=  
                    '<div id="window-after-add-to-shoppingcart-article-display" class="article-display">'.
                        $display.
                    '</div>';            
        }
        
        // Brand
        $brand = $brand_controller->getBrandByCode($article->brand, true);
        $brand_name = $brand_controller->getBrandName($brand);
        $html .= 
                '<div id="window-after-add-to-shoppingcart-article-brand" class="brand">'.
                    //$article->brandName.
                    $brand_name.
                '</div>'.
                '';
        
        // Ref.
        $html .= 
                '<div id="window-after-add-to-shoppingcart-article-ref" class="label-ref">'.
                    'Ref. '.$article->code.
                '</div>'.
                '';
        
        // End container text
        $html .= '</div>';
            
        // End article
        $html .= '</div>';     
        
        // Action buttons
        $html .= $this->_renderActionButtons();
        
        // End render
        $html .= '</div>';         
  
        return $html;
    }
    
    private function _renderActionButtons()
    {
        $ecommerce_controller = new ecommerceController();
        $current_lang = lang::getCurrentLanguage();
        
        $html = '<div id="window-after-add-to-shoppingcart-action-buttons">'; 
        
        // Continue shopping button
        $html .=         
                '<button '.
                    'type="button" '.
                    'class="window-after-add-to-shoppingcart-action-buttons-continueshopping-button '.
                           'button button-continueshopping" '.
                    'onClick="$.fancybox.close();" '.                
                '>'.
                    lang::trans('continue_shopping').
                '</button>';     

        // Ordering button
        //$personaldata = new personaldata();
        //$next_webpage = ($personaldata->isEmpty())? 'personaldata' : 'validation';
        $next_webpage = 'shoppingcart';
        $ordering_url = $ecommerce_controller->getUrl(array($current_lang, $next_webpage));        
        $html .= 
                '<button '.
                    'type="button" '.
                    'class="window-after-add-to-shoppingcart-action-buttons-ordering-button '.
                           'button button-ordering" '.
                    'onClick="window.location.href=\''.$ordering_url.'\'" '.
                '>'.
                    lang::trans('ordering').
                '</button>';

        $html .= '</div>';    
        
        return $html;  
    }    
}