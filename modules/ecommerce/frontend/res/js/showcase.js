
var showcase = {
    
    scroll_position_timer: null,
    
    setStyles: function()
    {
        if (is_mobile) return;
        
        // Set width articles content
        var page_center_width = $('.page-center').width();
        var sidebar_width = $('#showcase-sidebar').width();
        var content_width = page_center_width - sidebar_width - 50;
        $('#showcase-content').css({'width': content_width + 'px'});
        
        // Set size of article img
        var article_img_width = $('.showcase-content-article').width();
        $('.showcase-content-article-img-wrapper').css({'width': article_img_width + 'px', 'height': article_img_width + 'px'});
        
        // Show articles with fade effect
        if ( $(".showcase-content-article-wrapper").is(".fadeout") )
        {
            $(".showcase-content-article-wrapper").removeClass("fadeout").addClass("fadein");
        }
        
    },

    sendSearcherMsg: function()
    {
        // Set mask
        ecommerce.setMask(msg_wait_please);
    
        var msg = $("#showcase-content-searchresult-form-text").val();
        var email = $("#showcase-content-searchresult-form-email").val();

        var postdata = "controller=" + ecommerce.getBaseController() + "\\showcase" +
                       "&method=sendSearcherMsg" + 
                       "&msg=" + msg + 
                       "&email=" + email;  
        $.ajax({
            type: "POST",
            url: "index.php",
            data: postdata,
            success: function(result)
            {
                // Unset mask
                $.unblockUI();
                
                result = JSON.parse(result);
                if (!result.success)
                {
                    $('#showcase-content-searchresult-form-error-msg').html(result.msg);
                    return false;
                }

                $.fancybox({
                    content: result.htmlMsg,
                    closeEffect: 'none',
                    closeBtn: true,
                    beforeClose: function() 
                    {
                        // Reload page
                        window.location.reload();
                    }
                });
            }
        });        
    }
    
};

$(function()
{
    
    $(window).resize(function() {
        showcase.setStyles();
    }); 
    showcase.setStyles();
    
    $(".fancybox").fancybox();
    
    // Menus
    initMenus();
    
    // Sliders
    initSliders();    

    // Init spinners (amount)
    initSpinners();
    
    // Init add buttons
    initAddButtons();
    
    // Set the scroll position
    if (is_mobile)
    {
        $('html, body').animate({
            scrollTop: scroll_position
        }, 800, function() {
            // if you need a callback function
        });        
    }
    else
    {
        $(window).scrollTop(scroll_position);        
    }
    
    // When the webpage is ready...
    $(document).ready(function()
    {
        $(window).scroll(function() {

            // Init timer to save the scroll position
            clearInterval(showcase.scroll_position_timer);
            showcase.scroll_position_timer = setTimeout('saveScrollPosition()', 500);
        }); 
    });
});

function initMenus()
{
    if (is_mobile) return;
    
    // Vertical menus
    $("#showcase-sidebar-categories-menu").menu();
    $("#showcase-sidebar-brands-combo").menu();
    initContentMenus();
}

function initContentMenus()
{
    if (is_mobile) return;
    
    // Content menus
    $("#showcase-content-menu-sortby-combo").menu();
    $("#showcase-content-footer-articlesperpage-combo").menu();
}

function initSliders()
{
    if (is_mobile) return;
        
    // Sliders
    if (!$("#showcase-sidebar-slider-price-range"))
    {
        return;
    }
    
    var myslider = $("#showcase-sidebar-slider-price-range");
    var min = parseFloat(myslider.attr("_min"));
    var max = parseFloat(myslider.attr("_max"));
    var min_value = parseFloat(myslider.attr("_min_value"));
    var max_value = parseFloat(myslider.attr("_max_value"));
    
    myslider.slider({
      range: true,
      min: min,
      max: max,
      values: [min_value, max_value],
      slide: function(event, ui) {
        $("#showcase-sidebar-price-range-filter-value").html(ui.values[0] + "\u20ac" + " - " + ui.values[1] + "\u20ac");
      },
      change: function( event, ui ) {
          setContent();
      }
    });
    $("#showcase-sidebar-price-range-filter-value").html(
        myslider.slider("values", 0) + "\u20ac" +
        " - " + $("#showcase-sidebar-slider-price-range").slider("values", 1) + "\u20ac");     
}

