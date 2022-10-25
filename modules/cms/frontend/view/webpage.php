<?php

namespace modules\cms\frontend\view;

// Controllers
use core\device\controller\device;
use modules\cms\frontend\controller\webpage as webpageController;

/**
 * CMS frontend webpage view
 *
 * @author Dani Gilabert
 * 
 */
class webpage
{     
    protected $_webpage_controller;
    
    public function __construct()
    {
        $this->_webpage_controller = new webpageController();
    }
    
    public function renderStartPage($lang)
    {
        $html = 
                '<!DOCTYPE html>'.PHP_EOL.
                '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$lang.'" lang="'.$lang.'">'.PHP_EOL.
                '';
        
        return $html;
    }  
    
    public function renderEndPage()
    {
        $html = '</html>';
        
        return $html;
    }  
    
    public function renderStartHead()
    {
        $html = '<head>'.PHP_EOL;
        
        return $html;
    }          
    
    public function renderHead($title, $description, $keywords, $robots, $favicon_path, $copyright, $hreflangs, $canonical_url, $pagination, $version)
    {
        $html = '';
        
        // Title
        $html .= '<title>'.$title.'</title>'.PHP_EOL;
        
        // Metatags
        if (device::isMobileVersion())
        {
            $html .= '<meta name="viewport" content="width=device-width, initial-scale=1" />'.PHP_EOL;
        }
        $html .= '<meta charset="UTF-8" />'.PHP_EOL;
        $html .= '<meta name="description" content="'.$description.'" />'.PHP_EOL;
        if (!empty($keywords))
        {
            $html .= '<meta name="keywords" content="'.strtolower($keywords).'" />'.PHP_EOL;             
        }
        //$html .= '<meta name="author" content="'.'Dani Gilabert'.'" />'.PHP_EOL;
        $html .= '<meta name="robots" content="'.$robots.'" />'.PHP_EOL; 
        $html .= '<meta name="audience" content="all" />'.PHP_EOL; 
        $html .= '<meta name="copyright" content="'.$copyright.'" />'.PHP_EOL; 
        $html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge" />'.PHP_EOL; 
        
        // Ico
        if (!empty($favicon_path))
        {
            $html .= $this->_renderFavicon($favicon_path);      
        }
        
        // Alternate langs
        if (!empty($hreflangs))
        {
            foreach ($hreflangs as $values) {
                $html .= '<link rel="alternate" type="text/html" hreflang="'.$values['code'].'" href="'.$values['url'].'" title="'.$values['name'].'"/>'.PHP_EOL; 
            }            
        }
        
        // Canonical url
        if (!empty($canonical_url))
        {
            $html .= '<link rel="canonical" href="'.$canonical_url.'"/>'.PHP_EOL; 
        }
        
        // Pagination
        if (!empty($pagination))
        {
            foreach ($pagination as $values) {
                $html .= '<link rel="'.$values['rel'].'" href="'.$values['url'].'"/>'.PHP_EOL; 
            }            
        }
        
        // Styles and scripts
        $html .= $this->_renderHeadStyleSheets($version);
        $html .= $this->_renderHeadScripts($version);        
        
        return $html;
    }     
    
    public function renderEndHead()
    {
        $html = '</head>'.PHP_EOL;
        
        return $html;
    }  
    
    public function renderStartBody()
    {
        $html = '<body>'.PHP_EOL;
        
        return $html;
    }      
    
    public function renderEndBody()
    {
        $html = '</body>'.PHP_EOL; 
        
        return $html;
    }    
    
    public function renderStartHeader()
    {
        $html = '';
        
        if (!device::isMobileVersion())
        {
            $html .= '<header>'.PHP_EOL;            
        }
        else
        {
            $html .= '<div data-dom-cache="false" data-role="page" class="page">'.PHP_EOL;
        }
                
        return $html;
    }       
    
    public function renderEndHeader()
    {
        $html = '';
        
        if (!device::isMobileVersion())
        {
            $html .= PHP_EOL.'</header>'.PHP_EOL;           
        }
        else
        {
            $html .= PHP_EOL.'</div></div>'.PHP_EOL;
        }
        
        return $html;
    }   
    
    public function renderStartMenu()
    {
        $html = '<nav>'.PHP_EOL;
        
        return $html;
    }      
    
    public function renderEndMenu()
    {
        $html = PHP_EOL.'</nav>'.PHP_EOL;
        
        return $html;
    }   
    
    public function renderStartContentWrapper()
    {
        $html = '<div id="content-wrapper">'.PHP_EOL;
        
        return $html;
    }    
    
    public function renderEndContentWrapper()
    {
        $html = PHP_EOL.'</div>';
        
        return $html;
    }
    
    public function renderStartSection()
    {
        $html = '<section>';
        
        return $html;
    }      
    
    public function renderEndSection()
    {
        $html = '</section>'.PHP_EOL;
        
        return $html;
    } 
    
