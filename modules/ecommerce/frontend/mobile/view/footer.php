<?php

namespace modules\ecommerce\frontend\mobile\view;

// Views
use modules\ecommerce\frontend\view\footer as footerView;

/**
 * Footer mobile view
 *
 * @author Dani Gilabert
 * 
 */
class footer extends footerView
{   

    public function renderFooter($website)
    {
        $html = PHP_EOL.PHP_EOL;
        $html .= $this->_renderFirstRow($website);
        $html .= $this->_renderPayment();
        $html .= $this->_renderLegal();
        return $html;
    }

    protected function _renderFirstRow($website)
    {
        $html = '';
        
        // Start
        $html .= '<div id="footer-firstrow">';
        
        // Logo
        $html .= $this->_getLogo($website); 
        
        $html .= '<div class="footer-short-delimiter"></div>';
                
        // Attention to customer
        $html .=  $this->_getAttention2Customer($website); 
        
        $html .= '<div class="footer-short-delimiter"></div>';
        
        // Info column
        $html .=  $this->_getInfo();
        
        // End
        $html .= '</div>';
        $html .= '<div class="footer-long-delimiter"></div>';        
        
        return $html;
    } 
    
    protected function _renderPayment()
    {
        $html = '';

        // Start
        $html .= '<div id="footer-payment">';
        
        // Payment ways
        $html .= $this->_renderPaymentWays();
        
        // End
        $html .= '</div>';
        $html .= '<div class="footer-long-delimiter"></div>';  
        
        return $html;
    } 
    
  
}