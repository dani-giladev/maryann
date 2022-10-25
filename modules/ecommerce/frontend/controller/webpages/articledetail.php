<?php

namespace modules\ecommerce\frontend\controller\webpages;

// Controllers
use core\config\controller\config;
use modules\ecommerce\frontend\controller\lang;
use modules\ecommerce\frontend\controller\shoppingcart as shoppingcartController;
use modules\ecommerce\frontend\controller\articledetail as articledetailController;
use modules\ecommerce\frontend\controller\menu\main as mainMenu;
use modules\ecommerce\frontend\controller\menu\breadcrumbs as breadcrumbsMenu;
use modules\ecommerce\frontend\controller\webpages\showcase as showcaseWebpageController;
use modules\ecommerce\frontend\controller\mailing\mail;

// Models
use modules\ecommerce\model\articleReview as articleReviewModel;

// Views
use modules\ecommerce\frontend\view\articledetail\articledetail as view;
use modules\ecommerce\frontend\view\actionButtons as actionButtonsView;
use modules\ecommerce\frontend\view\articledetail\reviews as reviewsView;
//use modules\ecommerce\frontend\view\medicinesInfo as medicinesInfoView;

/**
 * Article detail webpage
 *
 * @author Dani Gilabert
 * 
 */
class articledetail extends articledetailController
{
    protected $_view;
    protected $_action_buttons_view;
    protected $_shoppingcart_controller;
    protected $_categories;
    
    public function init($data)
    {
        // If article url is not defined, render showcase
        if (!isset($data->article_url))
        {
            $this->goToError404Webpage();
            return;            
        }
        
        // Set article by url
        $current_lang = lang::getCurrentLanguage();
        $this->_article = $this->_getArticleByUrl($current_lang, $data->article_url);
        if (empty($this->_article))
        {
            $this->goToError404Webpage();
            return;  
        }
        
        // Set canonical article
        $this->_canonical_article = $this->_getCanonicalArticle();
        
        // Create objects
        $this->_initView();
        $this->_action_buttons_view = new actionButtonsView();
        $this->_shoppingcart_controller = new shoppingcartController();
        
        // Render this page
        $this->renderPage();
    }    

    protected function _initView()
    {
        $this->_view = new view($this->_article);
    }
    
    protected function _getTitle()
    {
        $ret = '';
        $website = $this->getWebsite();
        
        /*
        $brand_name = $this->_article->brandName;
        $article_title = $this->_article_controller->getTitle($this->_article);
        $display = $this->_article_controller->getDisplay($this->_article);
        
        if (strpos($article_title, $brand_name) === false)
        {
            $ret .= $brand_name.', ';
        }
        
        $ret .= $article_title;
        
        
        if (!empty($display))
        {
            $ret .= ', '.$display;
        }*/
        
        $ret .= $this->_view->getComposedTitle($this->_article);
        
        $ret .= ' | '.$website->name;
        
        return $ret;
    }           
    
    protected function _getDescription()
    {
        $description = $this->_article_controller->getMetaDescription($this->_article);
        if (!isset($description) || empty($description))
        {
            $description = $this->_article_controller->getShortDescription($this->_article);
            $description = (isset($description) && !empty($description)) ? strip_tags($description) : '';
        }
            
        return $description;
    }  
    
    protected function _getKeywords()
    {
        $ret = $this->_article_controller->getKeywords($this->_article);
        return $ret;
    } 
    
    protected function _renderMenu()
    {
        $html = '';
        
        // Render main menu
        $main_menu = new mainMenu();
        $html .= $main_menu->renderMainMenu();
        $this->_categories = $main_menu->getCategoriesTree();
        
        // Render breadcrumbs menu
        $breadcrumbs_menu = new breadcrumbsMenu(array(
            'categories' => $this->_categories, 
            'article' => $this->_article
        ));          
        $html .= $breadcrumbs_menu->renderBreadcrumbsMenu();
     
        return $html;
    }
    
    protected function _renderContent()
    {
        $current_lang = lang::getCurrentLanguage();
        $html = '';
        
        // Setting view properties
        $brands = $this->_getBrands();
        $this->_view->brands = $brands;
        $gammas = $this->_getGammas();
        $this->_view->gammas = $gammas;
        $brand = $this->_getBrand($this->_article, $brands);
        $this->_view->brand = $brand;
        $gamma = $this->_getGamma($this->_article, $brands, $gammas);
        $this->_view->gamma = $gamma;  
        $this->_view->canonical_article = $this->_canonical_article;
        $this->_view->related_articles = $this->_getRelatedArticles($this->_article);
        $this->_view->current_reviews = $this->_article_controller->getReviews($this->_article);
        $this->_view->articles_grouped_by_display = $this->_getArticlesGroupedByDisplay($this->_article);
        $this->_view->tabdata = $this->_getTabdata($this->_article, $brand, $gamma);
        $this->_view->botplusdata = $this->_getBotplusData($this->_article);
        $this->_view->is_article_available = $this->_isArticleAvailable($this->_article);
        
        // Render content
        $html .= $this->_view->renderContent();
        
        // Render action buttons
        $total_amount = $this->_shoppingcart_controller->getTotalAmount();
        $html .= $this->_action_buttons_view->renderActionButtons($continue_shopping_button = array('visible' => true,
                                                                                                    'type' => 'button',
                                                                                                    'text' => lang::trans('continue_shopping'),
                                                                                                    'onClick' => 'window.location.href=\''.$this->getUrl(array($current_lang, 'showcase')).'\''),
                                                                  $ordering_button = array('visible' => ($total_amount > 0),
                                                                                           'type' => 'button',
                                                                                           'text' => lang::trans('ordering'),
                                                                                           'onClick' => 'window.location.href=\''.$this->getUrl(array($current_lang, 'shoppingcart')).'\''));
        return $html;
    }
    