    public function renderStartFooter()
    {
        $html = PHP_EOL.'<footer>'.PHP_EOL;
        
        return $html;
    }  
    
    public function renderEndFooter()
    {
        $html = PHP_EOL.'</footer>'.PHP_EOL;
        
        return $html;
    }
    
    public function getStyleSheetFileContent($path)
    {
        if (!file_exists($path))
        {
            return '';
        }
        
        $content = file_get_contents($path);
        
        // Remove spaces, carry return and line feed
        $find = array(chr(13).chr(10), "\r\n", PHP_EOL, "\r", "  ", "   ", "    ");
        $ret1 = str_replace($find, ' ', $content);
        $ret2 = str_replace(array(" {", " :", " }"), array("{", ":", "}"), $ret1);
        
        return $ret2;
    }      
    
    protected function _renderFavicon($favicon_path)
    {
        $html = 
                '<link rel="shortcut icon" href="'.$favicon_path.'" type="image/x-icon" />'.PHP_EOL.
                '';  
        
        return $html;
    }  
    
    protected function _getHeadCommonStyleSheetsPaths()
    {
        if (device::isMobileVersion())
        {
            $ret = array( 
//                '/res/css/jquery/jquery.mobile/1.4.5/jquery.mobile.min.css',
                '/res/css/jquery/jquery.mobile/1.4.5/themes/deemm.v2-min.css',
                '/res/css/jquery/jquery.mobile/1.4.5/themes/jquery.mobile.icons.min.css',
                '/res/css/jquery/jquery.mobile/1.4.5/jquery.mobile.structure.min.css'
            ); 
        }
        else
        {
            $ret = array( 
                '/res/css/jquery/jquery-ui/1.10.4-deemm.v2/jquery-ui.min.css',
                '/modules/cms/frontend/res/css/common.css'
            );                
        }
        
        return $ret;
    }           
    
    protected function _getHeadStyleSheetsPaths()
    {
        if ($this->_webpage_controller->isDevelopment())
        {
            $ret = $this->getDevelopmentHeadStyleSheetsPaths();
        }
        else
        {
            $ret = array($this->getProductionStyleSheetsPath().$this->getWebpageName().'.min.css');
        }
        
        return $ret;
    }            
    
    protected function _renderHeadStyleSheets($version)
    {
        $html = '';
        
        $style_sheets_paths = $this->_getHeadStyleSheetsPaths();
        foreach ($style_sheets_paths as $path)
        {
            $href = $path.'?v'.$version;
            $html .= '<link rel="stylesheet" href="'.$href.'" type="text/css" media="screen" />'.PHP_EOL;
        }
        
        $html .= $this->_renderHeadStyles();
        
        return $html;
    }          
    
    protected function _renderHeadStyles()
    {
        $html = '';      
        return $html;
    }            
    
    protected function _getHeadCommonScriptsPaths()
    {
        if (device::isMobileVersion())
        {
            $ret = array( 
                //'/res/js/jquery/jquery-1.10.4.js',
                '/res/js/jquery/jquery-1.12.1.js',
                '/res/js/jquery/jquery.mobile/1.4.5/jquery.mobile.min.js',
                '/modules/cms/frontend/res/js/common.js'
            );              
        }
        else
        {
            $ret = array( 
                '/res/js/jquery/jquery-1.10.4.js',
                '/res/js/jquery/jquery-ui/1.10.4/jquery-ui.min.js',
                '/modules/cms/frontend/res/js/common.js'
            );              
        }
        
        return $ret;
    }     
    
    protected function _getHeadScriptsPaths()
    {
        if ($this->_webpage_controller->isDevelopment())
        {
            $ret = $this->getDevelopmentHeadScriptsPaths();
        }
        else
        {
            $ret = array($this->getProductionScriptsPath().$this->getWebpageName().'.min.js');
        }
        
        return $ret;
    }
    
    protected function _renderHeadScripts($version)
    {
        $html = '';
        
        $html .= $this->_addJavascriptVars();
        
        $scripts_paths = $this->_getHeadScriptsPaths();
        foreach ($scripts_paths as $path)
        {
            $src = $path.'?v'.$version;
            $html .= '<script type="text/javascript" src="'.$src.'"></script>'.PHP_EOL;
        }
        
        $html .= $this->_addMicrodata();
        $html .= $this->_addGoogleAnalyticsScript();
        $html .= $this->_addAdditionalScripts();
        
        return $html;
    }       
    
    protected function _addJavascriptVars()
    {
        $html = '';      
        return $html;
    }       
    
    protected function _addMicrodata()
    {
        $html = '';      
        return $html;
    }       
    
    protected function _addGoogleAnalyticsScript()
    {
        $html = '';      
        return $html;
    }    
    
    protected function _addAdditionalScripts()
    {
        $html = '';      
        return $html;
    }
}