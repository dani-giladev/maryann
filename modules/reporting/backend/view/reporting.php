<?php   

namespace modules\reporting\backend\view;

// Controllers
use core\config\controller\config;
use core\backend\controller\lang;

/**
 * Backend common view for reporting
 *
 * @author Dani Gilabert
 * 
 */
class reporting
{
    protected $_lang;
    protected $_base_path;
    protected $_filemanger_path;
    
    public function __construct()
    {
        $this->_lang = lang::getLanguage();
        
        $this->_base_path = config::getConfigParam(array("application", "base_path"))->value;
        $this->_filemanger_path = config::getFilemanagerPath();        
    }
    
    protected function _getStyles()
    {
        $html = '';
        
        $html .= <<<HTML
                <style type="text/css">
                
                    .reporting-title {
                        width: 100%;
                        border-bottom: 1px solid black;
                    }
                    
                    .reporting-table {
                        margin: 10px 0px 0px 50px;
                    }
                
                    .reporting-column {
                        padding: 5px;
                        border: 1px solid #E5EEFF;
                        font-size: 10px;
                    }
                
                    .reporting-column-center {
                        text-align: center;
                    }
                
                    .reporting-column-header {
                        background-color:  #E5EEFF;
                    }
                
                </style>
HTML;
        
        return $html;
    }
    
    protected function _getTitle($text)
    {
        return 
            '<div class="reporting-title">'.
                $this->_getText($text, 'black', true, '4').
            '</div>'.
            '<br>'.
            '';
    }
    
    protected function _getText($text, $color = "black", $bold = false, $size = null)
    {
        $html = '';
        
        $size_text = (is_null($size))? '' : (' size="'.$size.'"');
        
        $html .= '<font'.$size_text.' color="'.$color.'">'.$text.'</font>'; 
        if ($bold)
        {
            $html = '<b>'.$html.'</b>';
        }
        
        return $html;
    }
    
    protected function _getArticleName($article)
    {
        $ret = '';
        
        $lang = $this->_lang;
        $display = isset($article->displays->$lang)? $article->displays->$lang : "";
        $name = $article->titles->$lang;
        
        $ret .= $name;
        if (!empty($display))
        {
            $ret .= ' - '.$display;
        }
        
        return $ret;
    }
    
}