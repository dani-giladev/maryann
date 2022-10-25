
var paypal_data = null;
var trigger = {};

$(function()
{

    // Init click to pay tooltip
    initClickToPayTooltip();
  
    // Init paypal button
    initPaypalButton();
    
//    $( ".payment-paymentway-radio" ).checkboxradio();
    
    // When the webpage is ready...
    $(document).ready(function()
    {
        if (payment_way.length === 0)
        {
            // Show/hide submit buttons
            showHideSumitButtons();
        }
        else
        {
            onClickPaymentWay(payment_way);
        }
        
        // Init validators
        initValidator('card');
        initValidator('clicktopay');
        initValidator('iupay');
        
        // Show initial msg if there is an error
        if (msg_payment_failed_msg.length > 0)
        {
            ecommerce.showBasicDialog(msg_payment_failed_title, msg_payment_failed_msg);
        }

    });
});

function initClickToPayTooltip()
{
    if (is_mobile) return;
    var menu = $('#payment-paymentway-clicktopay-moreinfo');
    if (!menu) return;
    var raw_content = menu.attr("_content");
    if (!raw_content) return;
    var content = $(raw_content);
    
    menu.tooltipster({
        content: content,
        theme: 'login-tooltip-theme',
        interactive: true,
        arrow: true,
        
        // v3.2.6
        position: 'bottom',
        offsetY: -11,
        interactiveTolerance: 5,
        speed: 0,
        trigger: (is_touch_device? 'click' : 'hover')//, 
        
        // 4.1.6
//        side: ['bottom', 'right'],
//        distance: 2
    });           
}

function initValidator(pw)
{
    var validator = $("#payment-submit-" + pw + "-form");
    if (!validator)
    {
        return;
    }
    
    validator.validate({ 
        rules: {
            //-- rules--
        },
        messages: {
            //-- messages--
        },
        submitHandler: function(form) {

            var selected_radio = $('input[name=paymentway]:checked').val();
            if (!selected_radio)
            {
                ecommerce.showBasicDialog(msg_payment_ways, msg_select_payment_way);
                return;
            }

            // Set mask
            ecommerce.setMask(msg_processing_your_order);

            // Validate before paying
            $(trigger).on( 'onValidated', function( event, success, result )
            {
                $(trigger).off('onValidated');

                if (!success || !result.success)
                {
                    return;
                }

                // Pay!
                form.submit();

            });

            validate();                
        }
    });    
}

function initPaypalButton()
{
    if (typeof paypal == 'undefined')
    {
        return;
    }
    
    $('#payment-submit-paypal-button').hide();
    
    paypal.Button.render({

        env: paypal_env, // Specify 'sandbox' for the test environment

        locale: paypal_locale,

        style: {
            size: 'medium',
            color: 'gold',
            shape: 'pill'
        },
        
        client: {
            sandbox:    'AaMyA02MkgeUQwggA3wIiTI8965eSmRVgzzwGD7I0dD26lpJs042DMVAxVJ2huh9uMEukjF1pSganiaD',
            production: 'Af1Sm6BWD10GnaCS1SUtcPCpIJcKFQk0zXELrhI-AiPACHTFRhQWf0zP6--MpopuRh6EM-KSb5m-Tyho'
        },        
            
         payment: function(resolve, reject) {
            // Set up the payment here, when the buyer clicks on the button
        
            var env    = this.props.env;
            var client = this.props.client;
            
            return paypal.rest.payment.create(env, client, {
                transactions: [
                    {
                        amount: { total: paypal_total_price, currency: 'EUR' }
                    }
                ]
            });
        },
        
        commit: true, // Optional: show a 'Pay Now' button in the checkout flow        

        onAuthorize: function(data, actions) {
            // Execute the payment here, when the buyer approves the transaction
        
            paypal_data = data;
            //console.log(paypal_data);
                        
            // Validate before ordering
            $(trigger).on( 'onValidated', function( event, success, result )
            {
                $(trigger).off('onValidated');
                        
                if (!success || !result.success)
                {
                    return;
                }
                        
                return actions.payment.execute().then(function() {
                    // Show a success page to the buyer
                    ordering();
                });                        
                        
            });
                        
            validate();           
        },

        onCancel: function(data, actions) {
            //return actions.redirect();
        }

    }, '#payment-submit-paypal-button');    
}

