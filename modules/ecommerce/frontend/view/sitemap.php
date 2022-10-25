<?php

namespace modules\ecommerce\frontend\view;

// Controllers
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\availability;
use modules\ecommerce\frontend\controller\article;
use modules\ecommerce\backend\controller\botplus;

// Views
use modules\cms\frontend\view\sitemap as CmsSitemap;

/**
 * E-commerce frontend sitemap view
 *
 * @author Dani Gilabert
 * 
 */
class sitemap extends CmsSitemap
{
    
    public function getSitemapByLanguage($website, $lang, $last_control = array())
    {
        $domain = $this->_protocol.$website->domain;
        //$domain = 'https://www.deemm.com'; // Test
        $url_article_property = 'url'.ucfirst($lang);
        $data = array();
        
        // Domain
        /*$url = $domain;
        $data[] = array(
            'type' => '',
            'object' => null,
            'url' => $url,
            'hash' => md5(file_get_contents($url)),
            'priority' => '1.0',
            'freq' => 'daily'
        );*/
        
        // Domain + lang
        $url = $domain.'/'.$lang;
        $data[] = array(
            'type' => '',
            'object' => null,
            'url' => $url,
            'hash' => md5(file_get_contents($url)),
            'priority' => '1.0',
            'freq' => 'daily'
        );
        
        // Init availability class
        $availability = new availability();
        $availability->setDelegation($website->delegation);
        echo 'Getting  list articles for sale...'.PHP_EOL;
        $brands = $availability->getBrands();
        $availability->setBrands($brands);
        $articles_for_sale = $availability->getArticlesForSale();
        
        // Articles
        $articles = array();
        echo 'Getting URLs of articles...'.PHP_EOL;
        if (!empty($articles_for_sale))
        {
            $article_controller = new article();
            $botplus = new botplus();
        
            foreach ($articles_for_sale as $article)
            {
                if (!isset($article->$url_article_property) || empty($article->$url_article_property))
                {
                    continue;
                }
                $url = $domain.'/'.$lang.'/'.lang::trans('url-articles', $lang).'/'.$article->$url_article_property;
                
                // Get canonical
                $canonical = null;
                if (isset($article->canonical) && !empty($article->canonical))
                {
                    $canonical_article_code = $article->canonical;
                    if (isset($articles_for_sale->$canonical_article_code))
                    {
                        $canonical = $articles_for_sale->$canonical_article_code;
                    }
                }
                
                $raw_botplusdata = $botplus->getBotplusDataByCode($article->code);
                $botplusdata = array();
                if (!empty($raw_botplusdata))
                {
                    $botplusdata = array(
                        $raw_botplusdata['epigraphs'],
                        $raw_botplusdata['messages']
                    );
                }
                
                $object = array(
                    $article->images,
                    $article->prices,
                    $article->brandName,
                    
                    $article_controller->getTitle($article, $lang),
                    $article_controller->getDisplay($article, $lang),
                    $article_controller->getShortDescription($article, $lang, $canonical),
                    $article_controller->getDescription($article, $lang, $canonical),
                    $article_controller->getApplication($article, $lang, $canonical),
                    $article_controller->getActiveIngredients($article, $lang, $canonical),
                    $article_controller->getComposition($article, $lang, $canonical),
                    $article_controller->getProspect($article, $lang),
                    $article_controller->getDatasheet($article, $lang),
                    $article_controller->getKeywords($article, $lang),
                    
                    (isset($article->canonical)? $article->canonical : ''),
                    (isset($article->articleCode2GroupDisplays)? $article->articleCode2GroupDisplays : ''),
                    
                    $botplusdata
                );
                $serialized = json_encode($object);
                
//                // Test
//                if (!empty($botplusdata))
//                {
//                    file_put_contents("/tmp/sitemaptest", $serialized, JSON_PRETTY_PRINT);
//                }
                
                $data[] = array(
                    'type' => 'article',
                    'object' => $article,
                    'url' => $url,
                    'hash' =>  md5($serialized),
                    'priority' => '0.8',
                    'freq' => null
                );
                
                $articles[] = $article;
            }
        }
        
        // Brands
        echo 'Getting URLs of brands...'.PHP_EOL;
        $empty_brands = array();
        if (isset($brands) && !empty($brands))
        {
            foreach ($brands as $brand)
            {
                if (
                        !$brand->available || 
                        (isset($brand->visible) && !$brand->visible) || 
                        (isset($brand->empty) && $brand->empty)
                )
                {
                    continue;
                }
                
                $objects = array();
                foreach ($articles as $article)
                {
                    $article_laboratory = $this->_getLaboratory($article->brand, $brands);
                    if ($article->brand !== $brand->code)
                    {
                        // Is a laboratory?
                        if (!$this->_isLaboratory($brand->code, $brands))
                        {
                            continue;
                        }
                        if (empty($article_laboratory) || $brand->code !== $article_laboratory)
                        {
                            continue;
                        }   
                    }
                    
                    $objects[] = array(
                        $article->titles,
                        $article->displays,
                        $article->images,
                        $article->prices,
                        $article->brandName,
                        $article_laboratory
                    );
                }
                
                if (empty($objects))
                {
                    $empty_brands[] = array(
                        'code' => $brand->code,
                        'name' => $brand->name
                    );
                    continue;
                }
                
                $url = $domain.'/'.$lang.'/'.lang::trans('url-brands', $lang).'/'.$brand->code;
                $serialized = json_encode($objects);
                
                $data[] = array(
                    'type' => 'brand',
                    'object' => $brand,
                    'url' => $url,
                    'hash' =>  md5($serialized),
                    'priority' => '0.6',
                    'freq' => null
                );
            }
        }
        
        // Categories
        echo 'Getting URLs of categories...'.PHP_EOL;
        $empty_categories = array();
        $categories = $availability->getCategoriesTree();
        if (isset($categories) && isset($categories->categories))
        {
            $this->getCategoriesData($categories->tree[0]->children, $domain, $articles, $lang, $data, $empty_categories);          
        }
        
        // Buildind xml
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $control = array();
        foreach ($data as $key => $values)
        {
            $type = $values['type'];
            $object = $values['object'];
            $url = $values['url'];
            $hash = $values['hash'];
            $priority = $values['priority'];
            $freq = $values['freq'];
            
            $object_text = '';
            if ($type === 'article')
            {
                $object_text = '('.$object->code.')';
            }
            
            echo 'Building url ('.$key.' of '.count($data).') : '.$url.' '.$object_text.PHP_EOL;
            $date = $this->_getLastmodDate($url, $hash, $last_control);
            
            // Set control
            $control[$url]['hash'] = $hash;
            $control[$url]['date'] = $date;

            // Set xml
            $xml .= 
                    '<url>'.
                        '<loc>'.$url.'</loc>'.
                        $this->_getPriority($priority).
                        $this->_getLastmod($date).
                        $this->_getChangefreq($freq).
                    '</url>';            
        }
        $xml .= '</urlset>';
        
        if (!empty($empty_brands))
        {
            echo PHP_EOL;
            echo 'Atenció!! Les següents marques estan buides o sense articles:'.PHP_EOL;
            foreach ($empty_brands as $brand) {
                echo $brand['code'].' '.$brand['name'].PHP_EOL;
            }
            echo PHP_EOL;
        }
        
        if (!empty($empty_categories))
        {
            echo PHP_EOL;
            echo 'Atenció!! Les següents categories estan buides o sense articles:'.PHP_EOL;
            foreach ($empty_categories as $category) {
                echo $category['code'].' '.$category['name'].PHP_EOL;
            }
            echo PHP_EOL;
        }
        
        return array(
            'xml' => $xml,
            'control' => $control
        );
    }

