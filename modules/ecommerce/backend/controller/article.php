<?php

namespace modules\ecommerce\backend\controller;

// Controllers
use core\config\controller\config;
use core\helpers\controller\helpers;
use core\ajax\controller\ajax;
use core\farmatic\controller\farmatic;
use core\backend\controller\backend;
use core\backend\controller\lang;
use core\backend\controller\maintenance\type1 as maintenance;
use core\backend\controller\maintenance\data;
use core\botplus\controller\botplus as botplusController;
use modules\admin\controller\language as adminLang;
use modules\ecommerce\frontend\controller\article as ecommerceFrontendArticleController;
use modules\ecommerce\frontend\controller\availability;
use modules\ecommerce\frontend\controller\gamma;
use modules\ecommerce\frontend\controller\lang as ecommerceFrontendLangController;
use modules\seo\controller\url as seoUrl;

// Views
use modules\ecommerce\view\couch\articlesByValidated;

/**
 * Backend E-commerce article controller
 *
 * @author Dani Gilabert
 * 
 */
class article extends backend
{
    protected $_article_controller;
    
    public function __construct()
    {
        parent::__construct();
        $this->module_id = 'ecommerce';
        $this->_article_controller = new ecommerceFrontendArticleController();
    }  
    
    public function getRecords($data)
    {
        $dc = new data($data);
        
        // Get model
        $model = $dc->getModel();
        if ($model === false)
        {
            $msg = "The model is not defined";
            ajax::fuckYou($msg);
            return;                
        }    
        
        $validated = (isset($data->validated))? $data->validated : null;

        // Get data from specific view
        $stale = $dc->getStale();
        $object = $this->getData($model, $stale, $validated);
        
        // Filtering
        $dc->filtering($object);
        
        // Add data
        $dc->addData($object);
        
        // Discard fields
        $dc->discardFields($object);

        $raw_articles = $object;
        
        $ret = array();
        if (isset($raw_articles->rows) && !empty($raw_articles->rows))
        {
            $availability = new availability();
            $articles_for_sale = $availability->getArticlesForSale();
        
            foreach ($raw_articles->rows as $raw_article) 
            {
                $article = $raw_article->value;
                
                // Set dynamic properties
                $this->_setDynamicProperties($article, $articles_for_sale);

                // Remove addittinal data (only for grid and form)
                unset($article->categories);
                unset($article->shortDescriptions);
                unset($article->metaDescriptions);
                unset($article->descriptions);
                unset($article->applications);
                unset($article->activeIngredients);
                unset($article->compositions);
                unset($article->prospects);
                unset($article->dataSheets);
                unset($article->images);
                unset($article->erp);
                
                $article = $dc->getConcreteFields($article);
                
                // Add article
                $ret[] = $article;
            }            
        }    

        ajax::sendData($ret);
    } 

    public function getData($model, $stale, $validated) 
    {
        if (is_null($validated) || $validated == 'all')
        {
            $model_type = $model->type;
            $ret = $model->getDataView($model_type, $model_type, $stale);
        }
        else
        {
            $validated = ($validated == 'true');
            $prefix = $this->module_id;
            $view = new articlesByValidated($model, $prefix);
            $params = array(
                "key" => array($validated)
            );
            $ret = $view->getDataView($params, $stale);  
        }   
        
        return $ret;
    }
    
