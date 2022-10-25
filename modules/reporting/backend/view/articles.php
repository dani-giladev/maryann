<?php   

namespace modules\reporting\backend\view;

// Controllers
use core\botplus\controller\botplus;

// Views
use modules\reporting\backend\view\reporting;

/**
 * Backend Articles view for reporting
 *
 * @author Dani Gilabert
 * 
 */
class articles extends reporting
{
    
    public function getHtmlReport($data)
    {
        $html = '';
        
        // Styles
        $html .= $this->_getStyles();
            
        // Start content
        $html .= '<br>';
        
        /*
         * 
         * RESUM TOTALS
         * 
         */
        $html .= $this->_getTitle('TOTALS');
        
        $html .= 
                'TOTAL articles'.' : '.$data['total_articles'].'<br>'.
                'TOTAL articles en venta'.' : '.$data['total_articles_for_sale'].'<br>'.
                'TOTAL articles en venta i visibles'.' : '.$this->_getText($data['total_articles_for_sale_and_visible'], 'green').'<br>'.
                '';
        
        $html .= '<br>';
        
        $html .= 
                '<u>'.
                    $this->_getText('TOTAL articles en venta, visibles i amb STOCK', 'green').' : '.  $this->_getText($data['total_articles_for_sale_with_stock'], 'green', true).'<br>'.
                '</u>'.
                '';
        
        $html .= '<br>';
        
        $html .= 
                'TOTAL articles en venta sense STOCK'.' : '.$this->_getText($data['total_articles_for_sale_without_stock'], 'red').
                '<ul>'.
                    '<li>'.'Visibles'.' : '.$data['total_articles_for_sale_without_stock_visible'].'</li>'.
                    '<li>'.'No visibles'.' : '.$data['total_articles_for_sale_without_stock_no_visible'].'</li>'.
                '</ul>'.
                '';
        
        $html .= 
                $this->_getText('TOTAL articles NO en venta', 'red').' : '.$this->_getText($data['total_articles_not_for_sale'], 'red', true).'<br>'.
                '';
        $html .= '<br><br>';
        
        /*
         * 
         * ARTICLES EN VENTA I VISIBLES
         * 
         */
        $html .= $this->_getTitle('ARTICLES EN VENTA I VISIBLES');
        $html .= 
                'Amb stock infinit'.' : '.count($data['articles_for_sale_with_stock_infinit']).'<br>'.
                '';
        $html .= '<ul>';
        /*foreach ($data['articles_for_sale_with_stock_infinit'] as $article_code => $article)
        {
            $article_name = $this->_getArticleName($article);
            $html .= '<li>'.$article_code.' - '.$this->_getText($article->brandName, 'black', true).' - '.$article_name.'</li>';
        }*/
        $html .= '</ul>';
        $without_photos = count($data['articles_for_sale_and_visible_without_photos']);
        $without_photos_text = 'Sense fotos'.' : '.$without_photos;
        $without_photos_final_text = ($without_photos > 0)? $this->_getText($without_photos_text, 'red') : $without_photos_text;
        $html .= 
                $without_photos_final_text.
                '<ul>'.
                '';
        foreach ($data['articles_for_sale_and_visible_without_photos'] as $article_code => $article)
        {
            $article_name = $this->_getArticleName($article);
            $html .= '<li>'.$article_code.' - '.$this->_getText($article->brandName, 'black', true).' - '.$article_name.'</li>';
        }
        $html .= '</ul>';
        $with_incoherent_sale_rate = count($data['articles_for_sale_with_incoherent_sale_rate']);
        $with_incoherent_sale_rate_text = 'Amb incoherencies en la Tarifa de preus'.' : '.$with_incoherent_sale_rate;
        $with_incoherent_sale_rate_final_text = ($with_incoherent_sale_rate > 0)? $this->_getText($with_incoherent_sale_rate_text, 'red') : $with_incoherent_sale_rate_text;
        $html .= 
                $with_incoherent_sale_rate_final_text.
                '<ul>'.
                '';
        foreach ($data['articles_for_sale_with_incoherent_sale_rate'] as $article_code => $article)
        {
            $article_name = $this->_getArticleName($article);
            $html .= '<li>'.$article_code.' - '.$this->_getText($article->brandName, 'black', true).' - '.$article_name.'</li>';
        }
        $html .= '</ul>';
        $html .= '<br>';
        
        /*
         * 
         * ARTICLES NO EN VENTA
         * 
         */
        $html .= $this->_getTitle('ARTICLES NO EN VENTA');
        $html .= 
                'Disponibles però pendents de validar'.' : '.count($data['articles_not_for_sale_and_pending_of_validate']).
                '<ul>'.
                '';
        foreach ($data['articles_not_for_sale_and_pending_of_validate'] as $article_code => $article)
        {
            $article_name = $this->_getArticleName($article);
            $html .= '<li>'.$article_code.' - '.$this->_getText($article->brandName, 'black', true).' - '.$article_name.'</li>';
        }
        $html .= '</ul>';
        $html .= 
                $this->_getText('TOTAL articles NO en venta', 'black').' : '.$this->_getText($data['total_articles_not_for_sale'], 'black').'<br>'.
                '';
        if (!empty($data['articles_not_for_sale']))
        {
            $html .= 
                    '<table class="reporting-table" border="0" cellpadding="0" cellspacing="0">'.
                        '<tr>'.
                            '<td class="reporting-column reporting-column-header reporting-column-center">'.'Codi article'.'</td>'.
                            '<td class="reporting-column reporting-column-header">'.'Nom'.'</td>'.
                            '<td class="reporting-column reporting-column-header reporting-column-center">'.'Marca'.'</td>'.
                            '<td class="reporting-column reporting-column-header reporting-column-center">'.'Preu'.'</td>'.
                            '<td class="reporting-column reporting-column-header reporting-column-center">'.'En farmatic?'.'</td>'.
                            '<td class="reporting-column reporting-column-header reporting-column-center">'.'Té foto?'.'</td>'.
                            '<td class="reporting-column reporting-column-header reporting-column-center">'.'Stock?'.'</td>'.
                            '<td class="reporting-column reporting-column-header">'.'Notes'.'</td>'.
                        '</tr>'.
                    '';
            foreach ($data['articles_not_for_sale'] as $article)
            {
                $article_name = $this->_getArticleName($article);
                $notes = $article->notes;
                $known_cause = false;
                
                // Price
                $price = (isset($article->prices))? $article->prices->finalRetailPrice : 0;
                $price_text = $price;
                if ($price == 0)
                {
                    $price_text = $this->_getText($price, 'red', true);
                    if (!empty($notes))
                    {
                        $notes .= '. ';
                    }
                    $notes .= 'No té preu';
                    $known_cause = true;
                }
                
                // In farmatic?
                $in_farmatic = $article->inErp;
                $in_farmatic_text = $in_farmatic? 'Si' : $this->_getText('NO', 'red', true);
                if (!$in_farmatic)
                {
                    $known_cause = true;
                }
                
                // Photo
                $any_foto = false;
                if (isset($article->images) && !empty($article->images))
                {
                    foreach ($article->images as $image)
                    {
                        $filename = $this->_base_path.'/'.$this->_filemanger_path."/".$image->relativePath.'/'.$image->filename;
                        if (file_exists($filename))
                        {
                            $any_foto = true;
                            break;
                        }
                    }
                }
                $any_foto_text = $any_foto? 'Si' : $this->_getText('NO', 'red', true);
                if (!$any_foto)
                {
                    $known_cause = true;
                }
                
                // Bot plus
                $is_authorized_by_botplus = $this->_isAuthorizedByBotplus($article);
                if (!$is_authorized_by_botplus)
                {
                    if (!empty($notes))
                    {
                        $notes .= '. ';
                    }
                    $notes .= 'BOTPLUS: '.$this->_getBotplusStatusName($article);
                    $known_cause = true;
                }
                
                // Any stock?
                $any_stock = $article->anyStock;
                if ($any_stock)
                {
                    $any_stock_text = "Si";
                }
                else
                {
                    if ($known_cause)
                    {
                        $any_stock_text = "NO";
                    }
                    else
                    {
                        $any_stock_text = $this->_getText('NO', 'red', true);
                    }
                    $known_cause = true;
                }
                
                if ($known_cause && $price > 0 && $in_farmatic && $is_authorized_by_botplus && $any_stock && !$any_foto)
                {
                    // Only missing the photo?
                    if (!empty($notes))
                    {
                        $notes .= '. ';
                    }
                    $notes .= $this->_getText('Només falta la foto?', 'red');
                }
                
                // It should be available. Why not?
                if (!$known_cause)
                {
                    if (empty($notes))
                    {
                        $notes = $this->_getText('Per què no està disponible?', 'red');
                    }                    
                }
                
                $html .= 
                        '<tr>'.
                            '<td class="reporting-column reporting-column-center">'.$article->code.'</td>'.
                            '<td class="reporting-column">'.$article_name.'</td>'.
                            '<td class="reporting-column reporting-column-center">'.$article->brandName.'</td>'.
                            '<td class="reporting-column reporting-column-center">'.$price_text.'</td>'.
                            '<td class="reporting-column reporting-column-center">'.$in_farmatic_text.'</td>'.
                            '<td class="reporting-column reporting-column-center">'.$any_foto_text.'</td>'.
                            '<td class="reporting-column reporting-column-center">'.$any_stock_text.'</td>'.
                            '<td class="reporting-column">'.$notes.'</td>'.
                        '</tr>'.
                    '';                
            }
            $html .= '</table>';
        }
        $html .= '<br><br>';
        
        return $html;
    } 
    
    protected function _isAuthorizedByBotplus($article)
    {
        $botplus_controller = new botplus();
        $is_auth = $botplus_controller->isAuthorized($article->code, $article->articleType);
        return $is_auth;
    }
    
    protected function _getBotplusStatusName($article)
    {
        $botplus_controller = new botplus();
        return $botplus_controller->getStatusName($article->code);
    }
    
}