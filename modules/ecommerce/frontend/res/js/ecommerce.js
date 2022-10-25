
var ecommerce = {
  
    setMask: function(msg)
    {
        $.blockUI({ message: msg,
            css: {
                fontSize: '16px',
                border: 'none',
                padding: '15px', 
                backgroundColor: '#fff', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .5, 
                color: '#000'
            }
        });
    },
    
    showDialogWhenAddToCartWithSpinner: function(article_title, enable, stock, max_to_add)
    {
        var text, in_cart;
    //    console.log(article_title);
    //    console.log(enable);
    //    console.log(stock);
    //    console.log(max_to_add);

        var dialog = $("#addtocart-amount-spinner-dialog-warning").dialog({
            resizable: false,
    //        height:180,
            title: msg_attention + '!',
            modal: true,
            show: {
              effect: "blind",
              duration: 300
            },
            hide: {
              effect: "explode",
              duration: 1000
            },
            buttons : 
            [
                {
                    text: msg_accept,
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }
            ]
        });

        if (enable)
        {
            //text = 'Nos quedan' + ' <b>' + stock + '</b> ' + 'unidades en stock' + '.';
            text = msg_there_is_only + ' <b>' + stock + '</b> ' + msg_units_in_stock + '.';
            in_cart = stock - max_to_add;

            if (stock > 0)
            {
                if (in_cart === stock)
                {
                    //text += ' ' + 'Y ya están todas en el carrito de la compra' + '.';
                    text += ' ' + msg_and_all_are_in_shoppingcart + '.';
                }
                else
                {
                    if (in_cart > 0)
                    {
                        //text += '</br></br>' + 'Ya ha añadido' + ' ' + in_cart + ' ' + 'al carrito de la compra' + '.';
                        text += '</br></br>' + msg_you_have_already_added + ' ' + in_cart + ' ' + msg_to_shoppingcart + '.';
                        //text += ' ' + 'Sólo puede añadir' + ' ' + ' ' + max_to_add + ' ' + 'más' + '.';
                        text += ' ' + msg_you_can_only_add + ' ' + ' ' + max_to_add + ' ' + msg_more + '.';
                    }
                }        
            }        
        }
        else
        {
            text = msg_you_can_add_max_units_in_shoppingcart + '.';
            in_cart = 100 - max_to_add;
            if (in_cart > 0)
            {
                if (in_cart >= 100)
                {
                    //text += ' ' + 'Y ya están todas en el carrito de la compra' + '.';
                    text += ' ' + msg_and_all_are_in_shoppingcart + '.';
                }
                else
                {
                    //text += '</br></br>' + 'Ya ha añadido' + ' ' + in_cart + ' ' + 'y' + ' ';
                    text += '</br></br>' + msg_you_have_already_added + ' ' + in_cart + ' ' + 'y' + ' ';
                    //text += ' ' + 'Sólo puede añadir' + ' ' + ' ' + max_to_add + ' ' + 'más' + '.';
                    text += ' ' + msg_you_can_only_add.toLowerCase() + ' ' + ' ' + max_to_add + ' ' + msg_more + '.';                 
                }           
            }
        }

        dialog.html(
                '<div class="addtocart-amount-spinner-dialog-warning-article-title">' + 
                    article_title + 
                '</div>' +
                '<div class="addtocart-amount-spinner-dialog-warning-text">' + 
                    text + 
                '</div>' + 
                ''
        );
    },
    
    onClickAcceptMobileBasicDialog: function() {
        if (ecommerce.mobileRedirect)
        {
            window.location.href = ecommerce.mobileRedirect;
            return;
        }
        $("#basic-dialog").popup('close');
    },
    
    mobileRedirect: null,
    showBasicDialog: function(title, html, redirectUrl)
    {
        if (is_mobile)
        {
            $("#basic-dialog").find( "h3" ).html(title);
            $("#basic-dialog").find( "p" ).html(html);
            ecommerce.mobileRedirect = redirectUrl;
            $("#basic-dialog").popup("open");
            return;
        }

        var dialog = $("#basic-dialog").dialog({
            resizable: false,
            title: title,
            modal: true,
            show: {
              effect: "blind",
              duration: 300
            },
            hide: {
              effect: "explode",
              duration: 1000
            },
            buttons : 
            [
                {
                    text: msg_accept,
                    click: function() {

                        if (!redirectUrl)
                        {
                            $( this ).dialog( "close" );
                            return;
                        }

                        window.location.href = redirectUrl;
                    }
                }
            ]
        });

        dialog.html(
                '<div class="basic-dialog-content">' + 
                    html + 
                '</div>' +
                ''
        );
    },
    
    resetAll: function()
    {
        var postdata = "controller=modules\\ecommerce\\frontend\\controller\\session" +
                       "&method=resetAll";

        $.ajax({
            type: "GET",
            url: "index.php",
            data: postdata,
            success: function()
            {

            }
        });  
    },
    
    getBaseController: function()
    {
        if (is_mobile)
        {
            return "modules\\ecommerce\\frontend\\mobile\\controller";
        }
        else
        {
            return "modules\\ecommerce\\frontend\\controller\\webpages";
        }
    },
    
    showIFrame: function(iframe_content)
    {
        var width = $(window).width();
        var height = $(window).height();

        var html = 
                '<div style="width:' + width + 'px; height:' + height + 'px;">' + 
                    iframe_content + 
                '</div>';

        $.fancybox({
            content: html,
            closeEffect: 'none',
            closeBtn: true
        });    
    }    
};