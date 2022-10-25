
var menu = {
    
    search_articles_timer: null,
    
    initSearcher: function()
    {
        
        $(document).on('click', '.ui-input-clear', function () {
            var list = $("#searcher-list");
            list.html( "" );
            list.listview( "refresh" );
        });

        $("#searcher-input").keyup(function(e)
        {
            if (e.keyCode == 13) 
            {
                menu.updateList(true);
                return;
            }
    
            // Init timer to save the scroll position
            clearInterval(menu.search_articles_timer);
            menu.search_articles_timer = setTimeout('menu.updateList()', 500);
        });   
    },
    
    updateList: function(enter)
    {    
        var list = $("#searcher-list");
        var value = $("#searcher-input").val();
        if (value.length < 3)
        {
            list.html( "" );
            list.listview( "refresh" );
            return;
        }
        
        if (enter)
        {
            window.location.href = base_url + '/showcase?search=' + value;
            return;
        }
        
//        list.html( "<li><div class='ui-loader'><span class='ui-icon ui-icon-loading'></span></div></li>" );
//        list.listview( "refresh" );
            
        var postdata = "controller=modules\\ecommerce\\frontend\\mobile\\controller\\menu\\searcher" +
                       "&method=search" + 
                       "&value=" + value;
        $.ajax({
            type: "GET",
            url: "index.php",
            data: postdata,
            success: function(result)
            {
                result = JSON.parse(result);
                
                var html = '';
                $.each( result, function ( i, val ) {
                    html += "<li>" + val + "</li>";
                });
                var list = $("#searcher-list");
                list.html( html );
                list.listview( "refresh" );
//                list.trigger( "updatelayout");
            }
        });         
    },
    
    onClickSearchButton: function()
    {
        menu.updateList(true);
    }
    
};

$(function()
{
    // Search articles
    menu.initSearcher();

    // When the webpage is ready...
    $(document).ready(function()
    {


    });
});
