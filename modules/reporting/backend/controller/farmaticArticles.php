<?php

namespace modules\reporting\backend\controller;

// Controllers
use core\ajax\controller\ajax;
use core\botplus\controller\botplus;
use modules\reporting\backend\controller\reporting;
use core\farmatic\controller\farmatic;
use modules\ecommerce\controller\article;
use modules\ecommerce\frontend\controller\availability;

/**
 * Backend Farmatic articles controller for reporting
 *
 * @author Dani Gilabert
 * 
 */
class farmaticArticles extends reporting
{
    
    public function getRecords($params)
    {
        $article_type = $params->articleType;
        $name = $params->name;
        $stock = (integer) $params->stock;
        
        $farmatic_articles = $this->getFarmaticArticles($article_type, $name, $stock);
        if (!$farmatic_articles['success'])
        {
            ajax::sendData(array($farmatic_articles));
            return;              
        }
        
        ajax::sendData($farmatic_articles['data']);
    } 
    
    public function getFarmaticArticles($article_type, $name, $stock)
    {
        $data = array();
        
        /*
         * Firstly, get erp articles
         */
        
        // Check the connectivity
        $farmatic = new farmatic();
        if (!$farmatic->isConnected())
        {
            return array(
                'success' => false,
                'msg'=> 'No se ha podido conectar con la base de datos de Farmatic'
            );
        }            

        $sql = 
                "SELECT".
                " IdArticu, Descripcion, XFam_IdFamilia, Pvp, StockActual".
                " FROM Articu".
                " WHERE";
        
        $any_condition = false;
        if (!empty($article_type))
        {
            if ($article_type == '1')
            {
                //$falimies = array(5, 6, 7, 8 ,9, 10, 11, 12, 13, 14, 15, 16, 18, 19, 20, 24, 25, 29, 30, 31);
                $falimies = array(5, 6, 7, 8 ,10, 11, 12, 13);
            }
            elseif ($article_type == '2')
            {
                $falimies = array(1, 3);
            }
            else
            {
                $falimies = array(18, 19);
            }
            $first_time = true;
            $sql .= " (";
            foreach ($falimies as $family)
            {
                if (!$first_time)
                {
                    $sql .= " OR";
                }
                $first_time = false;
                $sql .= " XFam_IdFamilia=".$family;
            }
            $sql .= " )";
            $any_condition = true;
        }
        if (!empty($name))
        {
            $sql .= $any_condition? " AND" : "";
            $sql .= " Descripcion LIKE '%".$name."%'";
            $any_condition = true;
        }
        
        $sql .= $any_condition? " AND" : "";
        $sql .= " StockActual>".$stock;
        
        $sql .= " ORDER BY Descripcion, StockActual DESC";
        
        $data_query = $farmatic->executeQuery($sql);
        
        $erp_articles = array();
        while(!$data_query->EOF)
        {
            $item = array();
            $item['code'] = $data_query->fields[0];
            $item['name'] = iconv("CP1252", "UTF-8", $data_query->fields[1]);
            $item['efp'] = $data_query->fields[2];
            $item['pvp'] = $data_query->fields[3];
            $item['stock'] = $data_query->fields[4];
            
            if ($item['pvp'] > 0)
            {
                $erp_articles[$item['code']] = $item;
            }
            
            $data_query->movenext();
        }
        
        if (!empty($erp_articles))
        {
            /*
             * Get all articles in database
             */            
            $article_controller = new article();
            $raw_all_articles_list = $article_controller->getArticles();
            $all_articles = array();
            foreach($raw_all_articles_list as $article)
            {
                $all_articles[$article->code] = $article;
            }
        
            /*
             * 
             * Articles for sale
             * 
             */
            $availability = new availability($this->_delegation);        
            $raw_articles_for_sale_list = $availability->getArticlesForSale(array(), true);
            $articles_for_sale = array();
            foreach ($raw_articles_for_sale_list as $article)
            {
                $articles_for_sale[$article->code] = $article;
            }
            
            $botplus_controller = new botplus();
            
            // Building result
            foreach ($erp_articles as $article_code => $erp_article)
            {
                $name_pieces = explode(' ', $erp_article['name']); 
                $brand_name = $name_pieces[0];

                /*if ($article_code === '672905')
                {
                    $test = true;
                }*/               
                
                if ($article_type == '2' && !$botplus_controller->isMSR($article_code))
                {
                    continue;
                }
                
                $art_type = '';
                $art_type_name = '';
                $stock_in_db = $erp_article['stock'];
                if (isset($all_articles[$article_code]))
                {
                    $art_type = $all_articles[$article_code]->articleType;
                    $art_type_name = $all_articles[$article_code]->articleTypeName;
                    //$any_stock = $article_controller->anyStock($article);
                    $any_stock = (isset($all_articles[$article_code]->stock) && $all_articles[$article_code]->stock > 0);
                    $stock_in_db = $all_articles[$article_code]->stock;
                }
                else
                {
                    $any_stock = ($erp_article['stock'] > 0);
                }
                
                // For sale and visible?
                $for_sale = (isset($articles_for_sale[$article_code]));
                if (!$for_sale)
                {
                    $visible = false;
                }
                else
                {
                    if ($any_stock)
                    {
                        $visible = true;
                    }
                    else
                    {
                        $visible = $article_controller->isVisibleIfNoStock($articles_for_sale[$article_code]);
                    }                     
                }   
        
                $data[] = array(
                    
                    // Farmatic prop.
                    'code' => $article_code,
                    'name' => $erp_article['name'],
                    'efp' => ($erp_article['efp'] == 1),
                    'stock' => $erp_article['stock'],
                    'pvp' => $erp_article['pvp'],
                    
                    // Db properties
                    'brandName' => $brand_name,
                    'inDb' => (isset($all_articles[$article_code])),
                    'anyStock' => $any_stock,
                    'stockInDb' => $stock_in_db,
                    'forSale' => $for_sale,
                    'forSaleAndVisible' => $visible,
                    'articleType' => $art_type,
                    'articleTypeName' => $art_type_name
                );
            }
        }
        
        return array(
            'success' => true,
            'msg'=> '',
            'data' => $data
        );
    }
    
    public function exportRecords($params)
    {
        $article_type = $params->articleType;
        $name = $params->name;
        $stock = (integer) $params->stock; 
        
        $farmatic_articles = $this->getFarmaticArticles($article_type, $name, $stock);
        
        $data_file = '';
        
        if (!$farmatic_articles['success'])
        {
            $data_file = $farmatic_articles['msg'];
        }
        else
        {
            if (!empty($farmatic_articles['data']))
            {
                // Header
                $article = $farmatic_articles['data'][0];
                foreach ($article as $key => $value)
                {
                    $data_file .= "\"".$key."\";";
                }                
                $data_file .= PHP_EOL;
                
                // Content
                foreach ($farmatic_articles['data'] as $article)
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
    
}