    public function getCategoriesData($tree, $domain, $articles, $lang, &$data, &$empty_categories)
    {
        if (!isset($tree) || empty($tree))
        {
            return;
        }
        
        $url_property = 'url'.ucfirst($lang);
        
        foreach ($tree as $key => $value)
        {
            $category = $value->_data;
            
            // Is it available?
            if (!$category->available) continue;
            
            // Is it canonical?
            if (isset($category->canonical) && !empty($category->canonical))
            {
                continue;
            }            
            
            if (isset($category->$url_property) &&
                !empty($category->$url_property))
            {
                $url_value = $category->$url_property;
            }
            else
            {
                $url_value = $category->code; 
            }                

            $objects = array();
            foreach ($articles as $article)
            {
                if (!isset($article->categories) || empty($article->categories)) continue;   
                $article_categories = explode('|', $article->categories);
                if (!in_array($category->code, $article_categories)) continue;  

                $objects[] = array(
                    $article->titles,
                    $article->displays,
                    $article->images,
                    $article->prices,
                    $article->brandName
                );
            }

            if (empty($objects))
            {
                $empty_categories[] = array(
                    'code' => $category->code,
                    'name' => $category->name
                );
                continue;
            }

            $url = $domain.'/'.$lang.'/'.lang::trans('url-categories', $lang).'/'.$url_value;
            $serialized = json_encode($objects);

            $data[] = array(
                'type' => 'category',
                'object' => $category,
                'url' => $url,
                'hash' =>  md5($serialized),
                'priority' => '0.7',
                'freq' => null
            );        

            if (isset($value->children) && !empty($value->children))
            {
                $this->getCategoriesData($value->children, $domain, $articles, $lang, $data, $empty_categories);
            }    
        }  
        
    }
    
    protected function _getLastmodDate($url, $hash, $last_control)
    {
        if (isset($last_control[$url]) && $hash == $last_control[$url]['hash'])
        {
            $date = $last_control[$url]['date'];
        }
        else
        {
            $date = date('Y-m-d');
        }
                
        return $date;
    }
    
    private function _isLaboratory($brand, $brands)
    {
        // Is a laboratory?
        return (isset($brands[$brand]) && 
                isset($brands[$brand]->isLaboratory) && 
                $brands[$brand]->isLaboratory);
    }
    
    private function _getLaboratory($brand, $brands)
    {
        // Is the brand assigned to some laboratory?
        if (!isset($brands[$brand]) ||
            !isset($brands[$brand]->laboratory) || 
            empty($brands[$brand]->laboratory)
        )
        {
            return '';
        }
        
        return $brands[$brand]->laboratory;
    }
    
}