    public function saveRecord($data)
    {   
        $core_maintenance = new maintenance();
        $dc = $core_maintenance->getDc($data);
        
        if ($dc->getIsNewRecord())
        {
            $code = $data->code;
            if ($this->_article_controller->hasNationalCodeFormat($code) && !$this->_article_controller->_checkNationalCode($code))
            {
                $msg = $this->trans('the_national_code')." '".$code."' ".$this->trans('is_not_valid');
                ajax::fuckYou($msg);
                return;
            }
        }
        else
        {
            $old_article = $this->_article_controller->getArticleById($data->record_id);            
        }

        // Set delegation by default
        $only_one_delegation = config::getConfigParam(array("ecommerce", "only_one_delegation"))->value;
        if (!empty($only_one_delegation))
        {
            $data->delegations = $only_one_delegation;
        }
        
        // Set urls
        $this->_setUrls($data);
        
        // Saving
        $saving_ret = $core_maintenance->saveRecord($data, false);
        if (!$saving_ret['success'])
        {
            ajax::fuckYou($saving_ret['msg']);
            return;
        }
        $article = helpers::objectize($saving_ret['data']);
        
        // Refresh article views
        $this->_article_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_article_controller->resetFrontendVars();
        
        // Add botplus data
        if ($dc->getIsNewRecord())
        {
            $botplus_controller = new botplusController();
            $botplus_controller->getEpigraph($article);
            $botplus_controller->getMessage($article);
            $botplus_controller->update($article);
        }
        
        // Create img links
        $this->_article_controller->createImagesLinks($article);
        
        // Create redirections
        if (!$dc->getIsNewRecord() && $old_article->validated)
        {
            $this->_redirectOldUrls($data, $old_article);
        }
                
        // Set dynamic properties
        $availability = new availability();
        $articles_for_sale = $availability->getArticlesForSale();        
        $this->_setDynamicProperties($article, $articles_for_sale);
        
        $new_record = (array) $article;
        ajax::ohYeah($new_record);
    }
    
    public function saveAdditionalData($data)
    {
        $core_maintenance = new maintenance();
        $dc = $core_maintenance->getDc($data);
        $model = $dc->getModel();
        
        // Remove public images before saving
        if (isset($data->images) && ($dc->getPublish() || !$model->isPublicationEnabled()))
        {
            $article = $this->_article_controller->getArticleById($data->record_id);
            $this->_article_controller->removePublishedImages($article);            
        }
        
        // Saving
        $saving_ret = $core_maintenance->saveRecord($data, false);
        if (!$saving_ret['success'])
        {
            ajax::fuckYou($saving_ret['msg']);
            return;
        }
        $article = helpers::objectize($saving_ret['data']);
        
        // Publish new public images
        if (isset($data->images) && ($dc->getPublish() || !$model->isPublicationEnabled()))
        {
            //$article = $this->_article_controller->getArticleById($data->record_id);
            $this->_article_controller->publishImages($article);
            // Create img links
            $this->_article_controller->createImagesLinks($article);
        }
        
        // Refresh article views
        $this->_article_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_article_controller->resetFrontendVars();
                
        // Set dynamic properties / and fix some of them
        $availability = new availability();
        $articles_for_sale = $availability->getArticlesForSale();        
        $this->_setDynamicProperties($article, $articles_for_sale);
        
        $new_record = (array) $article;
        ajax::ohYeah($new_record);
    }
    
    public function saveProperty($data)
    {
        // Saving
        $core_maintenance = new maintenance();
        $core_maintenance->saveProperty($data);
        
        // Refresh article views
        $this->_article_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_article_controller->resetFrontendVars();
    }
    
    public function publishRecord($data)
    {
        /*
        // Remove public images before saving
        $article = $this->_article_controller->getArticleById($data->record_id);        
        $this->_article_controller->removePublishedImages($article);            
        */
        
        // Publishing
        $core_maintenance = new maintenance();
        $core_maintenance->publishRecord($data);
        
        /*
        // Publish new public images
        $article = $this->_article_controller->getArticleById($data->record_id);
        $this->_article_controller->publishImages($article);
        */
        
        // Refresh article views
        $this->_article_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_article_controller->resetFrontendVars();
    }
    
    public function publishAllRecords($data)
    {
        // Publishing
        $core_maintenance = new maintenance();
        $core_maintenance->publishAllRecords($data);
        
        // Refresh article views
        $this->_article_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_article_controller->resetFrontendVars();
    }
    
    public function deleteRecord($data)
    {
        $article = $this->_article_controller->getArticleById($data->record_id);        
        $this->_article_controller->removePublishedImages($article);
     
        // Deleting
        $core_maintenance = new maintenance();
        $core_maintenance->deleteRecord($data);
        
        // Delete botplus data
        $botplus_controller = new botplusController();
        $botplus_controller->remove($article);
        
        // Refresh article views
        $this->_article_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_article_controller->resetFrontendVars();
    }
    
