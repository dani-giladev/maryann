<?php

namespace modules\reporting\backend\controller;

// Controllers
use core\config\controller\config;
use core\ajax\controller\ajax;
//use core\botplus\controller\botplus;
use modules\reporting\backend\controller\reporting;

/**
 * Backend botplus medicines controller for reporting
 *
 * @author Dani Gilabert
 * 
 */
class botplusMedicines extends reporting
{
    private $_keywords = array();
    private $_nokeywords = array();
    private $_export = false;
    
    public function getRecords($params)
    {
        $data = $this->getMedicines($params);
        if (!$data['success'])
        {
            ajax::sendData(array($data));
            return;              
        }
        
        ajax::sendData($data['data']);
    } 
    
    public function getMedicines($params, $export = false)
    {
        $data = array();
        //$option = $params->option;
        $authorized = $params->authorized;
        $raw_keywords = str_replace(array("\n", "\r"), '', $params->keywords) ;
        $raw_nokeywords = str_replace(array("\n", "\r"), '', $params->nokeywords) ;
        
        $base_path = config::getConfigParam(array("application", "base_path"))->value;
        $botplus_path = $base_path.'/'.config::getBotplusPath().'/articles/medicamentos';
        $pattern = $botplus_path.'/*';
        $files = glob($pattern);
        if (empty($files))
        {
            return array(
                'success' => true,
                'msg'=> '',
                'data' => $data
            );
        }     
        
        if (!empty($raw_keywords))
        {
            $this->_keywords = explode(',', $raw_keywords);
        }
        if (!empty($raw_nokeywords))
        {
            $this->_nokeywords = explode(',', $raw_nokeywords);
        }        
        $this->_export = $export;
        
        $counter = 0;
        foreach ($files as $file) {
            $maindata = json_decode(file_get_contents($file), true);
            $code = substr($maindata['ESPECOD'], 0, 6);
            if ($code == '602954')
            {
                $test = true;
            }
            $CODESTADO = $maindata['CODESTADO'];
            $GTVMPDES = (!isset($maindata['GTVMPDES']) || is_array($maindata['GTVMPDES']))? '' : $maindata['GTVMPDES'];
            
            if (
                    ($authorized === 'yes' && $CODESTADO != '0') ||
                    ($authorized === 'no' && $CODESTADO == '0')
            )
            {
                continue;
            }
            
            $is_keyword_matched = false;
            if (!$is_keyword_matched)
            {
                $is_keyword_matched = $this->_isKeywordMatched($GTVMPDES, $this->_keywords);
            }
            
            // Add Dynamic properties
            $maindata['code'] = $code;
            if (isset($maindata['COMPOSICION']) && isset($maindata['COMPOSICION']['Registro']) && !empty($maindata['COMPOSICION']['Registro']))
            {
                $i = 1;
                foreach ($maindata['COMPOSICION']['Registro'] as $values)
                {
                    $CODACT = (!isset($values['CODACT']) || is_array($values['CODACT']))? '' : $values['CODACT'];
                    $CODIGOPA = (!isset($values['CODIGOPA']) || is_array($values['CODIGOPA']))? '' : $values['CODIGOPA'];
                    $DENOFI = (!isset($values['DENOFI']) || is_array($values['DENOFI']))? '' : $values['DENOFI'];
                    $SALCOD = (!isset($values['SALCOD']) || is_array($values['SALCOD']))? '' : $values['SALCOD'];
                    $SALDES = (!isset($values['SALDES']) || is_array($values['SALDES']))? '' : $values['SALDES'];
                    $COMPOCANT = (!isset($values['COMPOCANT']) || is_array($values['COMPOCANT']))? '' : $values['COMPOCANT'];
                    $COMPOUNID = (!isset($values['COMPOUNID']) || is_array($values['COMPOUNID']))? '' : $values['COMPOUNID'];
                    
                    if (!$is_keyword_matched)
                    {
                        $is_keyword_matched = $this->_isKeywordMatched($DENOFI, $this->_keywords);
                    }
                    if (!$is_keyword_matched)
                    {
                        $is_keyword_matched = $this->_isKeywordMatched($SALDES, $this->_keywords);
                    }
                    
                    $delimiter = $this->_export? '|' : '<br>';
                    
                    $CODACT_text = !empty($CODACT)? 'CODACT: '.$CODACT.$delimiter : '';
                    $CODIGOPA_text = !empty($CODIGOPA)? 'CODIGOPA: '.$CODIGOPA.$delimiter : '';
                    $DENOFI_text = !empty($DENOFI)? 'DENOFI: '.$DENOFI.$delimiter : '';
                    $SALCOD_text = !empty($SALCOD)? 'SALCOD: '.$SALCOD.$delimiter : '';
                    $SALDES_text = !empty($SALDES)? 'SALDES: '.$SALDES.$delimiter : '';
                    $COMPOCANT_text = !empty($COMPOCANT)? 'COMPOCANT: '.$COMPOCANT.$delimiter : '';
                    $COMPOUNID_text = !empty($COMPOUNID)? 'COMPOUNID: '.$COMPOUNID.$delimiter : '';
                    
                    $maindata['COMP'.$i] = '';
                    $maindata['COMP'.$i] .= $this->_export? '' : '<div style="font-size:10px;">';
                    $maindata['COMP'.$i] .= 
                                $CODACT_text.
                                $CODIGOPA_text.
                                $DENOFI_text.
                                $SALCOD_text.
                                $SALDES_text.
                                $COMPOCANT_text.
                                $COMPOUNID_text.
                                '';
                    $maindata['COMP'.$i] .= $this->_export? '' : '</div>';
                    $i++;
                }
            }
            if (isset($maindata['DATOSFARMACEUTICOS']) && isset($maindata['DATOSFARMACEUTICOS']['Registro']) && !empty($maindata['DATOSFARMACEUTICOS']['Registro']))
            {
                $i = 1;
                foreach ($maindata['DATOSFARMACEUTICOS']['Registro'] as $values)
                {
                    $CODVALORATO = (!isset($values['CODVALORATO']) || is_array($values['CODVALORATO']))? '' : $values['CODVALORATO'];
                    $VALOFARM = (!isset($values['VALOFARM']) || is_array($values['VALOFARM']))? '' : $values['VALOFARM'];
                    
                    $delimiter = $this->_export? '|' : '<br>';
                    
                    $CODVALORATO_text = !empty($CODVALORATO)? 'CODVALORATO: '.$CODVALORATO.$delimiter : '';
                    $VALOFARM_text = !empty($VALOFARM)? 'VALOFARM: '.$VALOFARM.$delimiter : '';
                    
                    $maindata['DF'.$i] = '';
                    $maindata['DF'.$i] .= $this->_export? '' : '<div style="font-size:10px;">';
                    $maindata['DF'.$i] .= 
                                $CODVALORATO_text.
                                $VALOFARM_text.
                                '';
                    $maindata['DF'.$i] .= $this->_export? '' : '</div>';
                    $i++;
                }
            }
            
            if (!empty($this->_keywords) && !$is_keyword_matched)
            {
                continue;
            }
            
            // Clean data
            unset($maindata['ESPECOD']);
            unset($maindata['COMPOSICION']);
            unset($maindata['DATOSFARMACEUTICOS']);
            
            // Add item
            $data[] = $maindata;
            $counter++;
            //if ($counter >= 100) break;
        }        
        
        return array(
            'success' => true,
            'msg'=> '',
            'data' => $data
        );
    }
    
