<?php

namespace modules\ecommerce\frontend\view;

// Controllers
use core\config\controller\config;
use core\device\controller\device;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\ecommerce as ecommerceController;
use modules\ecommerce\frontend\controller\user as userController;

// Views
use modules\cms\frontend\view\webpage;
use modules\ecommerce\frontend\view\header\preheader;
use modules\ecommerce\frontend\view\header\header;
use modules\ecommerce\frontend\view\footer as footerView;
use modules\ecommerce\frontend\view\user\login as loginView;
use modules\ecommerce\frontend\mobile\view\footer as mobileFooterView;
use modules\ecommerce\frontend\mobile\view\shoppingcart\shoppingcart as mobileShoppingcartView;

/**
 * Common view
 *
 * @author Dani Gilabert
 * 
 */
class ecommerce extends webpage
{     
    
    protected $_ecommerce_controller;
    protected $_skin;
    protected $_rel_external;
    
    public function __construct()
    {
        parent::__construct();
        $this->_ecommerce_controller = new ecommerceController();
        $this->_skin = $this->_ecommerce_controller->getSkin();
        $this->_rel_external = $this->getRelExternalTag();
    }           
    
    protected function _getHeadEcommerceStyleSheetsPaths()
    {
        $common_styles = $this->_getHeadCommonStyleSheetsPaths();
        
        if (device::isMobileVersion())
        {
            $ecommerce_styles = array(
                '/modules/ecommerce/frontend/mobile/res/css/common/ecommerce.css',
                '/modules/ecommerce/frontend/mobile/res/skins/'.$this->_skin.'/common/ecommerce.css',  
                '/modules/ecommerce/frontend/mobile/res/css/user/login.css' 
            );            
        }
        else
        {
            $ecommerce_styles = array(
                //'/res/css/jquery/jquery.tooltipster/4.1.6/tooltipster.bundle.min.css',
                '/res/css/jquery/jquery.tooltipster/3.2.6/tooltipster.css',

                '/modules/ecommerce/frontend/res/css/common/ecommerce.css',
                '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/common/ecommerce.css',
                '/modules/ecommerce/frontend/res/css/header/pre-header.css',
                '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/header/pre-header.css',
                '/modules/ecommerce/frontend/res/css/header/header.css',
                '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/header/header.css',
                '/modules/ecommerce/frontend/res/css/common/footer.css',
                '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/common/footer.css',

                '/modules/ecommerce/frontend/res/css/menu/main.css',
                '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/menu/main.css',
                '/modules/ecommerce/frontend/res/css/menu/breadcrumbs.css',
                '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/menu/breadcrumbs.css', 
                '/modules/ecommerce/frontend/res/css/common/searcher.css',
                '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/common/searcher.css', 
                '/modules/ecommerce/frontend/res/css/shoppingcart/shoppingcart-menu-option.css',
                '/modules/ecommerce/frontend/res/skins/'.$this->_skin.'/shoppingcart/shoppingcart-menu-option.css',  
                '/modules/ecommerce/frontend/res/css/user/login.css' 
            );            
        }
        
        $ret = array_merge($common_styles, $ecommerce_styles);
        
        return $ret;
    }          
    
    protected function _getHeadEcommerceScriptsPaths()
    {
        $common_scripts = $this->_getHeadCommonScriptsPaths();
        
        if (device::isMobileVersion())
        {
            $ecommerce_scripts = array(
                '/res/js/jquery/jquery.blockUI-2.66.0.js',
                
                '/modules/ecommerce/frontend/res/js/ecommerce.js',
                '/modules/ecommerce/frontend/mobile/res/js/menu.js',
                '/modules/ecommerce/frontend/res/js/login.js'
            );            
        }
        else
        {
            $ecommerce_scripts = array(
                '/res/js/jquery/jquery.blockUI-2.66.0.js',
                //'/res/js/jquery/jquery.tooltipster/4.1.6/tooltipster.bundle.min.js',
                '/res/js/jquery/jquery.tooltipster/3.2.6/tooltipster.min.js',

                '/modules/ecommerce/frontend/res/js/ecommerce.js',
                '/modules/ecommerce/frontend/res/js/menu.js',
                '/modules/ecommerce/frontend/res/js/login.js'
            );            
        }
        
        $ret = array_merge($common_scripts, $ecommerce_scripts);
        
        return $ret;     
    }                     
    
