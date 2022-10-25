
var articledetail = {
    
    setStyles: function()
    {
        if (is_mobile) return;
        
        // Set width articles content
        var page_center_width = $('.page-center').width();
        var sidebar_width = 200;
        var content_width = page_center_width- sidebar_width - 50;
        $('#article-detail-related-articles-content').css({'width': content_width + 'px'});
        
        // Set size of article img
        var article_img_width = $('.showcase-content-article').width();
        $('.showcase-content-article-img-wrapper').css({'width': article_img_width + 'px', 'height': article_img_width + 'px'});
        
        // Show articles with fade effect
        if ( $(".showcase-content-article-wrapper").is(".fadeout") )
        {
            $(".showcase-content-article-wrapper").removeClass("fadeout").addClass("fadein");
        }
    }
    
};

$(function()
{
    
    $(window).resize(function() {
        articledetail.setStyles();
    }); 
    articledetail.setStyles();
    
    // Carousels
    initCarousels();   
    
    // Menus
    initMenus();

    // Init spinners (amount)
    initSpinners(); 
    
    // Init add buttons
    initAddButtons();
    
    // Tabs Content
    initTabs();
    
    // Init star ratings (reviews)
    initStarsRating();
    
    // When the webpage is ready...
    $(document).ready(function()
    {
        // Init reviews form
        initValidateReviewsForm();

    });
    
});

function initCarousels()
{
    // Hide or not, the article detail carousel controls
    var carousel = $('#article-detail-carousel');
    var items = carousel.attr("_items");
    //console.log(items);
    if (items < 2)
    {
        $('#article-detail-carousel-control-prev').hide();
        $('#article-detail-carousel-control-next').hide();
        $('#article-detail-carousel-pagination').hide();
    }
    
    $(".fancybox").fancybox();
}

function initMenus()
{
    if (is_mobile) return;
    
    $("#article-detail-introduction-availableformats-combo").menu();
}

function initSpinners()
{
    if (is_mobile) return;
        
    initSpinner($("#article-detail-introduction-addtocart-amount-spinner")); 
    
    $("input[name=showcase-content-article-addtocart-amount-spinner-name]").each( function () {
        initSpinner($(this));
    });       
}

function initSpinner(myspinner)
{
    var article_title = myspinner.attr("_article_title");
    var enable = (myspinner.attr("_enable") == '1');
    var stock = parseInt(myspinner.attr("_stock"));
    var min = parseInt(myspinner.attr("_min"));
    var max = parseInt(myspinner.attr("_max"));
    
    myspinner.spinner({
        min: min,
        max: max + 1,
        step: 1 ,
        change: function( event, ui ) {
            var value = $(this).spinner("value");
            if (value < min ) {
                $(this).spinner("value", min);
            } else if (value > max) {
                $(this).spinner("value", max);
                ecommerce.showDialogWhenAddToCartWithSpinner(article_title, enable, stock, max);
            }
        },
        spin: function( event, ui ) {
            var value = ui.value;
            if (value > max)
            {
                myspinner.spinner("value", max);
                event.preventDefault();
                ecommerce.showDialogWhenAddToCartWithSpinner(article_title, enable, stock, max);
            }
        }         
    });      
}

function initAddButtons()
{
    initAddButton($("#article-detail-introduction-addtocart-button"));
    
    $("button[name=showcase-content-article-addtocart-button-name]").each( function () {
        initAddButton($(this));
    });       
}

function initAddButton(mybutton)
{
    var disabled = mybutton.attr("_disabled");
    if (disabled)
    {
        // Disable button
        mybutton.prop('disabled', true);
        mybutton.removeClass('button-addtocart').addClass('button-disabled'); 
    }   
}

function initTabs()
{
    if (is_mobile) return;
    
    $("#article-detail-tabs").tabs({
//        event: "mouseover"
    });
    
    $("#article-detail-tab-accordion-epigraphs").accordion({
        heightStyle: "content"
    });
    
    $("#article-detail-tab-accordion-messages").accordion({
        heightStyle: "content"
    });
}

function initStarsRating()
{    
    var rating_intro = $("#article-detail-introduction-reviews-star-rating");
    var rating = rating_intro.attr("_rating");
    rating_intro.rateYo({
        starWidth: "17px",
        rating: rating,
        readOnly: true        
    });  
    
    $("div[class=article-detail-reviews-review-star-rating]").each( function () {
        var rating = $(this).attr("_rating");
        $(this).rateYo({
            starWidth: "17px",
            rating: rating,
            fullStar: true,
            readOnly: true        
        });         
    });
    
    $("#article-detail-reviews-form-star-rating").rateYo({
        starWidth: "30px",
        spacing: "5px",
        fullStar: true
    });  
     
}