    public function exportRecords($params)
    {
        $data = $this->getMedicines($params, true);
        $data_file = '';
        
        if (!$data['success'])
        {
            $data_file = $data['msg'];
        }
        else
        {
            if (!empty($data['data']))
            {
                // Header
                $article = $data['data'][0];
                foreach ($article as $key => $value)
                {
                    $data_file .= "\"".$key."\";";
                }                
                $data_file .= PHP_EOL;
                
                // Content
                foreach ($data['data'] as $article)
                {
                    foreach ($article as $key => $value)
                    {
                        if ($key === 'efp' || 
                            $key === 'inDb' || 
                            $key === 'anyStock' || 
                            $key === 'forSale' || 
                            $key === 'forSaleAndVisible')
                        {
                            $val = $value? 'SI' : 'NO';
                        }
                        else
                        {
                            $val = $value;
                        }

                        if (is_array($val))
                        {
                            $val = '';
                        }
                        $data_file .= "\"".$val."\";";
                    }
                    $data_file .= PHP_EOL;                 
                }
            }            
        }
        
        header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
        header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
        header ("Cache-Control: no-cache, must-revalidate");  
        header ("Pragma: no-cache");  
        header ("Content-type: application/vnd.ms-excel");
        header ("Content-Disposition: attachment; filename=\""."farmatic-articles-list.csv\"" );
        
        echo $data_file; 
    }
    
