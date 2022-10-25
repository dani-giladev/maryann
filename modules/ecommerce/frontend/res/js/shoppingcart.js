
$(function()
{ 
  
    // Init spinners (amount)
    initSpinners();

    // Init voucher tooltip
    //initVoucherTooltip();
    $('#shoppingcart-content-voucher-tooltip').hide();
        
    var handleClick= 'ontouchstart' in document.documentElement ? 'touchstart': 'click';
    $(document).on(handleClick,'#shoppingcart-content-voucher-tooltip-button',function(){
//        alert('Click is now working with touch and click both');
        confirmVoucher(); 
    });

    // When the webpage is ready...
    $(document).ready(function()
    {

    });
});

function initSpinners()
{
    if (is_mobile) return;
    
    $("input[name=shoppingcart-content-articles-table-column-amount-content-spinner-name]").each( function () {
        var article_code = $(this).attr("_article_code");
        var article_title = $(this).attr("_article_title");
        var enable = ($(this).attr("_enable") == '1');
        var stock = parseInt($(this).attr("_stock"));
        var min = parseInt($(this).attr("_min"));
        var max = parseInt($(this).attr("_max"));
        
        $(this).spinner({
            min: min,
            max: max + 1,
            step: 1,
            change: function( event, ui ) {
                var value = $(this).spinner("value");
                var last_value = $(this).attr("last_value");
                if (value < min ) {
                    value = min;
                    $(this).spinner("value", value);
                } else if (value > max) {
                    value = max;
                    $(this).spinner("value", value);
                    ecommerce.showDialogWhenAddToCartWithSpinner(article_title, enable, stock, (stock - max));
                }
                
                if (value != last_value)
                {
                    onChangeArticleAmount(article_code, value);
                    $(this).attr("last_value", value);
                }
            },
            spin: function( event, ui ) {
                var value = ui.value;
                if (value > max)
                {
                    value = max;
                    $(this).spinner("value", value);
                    event.preventDefault();
                    ecommerce.showDialogWhenAddToCartWithSpinner(article_title, enable, stock, (stock - max));
                }
            
                var last_value = $(this).attr("last_value");
                if (value != last_value)
                {
                    onChangeArticleAmount(article_code, value);
                    $(this).attr("last_value", value);
                }
            }           
        });
        
        var value = $(this).spinner("value");
        $(this).attr("last_value", value);
    });    
}

function showVoucherDialog()
{
//    var menu = $('#shoppingcart-content-voucher');
//    if (!menu) return;
//    var raw_content = menu.attr("_content");
//    if (!raw_content) return;
//    var content = $(raw_content);
//    
//    // Show window after submit
//    $.fancybox({
//        content: content,
//        closeEffect: 'none',
//        closeBtn: true
//    });    

    if ($('#shoppingcart-content-voucher-tooltip').is(':visible')) 
    {
        $('#shoppingcart-content-voucher-tooltip').hide();
    }
    else
    {
        $('#shoppingcart-content-voucher-tooltip').show();
    }

}

function initVoucherTooltip()
{
    if (is_mobile) return;
    var menu = $('#shoppingcart-content-voucher');
    if (!menu) return;
    var raw_content = menu.attr("_content");
    if (!raw_content) return;
    var content = $(raw_content);
    
    menu.tooltipster({
        content: content,
        theme: 'login-tooltip-theme',
        interactive: true,
        delay: 0,
        arrow: true,
        
        // v3.2.6
        position: 'bottom',
        offsetY: -3,
        interactiveTolerance: 5,
        speed: 0,
        trigger: 'click',
        
        // 4.1.6
//        side: 'bottom',
//        distance: 0,
        
        // OPTION 1
//        autoClose: false,
//        functionInit: function(){
//            $(document).click(function (e) {
//                var target = $(e.target);
//                if(!$(target).parents('.tooltipster-base').length > 0) {
//                    menu.tooltipster('hide');
//                }
//            });
//        }
        
        // OPTION 2
        autoClose: true,        
        functionReady: function(){
            $('.tooltipster-default .close').on('click', function(e){
                e.preventDefault();
                $('body').click();
            });
        }
            
    });         
}

