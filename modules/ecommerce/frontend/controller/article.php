<?php

namespace modules\ecommerce\frontend\controller;

// Controllers
use core\config\controller\config;
use core\helpers\controller\helpers;
//use core\url\controller\url;
use modules\ecommerce\frontend\controller\ecommerce;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\controller\article as ecommerceArticle;

// Models
use modules\ecommerce\model\articleReview as articleReviewModel;

// Views
use modules\ecommerce\view\couch\articleReviewsByArticlecode;

/**
 * Article controller
 *
 * @author Dani Gilabert
 * 
 */
class article extends ecommerceArticle
{

    public function getTitle($article, $forced_lang = null)
    {
        $title = $this->_getText($article, 'titles', $forced_lang);
        return (empty($title))? '?' : $title;
    }

    public function getDisplay($article, $forced_lang = null)
    {
        return $this->_getText($article, 'displays', $forced_lang);
    }

    public function getMetaDescription($article, $forced_lang = null, $canonical = null)
    {
        return $this->_getText($article, 'metaDescriptions', $forced_lang, true, $canonical);
    }

    public function getShortDescription($article, $forced_lang = null, $canonical = null)
    {
        return $this->_getText($article, 'shortDescriptions', $forced_lang, true, $canonical);
    }

    public function getDescription($article, $forced_lang = null, $canonical = null)
    {
        return $this->_getText($article, 'descriptions', $forced_lang, true, $canonical);
    }

    public function getApplication($article, $forced_lang = null, $canonical = null)
    {
        return $this->_getText($article, 'applications', $forced_lang, true, $canonical);
    }

    public function getActiveIngredients($article, $forced_lang = null, $canonical = null)
    {
        return $this->_getText($article, 'activeIngredients', $forced_lang, true, $canonical);
    }

    public function getComposition($article, $forced_lang = null, $canonical = null)
    {
        return $this->_getText($article, 'compositions', $forced_lang, true, $canonical);
    }

    public function getProspect($article, $forced_lang = null)
    {
        $ret = $this->_getText($article, 'prospects', $forced_lang);
        if (!empty($ret))
        {
            if (!filter_var($ret, FILTER_VALIDATE_URL)) { 
                $ret = "/".config::getFilemanagerPath()."/".$ret;
            }            
        }
        return $ret;
    }
    
    public function getDatasheet($article, $forced_lang = null)
    {
        $ret = $this->_getText($article, 'dataSheets', $forced_lang);
        if (!empty($ret))
        {
            if (!filter_var($ret, FILTER_VALIDATE_URL)) { 
                $ret = "/".config::getFilemanagerPath()."/".$ret;
            }            
        }
        return $ret;
    }
    
    public function getKeywords($article, $forced_lang = null)
    {
        return $this->_getText($article, 'keywords', $forced_lang);
    }

    private function _getText(
            $article, 
            $property, 
            $forced_lang = null, 
            $use_default_lang_if_not_matched = true, 
            $canonical = null)
    {
        $lang = (is_null($forced_lang))? (lang::getCurrentLanguage()) : $forced_lang;
        
        if(!isset($article->$property) ||
            !isset($article->$property->$lang) ||
            empty($article->$property->$lang)) 
        {
            if (!is_null($canonical))
            {
                return $this->_getText($canonical, $property, $forced_lang, $use_default_lang_if_not_matched, null);
            }
            if ($use_default_lang_if_not_matched)
            {
                $default_language = config::getConfigParam(array("application", "default_language"))->value;
                return $this->_getText($article, $property, $default_language, false);
            }
            return '';
        }
        
        return $article->$property->$lang;
    }

    public function getReviews($article)
    {
        $ret = array();
        
        $review_model = new articleReviewModel();
        $view = new articleReviewsByArticlecode($review_model);
        $params = array(
            "key" => array($article->code)
        );           
        
        $list = $view->getDataView($params, false);
        if (!isset($list))
        {
            return $ret;
        }
        foreach ($list->rows as $row) {
            $ret[] = (array) $row->value;
        }
        
        $ret = helpers::sortArrayByField($ret, 'date', SORT_DESC);
        $ret = helpers::objectize($ret);
        return $ret;
    }
    
    public function getRatingAverageReviews($reviews)
    {
        $total_reviews = count($reviews);
        
        $total_rating = 0;
        foreach ($reviews as $review) {
            $total_rating += $review->rating;
        }
        
        $ret = $total_rating / $total_reviews;
        return $ret;
    }
    
    public function getArticleUrl($article, $forced_lang = null)
    {
        $lang = (is_null($forced_lang))? (lang::getCurrentLanguage()) : $forced_lang;
        $url_property = 'url'.ucfirst($lang);
        $article_url = $article->$url_property;
        
        $ecommerce_controller = new ecommerce();
        $url = $ecommerce_controller->getUrl(array($lang, lang::trans('url-articles', $lang), $article_url));
        
        return $url;
    }
    
    public function getImages($article, $hq = true, $forced_lang = null)
    {
        $ret = array();
        
        if (!isset($article->images) || empty($article->images))
        {
            return $ret;
        }
        
        $public_path = config::getPublicPath();
        
        $base_path = config::getConfigParam(array("application", "base_path"))->value;
        $images_path = $base_path.'/'.$public_path.'/articles/img';
        $burned_img_path = $images_path.'/burned';
        
        //$base_url = url::getProtocol().url::getServerName();
        $base_url = config::getConfigParam(array("application", "url"))->value;
        $images_path_url = $base_url.'/'.$public_path.'/articles/img';
        $burned_img_path_url = $images_path_url.'/burned';        
                
        if (!$article->validated)
        {
            foreach ($article->images as $image) {
                $ret[] = $burned_img_path_url.'/'.$image->filename;
            }
            return $ret;
        }
        
        $lang = (is_null($forced_lang))? (lang::getCurrentLanguage()) : $forced_lang;
        $urlLang = 'url'.ucfirst($lang);
        if (!isset($article->$urlLang) || empty($article->$urlLang))
        {
            return $ret;
        }
            
        $url = $article->$urlLang;
        $counter = 0;
        foreach ($article->images as $img)
        {
            $img = (object) $img;
            $filename_pieces = explode('.', $img->filename);
            $filename = $filename_pieces[0];
            $extension = $filename_pieces[1];
            
            $counter++;
            if ($hq)
            {
                $path = "HQ/".$url.'-'.($counter).".".$extension;
            }
            else
            {
                $path = "LQ/".$url.".".$extension;
            }
            
            /*$link_file = $images_path."/".$path;
            if (!file_exists($link_file))
            {
                continue;
            }*/
            
            $ret[] = $images_path_url."/".$path;
            
            if (!$hq) break;
        }
        
        return $ret;
    }
    
}