    public function cloneRecord($data)
    {       
        $code = $data->code;
        if ($this->_article_controller->hasNationalCodeFormat($code) && !$this->_article_controller->_checkNationalCode($code))
        {
            $msg = $this->trans('the_national_code')." '".$code."' ".$this->trans('is_not_valid');
            ajax::fuckYou($msg);
            return;
        }
        
        // Cloning
        $core_maintenance = new maintenance();
        $cloning_ret = $core_maintenance->cloneRecord($data, false);
        if (!$cloning_ret['success'])
        {
            ajax::fuckYou($cloning_ret['msg']);
            return;
        }
        
        $article = helpers::objectize($cloning_ret['data']);
        
        // Clean article
        $model = $this->_article_controller->getArticleModel();
        $type = $model->type;
        $id = $type.'-'.$code;
        $model->loadData($id);
        $model->validated = false;
        $model->erp = array();
        $model->cloned = true;
        $model->save();
        
        // Refresh article views
        $this->_article_controller->updateViews(false);
        
        // Reset frontend session vars
        $this->_article_controller->resetFrontendVars();
        
        $msg = $this->trans('the_article_has_been_cloned_successfully');
        if ($article->validated)
        {
            $msg .= '</br></br><font color="red">'.$this->trans('warning', 'core')."! ".$this->trans('the_cloned_article_is_pending_of_validation').'.</font>';
        }
        ajax::ohYeah(htmlentities($msg));
    }
    
    public function exportRecords($params)
    {
        $dc = new data($params);
        
        // Get model
        $model = $dc->getModel();
        if ($model === false)
        {
            $msg = "The model is not defined";
            ajax::fuckYou($msg);
            return;                
        }    
        $model_type = $model->type;

        // Get data
        $stale = $dc->getStale();
        $object = $model->getDataView($model_type, $model_type, $stale);

        // Set dynamic properties
        $availability = new availability();
        $articles_for_sale = $availability->getArticlesForSale();   
        
        // Get data
        $data = array();
        foreach ($object->rows as $row) {
            $article = $row->value;
            unset($article->_id);
            unset($article->_rev);
            unset($article->id);
            unset($article->_conflicts);
            unset($article->_deleted_conflicts);
            unset($article->public);
            unset($article->type);     
        
            $this->_setDynamicProperties($article, $articles_for_sale);
        
            $data[] = $article;
        }
        
        $core_maintenance = new maintenance();
        
        $data_file = '';
        if (!empty($data))
        {
            $model_properties = $model->getBackendModel();
            $data_file = $core_maintenance->getDataFileToExportRecords($data, $model_properties);
        }
        
        header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
        header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
        header ("Cache-Control: no-cache, must-revalidate");  
        header ("Pragma: no-cache");  
        header ("Content-type: application/vnd.ms-excel");
        header ("Content-Disposition: attachment; filename=\"".$model_type."-list.csv\"" );
        
        echo $data_file;               
    }
    
    public function getArticleDataFromFarmatic($data)
    {
        $arr = array();
        $code = $data->article_code;
        $from_db = (isset($data->from_db) && $data->from_db=='true') ? true : false;

        if ($from_db)
        {
            if ($code !== '')
            {
                $model = $this->_article_controller->getArticleModel();
                $type = $model->type;
                $id = $type.'-'.$code;
                $storage = $model->loadData($id);
                if (!is_null($storage))
                {
                    $arr = $model->erp;
                }                
            }            
        }
        else
        {
            // Article without national code
            if ($this->_article_controller->hasNationalCodeFormat($code))
            {
                $dot_pos = strpos($code, '.');
                $code = substr($code, 0, $dot_pos);
            }            
            
            // Check the connectivity
            $farmatic = new farmatic();
            if (!$farmatic->isConnected())
            {
                $msg = 'No se ha podido conectar con la base de datos de Farmatic';
                ajax::fuckYou($msg);
                return;
            }            
            
            // Does article exist?
            if (!$farmatic->exist($code))
            {
                $msg = "El artículo"." '".$code."' "."no está creado en la base de datos de Farmatic";
                ajax::fuckYou($msg);
                return;
            }
            
            // Get article data
            $data = $farmatic->getArticleData($code);
            $farmatic->close();            
            $arr[] = $data;
            
            // Update farmatic data in db
            $model = $this->_article_controller->getArticleModel();
            $type = $model->type;
            $id = $type.'-'.$code;
            $storage = $model->loadData($id);
            if (!is_null($storage))
            {
                $model->erp = $arr;
                $model->save();
            }              
        }
        
        $ret = helpers::objectize($arr);
        ajax::sendData($ret);
    }
    
