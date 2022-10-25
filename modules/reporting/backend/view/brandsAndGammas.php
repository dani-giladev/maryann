<?php   

namespace modules\reporting\backend\view;

// Views
use modules\reporting\backend\view\reporting;

/**
 * Backend Brands and gammas view for reporting
 *
 * @author Dani Gilabert
 * 
 */
class brandsAndGammas extends reporting
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
                'TOTAL marques'.' : '.$data['total_brands'].'<br>'.
                'TOTAL gammes'.' : '.$data['total_gammas'].'<br>'.
                'TOTAL laboratoris'.' : '.$data['total_labs'].'<br>'.
                '';
        
        $html .= '<br><br>';
        
        /*
         * 
         * MARQUES
         * 
         */
        $html .= $this->_getTitle('MARQUES');
        
        $html .= 
                'TOTAL marques disponibles'.' : '.count($data['available_brands']).
                '<ul>'.
                    '<li>'.'Visibles'.' : '.$data['total_available_brands_visible'].'</li>'.
                    '<li>'.'No visibles'.' : '.$data['total_available_brands_no_visible'].' (Es veuran els LABORATORIS)'.'</li>'.
                '</ul>'.                
                '';
        
        $not_available_brands = count($data['not_available_brands']);
        $not_available_brands_text = 
                'TOTAL marques NO disponibles'.' : '.$not_available_brands;
        $not_available_brands_final_text = ($not_available_brands > 0)? $this->_getText($not_available_brands_text, 'red') : $not_available_brands_text;
        $html .= 
                $not_available_brands_final_text.
                '<ul>'.
                '';
        foreach ($data['not_available_brands'] as $brand_code => $brand)
        {
            $notes = '';
            if (isset($brand->notes) && !empty($brand->notes))
            {
                $notes = ' (Comentaris: <font color="blue"><i>'.$brand->notes.'</i></font>)';
            }
            $html .= 
                    '<li>'.
                        $brand_code.' - '.
                        $brand->name.
                        $notes.
                    '</li>';
        }
        $html .= '</ul>';
        
        $articles_with_inexistent_brands = count($data['articles_with_inexistent_brands']);
        $articles_with_inexistent_brands_text = 
                'Articles amb marques que NO existeixen (INCOHERENCIES)'.' : '.$articles_with_inexistent_brands;
        $articles_with_inexistent_brands_final_text = ($articles_with_inexistent_brands > 0)? $this->_getText($articles_with_inexistent_brands_text, 'red') : $articles_with_inexistent_brands_text;
        $html .= 
                $articles_with_inexistent_brands_final_text.
                '<ul>'.
                '';
        foreach ($data['articles_with_inexistent_brands'] as $article_code => $article)
        {
            $article_name = $this->_getArticleName($article);
            $html .= '<li>'.$article_code.' - '.$this->_getText($article->brand, 'red', true).' - '.$this->_getText($article->brandName, 'red', true).' - '.$article_name.'</li>';
        }
        $html .= '</ul>';
        
        $articles_with_incoherent_type = count($data['articles_with_incoherent_type']);
        $articles_with_incoherent_type_text = 
                'Articles amb incoherencies en el tipus d\'article i la seva marca/laboratori'.' : '.$articles_with_incoherent_type;
        $articles_with_incoherent_type_final_text = ($articles_with_incoherent_type > 0)? $this->_getText($articles_with_incoherent_type_text, 'red') : $articles_with_incoherent_type_text;
        $html .= 
                $articles_with_incoherent_type_final_text.
                '<ul>'.
                '';
        foreach ($data['articles_with_incoherent_type'] as $article_code => $article)
        {
            $article_name = $this->_getArticleName($article);
            $html .= '<li>'.$article_code.' - '.$this->_getText($article->brand, 'red', true).' - '.$this->_getText($article->brandName, 'red', true).' - '.$article_name.'</li>';
        }
        $html .= '</ul>';
        
        $html .= '<br>';
        
        /*
         * 
         * GAMMES
         * 
         */
        $html .= $this->_getTitle('GAMMES');
        
        $html .= 
                'TOTAL gammes disponibles'.' : '.count($data['available_gammas']).'<br>'.'<br>'.
                '';
        
        $not_available_gammas = count($data['not_available_gammas']);
        $not_available_gammas_text = 'TOTAL gammes NO disponibles'.' : '.$not_available_gammas;
        $not_available_gammas_final_text = ($not_available_gammas > 0)? $this->_getText($not_available_gammas_text, 'red') : $not_available_gammas_text;
        $html .= 
                $not_available_gammas_final_text.
                '<ul>'.
                '';
        foreach ($data['not_available_gammas'] as $gamma)
        {
            $article_name = $this->_getArticleName($article);
            $html .= '<li>'.$gamma->code.' - '.$gamma->brand.' - '.$article_name.'</li>';
        }
        $html .= '</ul>';
        
        $html .= '<br>';
        
        /*
         * 
         * LABORATORIS
         * 
         */
        $html .= $this->_getTitle('LABORATORIS');
        
        $html .= 
                'TOTAL laboratoris disponibles'.' : '.count($data['available_labs']).'<br>'.'<br>'.
                '';
        
        $not_available_labs = count($data['not_available_labs']);
        $not_available_labs_text = 'TOTAL laboratoris NO disponibles'.' : '.$not_available_labs;
        $not_available_labs_final_text = ($not_available_labs > 0)? $this->_getText($not_available_labs_text, 'red') : $not_available_labs_text;
        $html .= 
                $not_available_labs_final_text.
                '<ul>'.
                '';
        foreach ($data['not_available_labs'] as $lab_code => $lab)
        {
            $notes = '';
            if (isset($lab->notes) && !empty($lab->notes))
            {
                $notes = ' (Comentaris: <font color="blue"><i>'.$lab->notes.'</i></font>)';
            }
            $html .= 
                    '<li>'.
                        $lab_code.' - '.
                        $lab->name.
                        $notes.
                    '</li>';
        }
        $html .= '</ul>';
        
        $html .= '<br><br>';
        
        return $html;
    } 
    
}