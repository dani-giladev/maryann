<?php

namespace modules\cms\frontend\controller;

// Controllers
use core\mail\controller\mail as mailCore;
use modules\cms\frontend\controller\webpage;

/**
 * CMS frontend mail controller
 *
 * @author Dani Gilabert
 * 
 */
class mail extends webpage
{
    
    public function renderPage()
    {
        $html = '';
        
        // Start rendering
        $html .= $this->_renderStartPage();
        
        // Head
        $html .= $this->_view->renderStartHead();
        $html .= $this->_renderHead();
        $html .= $this->_view->renderEndHead();
                  
        // Start body
        $html .= $this->_view->renderStartBody();
        
        // Content
        $html .= $this->_view->renderContent($this->getWebsite());
        
        // End body
        $html .= $this->_view->renderEndBody();
        
        // End rendering
        $html .= $this->_view->renderEndPage();   
        
        return $html;      
    }  
    
    protected function _getKeywords()
    {
        return '';
    }          
    
    protected function _getRobots()
    {
        return 'noindex, nofollow';
    }     
    
    protected function _getFaviconPath()
    {
        return '';
    }
    
    protected function _getAlternativeLanguages()
    {
        return '';
    }
    
    public function send($subject, $body, $to)
    {
        if (empty($to))
        {
            return false;
        }
        
        return mailCore::send($subject, $body, $to);
    }
    
    protected function _getMailAddressesParam($is_admin = false)
    {
        return array();
    }
    
    public function getMailAddresses($is_admin = false)
    {
        $ret = array();
        
        $mail_addresses = $this->_getMailAddressesParam($is_admin);
        if (!empty($mail_addresses))
        {
            foreach ($mail_addresses as $value) {
                $ret[$value->email] = $value->name;
            }
        }
        
        return $ret;
    }
}