function onClickPaymentWay(value)
{
    payment_way = value;
    
    var wrapper = $('#payment-paymentway-' + payment_way);
    var radio = $('#payment-paymentway-' + payment_way + '-radio');

    // Remove all styles
    $('#payment-paymentway-card').removeClass('payment-paymentway-selected');
    $('#payment-paymentway-clicktopay').removeClass('payment-paymentway-selected');
    $('#payment-paymentway-iupay').removeClass('payment-paymentway-selected');
    $('#payment-paymentway-paypal').removeClass('payment-paymentway-selected');  
    $('#payment-paymentway-fake').removeClass('payment-paymentway-selected');  

    // Set style to selected payment way
    wrapper.addClass('payment-paymentway-selected');

    // Check radio
    radio.prop('checked', true);
    
    // Show/hide submit buttons
    showHideSumitButtons();
}

function showHideSumitButtons()
{
    var card_button = $('#payment-submit-card-button');
    var clicktopay_button = $('#payment-submit-clicktopay-button');
    var iupay_button = $('#payment-submit-iupay-button');
    var paypal_button = $('#payment-submit-paypal-button');
    var fake_button = $('#payment-submit-fake-button');
    
    // Hide all buttons
    card_button.hide();
    if (clicktopay_button)
    {
        clicktopay_button.hide();        
    }
    iupay_button.hide();
    paypal_button.hide();
    fake_button.hide();
    
    // Show only one
    if (payment_way === 'clicktopay')
    {
        clicktopay_button.show();
    }
    else if (payment_way === 'iupay')
    {
        iupay_button.show();
    }
    else if (payment_way === 'paypal')
    {
        paypal_button.show();
    }
    else if (payment_way === 'fake')
    {
        fake_button.show();
    }
    else
    {
        card_button.show();
    } 
}

function validate()
{
    // Set mask
    ecommerce.setMask(msg_processing_your_order);             

    var postdata = "controller=modules\\ecommerce\\frontend\\controller\\webpages\\payment" +
                   "&method=validate" + 
                   "&paymentWay=" + payment_way + 
                   "";
           
    if (payment_way === 'paypal')
    {
        postdata += "&paypalPayerId=" + paypal_data.payerID + 
                   "&paypalPaymentId=" + paypal_data.paymentID + 
                   "&paypalPaymentToken=" + paypal_data.paymentToken + 
                   ""; 
    }

    $.ajax({
        type: "POST",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            result = JSON.parse(result);
            //console.log(result);
            if (!result.success)
            {   
                $.unblockUI();
                ecommerce.showBasicDialog(msg_attention + '!', result.msg, result.redirectUrl);
                $(trigger).trigger( 'onValidated', [ false, result ] );
                return;
            }

            $(trigger).trigger( 'onValidated', [ true, result ] );
        }
    });  
}

function ordering()
{
    // Set mask
    ecommerce.setMask(msg_processing_your_order);             

    var postdata = "controller=modules\\ecommerce\\frontend\\controller\\webpages\\payment" +
                   "&method=ordering" + 
                   "";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            result = JSON.parse(result);
            //console.log(result);
            if (!result.success)
            {   
                $.unblockUI();
                ecommerce.showBasicDialog(msg_attention + '!', result.msg, result.redirectUrl);
                return;
            }

            // Redirect to new url
            window.location.href = result.redirectUrl;
        }
    });    
}

function onFakeSubmit()
{
    // Validate before ordering
    $(trigger).on( 'onValidated', function( event, success, result )
    {
        $(trigger).off('onValidated');

        if (!success || !result.success)
        {
            return;
        }

        // Show a success page to the buyer
        ordering();

    });

    validate();     
}
