<?php

namespace modules\ecommerce\frontend\view\menu;

// Controllers
use core\config\controller\config;
use core\device\controller\device;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce;

/**
 * E-commerce frontend menu view
 *
 * @author Dani Gilabert
 * 
 */
class menu
{
    protected $_ecommerce_controller;
    protected $_current_lang;
    protected $_url_pieces;
    
    public function __construct()
    {
        $this->_ecommerce_controller = new ecommerce();
        $this->_current_lang = lang::getCurrentLanguage();
        $this->_url_pieces = array($this->_current_lang, lang::trans('url-categories'));
    }

    public function renderMenu($tree)
    {
        $html = '';
        
        if (!isset($tree) || empty($tree))
        {
            return $html;
        }
        
        foreach ($tree as $key => $value) {
            // Is available?
            if (!$value->_data->available) continue;
        
            // Is empty?
            if (isset($value->_data->empty) && $value->_data->empty) continue;

            $there_are_children = false;
            if (isset($value->children) && !empty($value->children))
            {
                $children_html = self::renderMenu($value->children);
                if (!empty($children_html))
                {
                    $there_are_children = true;
                }
            }
            
            // Get text
            $text = $this->_getText($value->_data);
            
            // Get url
            $url = $this->_getUrl($value->_data);
            
            // Build menu
            $html .= '<li>';      
            if (device::isTouchDevice() && $there_are_children)
            {
                $html .= '<a href="#">';                
            }
            else
            {
                $html .= '<a href="'.$url.'">';
            }
            //$html .= ($there_are_children? $text : ('<b>'.$text.'</b>')).'</a>';
            $html .= $text.'</a>';
            
            // Finishing...
            if ($there_are_children)
            {
                $html .= '<ul>';

                if (device::isTouchDevice())
                {
                    //$html .= '<li><a href="'.$url.'">'.'<i><b>'.strtoupper(lang::trans('view_all')).' '.$text.'</b></i>'.'</a>';
                    $html .= '<li><a href="'.$url.'">'.'<i>'.strtoupper(lang::trans('view_all')).' '.$text.'</i>'.'</a>';
                    $html .= '</li>';
                }

                $html .= $children_html;
                $html .= '</ul>';                    
            }
       
            $html .= '</li>';
        }  
        
        return $html;
    }
    
    public function getEmptyMenu()
    {
        return '?';
    }
    
    protected function _getText($data, $forced_lang = null)
    {
        $lang = (is_null($forced_lang))? $this->_current_lang : $forced_lang;
        $property = 'titles-'.$lang;
        
        if (!isset($data->$property) ||
            empty($data->$property)) 
        {
            if (is_null($forced_lang))
            {
                $default_language =  config::getConfigParam(array("application", "default_language"))->value;
                return $this->_getText($data, $default_language);                
            }
            return '?';
        }
        
        return $data->$property;
    }
    
    protected function _getUrl($data)
    {
        $url_property = 'url'.ucfirst($this->_current_lang);
        if (isset($data->$url_property) && !empty($data->$url_property))
        {
            $new_piece = $data->$url_property;
        }
        else
        {
            $new_piece = $data->code;
        }
        $url_pieces = array_merge($this->_url_pieces, array($new_piece));
        $url = $this->_ecommerce_controller->getUrl($url_pieces);

        return $url;
    }
    

    
}