    public function markOffArticleAsOnlineOrOfflineToFarmatic($data)
    {
        $arr = array();
        $code = $data->article_code;
        $online = (isset($data->online) && $data->online=='true') ? true : false;
        
        // Article without national code
        if ($this->_article_controller->hasNationalCodeFormat($code))
        {
            $dot_pos = strpos($code, '.');
            $code = substr($code, 0, $dot_pos);
        }            

        // Check the connectivity
        $farmatic = new farmatic();
        if (!$farmatic->isConnected())
        {
            $msg = 'No se ha podido conectar con la base de datos de Farmatic';
            ajax::fuckYou($msg);
            return;
        }            

        // Does article exist?
        if (!$farmatic->exist($code))
        {
            $msg = "El artículo"." '".$code."' "."no está creado en la base de datos de Farmatic";
            ajax::fuckYou($msg);
            return;
        }
            
        // Mark off the article
        $farmatic->markOffAsOnlineOrOffline($code, $online);
        
        // We read the article data from farmatic again
        $data = $farmatic->getArticleData($code);
        $farmatic->close();            
        $arr[] = $data;
            
        // Update farmatic data in db
        $model = $this->_article_controller->getArticleModel();
        $type = $model->type;
        $id = $type.'-'.$code;
        $storage = $model->loadData($id);
        if (!is_null($storage))
        {
            $model->erp = $arr;
            $model->save();
        }
        
        $ret = helpers::objectize($arr);
        ajax::sendData($ret);
    }
    
    private function _setUrls(&$data)
    {
        $language_controller = new adminLang();
        $available_langs = $language_controller->getLanguages(true);
        $gamma_controller = new gamma();
        
        foreach ($available_langs as $lang)
        {
            $urlLang = 'url'.ucfirst($lang->code);
            if (!isset($data->$urlLang) || empty($data->$urlLang))
            {
                $brand = $this->_getBrandName($data);
                $gamma = '';
                if (!empty($data->gamma))
                {
                    $gamma_model = $gamma_controller->getGammaByCode($data->gamma, $data->brand);
                    if (isset($gamma_model) && $gamma_model->visible)
                    {
                        $gamma_object = $gamma_model->getStorage();
                        $gamma = $gamma_controller->getTitle($gamma_object, $lang->code);
                    }
                }
                $title = $this->_getPropertyValue($data, 'titles', $lang->code);
                $display = $this->_getPropertyValue($data, 'displays', $lang->code);
                $url = $this->_article_controller->getUrl($brand, $gamma, $title, $display);

                $data->$urlLang = $url;                
            }
        }
    }
    
    private function _redirectOldUrls($data, $old_article)
    {
        $language_controller = new adminLang();
        $available_langs = $language_controller->getLanguages(true);
        $any_updated_url = false;
        $seo_url_controller = new seoUrl();
        
        foreach ($available_langs as $lang)
        {
            $urlLang = 'url'.ucfirst($lang->code);
            $old_url = $old_article->$urlLang;
            $new_url = $data->$urlLang;
            if ($old_url !== $new_url)
            {
                // Redirection
                $old_url = "/".$lang->code."/".ecommerceFrontendLangController::trans('url-articles', $lang->code)."/".$old_url;
                $url_doc = $seo_url_controller->getUrl($old_url);
                $model = $seo_url_controller->getUrlModel();
                if (empty($url_doc))
                {
                    $model->code = date("YmdHis")."-".rand(100, 999);
                }
                else
                {
                    $model->code = $url_doc->code;
                    $model->setNewId();
                }
                $model->url = $old_url;
                $model->action = "redirection";
                $model->useAction = "redirect2Article";
                $model->redirect2Article = $old_article->code;
                $model->save();
                $any_updated_url = true;
            }
        }
        
        if ($any_updated_url)
        {
            // Refresh url views
            $seo_url_controller->updateViews();
        }
        
    }
    