    public function getProductionStyleSheetsPath()
    {
        if (device::isMobileVersion())
        {
            return '/modules/ecommerce/frontend/mobile/res/css/production/';
        }
        else
        {
            return '/modules/ecommerce/frontend/res/css/production/';
        }
    }  
    
    public function getProductionScriptsPath()
    {
        if (device::isMobileVersion())
        {
            return '/modules/ecommerce/frontend/mobile/res/js/production/';
        }
        else
        {
            return '/modules/ecommerce/frontend/res/js/production/';
        }
    }     
    
    protected function _renderHeadEcommerceJavascriptVars()
    {
        $current_lang = lang::getCurrentLanguage();
        
        $html = 
                '<script type="text/javascript">'.PHP_EOL.
                
                    'var base_url = "'.$this->_ecommerce_controller->getUrl(array($current_lang)).'";'.PHP_EOL.
                    'var is_touch_device = '.(device::isTouchDevice()? 'true' : 'false').';'.PHP_EOL.
                    'var is_mobile = '.(device::isMobileVersion()? 'true' : 'false').';'.PHP_EOL.
                
                    'var msg_attention = "'.lang::trans('attention').'";'.PHP_EOL.
                    'var msg_wait_please = "'.lang::trans('wait_please').'";'.PHP_EOL.
                    'var msg_yes = "'.lang::trans('yes').'";'.PHP_EOL.
                    'var msg_no = "'.lang::trans('no').'";'.PHP_EOL.
                    'var msg_accept = "'.lang::trans('accept').'";'.PHP_EOL.
                    'var msg_cancel = "'.lang::trans('cancel').'";'.PHP_EOL.
                '</script>'.PHP_EOL.
                '';   
        
        return $html;
    }       
    
    protected function _renderAddToCartDialogWarningScriptsMessages()
    {         
        $html = 
                '<script type="text/javascript">'.PHP_EOL.
                    'var msg_there_is_only = "'.lang::trans('there_is_only').'";'.PHP_EOL.
                    'var msg_units_in_stock = "'.lang::trans('units_in_stock').'";'.PHP_EOL.
                    'var msg_and_all_are_in_shoppingcart = "'.lang::trans('and_all_are_in_shoppingcart').'";'.PHP_EOL.
                    'var msg_you_have_already_added = "'.lang::trans('you_have_already_added').'";'.PHP_EOL.
                    'var msg_to_shoppingcart = "'.lang::trans('to_shoppingcart').'";'.PHP_EOL.
                    'var msg_you_can_only_add = "'.lang::trans('you_can_only_add').'";'.PHP_EOL.
                    'var msg_more = "'.lang::trans('more').'";'.PHP_EOL.
                    'var msg_you_can_add_max_units_in_shoppingcart = "'.lang::trans('you_can_add_max_units_in_shoppingcart').'";'.PHP_EOL.
                '</script>'.PHP_EOL.
                '';   
        
        return $html;
    }      
    
    protected function _addGoogleAnalyticsScript()
    {
        $html = '';      

        if ($this->_ecommerce_controller->isDevelopment())
        {
            return $html;
        }
        
        $website = $this->_ecommerce_controller->getWebsite();
        if (isset($website->googleAnalytics) && !empty($website->googleAnalytics))
        {
            $html .= $website->googleAnalytics.PHP_EOL;
        }
        
        return $html;
    }
    
    public function renderHeader($website, $langs)
    {
        $html = '';
        
        if (device::isMobileVersion())
        {
            $current_lang = lang::getCurrentLanguage();
            $home_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'home'));        
            //$logo_src = $this->_ecommerce_controller->getLogoPath($website);
            $logo_src = '/'.config::getProjectPath().'/ico/favicon.ico';
            $shoppingcart_url = $this->_ecommerce_controller->getUrl(array($current_lang, 'shoppingcart'));
            $shoppingcart_src = "/modules/ecommerce/frontend/res/img/shoppingcarts/shoppingcart5-white.png";
            $search_your_product = lang::trans("search_your_product", $current_lang);
            $mobile_shoppingcart_view = new mobileShoppingcartView();
            $shoppingcart_amount_html = $mobile_shoppingcart_view->renderShoppingcartMenuAmount();
            