    /*
    protected function _renderAdditionalContent()
    {      
        // Medicines info
        $html = $this->_renderMedicinesInfo();
        return $html;
    }
    
    private function _renderMedicinesInfo()
    {
        $html = '';
        
        if ($this->_article->articleType !== '2')
        {
            return $html;
        }
        
        $medicines_info_view = new medicinesInfoView();
        $medicines_info_view->is_individual_article = true;
        $html .= $medicines_info_view->render();
        
        return $html; 
    }
    */
    
    public function addIndividualArticleToShoppingcart($data)
    {
        $showcase_controller = new showcaseWebpageController();
        $showcase_controller->addToShoppingcart($data, true);
    }
    
    public function addReview($data)
    {
        $article_code = $data->article_code;
        $rating = $data->rating;
        $name = $data->name;
        $title = $data->title;
        $text = $data->text;
        $lang = lang::getCurrentLanguage();
        
        // Set properties
        $review = new articleReviewModel();
        $code = $review->getNewCode();
        $review->code = $code;
        $review->date = date(config::getConfigParam(array("application", "dateformat_database"))->value);
        $review->time = date(config::getConfigParam(array("application", "timeformat"))->value);
        
        // Get article
        $article = $this->_article_controller->getArticleByCode($article_code, true);
        $review->articleCode = $article_code;
        $review->articleName = $article->name;
        //$review->article = $article->getStorage();
        
        $review->rating = $rating;
        $review->name = $name;
        $review->title = $title;
        $review->text = $text;
        $review->lang = $lang;
        
        // Happy end
        $review->save();
        
        // Send admin email
        $mail = new mail();
        $website = $this->getWebsite();
        $subject = $website->name. ' - '.'Nova opinió'.' '.$review->code;
        $body = 
                $review->articleCode.' '.$review->articleName.'</br></br>'.
                'Puntuació : '.$review->rating.' stars</br>'.
                'Nom : '.$review->name.'</br>'.
                'Títol : '.$review->title.'</br>'.
                'Texte : '.$review->text.'</br>'.
                'Idioma : '.$review->lang.'</br>'.
                '';
        $to = $mail->getMailAddresses();
        $mail->send($subject, $body, $to);
        
        // Happy end
        $ret['success'] = true;
        $ret['msg'] = '';
        $reviews_view = new reviewsView();
        $ret['htmlMsg'] = $reviews_view->renderWindowAfterSubmit();
        $ret = json_encode($ret);
        echo $ret;          
    }
    
    private function _getTabdata($article, $brand, $gamma)
    {
        $ret = array();
        
        // Description
        $ret['description'] = $this->_article_controller->getDescription($article, null, $this->_canonical_article);
        
        // Application
        $ret['application'] = $this->_article_controller->getApplication($article, null, $this->_canonical_article);
        
        // Active ingredients
        $ret['active_ingredients'] = $this->_article_controller->getActiveIngredients($article, null, $this->_canonical_article);
        
        // Composition
        $ret['composition'] = $this->_article_controller->getComposition($article, null, $this->_canonical_article);
        
        // Prospect
        $ret['prospect'] = $this->_article_controller->getProspect($article);
        
        // Data sheet
        $ret['datasheet'] = $this->_article_controller->getDatasheet($article);
        
        // Brand
        $ret['brand'] = '';
        $ret['brand_title'] = '';
        if (
                isset($brand) && 
                $brand->available && 
                (!isset($brand->visible) || $brand->visible) && 
                (!isset($brand->empty) || !$brand->empty) && 
                (isset($article->brand) && !empty($article->brand))
        )
        {
            $brand_description = $this->_brand_controller->getDescription($brand);
            if (!empty($brand_description))
            {
                $ret['brand_title'] = $article->brandName;
                $ret['brand'] = 
                        //'<b>'.$ret['brand_title'].'</b>'.'<br><br>'.
                        $brand_description;                 
            }
        }
        
        // Gamma
        $ret['gamma'] = '';
        $ret['gama_title'] = '';
        if (isset($gamma) && $gamma->visible)
        {
            $gama_description = $this->_gamma_controller->getDescription($gamma);
            if (!empty($gama_description))
            {
                $ret['gama_title'] = $this->_gamma_controller->getTitle($gamma);
                $ret['gamma'] = 
                        //'<b>'.$ret['gama_title'].'</b>'.'<br><br>'.
                        $gama_description;                     
            }                
        }
        
        return $ret;
    }
    
    protected function _getCanonicalUrl()
    {
        $ret = '';
        
        if (!isset($this->_canonical_article))
        {
            $description = $this->_article_controller->getDescription($this->_article);
            if (empty($description))
            {
                $current_lang = lang::getCurrentLanguage();
                $default_language = config::getConfigParam(array("application", "default_language"))->value;
                if ($current_lang !== $default_language)
                {
                        $ret = $this->_article_controller->getArticleUrl($this->_article, $default_language);
                }
            }
            return $ret;
        }
        
        $ret = $this->_article_controller->getArticleUrl($this->_canonical_article);
        return $ret;
    }   
    
}