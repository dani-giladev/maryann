
$(function()
{
    
    // When the webpage is ready...
    $(document).ready(function()
    {
        
        // Validate form
        var validator = $("#signin-wrapper").validate(
        {
            event: "blur",
            rules: 
            {
                firstName: { required: true },
                lastName: { required: true },
                email: { required: true, email: true },
                password: { required: true, minlength: 5 },
                confirmPassword: { required: true, minlength: 5, equalTo: "#signin-field-password" },
                captcha: { required: true }  
            },
            messages: 
            {                    
                firstName: msg_required_field,
                lastName: msg_required_field,
                email: {
                    required: msg_required_field, 
                    email: msg_invalid_email
                },
                password: {
                    required: msg_required_field, 
                    minlength: msg_password_too_short
                },
                confirmPassword: {
                    required: msg_required_field, 
                    minlength: msg_password_too_short, 
                    equalTo: msg_please_enter_same_value
                },
                captcha: msg_required_field
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
                // Set mask
                ecommerce.setMask(msg_processing_your_request);
                
                var postdata = "controller=modules\\ecommerce\\frontend\\controller\\webpages\\signin" +
                               "&method=validate" + 
                               "&firstname=" + $("#signin-field-firstname").val() + 
                               "&lastname=" + $("#signin-field-lastname").val() + 
                               "&email=" + $("#signin-field-email").val() + 
                               "&password=" + $("#signin-field-password").val() + 
                               "&confirmpassword=" + $("#signin-field-confirm-password").val() + 
                               "&captcha=" + $("#signin-field-captcha").val() + 
                               "&newsletters=" + $("#signin-field-newsletters").prop("checked") + 
                               "";
                $.ajax({
                    type: "POST",
//                    type: "GET",
                    url: "index.php",
                    data: postdata,
                    success: function(result)
                    {
                        // Unset mask
                        $.unblockUI();
            
                        result = JSON.parse(result);
                        if (!result.success)
                        {
                            var fieldMsg = result.fieldMsg;
                            if (fieldMsg === 'email')
                            {
                                validator.showErrors({ email: result.msg });
                            }
                            else if (fieldMsg === 'confirmPassword')
                            {
                                validator.showErrors({ confirmPassword: result.msg });
                            }
                            else if (fieldMsg === 'captcha')
                            {
                                validator.showErrors({ captcha: result.msg });
                            }
                            return false;
                        }
                
                        // Show window after add
                        $.fancybox({
                            content: result.thanksForSigninWindow,
                            closeEffect: 'none',
                            closeBtn: true,
                            beforeClose: function() 
                            {
                                // Set mask
                                ecommerce.setMask(msg_wait_please);
                                // Redirect to showcase
                                window.location.href = result.redirectUrl;
                            }
                        });
            
                    }
                });
            }
        });

    });
});


function refreshCaptcha()
{
    var captcha = $('#signin-captcha-img');
    var src = captcha.attr("src");    
    document.getElementById('signin-captcha-img').src = src;
}