function initValidateReviewsForm()
{
    // Hide error label
    $('#article-detail-reviews-form-star-rating-error-text').hide();
    
    // Validate form
    $("#article-detail-reviews-form").validate(
    {
        event: "blur",
        rules: 
        {
            name: { required: true },
            title: { required: true },
            text: { required: true }
        },
        messages: 
        {                    
            name: msg_required_field,
            title: msg_required_field,
            text: msg_required_field
        }, 
        highlight: function(element) 
        {
            //var name = $(element)[0].name;
            $(element).addClass('error');
        }, 
        unhighlight: function(element) 
        {
            //var name = $(element)[0].name;
            $(element).removeClass('error');
        },
        debug: false,           
        errorClass: 'error',
        submitHandler: function(form)
        {              
            var rating = $("#article-detail-reviews-form-star-rating").rateYo("rating");
            //console.log(rating);
            if (rating <= 0)
            {
                $('#article-detail-reviews-form-star-rating-error-text').html(msg_rate_required).show();
                return;
            }
            
            // Hide error label
            $('#article-detail-reviews-form-star-rating-error-text').hide();
                
            // Set mask
            ecommerce.setMask(msg_wait_please);

            var postdata = "controller=" + ecommerce.getBaseController() + "\\articledetail" +
                           "&method=addReview" + 
                           "&article_code=" + article_code + 
                           "&rating=" + rating + 
                           "&name=" + $("input[name=name]").val() + 
                           "&title=" + $("input[name=title]").val() + 
                           "&text=" + $("textarea[name=text]").val() + 
                           "";
            $.ajax({
                type: "POST",
                url: "index.php",
                data: postdata,
                success: function(result)
                {
                    // Unset mask
                    $.unblockUI();
                    
                    result = JSON.parse(result);
                    
                    // Show window after submit
                    $.fancybox({
                        content: result.htmlMsg,
                        closeEffect: 'none',
                        closeBtn: true,
                        beforeClose: function() 
                        {
                            // Reload page
                            ecommerce.setMask(msg_wait_please);
                            window.location.reload();
                        }
                    });

                }
            });
        }
    });    
}

function addToShoppingcart(code)
{
    // Set mask
    ecommerce.setMask(msg_processing_your_request);

    var amount = $("input[name=showcase-content-article-addtocart-amount-spinner-name][_article_code='" + code + "']").val();
    
    var postdata = "controller=" + ecommerce.getBaseController() + "\\showcase" +
                   "&method=addToShoppingcart" + 
                   "&code=" + code +
                   "&amount=" + amount;  
    $.ajax({
//        type: "POST",
        type: "GET",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            // Unset mask
            $.unblockUI();
            
            result = JSON.parse(result);
            
            // Update tooltip and items from shopping cart menu
            $('#shoppingcart-menu-option-amount-wrapper').html(result.shoppingcartAmount);  
            $('#shoppingcart-menu-option-totalprice-wrapper').html(result.shoppingcartTotalPrice); 
            $('#shoppingcart-menu-option').tooltipster('content', $(result.shoppingcartTooltip));
            
            // Show window after add
            $.fancybox({
                content: result.windowAfterAddToShoppingcart,
                closeEffect: 'none',
                closeBtn: false 
            });
            
            // Update add shoppingcart option content
            $('#showcase-content-article-addtocart-article-' + code).html(result.addToShoppingcartWidgetsContent);
            initSpinner($("input[name=showcase-content-article-addtocart-amount-spinner-name][_article_code=" + code + "]"));
            initAddButton($("button[name=showcase-content-article-addtocart-button-name][_article_code=" + code + "]"));
            
        }
    });        
}

function addIndividualArticleToShoppingcart(code)
{
    // Set mask
    ecommerce.setMask(msg_processing_your_request);
    
    var amount = is_mobile? 1 : ($("#article-detail-introduction-addtocart-amount-spinner").val());
    
    var postdata = "controller=" + ecommerce.getBaseController() + "\\articledetail" +
                   "&method=addIndividualArticleToShoppingcart" + 
                   "&code=" + code +
                   "&amount=" + amount; 
    $.ajax({
//        type: "POST",
        type: "GET",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            // Unset mask
            $.unblockUI();
            
            result = JSON.parse(result);
            
            if (!is_mobile)
            {
                // Update tooltip and items from shopping cart menu
                $('#shoppingcart-menu-option-amount-wrapper').html(result.shoppingcartAmount);  
                $('#shoppingcart-menu-option-totalprice-wrapper').html(result.shoppingcartTotalPrice);                
                $('#shoppingcart-menu-option').tooltipster('content', $(result.shoppingcartTooltip));

                // Show window after add
                $.fancybox({
                    content: result.windowAfterAddToShoppingcart,
                    closeEffect: 'none',
                    closeBtn: false 
                });                

                // Update add shoppingcart option content
                $('#article-detail-introduction-addtocart').html(result.addToShoppingcartWidgetsContent);                
                initSpinner($("#article-detail-introduction-addtocart-amount-spinner"));                
            }
            else
            {
                // Update tooltip and items from shopping cart menu
                $('#shoppingcart-menu-option-amount-wrapper').html(result.shoppingcartAmount);  
                $('#article-detail-introduction-addtocart').empty();
                $('#article-detail-introduction-addtocart').append(result.addToShoppingcartWidgetsContent).trigger('create');                 
            }

            initAddButton($("#article-detail-introduction-addtocart-button"));
        }
    });
    
}