function confirmVoucher()
{
    // Set mask
    //ecommerce.setMask(msg_wait_please);
    
    //var vouchercode = $("[name=vouchercode").val(); // It doesn't work on Safari
    var vouchercode = $("#shoppingcart-content-voucher-tooltip-input").val();
        
    var postdata = "controller=" + ecommerce.getBaseController() + "\\shoppingcart" +
                   "&method=confirmVoucher" + 
                   "&vouchercode=" + vouchercode;  
    $.ajax({
        type: "POST",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            // Unset mask
//            $.unblockUI();
                        
            result = JSON.parse(result);
            if (!result.success)
            {
                $('#shoppingcart-content-voucher-tooltip-error-msg').html(result.msg);
                return false;
            }
                    
            if (!result.htmlMsg)
            {
                // Reload page
                //ecommerce.setMask(msg_wait_please);
                window.location.reload();
            }
            else
            {
                // Show window after submit
                $.fancybox({
                    content: result.htmlMsg,
                    closeEffect: 'none',
                    closeBtn: true,
                    beforeClose: function() 
                    {
                        // Reload page
                        //ecommerce.setMask(msg_wait_please);
                        window.location.reload();
                    }
                });
                
            }
        }
    });        
}

function removeFromShoppingcart(code)
{
    var postdata = "controller=" + ecommerce.getBaseController() + "\\shoppingcart" +
                   "&method=removeFromShoppingcart" + 
                   "&code=" + code;  
    $.ajax({
        type: "GET",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            if (is_mobile)
            {
                window.location.reload();
                return;
            }
            
            result = JSON.parse(result);
            
            // Update shopping cart page
            $('#shoppingcart-content').html(result.content);

            // Update tooltip and items from shopping cart menu
            $('#shoppingcart-menu-option-amount-wrapper').html(result.shoppingcartAmount);  
            $('#shoppingcart-menu-option-totalprice-wrapper').html(result.shoppingcartTotalPrice);               
            $('#shoppingcart-menu-option').tooltipster('content', $(result.shoppingcartTooltip));
            
            // Init spinners (amount)
            initSpinners();            
        }
    });        
}

function removeAllFromShoppingcart()
{
    var postdata = "controller=" + ecommerce.getBaseController() + "\\shoppingcart" +
                   "&method=removeAllFromShoppingcart";  
    $.ajax({
        type: "GET",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            if (is_mobile)
            {
                window.location.reload();
                return;
            }
            
            result = JSON.parse(result);
            
            // Update shopping cart page
            $('#shoppingcart-content').html(result.content);

            // Update tooltip and items from shopping cart menu
            $('#shoppingcart-menu-option-amount-wrapper').html(result.shoppingcartAmount);  
            $('#shoppingcart-menu-option-totalprice-wrapper').html(result.shoppingcartTotalPrice);              
            $('#shoppingcart-menu-option').tooltipster('content', $(result.shoppingcartTooltip));    
            
            // Init spinners (amount)
            initSpinners();     
        }
    }); 
}

function onChangeArticleAmount(code, amount)
{
    if (is_mobile) return;
    var postdata = "controller=" + ecommerce.getBaseController() + "\\shoppingcart" +
                   "&method=onChangeArticleAmount" + 
                   "&code=" + code + 
                   "&amount=" + amount;  
    $.ajax({
        type: "GET",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            result = JSON.parse(result);
            
            // Update only total price
            $('#shoppingcart-content-articles-table-column-price-content-price-' + code).html(result.totalArticlePrice);
            $('#shoppingcart-content-totals-container').html(result.totalPrice);

            // Update tooltip and items from shopping cart menu
            $('#shoppingcart-menu-option-amount-wrapper').html(result.shoppingcartAmount);  
            $('#shoppingcart-menu-option-totalprice-wrapper').html(result.shoppingcartTotalPrice);              
            $('#shoppingcart-menu-option').tooltipster('content', $(result.shoppingcartTooltip));   
        }
    }); 
}

function validate()
{
    // Set mask
    ecommerce.setMask(msg_wait_please);
    
    var postdata = "controller=" + ecommerce.getBaseController() + "\\shoppingcart" +
                   "&method=validate";  
    $.ajax({
        type: "POST",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            result = JSON.parse(result);
            if (!result.success)
            {
                $.unblockUI();
                ecommerce.showBasicDialog(msg_attention + '!', result.msg, result.redirectUrl);
                return false;
            }
                
            // Redirect to new url
            window.location.href = result.redirectUrl;
        }
    });        
}