    public function getKeywords($params)
    {
        $key = $params->key;
        
        $base_path = config::getConfigParam(array("application", "base_path"))->value;
        $reporting_keywords_fullpath = $base_path.'/'.config::getBotplusPath().'/reporting-medicines-'.$key.'.txt';
        if (file_exists($reporting_keywords_fullpath))
        {
            $keywords = file_get_contents($reporting_keywords_fullpath);
        }
        else
        {
            if ($key === 'keywords')
            {
                $keywords = <<<EOT
GLUTEN,
CARBOXIMETILALMIDON,
ALMIDON (EXCIPIENTE),
ALMIDON DE TRIGO, TRIGO, DERIVADOS DEL ALMIDON DE TRIGO, DERIVADOS DEL TRIGO,
ALMIDON DE AVENA, AVENA, DERIVADOS DEL ALMIDON DE AVENA, DERIVADOS DE LA AVENA,
ALMIDON DE CEBADA, CEBADA, DERIVADOS DEL ALMIDON DE CEBADA, DERIVADOS DE LA CEBADA,
ALMIDON DE CENTENO, CENTENO, DERIVADOS DEL ALMIDON DE CENTENO, DERIVADOS DEL CENTENO,
ALMIDON DE TRITICALE, TRITICALE, DERIVADOS DEL ALMIDON DE TRITICALE, DERIVADOS DEL TRITICALE
EOT;
            }
            else
            {
                $keywords = <<<EOT
EXENTO DE GLUTEN, SIN GLUTEN
EOT;
            }

            file_put_contents($reporting_keywords_fullpath, $keywords);
        }
        
        echo $keywords;
    }
    
    public function saveKeywords($params)
    {
        $key = $params->key;
        $keywords = $params->keywords;
        
        $base_path = config::getConfigParam(array("application", "base_path"))->value;
        $reporting_keywords_fullpath = $base_path.'/'.config::getBotplusPath().'/reporting-medicines-'.$key.'.txt';
        
        file_put_contents($reporting_keywords_fullpath, $keywords);        
    }
    
    private function _isKeywordMatched(&$value, $keywords, $search_by_keywords = true)
    {
        if (empty($value) || empty($keywords))
        {
            return false;
        }
        
        foreach ($keywords as $keyword)
        {
            if (empty($keyword))
            {
                continue; 
            }
            $keyword = trim($keyword);
            $pos = strpos($value, $keyword);
            if ($pos !== false)
            {
                if ($search_by_keywords)
                {
                    $is_nokeyword_matched = $this->_isKeywordMatched($value, $this->_nokeywords, false);
                    if ($is_nokeyword_matched)
                    {
                        return false;
                    }                    
                }
                
                if (!$this->_export)
                {
                    // Replace text by {text}
                    $replace = '<span style="color:red; font-weight:bold;">'.substr($value, $pos, strlen($keyword)).'</span>';
                    $value = substr_replace($value, $replace, $pos, strlen($keyword));                    
                }
                return true;
            }
        }
            
        return false;
    }
    
}