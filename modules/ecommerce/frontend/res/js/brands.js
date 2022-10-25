
var brands = {
    
    initTabs: function()
    {
        if (is_mobile) return;

        $("#brands-content-tabs").tabs({
            //event: "mouseover"
        });
    }
    
};

$(function()
{
    // Init tabs
    brands.initTabs();
    
    // When the webpage is ready...
    $(document).ready(function()
    {
        

    });
    
});