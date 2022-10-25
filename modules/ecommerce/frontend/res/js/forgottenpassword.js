
var current_password = '';

$(function()
{
    
    // When the webpage is ready...
    $(document).ready(function()
    {
        
        // Validate form
        var validator = $("#forgotten-password-wrapper").validate(
        {
            event: "blur",
            rules: 
            {
                email: { required: true, email: true }
            },
            messages: 
            {                    
                email: {
                    required: msg_required_field, 
                    email: msg_invalid_email
                }
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
                
                var postdata = "controller=modules\\ecommerce\\frontend\\controller\\webpages\\forgottenpassword" +
                               "&method=submit" + 
                               "&email=" + $("#forgotten-password-field-email").val() + 
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
                                return false;
                            }

                            else if(fieldMsg === 'sendingEmail')
                            {
                                // Then... show fancy message
                            }
                            else
                            {
                                alert(result.msg);
                                return false;
                            }
                        }
                
                        // Show window after submit
                        $.fancybox({
                            content: result.messageAfterSubmitWindow,
                            closeEffect: 'none',
                            closeBtn: true,
                            beforeClose: function() 
                            {
                                if (result.redirectUrl)
                                {
                                    // Set mask
                                    ecommerce.setMask(msg_wait_please);
                                    // Redirect to showcase
                                    window.location.href = result.redirectUrl;
                                }                                
                            }
                        });
            
                    }
                });
            }
        });

    });
});