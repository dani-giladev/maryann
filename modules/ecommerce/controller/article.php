<?php

namespace modules\ecommerce\controller;

// Controllers
use core\config\controller\config;
use core\helpers\controller\helpers;
use core\helpers\controller\image as imagehelper;
use modules\admin\controller\language as adminLang;
use modules\ecommerce\controller\ecommerce;
use modules\ecommerce\frontend\controller\session as frontendSession;
//use modules\ecommerce\frontend\controller\mailing\mail;

// Models
use modules\ecommerce\model\article as articleModel;

// Views
use modules\ecommerce\view\couch\articlesByUrl;
use modules\ecommerce\view\couch\outstandingArticlesByCategories;
use modules\ecommerce\view\couch\outstandingArticles;
use modules\ecommerce\view\couch\noveltyArticles;
use modules\ecommerce\view\couch\articlesByBrand;
use modules\ecommerce\view\couch\articlesByArticleType;

/**
 * E-commerce article controller
 *
 * @author Dani Gilabert
 * 
 */
class article extends ecommerce
{
    public function getArticleModel($id = null)
    {
        return new articleModel($id);
    }
    
    protected function _getRawData($public = false, $stale = 'update_after')
    {
        $model = $this->getArticleModel();
        $model_type = $model->type;

        if ($model->isPublicationEnabled() && $model->getPublicationMode() === 'OTHER_DOCUMENT')
        {
            $name = ($public) ? 'public-'.$model_type : $model_type;
        }
        else
        {
            $name = $model_type;
        }
        $type = $name;
        
        // Get data
        $ret = $model->getDataView($name, $type, $stale);
        return $ret;
    }
    
    public function getArticles($public = false, $stale = 'update_after')
    {
        $ret = null;
        $list = $this->_getRawData($public, $stale);
        
        if (isset($list))
        {
            $model = $this->getArticleModel();
            $is_publication_enabled = $model->isPublicationEnabled();   
            $publication_mode = $model->getPublicationMode();  
            $arr = array();
            foreach($list->rows as $row_key => $row_values)
            {
                $object = $row_values->value;
                if ($public && $is_publication_enabled && $publication_mode === 'SAME_DOCUMENT')
                {
                    $object = $object->public;
                    if(!isset($object))
                    {
                        continue;
                    }
                }
                $arr[] = $object;
            }   
            $ret = helpers::objectize($arr);
        }
        
        return $ret;
    }
    
    public function getArticleByCode($code, $public = false)
    {
        return $this->_getArticle($code, true, $public);
    }
    
    public function getArticleById($id, $public = false)
    {
        return $this->_getArticle($id, false, $public);
    }
    
    private function _getArticle($key, $key_is_code = true, $public = false)
    {
        $ret = null;
        $model = $this->getArticleModel();
        if ($key_is_code)
        {
            $model_type = $model->type;
            $id = $model_type.'-'.strtolower($key);            
        }
        else
        {
            $id = strtolower($key);
        }

        if ($public && $model->isPublicationEnabled() && $model->getPublicationMode() === 'OTHER_DOCUMENT')
        {
            $id = 'public-'.$id;
        }
        $storage = $model->loadData($id);     
        if (is_null($storage))
        {
            return $ret;
        }
        
        if ($public && $model->isPublicationEnabled() && $model->getPublicationMode() === 'SAME_DOCUMENT')
        {
            $ret = $model->public;
            if (!isset($ret)) return $ret;
        }
        else
        {
            $ret = $model;
        }
        
        return $ret;
    }
    
    public function getPrices($code, $public = false)
    {
        $article = $this->getArticleByCode($code, $public);
        if(!isset($article)) return null;
        
        $ret = $this->getPricesByArticle($article);
        
        return $ret;
    }
    
