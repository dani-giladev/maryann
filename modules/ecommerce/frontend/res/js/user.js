
var current_password = '';

$(function()
{
    
    // Disable email
    $('#user-field-email').prop('disabled', true);
        
    // Disable country (only spain)
    $('#user-field-country').prop('disabled', true);
    
    // Keep safe the current password
    current_password = $("#user-field-password").val();
    
    // When the webpage is ready...
    $(document).ready(function()
    {
        
        // Validate form
        var validator = $("#user-wrapper").validate(
        {
            event: "blur",
            rules: 
            {
                firstName: { required: true },
                lastName: { required: true },
                email: { required: true, email: true },
                password: { required: true, minlength: 5 },
                confirmPassword: { required: true, minlength: 5, equalTo: "#user-field-password" },
                phone: { number: true },
                postalCode: { number: true }
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
                phone: {
                    number: msg_please_enter_numeric_value_without_spaces
                },
                postalCode: {
                    number: msg_please_enter_numeric_value_without_spaces
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
                
                var company = '';
                if ($("#user-field-company").val())
                {
                    company = $("#user-field-company").val();
                }
                
                var postdata = "controller=modules\\ecommerce\\frontend\\controller\\webpages\\user" +
                               "&method=save" + 
                               "&firstname=" + $("#user-field-firstname").val() + 
                               "&lastname=" + $("#user-field-lastname").val() + 
                               "&email=" + $("#user-field-email").val() + 
                               "&currentpassword=" + current_password +
                               "&password=" + $("#user-field-password").val() + 
                               "&confirmpassword=" + $("#user-field-confirm-password").val() + 
                               "&phone=" + $("#user-field-phone").val() + 
                               "&company=" + company + 
                               "&address=" + $("#user-field-address").val() + 
                               "&postalcode=" + $("#user-field-postalcode").val() + 
                               "&city=" + $("#user-field-city").val() + 
                               "&country=" + $("#user-field-country").val() + 
                               "&comments=" + $("#user-field-comments").val() + 
                               "&newsletters=" + $("#user-field-newsletters").prop("checked") + 
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
                            else if (fieldMsg === 'confirmPassword')
                            {
                                validator.showErrors({ confirmPassword: result.msg });
                                return false;
                            }
                            else if(fieldMsg === 'currentPassword')
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