<?php

namespace modules\ecommerce\frontend\view\articledetail;

// Controllers
use modules\ecommerce\frontend\controller\lang;

/**
 * Reviews for articles details view
 *
 * @author Dani Gilabert
 * 
 */
class reviews
{ 
    protected $_current_reviews = array();
    
    public function __construct($current_reviews = array())
    {
        $this->_current_reviews = $current_reviews;
    }
    
    public function render()
    {
        $html = '';
        $html .= $this->_renderCurrentReviews();
        $html .= $this->_renderForm();
        return $html;
    }
    
    private function _renderCurrentReviews()
    {
        $html = '';
        
        if (empty($this->_current_reviews))
        {
            return $html;
        }
        
        foreach ($this->_current_reviews as $review)
        {
            $rating = (isset($review->rating) && is_numeric($review->rating))? $review->rating : 0;
            
            $html .= 
                    '<div class="article-detail-reviews-review" itemprop="review" itemscope itemtype="http://schema.org/Review">'.
                        '<div class="article-detail-reviews-review-title" itemprop="name">'.
                            $review->title.
                        '</div>'.
                        '<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">'.
                            '<div class="article-detail-reviews-review-star-rating" _rating="'.$rating.'"></div>'.
                            '<meta itemprop="worstRating" content="1" />'.
                            '<meta itemprop="ratingValue" content="'.$rating.'" />'.
                            '<meta itemprop="bestRating" content="5" />'.
                        '</div>'.
                        '<div class="article-detail-reviews-review-text" itemprop="description">'.
                            nl2br($review->text).
                        '</div>'.
                        '<span class="article-detail-reviews-review-name" itemprop="author">'.$review->name.'</span>'.
                        '<span class="article-detail-reviews-review-date">, '.date('d/m/Y', strtotime($review->date)).'</span>'.
                        '<meta itemprop="datePublished" content="'.$review->date.'" />'.
                        '<div class="article-detail-reviews-review-delimiter"></div>'.
                    '</div>';
        }
        
        return $html;
    }
    
    private function _renderForm()
    {
        $html = '';
        
        $html .= '<form id="article-detail-reviews-form" method="post">';

        $html .= 
                '<div id="article-detail-reviews-form-title" class="title">'.
                    lang::trans('write_your_review').
                '</div>'.
                '';
        
        // Star rating
        $html .= '<div id="article-detail-reviews-form-star-rating"></div>';
        $html .= '<div id="article-detail-reviews-form-star-rating-error-text"></div>';
        $html .= '<div id="article-detail-reviews-form-star-rating-end"></div>';
        
        // Name
        $html .= '<div class="label">'.lang::trans('name').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'class="field" '.
                    'name="name" '.
                    'type="text" '.
                    'value="'.''.'" '.
                '>';
        
        // Title
        $html .= '<div class="label">'.lang::trans('title').'&nbsp;*'.'</div>';
        $html .= 
                '<input '.
                    'class="field" '.
                    'name="title" '.
                    'type="text" '.
                    'value="'.''.'" '.
                '>';
        
        // Text
        $html .= '<div class="label">'.lang::trans('text').'&nbsp;*'.'</div>';
        $html .= 
                '<textarea '.
                    'class="field article-detail-reviews-form-field-text" '.
                    'name="text" '.
                '>'.''.'</textarea>';   
        
        // Submit
        $html .= 
                '<button '.
                    'type="submit" '.
                    'class="'.
                        'action-buttons-button '.
                        'action-buttons-right-position '.
                        'button '.
                        'button-ordering '.
//                        'button-addtocart '.
                        'article-detail-reviews-form-submit-button'.
                    '"'.
                '>'.
                    lang::trans('add_review').
                '</button>';   
            
        $html .= '</form>';
        
        return $html;
    }
    
    public function renderWindowAfterSubmit()
    {
        // Start render
        $html = '<div id="article-detail-reviews-form-message-after-submit" >';
        
        $html .= 
                '<div id="article-detail-reviews-form-message-after-submit-title" >'.
                    lang::trans('thanks_for_reviewing').'.'.
                '</div>'.
                '';
        
        $html .= 
                '<div id="article-detail-reviews-form-message-after-submit-success" class="label-info" >'.
                    lang::trans('we_will_consider_your_review').'.'.
                '</div>'.
                '';            
        
        // End render
        $html .= '</div>';         
  
        return $html;
    }  
  
}