    public function getPricesByArticle($article)
    {
        if(!isset($article)) return null;
        
        $sale_rate = (isset($article->saleRate))? $article->saleRate : '';
        $sale_rate_name = (isset($article->saleRateName))? $article->saleRateName : '';
        $cost_price = (isset($article->costPrice))? $article->costPrice : 0;
        $margin = (isset($article->margin))? $article->margin : 0;
        $use_margin = (isset($article->useMargin))? $article->useMargin : 'article';
        $base_price_for_cost_price_0 = (isset($article->basePriceForCostPrice0))? $article->basePriceForCostPrice0 : 0;
        $included_vat = (isset($article->includedVat))? $article->includedVat : false;
        $recommended_retail_price = (isset($article->recommendedRetailPrice))? $article->recommendedRetailPrice : 0;
        $discount = (isset($article->discount))? $article->discount : 0;
        $use_discount = (isset($article->useDiscount))? $article->useDiscount : 'article';
        $final_retail_price = (isset($article->finalRetailPrice))? $article->finalRetailPrice : 0;
        
        $ret = array();
        $ret['saleRate'] = $sale_rate;
        $ret['saleRateName'] = $sale_rate_name;
        $ret['costPrice'] = (float) $cost_price;
        $ret['margin'] = (float) $margin;
        $ret['useMargin'] = $use_margin;
        $ret['basePriceForCostPrice0'] = (float) $base_price_for_cost_price_0;
        $ret['includedVat'] = $included_vat;
        $ret['recommendedRetailPrice'] = (float) $recommended_retail_price;
        $ret['discount'] = (float) $discount;
        $ret['useDiscount'] = $use_discount;
        $ret['finalRetailPrice'] = (float) $final_retail_price;
        
        return helpers::objectize($ret);
    }  
    
    public function getArticleByUrl($lang, $url, $public = true, $stale = true)
    {
        $ret = array();
        $model = $this->getArticleModel();
        
        $prefix = $this->module_id;
        if ($model->isPublicationEnabled() && $model->getPublicationMode() === 'OTHER_DOCUMENT')
        {
            $prefix = ($public) ? ('public-'.$prefix) : $prefix;
        }
        
        $view = new articlesByUrl($model, $prefix, $lang);
        $params = array(
            "key" => array($url)
        );
        $object = $view->getDataView($params, $stale);
        
        if (!empty($object->rows))
        {
            if ($public && $model->isPublicationEnabled() && $model->getPublicationMode() === 'SAME_DOCUMENT')
            {
                if (isset($object->rows[0]->value->public))
                {
                    $ret = $object->rows[0]->value->public;
                }
            }
            else
            {
                $ret = $object->rows[0]->value;
            }
        }   
        
        return $ret;
    } 
    
    public function updateMainView()
    {
        // Refresh the data view
        $model = $this->getArticleModel();
        $model_type = $model->type;
        $update = $model->updateDataView($model_type, $model_type);
        return $update;
    }
    
    public function updateViews($update_main_view = true)
    {
        $model = $this->getArticleModel();
        
        if ($update_main_view)
        {
            $update = $this->updateMainView();
            if (!$update)
            {
                return;
            }
        }
        
        // Get admin available languages so as to update the 'articlesByUrl' views
        $lang_controller = new adminLang();
        $available_languages = $lang_controller->getLanguages(true);
        foreach ($available_languages as $lang_values)
        {
            $articles_by_url_1 = $this->getArticleByUrl($lang_values->code, "", false, false);
            if ($model->isPublicationEnabled() && $model->getPublicationMode() === 'OTHER_DOCUMENT')
            {
                $articles_by_url_2 = $this->getArticleByUrl($lang_values->code, "", true, false);
            }            
        }  
                
        // Update the 'outstandingArticlesByCategories' view
        $outstanding_articles_by_categories = $this->getOutstandingArticlesByCategories("", true, false);
        
        // Update the 'outstandingArticles' view
        $outstanding_articles = $this->getOutstandingArticles(true, false);
        
        // Update the 'noveltyArticles' view
        $novelty_articles = $this->getNoveltyArticles(true, false);
    }
    
    public function resetFrontendVars($params = null)
    {
        // Reset frontend session vars
        $session_controller = new frontendSession();
        $session_controller->resetArticles();
        
        if (!is_null($params) && $params->remote)
        {
            echo "<br><br><i>Reset frontendvars has been executed successfully!!</i><br><br>";
        }
    }
    
    public function resetArticlesForSaleFrontendVars()
    {
        $session_controller = new frontendSession();
        $session_controller->resetArticlesForSale();
    }
    
    public function hasNationalCodeFormat($code)
    {
        $dot_pos = strpos($code, '.');
        if ($dot_pos === false)
        {
            return false;
        }
        
        return true;
    }
    
