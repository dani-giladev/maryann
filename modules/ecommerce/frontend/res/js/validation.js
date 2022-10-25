
$(function()
{
    
    // When the webpage is ready...
    $(document).ready(function()
    {



    });
});


function validate()
{ 
    var postdata = "controller=modules\\ecommerce\\frontend\\controller\\webpages\\validation" +
                   "&method=validate";  

    $.ajax({
//        type: "POST",
        type: "GET",
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
                }
            }
            
            // Redirect to new url
            window.location.href = result.redirectUrl;
        }
    });      
}