    private function _setDynamicProperties(&$article, $articles_for_sale)
    {
        $article_code = $article->code;
                
        //$article->anyStock = $this->_article_controller->anyStock($article);
        $article->anyStock = (isset($article->stock) && $article->stock > 0);
        $article->inErp = $this->_article_controller->isInErp($article);
        $article->forSale = (isset($articles_for_sale->$article_code));
        $article->anyGtin = (isset($article->gtin) && !empty($article->gtin));
        $article->checkedPackaging = (isset($article->checkedPackagingDate) && !empty($article->checkedPackagingDate));
        $article->anyImage = (isset($article->images) && !empty($article->images));

        // For sale and visible?
        if (!$article->forSale)
        {
            $visible = false;
        }
        else
        {
            if ($article->anyStock)
            {
                $visible = true;
            }
            else
            {
                $visible = $this->_article_controller->isVisibleIfNoStock($article);
            }                     
        }               
        $article->forSaleAndVisible = $visible;
        
        // Name
        $lang = lang::getLanguage();
        $display = isset($article->displays->$lang)? $article->displays->$lang : '';
        $name = $article->titles->$lang;
        if (!empty($display))
        {
            $name .= ', '.$display;
        }
        $article->name = $name;
        
        // Fix some properties
        $article->useMargin = (isset($article->useMargin) && !empty($article->useMargin))? $article->useMargin : 'article';
        $article->useDiscount = (isset($article->useDiscount) && !empty($article->useDiscount))? $article->useDiscount : 'article';
    }
    
    private function _getBrandName($data)
    {
        return $data->brandName;
    }
    
    private function _getPropertyValue($data, $property, $lang)
    {
        $value = '';
        
        $key = $property.'-'.$lang;
        if (isset($data->$key) && !empty($data->$key))
        {
            $value = $data->$key;
        }
        else
        {
            $default_language =  config::getConfigParam(array("application", "default_language"))->value;
            $key = $property.'-'.$default_language;
            if (isset($data->$key) && !empty($data->$key))
            {
                $value = $data->$key;
            }
        }
        
        return $value;
    }
    
    public function getImages($data)
    {
        $arr = array();
        $id = $data->record_id;
        
        if ($id !== '')
        {
            $model = $this->_article_controller->getArticleModel($id);
            if ($model->exists())
            {
                $images = $model->images;
                if (isset($images))
                {
                    foreach ($images as $values)
                    {
                        $item = array();
                        $item['filename'] = $values->filename;  
                        $item['filesize'] = $values->filesize;  
                        $item['filedate'] = $values->filedate;  
                        $item['relativePath'] = $values->relativePath;
                        $arr[] = $item;            
                    }                     
                }                    
            }                
        }
        
        $ret = helpers::objectize($arr);
        ajax::sendData($ret);
    }
    
    public function getImageFilePaths($data)
    {
        $arr = array();
        $id = $data->record_id;
        
        if ($id !== '')
        {
            $model = $this->_article_controller->getArticleModel($id);
            if ($model->exists())
            {
                $arr = $this->_article_controller->getImageFilePaths($model->code);
            }                
        }
        
        $ret = helpers::objectize($arr);
        ajax::sendData($ret);
    }
    
    public function getUrl($data)
    {
        $code = $data->code;         
        
        $article = $this->_article_controller->getArticleByCode($code, true);
        $url = $this->_article_controller->getArticleUrl($article);
        
        ajax::ohYeah($url);
    }
    
}