    protected function _checkNationalCode($code)
    {
        if (!is_numeric($code))
        {
            return false;
        }
        
        if (strlen($code) !== 8)
        {
            return false;
        }
        
        $dot_pos = strpos($code, '.');
        if ($dot_pos === false)
        {
            return false;
        }
        
        $article_code = substr($code, 0, $dot_pos);
        if (strlen($article_code) !== 6)
        {
            return false;
        }
        
        $check_code = substr($code, $dot_pos + 1);
        if (strlen($check_code) !== 1)
        {
            return false;
        }
        
        // Calculate national code
        $calculated_check_code = $this->_calculateCheckCode($article_code);
        
        // Happy end?
        if ($check_code != $calculated_check_code)
        {
            return false;
        }
        
        return true;
    }
    
    protected function _calculateCheckCode($article_code)
    {
        // Calculate national code
        // http://blogsigre.es/2013/08/01/simbolos-en-los-envases-de-medicamentos-sabes-que-significan-i/
        // ej. El código nacional 713479.4 
        
        // a) Multiplicar por tres cada uno de los dígitos pares: (1×3) + (4×3) + (9×3)= 42
        $a = ($article_code[1] * 3) + ($article_code[3] * 3) + ($article_code[5] * 3);
        
        // b) Sumar los tres dígitos impares: 7+3+7= 17
        $b = $article_code[0] + $article_code[2] + $article_code[4];
        
        // c) Sumar los resultados obtenidos en a) y b): 42+17= 59
        $c = $a + $b;
        
        // d) Sumar 27 al resultado obtenido en c): 59 +27= 86
        $d = $c + 27;
        
        // e) Calcular la diferencia hasta la siguiente decena: en este caso serían los 4 números que restan para llegar a 90.
        $next_ten = (int) ceil($d/10) * 10;
        $ret = (int) $next_ten - $d;
        
        return $ret;
    }

    public function updateStockFromFarmatic($data) 
    {
        $text = '';
        
        if (!isset($data->data) || empty($data->data))
        {
            $text = 'No hay datos que procesar';
            echo 'Success:false; Text:'.$text;
            return;
        }
        
        $data_pieces = explode(';', $data->data);
        if (empty($data_pieces))
        {
            $text = 'No hay datos que procesar';
            echo 'Success:false; Text:'.$text;
            return;
        }
        
        $update = false;
        
        foreach ($data_pieces as $data_pieces) 
        {
            $article_data_pieces = explode(':', $data_pieces);  
            $code = $article_data_pieces[0];
            $new_stock = (int) $article_data_pieces[1];
              
            $model = $this->getArticleModel();
            $type = $model->type;
            $id = $type.'-'.$code;
            $storage = $model->loadData($id);
            if (is_null($storage))
            {
                // Perhaps, it has national code
                $calculated_check_code = $this->_calculateCheckCode($code);
                $national_code = $code.'.'.$calculated_check_code;
                $model = $this->getArticleModel();
                $id = $type.'-'.$national_code;
                $storage = $model->loadData($id);
                if (is_null($storage))
                {         
                    if (!empty($text))
                    {
                        $text .= ', '; 
                    }
                    $text .= $code.' no existe en WEBSERVER';
                    continue;                    
                }       
            }
            
            $sync_stock = $model->syncStock;
            if (isset($sync_stock) && $sync_stock)
            {
                $current_stock = $model->stock;
                $model->stock = $new_stock;
                $model->save(true);
                $model->publish(true);
                $update = true;
            }            
        }  
        
        if ($update)
        {
    //        // Sending email...
    //        $mail = new mail();
    //        $subject = 'Deemm - updating stock from farmatic';
    //        $body = 
    //            'Text: '.$text.'</br>'.
    //            'Data: '.$data->data.
    //            '';
    //        $to = $mail->getMailAddresses(true);
    //        $mail->send($subject, $body, $to);    

            // Refresh article views
            $this->updateViews();

            // Reset frontend vars
            $this->resetFrontendVars();            
        }
        
        echo 
            'Success:true;'.
            ((!empty($text))? ' Text:'.$text : '');
        
    }
    
    public function getOutstandingArticlesByCategories($categories, $public = true, $stale = true, $outstanding = true)
    {
        $model = $this->getArticleModel();
        
        $prefix = $this->module_id;
        if ($model->isPublicationEnabled() && $model->getPublicationMode() === 'OTHER_DOCUMENT')
        {
            $prefix = ($public) ? ('public-'.$prefix) : $prefix;
        }
        
        $view = new outstandingArticlesByCategories($model, $prefix);
        $params = array(
            "startkey" => array($outstanding, $categories),
            "endkey" => array($outstanding, $categories.'ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ')
//            "limit" => 5
        );

        $object = $view->getDataView($params, $stale);        
        $ret = $this->_getRows($object, $public, $model->isPublicationEnabled(), $model->getPublicationMode());
        return $ret;
    }
    
