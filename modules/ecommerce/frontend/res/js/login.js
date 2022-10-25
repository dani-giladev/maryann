
$(function()
{

    // Init login menu
    initLoginMenu();
    
    // Init languages menu
    initLanguagesMenu();    

});

function initLanguagesMenu()
{
    if (is_mobile) return;
    var menu = $('#pre-header-languages');
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

function initLoginMenu()
{
    if (is_mobile) return;
    var menu = $('#pre-header-menu-login');
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

function login()
{
    // Set mask
    //ecommerce.setMask(msg_wait_please);
    
    //var email = $("[name=loginuser").val(); // It doesn't work on Safari
    var email = $("#login-tooltip-loginuser").val();
    //var password = $("[name=loginpassword").val(); // It doesn't work on Safari
    var password = $("#login-tooltip-loginpassword").val();
    
    var postdata = "controller=modules\\ecommerce\\frontend\\controller\\webpages\\user" +
                   "&method=login" + 
                   "&email=" + email +
                   "&password=" + password;  
    $.ajax({
        type: "POST",
//        type: "GET",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            // Unset mask
//            $.unblockUI();
                        
            result = JSON.parse(result);
            if (!result.success)
            {
                $('#login-tooltip-error-msg').html(result.msg);
                return false;
            }
            
            // Hide menu
            if (!is_mobile)
            {
                var menu = $('#pre-header-menu-login');
                menu.tooltipster('hide');                
            }
                
            // Set mask
            //ecommerce.setMask(msg_wait_please);
    
            // Reload page
            window.location.reload();
        }
    });        
}

function logout()
{
    // Set mask
    //ecommerce.setMask(msg_wait_please);
        
    var postdata = "controller=modules\\ecommerce\\frontend\\controller\\webpages\\user" +
                   "&method=logout";  
    $.ajax({
        type: "POST",
//        type: "GET",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            result = JSON.parse(result);
            if (result.redirectUrl)
            {
                // Redirect to new url
                window.location.href = result.redirectUrl;
            }
            else
            {
                // Reload page
                window.location.reload();                
            }
        }
    });     
}
  