function initSpinners()
{
    if (is_mobile) return;
    
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
            var value = myspinner.spinner("value");
            if (value < min ) {
                myspinner.spinner("value", min);
            } else if (value > max) {
                myspinner.spinner("value", max);
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
    if (is_mobile) return;
    
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

function onClickOutstandingArticlesCheckboxFilter()
{
    setContent();
}

function onClickNoveltyArticlesCheckboxFilter()
{
    setContent();
}

function onClickPackArticlesCheckboxFilter()
{
    setContent();
}

function onClickChristmasArticlesCheckboxFilter()
{
    setContent();
}

function onClickCategoriesCheckboxFilter()
{
    setContent();
}

function onClickBrandsCheckboxFilter()
{
    setContent();
}

function onClickGammaCheckboxFilter()
{
    setContent();
}

function onClickArticlePropertiesCheckboxFilter()
{
    setContent();
}

function onChangeArticlesPerPage()
{
    setContent();
}

function onChangeSortby()
{
    setContent();
}

function setContent()
{
    var arr, object;

    // Get price range filter
    var min_price = '';
    var max_price = '';
    if ($('#showcase-sidebar-slider-price-range').is(':visible')) 
    {
        min_price = $("#showcase-sidebar-slider-price-range").slider("values", 0);
        max_price = $("#showcase-sidebar-slider-price-range").slider("values", 1);
    }
    
    // Get checked value of first filters
    var outstanding = false;
    if ($('#showcase-sidebar-outstanding-filter'))
    {
        outstanding = $('#showcase-sidebar-outstanding-filter').prop("checked");
    }
    var novelty = false;
    if ($('#showcase-sidebar-novelty-filter'))
    {
        novelty = $('#showcase-sidebar-novelty-filter').prop("checked");
    }
    var pack = false;
    if ($('#showcase-sidebar-pack-filter'))
    {
        pack = $('#showcase-sidebar-pack-filter').prop("checked");
    }
    var christmas = false;
    if ($('#showcase-sidebar-christmas-filter'))
    {
        christmas = $('#showcase-sidebar-christmas-filter').prop("checked");
    }
    
    // Get checked categories
    arr = [];
    $("input[name=showcase-sidebar-categories-filter]").each( function () {
        if ($(this).prop("checked"))
        {
            arr.push($(this).attr("code"));
        }
    });
    var categories = JSON.stringify(arr);

    // Get checked brands
    arr = [];
    $("input[name=showcase-sidebar-brands-filter]").each( function () {
        if ($(this).prop("checked"))
        {
            arr.push($(this).attr("code"));
        }
    });
    var brands = JSON.stringify(arr);

    // Get checked gamma
    arr = [];
    $("input[name=showcase-sidebar-gamma-filter]").each( function () {
        if ($(this).prop("checked"))
        {
            arr.push($(this).attr("code"));
        }
    });
    var gamma = JSON.stringify(arr);

    // Get checked article properties
    object = {};
    $("input[name=showcase-sidebar-articleproperties-filter]").each( function () {
        if ($(this).prop("checked"))
        {
            var code = $(this).attr("code");
            var amount = $(this).attr("amount");
            var value = $(this).attr("value_code");
            if (!object[code]) {object[code] = {}};
            if (!object[code]['amounts']) {object[code]['amounts'] = {}};
            if (!object[code]['amounts'][amount]) {object[code]['amounts'][amount] = {}};
            if (!object[code]['amounts'][amount]['values']) {object[code]['amounts'][amount]['values'] = {}};
            object[code]['amounts'][amount]['values'][value] = {};
        }
    });
    var article_properties = JSON.stringify(object);
    
    // Articles per page
    var articlesperpage = '';
    if ($("#showcase-content-footer-articlesperpage-combo").is(':visible'))
    {
        articlesperpage = $("#showcase-content-footer-articlesperpage-combo").val();
    }
    
    // Sort article by
    var sortby = '';
    if ($("#showcase-content-menu-sortby-combo").is(':visible'))
    {
        sortby = $("#showcase-content-menu-sortby-combo").val();
    }
    
    // Set the post data
    var postdata = "controller=" + ecommerce.getBaseController() + "\\showcase" +
                   "&method=sendContentToClient" +  
                   "&min_price=" + min_price + 
                   "&max_price=" + max_price + 
                   "&outstanding=" + outstanding +  
                   "&novelty=" + novelty + 
                   "&pack=" + pack + 
                   "&christmas=" + christmas + 
                   "&categories=" + categories + 
                   "&brands=" + brands + 
                   "&gamma=" + gamma + 
                   "&article_properties=" + article_properties + 
                   "&articlesperpage=" + articlesperpage + 
                   "&sortby=" + sortby;  
    $.ajax({
//        type: "POST",
        type: "GET",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            // Update content
            result = JSON.parse(result);
            $('#showcase-content').html(result.articlesContent);
            
            // Set styles again
            showcase.setStyles();
    
            // Init content menus
            initContentMenus();
            
            // Init spinners (amount)
            initSpinners();
            
            // Init add buttons (enable/disable)
            initAddButtons();
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

function saveScrollPosition()
{
    var position = $(window).scrollTop();
    if (position == scroll_position)
    {
        return;
    }
    
    var postdata = "controller=" + ecommerce.getBaseController() + "\\showcase" +
                   "&method=saveScrollPosition" + 
                   "&position=" + position;  
           
    $.ajax({
//        type: "POST",
        type: "GET",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            
        }
    });        
}