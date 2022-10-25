<?php   

namespace modules\reporting\backend\view;

// Views
use modules\reporting\backend\view\reporting;

/**
 * Backend Articles view for reporting
 *
 * @author Dani Gilabert
 * 
 */
class seoOnPage extends reporting
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
         * ARTICLES
         * 
         */
        $html .= $this->_getTitle('ARTICLES');
        
        $articles_with_repeated_urls = count($data['articles_with_repeated_urls']);
        $articles_with_repeated_urls_text = 
                'Articles amb URLs repetides'.' : '.$articles_with_repeated_urls;
        $articles_with_repeated_urls_final_text = ($articles_with_repeated_urls > 0)? $this->_getText($articles_with_repeated_urls_text, 'red') : $articles_with_repeated_urls_text;
        $html .= 
                $articles_with_repeated_urls_final_text.
                '<ul>'.
                '';
        foreach ($data['articles_with_repeated_urls'] as $values)
        {
            $article1 = $values['article1'];
            $article_name1 = $this->_getArticleName($article1);
            $article2 = $values['article2'];
            $article_name2 = $this->_getArticleName($article2);
            $html .= 
                    '<li>'.
                        $article2->code.' - '.
                        $article2->brandName.' - '.
                        $article_name2.' - '.
                        $this->_getText($values['lang_code'], 'red', true).' - '.
                        $this->_getText($values['url'], 'red').' ('.
                        "Igual que $article1->code)".
                    '</li>';
        }
        $html .= '</ul>';
        
        $html .= '<br>';
        
        /*
         * 
         * MARQUES
         * 
         */
        $html .= $this->_getTitle('MARQUES');
        
        $brands_with_repeated_urls = count($data['brands_with_repeated_urls']);
        $brands_with_repeated_urls_text = 
                'Marques amb URLs repetides'.' : '.$brands_with_repeated_urls;
        $brands_with_repeated_urls_final_text = ($brands_with_repeated_urls > 0)? $this->_getText($brands_with_repeated_urls_text, 'red') : $brands_with_repeated_urls_text;
        $html .= 
                $brands_with_repeated_urls_final_text.
                '<ul>'.
                '';
        foreach ($data['brands_with_repeated_urls'] as $values)
        {
            $html .= 
                    '<li>'.
                        $values['brand']->code.' - '.
                        $values['brand']->name.' - '.
                        $this->_getText($values['lang_code'], 'red', true).' - '.
                        $this->_getText($values['url'], 'red').
                    '</li>';
        }
        $html .= '</ul>';
        
        $empty_brands_not_marked_as_empty = count($data['empty_brands_not_marked_as_empty']);
        $empty_brands_not_marked_as_empty_text = 
                'Marques sense articles (marques buides) no marcades com a buides'.' : '.$empty_brands_not_marked_as_empty;
        $empty_brands_not_marked_as_empty_final_text = ($empty_brands_not_marked_as_empty > 0)? $this->_getText($empty_brands_not_marked_as_empty_text, 'red') : $empty_brands_not_marked_as_empty_text;
        $html .= 
                $empty_brands_not_marked_as_empty_final_text.
                '<ul>'.
                '';
        foreach ($data['empty_brands_not_marked_as_empty'] as $brand_code => $brand)
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
                        (isset($brand->isLaboratory)? (' (<i>És un LABORATORI</i>)') : '').
                        $notes.
                    '</li>';
        }
        $html .= '</ul>';
        
        $not_empty_brands_marked_as_empty = count($data['not_empty_brands_marked_as_empty']);
        $not_empty_brands_marked_as_empty_text = 
                'Marques amb articles (marques no buides) marcades com a buides'.' : '.$not_empty_brands_marked_as_empty;
        $not_empty_brands_marked_as_empty_final_text = ($not_empty_brands_marked_as_empty > 0)? $this->_getText($not_empty_brands_marked_as_empty_text, 'red') : $not_empty_brands_marked_as_empty_text;
        $html .= 
                $not_empty_brands_marked_as_empty_final_text.
                '<ul>'.
                '';
        foreach ($data['not_empty_brands_marked_as_empty'] as $brand_code => $brand)
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
                        (isset($brand->isLaboratory)? (' (<i>És un LABORATORI</i>)') : '').
                        $notes.
                    '</li>';
        }
        $html .= '</ul>';
        
        $html .= '<br>';
        
        /*
         * 
         * CATEGORIES
         * 
         */
        $html .= $this->_getTitle('CATEGORIES');
        
        $categories_with_repeated_urls = count($data['categories_with_repeated_urls']);
        $categories_with_repeated_urls_text = 
                'Categories amb URLs repetides'.' : '.$categories_with_repeated_urls;
        $categories_with_repeated_urls_final_text = ($categories_with_repeated_urls > 0)? $this->_getText($categories_with_repeated_urls_text, 'red') : $categories_with_repeated_urls_text;
        $html .= 
                $categories_with_repeated_urls_final_text.
                '<ul>'.
                '';
        foreach ($data['categories_with_repeated_urls'] as $values)
        {
            $html .= 
                    '<li>'.
                        $values['category']->code.' - '.
                        $values['category']->name.' - '.
                        $this->_getText($values['lang_code'], 'red', true).' - '.
                        $this->_getText($values['url'], 'red').' - '.
                        'Repetida amb'. ': '.$this->_getText($values['repeated_category']->code, 'red', true).', '.$values['repeated_category']->name.
                    '</li>';
        }
        $html .= '</ul>';
        
        $empty_categories_not_marked_as_empty = count($data['empty_categories_not_marked_as_empty']);
        $empty_categories_not_marked_as_empty_text = 
                'Categories sense articles (categories buides) no marcades com a buides'.' : '.$empty_categories_not_marked_as_empty;
        $empty_categories_not_marked_as_empty_final_text = ($empty_categories_not_marked_as_empty > 0)? $this->_getText($empty_categories_not_marked_as_empty_text, 'red') : $empty_categories_not_marked_as_empty_text;
        $html .= 
                $empty_categories_not_marked_as_empty_final_text.
                '<ul>'.
                '';
        foreach ($data['empty_categories_not_marked_as_empty'] as $category_code => $category)
        {
            $html .= 
                    '<li>'.
                        $category_code.' - '.
                        $category->name.
                        ' ('.$category->breadcrumb.')'.
                    '</li>';
        }
        $html .= '</ul>';
        
        $not_empty_categories_marked_as_empty = count($data['not_empty_categories_marked_as_empty']);
        $not_empty_categories_marked_as_empty_text = 
                'Categories amb articles (categories no buides) marcades com a buides'.' : '.$not_empty_categories_marked_as_empty;
        $not_empty_categories_marked_as_empty_final_text = ($not_empty_categories_marked_as_empty > 0)? $this->_getText($not_empty_categories_marked_as_empty_text, 'red') : $not_empty_categories_marked_as_empty_text;
        $html .= 
                $not_empty_categories_marked_as_empty_final_text.
                '<ul>'.
                '';
        foreach ($data['not_empty_categories_marked_as_empty'] as $category_code => $category)
        {
            $html .= 
                    '<li>'.
                        $category_code.' - '.
                        $category->name.
                        ' ('.$category->breadcrumb.')'.
                    '</li>';
        }
        $html .= '</ul>';
        
        $not_available_categories = count($data['not_available_categories']);
        $not_available_categories_text = 
                'Categories no disponibles'.' : '.$not_available_categories;
        $not_available_categories_final_text = ($not_available_categories > 0)? $this->_getText($not_available_categories_text, 'red') : $not_available_categories_text;
        $html .= 
                $not_available_categories_final_text.
                '<ul>'.
                '';
        foreach ($data['not_available_categories'] as $category_code => $category)
        {
            $html .= 
                    '<li>'.
                        $category_code.' - '.
                        $category->name.
                        ' ('.$category->breadcrumb.')'.
                    '</li>';
        }
        $html .= '</ul>';
        
        $html .= '<br>';
        
        return $html;
    } 
    
}