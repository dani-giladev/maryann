<?php

namespace modules\ecommerce\frontend\view;

/**
 * Action buttons view
 *
 * @author Dani Gilabert
 * 
 */
class actionButtons
{
    protected $_render_delimiter;
    
    public function __construct($render_delimiter = false) {
        $this->_render_delimiter = $render_delimiter;
    }

    public function renderActionButtons($continue_shopping_button = array('visible' => true,
                                                                          'type' => 'button',
                                                                          'text' => '',
                                                                          'onClick' => ''),
                                        $ordering_button = array('visible' => true,
                                                                 'type' => 'button',
                                                                 'text' => '',
                                                                 'onClick' => ''),
                                        $home_button = array('visible' => false,
                                                             'type' => 'button',
                                                             'text' => '',
                                                             'onClick' => ''))
    {
        $html = '<div id="action-buttons">'; 
        
        // Continue shopping button
        if ($continue_shopping_button['visible'])
        {
            $button_position_class = ($ordering_button['visible'])? 'action-buttons-left-position ' : 'action-buttons-center-position ';
            
            if (!empty($home_button['visible']))
            {
                $onClick = '';
                if (!empty($home_button['onClick']))
                {
                    $onClick = 'onClick="'.$home_button['onClick'].'" ';
                }
                $margin_right_class = 'action-buttons-margin-right ';
                $html .=
                        '<div class="'.$button_position_class.'">'.
                            '<button '.
                                'type="button" '.
                                'class="action-buttons-button '.
                                       'button '.
                                        $margin_right_class.
                                       'button-continueshopping" '.
                                $onClick.
                            '>'.
                                $home_button['text'].
                            '</button>';                   
            }
            
            $onClick = '';
            if (!empty($continue_shopping_button['onClick']))
            {
                $onClick = 'onClick="'.$continue_shopping_button['onClick'].'" ';
            }
            if (empty($home_button['visible']))
            {
                $html .= '<div class="'.$button_position_class.'">';
            }
            $html .=
                        '<button '.
                            'type="button" '.
                            'class="action-buttons-button '.
                                   'button '.
                                   'button-continueshopping" '.
                            $onClick.
                        '>'.
                            $continue_shopping_button['text'].
                        '</button>'.
                    '</div>';
        }        

        // Ordering button
        if ($ordering_button['visible'])
        {
            $button_position_class = ($continue_shopping_button['visible'])? 'action-buttons-right-position ' : 'action-buttons-center-position ';
            $onClick = '';
            if (!empty($ordering_button['onClick']))
            {
                $onClick = 'onClick="'.$ordering_button['onClick'].'" ';
            }
            $html .= 
                    '<div class="'.$button_position_class.'">'.
                        '<button '.
                            'type="'.$ordering_button['type'].'" '.
                            'class="action-buttons-button '.
                                   'button '.
                                   'button-ordering" '.
                            $onClick.
                        '>'.
                            $ordering_button['text'].
                            '</button>'.
                    '</div>';      
        }
        
        $html .= '</div>';  
        
        if ($this->_render_delimiter)
        {
            $html .= '<div class="action-buttons-delimiter"></div>'; 
        }
        
        return $html;  
    }
    
}