    public function getOutstandingArticles($public = true, $stale = true, $outstanding = true)
    {
        $model = $this->getArticleModel();
        
        $prefix = $this->module_id;
        if ($model->isPublicationEnabled() && $model->getPublicationMode() === 'OTHER_DOCUMENT')
        {
            $prefix = ($public) ? ('public-'.$prefix) : $prefix;
        }
        
        $view = new outstandingArticles($model, $prefix);
        $params = array(
            "startkey" => array($outstanding),
            "endkey" => array($outstanding)
//            "limit" => 5
        );

        $object = $view->getDataView($params, $stale);  
        $ret = $this->_getRows($object, $public, $model->isPublicationEnabled(), $model->getPublicationMode());
        return $ret;
    }
    
    public function getNoveltyArticles($public = true, $stale = true, $novelty = true)
    {
        $model = $this->getArticleModel();
        
        $prefix = $this->module_id;
        if ($model->isPublicationEnabled() && $model->getPublicationMode() === 'OTHER_DOCUMENT')
        {
            $prefix = ($public) ? ('public-'.$prefix) : $prefix;
        }
        
        $view = new noveltyArticles($model, $prefix);
        $params = array(
            "startkey" => array($novelty),
            "endkey" => array($novelty)
//            "limit" => 5
        );

        $object = $view->getDataView($params, $stale);  
        $ret = $this->_getRows($object, $public, $model->isPublicationEnabled(), $model->getPublicationMode());
        return $ret;
    }
    
    public function getArticlesByBrand($brand, $public = true, $stale = true)
    {
        $model = $this->getArticleModel();
        
        $prefix = $this->module_id;
        if ($model->isPublicationEnabled() && $model->getPublicationMode() === 'OTHER_DOCUMENT')
        {
            $prefix = ($public) ? ('public-'.$prefix) : $prefix;
        }
        
        $view = new articlesByBrand($model, $prefix);
        $params = array(
            "key" => array($brand)
        );

        $object = $view->getDataView($params, $stale);  
        $ret = $this->_getRows($object, $public, $model->isPublicationEnabled(), $model->getPublicationMode());
        return $ret;
    }
    
    public function getArticlesByArticleType($type, $public = true, $stale = true)
    {
        $model = $this->getArticleModel();
        
        $prefix = $this->module_id;
        if ($model->isPublicationEnabled() && $model->getPublicationMode() === 'OTHER_DOCUMENT')
        {
            $prefix = ($public) ? ('public-'.$prefix) : $prefix;
        }
        
        $view = new articlesByArticleType($model, $prefix);
        $params = array(
            "key" => array($type)
        );

        $object = $view->getDataView($params, $stale);  
        $ret = $this->_getRows($object, $public, $model->isPublicationEnabled(), $model->getPublicationMode());
        return $ret;
    }
    
    public function getUrl($brand, $gamma, $title, $display)
    {
        $url =  $this->getComposedTitle($brand, $gamma, $title, $display);
        $ret = helpers::slugify($url);
        return $ret;
    }
    
