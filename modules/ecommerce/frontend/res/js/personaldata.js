
$(function()
{
    
    // Disable country (only spain)
    $("[name=country]").prop('disabled', true);
        
    // When the webpage is ready...
    $(document).ready(function()
    {
        // Validate form
        var validator = $("#personal-data-wrapper").validate(
        {
            event: "blur",
            rules: 
            {
                firstName: { required: true },
                lastName: { required: true },
                email: { required: true, email: true },
                phone: { required: true, number: true },
                address: { required: true },
                postalCode: { required: true, number: true },
                city: { required: true },
                country: { required: true }
            },
            messages: 
            {                    
                firstName: msg_required_field,
                lastName: msg_required_field,
                email: {
                    required: msg_required_field, 
                    email: msg_invalid_email
                },
                phone: {
                    required: msg_required_field, 
                    number: msg_please_enter_numeric_value_without_spaces
                },
                address: msg_required_field,
                postalCode: {
                    required: msg_required_field, 
                    number: msg_please_enter_numeric_value_without_spaces
                },
                city: msg_required_field,
                country: msg_required_field
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
                var company = '';
                if ($("[name=company]").val())
                {
                    company = $("[name=company]").val();
                }
                
                var postdata = "controller=modules\\ecommerce\\frontend\\controller\\webpages\\personaldata" +
                               "&method=validate" + 
                               "&firstname=" + $("[name=firstName]").val() + 
                               "&lastname=" + $("[name=lastName]").val() + 
                               "&email=" + $("[name=email]").val() + 
                               "&phone=" + $("[name=phone]").val() + 
                               "&company=" + company + 
                               "&address=" + $("[name=address]").val() + 
                               "&postalcode=" + $("[name=postalCode]").val() + 
                               "&city=" + $("[name=city]").val() + 
                               "&country=" + $("[name=country]").val() + 
                               "&comments=" + $("[name=comments]").val() + 
                               "";
                $.ajax({
                    type: "POST",
//                    type: "GET",
                    url: "index.php",
                    data: postdata,
                    success: function(result)
                    {
                        result = JSON.parse(result);
                        if (!result.success)
                        {
                            if (!result.redirectUrl)
                            {
                                alert(result.msg);
                                return false;
                            }                
                            else
                            {
                                alert(result.msg);
                            }
                        }
                
                        // Redirect to new url
                        window.location.href = result.redirectUrl;
                    }
                });
            }
        });

    });
});
