<?php

namespace modules\ecommerce\frontend\view;

// Controllers
use core\device\controller\device;
use modules\ecommerce\frontend\controller\ecommerce as ecommerceController;
use modules\ecommerce\frontend\controller\lang;

/**
 * Final steps view, in order to finalize the order
 *
 * @author Dani Gilabert
 * 
 */
class finalSteps
{
    
    protected $_main_controller;
    protected $_hidden_steps;
    protected $_description = array();
    protected $_rel_external;
    
    public function __construct()
    {
        $this->_main_controller = new ecommerceController();
        $this->_hidden_steps = array();
        
        //$this->description[1] = lang::trans('shoppingcart');
        $this->description[1] = lang::trans('order_summary');
        $this->description[2] = lang::trans('shipping_data');
        $this->description[3] = lang::trans('confirmation');
        $this->description[4] = lang::trans('payment');
        $this->description[5] = lang::trans('done').'!';
        
        $this->_rel_external = device::isMobileVersion()? ' rel="external"' : '';
    }
    
    public function setDescription($step, $value)
    {
        $this->description[$step] = $value;
    }
    
    public function renderFinalStepsMenu($current_step)
    {
        // Start rendering
        $html = 
                '<div id="final-steps-menu">'.     
                    '<table id="final-steps-menu-table" cellpadding="0" cellspacing="0">'.
                        '<tr class="final-steps-menu-table-row">'.
                '';
        
        $html .= $this->_renderSteps($current_step);
        
        // End rendering
        $html .= 
                        '</tr>'.
                    '</table>'.
                '</div>';
         
        return $html;            
    }
    
    private function _renderSteps($current_step)
    {
        // Step 1: Shopping cart
        // Step 2: Personal data
        // Step 3: Validation
        // Step 4: Payment
        // Step 5: Done!
        $html = '';
        $current_lang = lang::getCurrentLanguage();
        $link_url = array();
        
        if ($current_step == 1)
        {
            // Shopping cart
            $link_url[1] = '';
            $link_url[2] = '';
            $link_url[3] = '';
            $link_url[4] = '';
            $link_url[5] = '';
        }
        
        if ($current_step == 2)
        {
            // Personal data
            $link_url[1] = $this->_main_controller->getUrl(array($current_lang, 'shoppingcart'));
            $link_url[2] = '';
            $link_url[3] = '';
            $link_url[4] = '';
            $link_url[5] = '';
        }
        elseif ($current_step == 3)
        {
            // Validation
            $link_url[1] = $this->_main_controller->getUrl(array($current_lang, 'shoppingcart'));
            $link_url[2] = $this->_main_controller->getUrl(array($current_lang, 'personaldata'));
            $link_url[3] = '';
            $link_url[4] = '';
            $link_url[5] = '';
            
        }
        elseif ($current_step == 4)
        {
            // Payment
            $link_url[1] = $this->_main_controller->getUrl(array($current_lang, 'shoppingcart'));
            $link_url[2] = $this->_main_controller->getUrl(array($current_lang, 'personaldata'));
            $link_url[3] = $this->_main_controller->getUrl(array($current_lang, 'validation'));
            $link_url[4] = '';
            $link_url[5] = '';
            
        }
        elseif ($current_step == 5)
        {
            
        }
        else
        {
            
        }
        
        $shows_step = 0;
        for ($step=1; $step<=count($this->description); $step++)
        {
            if (!in_array($step, $this->_hidden_steps))
            {
                $shows_step++;
                $html .= $this->_renderStep($step, $shows_step, $current_step, $link_url[$step]);
            }             
        }
        
        return $html;      
    }
    
    private function _renderStep($step, $shows_step, $current_step, $link_url)
    {
        
        // Status
        if ($step == $current_step)
        {
            $status_class = 'final-steps-menu-current-status';
        }
        else if ($step < $current_step)
        {
            $status_class = 'final-steps-menu-last-status';
        }
        else if ($step > $current_step)
        {
            $status_class = 'final-steps-menu-future-status';
        }
        
        // Border left style
        $diff = $step - $shows_step;
        if ($shows_step != 1 && 
                (
                    $shows_step > ($current_step + 1 + $diff) ||
                    $shows_step < ($current_step - $diff)
                )
            )
        {
            $border_left_class = 'final-steps-menu-border-left';
        }
        else
        {
            $border_left_class = '';
        }
        
        $html = 
                '<td class="final-steps-menu-table-column '.$status_class.' '.$border_left_class.'">';
        
        $html .= (!empty($link_url))? '<a href="'.$link_url.'" class="final-steps-menu-link"'.$this->_rel_external.'>' : '';
        $html .=
                '<div class="final-steps-menu-table-column-div-number">'.
                    $shows_step.
                '</div>'.
                $this->_renderStepDescription($step).
                '';
        $html .= (!empty($link_url))? '</a>' : '';
        
        $html .=
                '</td>'.
                '';    
        
        return $html;      
    }
    
    protected function _renderStepDescription($step)
    {    
        $html =
                '<div class="final-steps-menu-table-column-div-description">'.
                    $this->description[$step].
                '</div>';
                '';            
        
        return $html; 
    }
    
}