    public function publishImages($article)
    {
        if (!isset($article))
        {
            return 'Article inexistent';
        }
        
        $base_path = config::getConfigParam(array("application", "base_path"))->value;
        $public_path = config::getPublicPath();
        $burned_img_path = $base_path.'/'.$public_path.'/articles/img/burned';
        $watermark_path = $base_path.'/'.config::getProjectPath().'/img/watermarks/watermark.png';
        $filemanager_path = config::getFilemanagerPath();
        
        $images = $article->images;
        if (!isset($images) || empty($images))
        {
            return true;
        }

        $first_it_has_to_be_thumb = true;
        foreach ($article->images as $img)
        {
            $img = (object) $img;
            $src_path = $base_path.'/'.$filemanager_path."/";
            if (!empty($img->relativePath))
            {
                $src_path .= $img->relativePath."/";
            }
            $src_path .= $img->filename;
            $imgprop = imagehelper::getImageProperties($src_path);
            if (!isset($imgprop))
            {
                continue;
            }

            if ($first_it_has_to_be_thumb)
            {
                // Create LQ image (showcase) or copy
                $dst_path = $burned_img_path.'/'.$imgprop->filename.'-thumb.'.$imgprop->extension;
                if ($imgprop->bytes <= (100*1024))
                {
                    $ret = copy($src_path, $dst_path);
                }
                else
                {
                    $new_width = 250;
                    $new_height = 250;
                    $ret = imagehelper::createImage($src_path, $dst_path, $new_width, $new_height, 100, $imgprop->type);
                }
                if ($ret === false)
                {
                    return 'Error creating image (LQ): '.$dst_path;
                }
                
                // Add watermark
                if (strtolower($imgprop->extension) === 'jpg')
                {
                    $ret = imagehelper::addWatermark($dst_path, $watermark_path, $imgprop->type);
                    if ($ret === false)
                    {
                        return 'Error adding watermark to image: '.$dst_path;
                    }
                }
                
                $first_it_has_to_be_thumb = false;
            }

            // Create HQ image (detail)
            $dst_path = $burned_img_path.'/'.$img->filename;
            if ($imgprop->bytes <= (2000*1024))
            {
                $ret = copy($src_path, $dst_path);
            }
            else
            {
                $new_width = 2000;
                $new_height = 2000;
                $ret = imagehelper::createImage($src_path, $dst_path, $new_width, $new_height, 100, $imgprop->type);
            }
            if ($ret === false)
            {
                return 'Error creating image (HQ): '.$dst_path;
            }
                
            // Add watermark
            if (strtolower($imgprop->extension) === 'jpg')
            {
                $ret = imagehelper::addWatermark($dst_path, $watermark_path, $imgprop->type);
                if ($ret === false)
                {
                    return 'Error adding watermark to image: '.$dst_path;
                }
            }
        }
            
        $command = 'sudo find '.$burned_img_path.'/'.$article->code.'_* -name *.jpg -type f -exec jpegoptim --strip-all {} \;'.' > /dev/null 2>&1';
        $jpeg_ret = system($command, $return_var);
        if ($jpeg_ret === false)
        {
            return 'Error compressing image. Command: '.$command;
        }
        
        return true;
    }
    
    public function removePublishedImages($article)
    {
        if (!isset($article))
        {
            return 'Article inexistent';
        }

        $images = $article->images;
        if (!isset($images) || empty($images))
        {
            return true;
        }
        
        $base_path = config::getConfigParam(array("application", "base_path"))->value;
        $public_path = config::getPublicPath();
        $burned_img_path = $base_path.'/'.$public_path.'/articles/img/burned';
        
        $first_it_has_to_be_thumb = true;
        foreach ($images as $img)
        {
            $img = (object) $img;
            $src_path = $burned_img_path.'/'.$img->filename;
            $imgprop = imagehelper::getImageProperties($src_path);
            if (!isset($imgprop))
            {
                continue;
            }

            if ($first_it_has_to_be_thumb)
            {
                // Remove LQ image (showcase)
                $thumb_src_path = $burned_img_path.'/'.$imgprop->filename.'-thumb.'.$imgprop->extension;
                if (file_exists($thumb_src_path))
                {
                    unlink($thumb_src_path);
                }
                $first_it_has_to_be_thumb = false;
            }
            
            // Remove HQ image (detail)
            unlink($src_path);
        }
        
        return true;
    }
    
    public function getImageFilePaths($article_code)
    {
        $ret = array();
        
        $base_path = config::getConfigParam(array("application", "base_path"))->value;
        $filemanager_path = config::getFilemanagerPath();
        $path = $base_path.'/'.$filemanager_path."/ARTICLES".'/';
        
        $files = array();
        $iti = new \RecursiveDirectoryIterator($path);
        foreach(new \RecursiveIteratorIterator($iti) as $file)
        {
            $filename = $file->getFileName();
            if (strpos($filename, $article_code) !== false)
            {
                $files[] = $file->getPathName();
            }
        }
    
        if (empty($files))
        {
            return $ret;
        }
        
        foreach ($files as $file) {
            $imgprop = imagehelper::getImageProperties($file);

            $filename = $imgprop->basename;
            $filesize = $imgprop->filesize;
            $filedate = $imgprop->filedate;

            $relative_path = str_replace($base_path.'/'.$filemanager_path.'/', '', $imgprop->dirname);
            
            $ret[] = array(
                'filename' => $filename,
                'filesize' => $filesize,
                'filedate' => $filedate,
                'relativePath' => $relative_path,
            );           
            
        }
            
        return $ret;
    }
    
