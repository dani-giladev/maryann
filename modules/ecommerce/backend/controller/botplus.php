<?php

namespace modules\ecommerce\backend\controller;

// Controllers
use core\ajax\controller\ajax;
use core\helpers\controller\helpers;
use core\botplus\controller\botplus as botplusController;
use modules\admin\controller\language as adminLang;
use modules\ecommerce\controller\article;

// Models
use modules\ecommerce\model\botplus as botplusModel;

/**
 * Backend E-commerce bot plus controller
 *
 * @author Dani Gilabert
 * 
 */
class botplus
{
    
    public function getBotplusDataByCode($code)
    {
        $ret = array();
        
        $botplusModel = new botplusModel();
        $type = $botplusModel->type;
        $id = $type.'-'.$code;
        $storage = $botplusModel->loadData($id);
        if (is_null($storage))
        {
            return $ret;
        }
        
        $ret = (array) $storage;
        return $ret;
    }
    
    public function getBotplusData($params)
    {
        $code = $params->code;
            
        $article_controller = new article();
        $article_model = $article_controller->getArticleByCode($code);
        if (!isset($article_model))
        {
            ajax::fuckYou("L'article amb codi $code no existeix a la base de dades");
            return;
        }
        $article = $article_model->getStorage();
            
        $botplus_controller = new botplusController();
        
        $raw_data = $this->getBotplusDataByCode($code);
        if (empty($raw_data))
        {
            $botplus_controller->getEpigraph($article);
            $botplus_controller->getMessage($article);
            $botplus_controller->update($article);
            
            $raw_data = $this->getBotplusDataByCode($code);
            if (empty($raw_data))
            {
                ajax::fuckYou("Ups!! Encara no hi han dades disponibles de Bot plus per aquest article");
                return;
            }
        }
        $data = array();
        
        // Get main data
        $is_medicine = ($article->articleType === '2');
        $maindata = $botplus_controller->getMaindata($code, $is_medicine);
//        $data['maindata'] = json_encode($maindata, JSON_PRETTY_PRINT);
        $data['maindata'] = helpers::json2Html(json_encode($maindata));
        
        // Get epigraphs
        $data['epigraphs'] = $raw_data['epigraphs'];
        
        // Get messages
        $data['messages'] = helpers::json2Html(json_encode($raw_data['messages']));
             
        // Get model
        $model = array(array(
            'name' => 'maindata'
        ));
        foreach ($raw_data as $key => $value) {
            $model[] = array(
                'name' => $key
            );
        }
        
        $ret =  array(
            'data' => $data,            
            'model' => $model
        );
        
        ajax::ohYeah($ret);
    }
    
    public function saveBotplusData($data)
    {
        $code = str_replace('ecommerce-article-', '', $data->record_id);
                
        $botplusModel = new botplusModel();
        $type = $botplusModel->type;
        $id = $type.'-'.$code;
        $storage = $botplusModel->loadData($id);
        if (is_null($storage))
        {
            ajax::fuckYou('Inexistent record');
            return;
        }
        
        $language_controller = new adminLang();
        $available_langs = $language_controller->getLanguages(true);
                
        $epigraphs = $botplusModel->epigraphs;
        foreach ($epigraphs as $key => $values)
        {
            $mkey = str_replace(" ", "_", $key);
            $enabled_mkey = $mkey.'-enabled';
            if (property_exists($data, $enabled_mkey) && isset($data->$enabled_mkey))
            {
                $enabled_value = ($data->$enabled_mkey === 'on');
                $epigraphs->$key->enabled = $enabled_value;

                foreach ($available_langs as $lang)
                {
                    $lang_code = $lang->code;
                    if (!property_exists($epigraphs->$key, $lang_code) || !isset($epigraphs->$key->$lang_code))
                    {
                        $epigraphs->$key->$lang_code = new \stdClass();
                    }    
                    
                    $lang_name_mkey = $mkey.'-'.'name'.'-'.$lang_code;
                    if (property_exists($data, $lang_name_mkey) && isset($data->$lang_name_mkey))
                    {
                        $epigraphs->$key->$lang_code->name = $data->$lang_name_mkey;
                    }
                    
                    $lang_text_mkey = $mkey.'-'.'text'.'-'.$lang_code;
                    if (property_exists($data, $lang_text_mkey) && isset($data->$lang_text_mkey))
                    {
                        $epigraphs->$key->$lang_code->text = $data->$lang_text_mkey;
                    }
                }                
            }
            
        }
        
        // Save
        $botplusModel->epigraphs = $epigraphs;
        $botplusModel->save();
                
        ajax::ohYeah();
        
    }
    
}