            $user_controller = new userController();
            $is_logged_user = $user_controller->isLoggedUser();
            $login_view = new loginView();
            if ($is_logged_user)
            {
                $user = $user_controller->getUser();
                $login_html_content = $login_view->renderLogoutTooltip($user->firstName);
            }
            else
            {
                $login_html_content = $login_view->renderLoginTooltip();
            }
            $user_style = $is_logged_user? ' style="background:green;"': '';
            
            $html .=
                '<div data-role="content" class="content">'.
                    '<div class="content-header">'.
                        '<div id="content-preheader">'.
                            '<a href="'.$home_url.'" rel="external" id="content-preheader-logo">'.
                                '<img id="content-preheader-logo-ico" src="'.$logo_src.'" />'.
                                //'<img id="content-preheader-logo-img" src="'.$logo_src.'" />'.
                                '<span id="content-preheader-websitename">'.
                                    $website->name.
                                '</span>'.                    
                            '</a>'.                    
                            '<a data-role="button" href="#menu-languages" class="ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-notext ui-icon-gear content-preheader-menu"></a>'.
                            
                            //'<a data-role="button" href="#" class="ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-notext ui-icon-user content-preheader-menu"></a>'.
                            '<a data-role="button" href="#popupLogin" data-rel="popup" data-position-to="window" class="ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-notext ui-icon-user content-preheader-menu"'.$user_style.' data-transition="pop"></a>'.
                            '<div data-role="popup" id="popupLogin" data-theme="a" class="ui-corner-all" style="max-width:400px;">'.
                                '<a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>'.
                                $login_html_content.
                            '</div>'.
                    
                            '<a href="'.$shoppingcart_url.'" rel="external" class="content-preheader-menu">'.
                                $shoppingcart_amount_html.
                                '<img id="content-preheader-menu-shoppingcart-img" src="'.$shoppingcart_src.'" />'.
                            '</a>'.
                        '</div>'.

                        '<fieldset class="ui-grid-b content-header-searcher-wrapper" >'.
                            '<div class="ui-block-a" id="content-header-searcher-menu">'.
                                '<a data-role="button" href="#menu-categories-1" class="ui-btn ui-btn-inline ui-mini ui-corner-all ui-btn-icon-notext ui-icon-bars"></a>'.
                            '</div>'.
                            '<div class="ui-block-b" id="content-header-searcher">'.
                                '<input id="searcher-input" type="search" data-corners="false" placeholder="'.$search_your_product.'" class="ui-btn-inline ui-mini" />'.                    
                            '</div>'.
                            '<div class="ui-block-c send" id="content-header-searcher-button">'.
                                '<a data-role="button" onclick="menu.onClickSearchButton();" class="ui-btn ui-btn-inline ui-mini ui-btn-icon-notext ui-icon-search"></a>'.
                            '</div>'.
                        '</fieldset>'.
                        '<ul id="searcher-list" data-role="listview" data-inset="true"></ul>'.
                    '</div>'.
                    '';
        }
        else
        {
            // Pre-header
            $preheader = new preheader($website, $langs);
            $html .= $preheader->render();

            // Header
            $header = new header($website);
            $html .= $header->render();   
        }        
        
        return $html;
    }  
    
    public function renderMenu()
    {
        $html = '';
        
        return $html;
    }
    
    public function renderFooter($website)
    {
        $html = '';
        
        $footer = device::isMobileVersion()? (new mobileFooterView()) : (new footerView());
        $html .= $footer->renderFooter($website);       
                
        return $html;
    }
    
    public function renderPriceFormat($price, $hide_00 = false)
    {
        $ret = number_format(round($price, 2), 2, ",", ".");
        
        if ($hide_00)
        {
            $pieces = explode(',', $ret);
            if ($pieces[1] === '00')
            {
                $ret = $pieces[0];
            }
        }
        
        return $ret;
    }           
    
    public function getSkin()
    {
        return $this->_skin;
    }
    
    public function getRelExternalTag()
    {
        return device::isMobileVersion()? ' rel="external"' : '';
    }

}