    protected function _getRows($object, $public, $is_publication_enabled, $publication_mode)
    {
        $ret = array();
        
        if (!empty($object->rows))
        {
            foreach ($object->rows as $row)
            {
                if ($public && $is_publication_enabled && $publication_mode === 'SAME_DOCUMENT')
                {
                    if (isset($row->value->public))
                    {
                        $ret[] = $row->value->public;
                    }
                }
                else
                {
                    $ret[] = $row->value;
                }
            }
        }   
        
        return $ret;
    }
    
    public function isInErp($article)
    {
        return (isset($article->erp[0]) && $article->erp[0]->pvp > 0);
    }
    
    public function anyStock($article)
    {
       if (isset($article->infinityStock) && $article->infinityStock)
       {
           return true;
       }
       else
       {
           return (isset($article->stock) && $article->stock > 0);
       }
    }
    
    public function isVisibleIfNoStock($article)
    {
        return (isset($article->visibleIfNoStock) && $article->visibleIfNoStock);
    }
    
    public function getComposedTitle($brand, $gamma, $title, $display)
    {
        $ret = '';
        
        // Add title
        if (empty($title) || $title === '?')
        {
            return $ret;
        }
        $ret .= $title;
        
        // Add display
        if (!empty($display))
        {
            $ret .= ', '.$display;
        }
        
        // Add gamma
        if (!empty($gamma))
        {
            $gamma_pieces = explode(" ", $gamma);
            $matched = false;
            foreach ($gamma_pieces as $gamma_piece) {
                if (strpos(strtolower($ret), strtolower($gamma_piece)) !== false)
                {
                    $matched = true;
                    break;
                }
            }
            if (!$matched)
            {
                $ret = $gamma.' '.$ret;
            }
        }
        
        // Add brand
        if (!empty($brand))
        {
            $brand_pieces = explode(" ", $brand);
            $matched = false;
            foreach ($brand_pieces as $brand_piece) {
                if (strpos(strtolower($ret), strtolower($brand_piece)) !== false)
                {
                    $matched = true;
                    break;
                }
            }
            if (!$matched)
            {
                $ret = $brand.' '.$ret;
            }
        }
        
        return $ret;        
    }
    
    public function createImagesLinks($article)
    {     
        if (!$article->validated)
        {
            return true;
        }
        
        $images = $article->images;
        if (!isset($images) || empty($images))
        {
            return true;
        }
        
        $base_path = config::getConfigParam(array("application", "base_path"))->value;
        $public_path = config::getPublicPath();
        $images_path = $base_path.'/'.$public_path.'/articles/img';
        $burned_img_path = $images_path.'/burned';
        
        $language_controller = new adminLang();
        $available_langs = $language_controller->getLanguages(true);
        
        foreach ($available_langs as $lang)
        {
            $urlLang = 'url'.ucfirst($lang->code);
            $url = $article->$urlLang;
            if (!isset($url) || empty($url))
            {
                continue;
            }
            
            $first_it_has_to_be_thumb = true;
            $hq_counter = 0;
            foreach ($images as $img)
            {
                $img = (object) $img;
                $filename_pieces = explode('.', $img->filename);
                $filename = $filename_pieces[0];
                $extension = $filename_pieces[1];
                
                if ($first_it_has_to_be_thumb)
                {
                    // Low quality (LQ)
                    $burned_image_path = $burned_img_path.'/'.$filename.'-thumb.'.$extension;
                    $rel_burned_image_path = "../burned/".$filename.'-thumb.'.$extension;
                    if (file_exists($burned_image_path))
                    {
                        $link_file = $images_path."/LQ/".$url.".".$extension;
                        if (file_exists($link_file))
                        {
                            // Delete link
                            unlink($link_file);
                        }
                        // Create link
                        //symlink($burned_image_path, $link_file);
                        symlink($rel_burned_image_path, $link_file);
                        $first_it_has_to_be_thumb = false;
                    }                    
                }

                // High quality (HQ)
                $burned_image_path = $burned_img_path.'/'.$filename.'.'.$extension;
                $rel_burned_image_path = "../burned/".$filename.'.'.$extension;
                if (file_exists($burned_image_path))
                {
                    $link_file = $images_path."/HQ/".$url.'-'.($hq_counter+1).".".$extension;
                    if (file_exists($link_file))
                    {
                        // Delete link
                        unlink($link_file);
                    }
                    // Create link
                    //symlink($burned_image_path, $link_file);
                    symlink($rel_burned_image_path, $link_file);
                    $hq_counter++;
                }   
            }
        }

        return true;
    }
    
}