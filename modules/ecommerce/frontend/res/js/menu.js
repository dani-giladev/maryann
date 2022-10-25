
var menu = {
    
    search_articles_timer: null,
    
    setStyles: function()
    {
        // Disabled by Dani on 18/12/2019. Now, It's working on ecommerce.css by @media
 
//        var page_center_width = $('.page-center').width();
//        if (page_center_width > 1100)
//        {
//            $('.menu-main-firstlevel-text').css({'font-size': '15px', 'padding-top': '13px'});
//            $('.header-specialmenu').css({'font-size': '14px'});
//        }
//        else if (page_center_width > 1000)
//        {
//            $('.menu-main-firstlevel-text').css({'font-size': '14px', 'padding-top': '13px'});
//            $('.header-specialmenu').css({'font-size': '13px'});
//        }
//        else
//        {
//            $('.menu-main-firstlevel-text').css({'font-size': '13px', 'padding-top': '14px'});
//            $('.header-specialmenu').css({'font-size': '12px'});
//        }

        // Show it with fade effect
        if ( $("#menu-main-center").is(".fadeout") )
        {
            $("#menu-main-center").removeClass("fadeout").addClass("fadein");
        }
        if ( $("#header-specialmenu-wrapper").is(".fadeout") )
        {
            $("#header-specialmenu-wrapper").removeClass("fadeout").addClass("fadein");
        }

    }
    
};

$(function()
{
    // Sticky menu
    var sticky_menu = $('nav');
    var sticky_menu_offset = sticky_menu.offset();
    var sticky_menu_height = sticky_menu.height();
    
    $(window).resize(function() {
        menu.setStyles();
    }); 
    menu.setStyles();

    $(window).scroll(function() {      

        var scroll_top = $(window).scrollTop();

        // Sticky menu
        var content = $('#content-wrapper');
        if (scroll_top > sticky_menu_offset.top) {
            sticky_menu.addClass('nav-fixed');
            content.css({'padding-top': sticky_menu_height + 'px'});
        } else {
            sticky_menu.removeClass('nav-fixed');
            content.css({'padding-top': 0 + 'px'});
        }
        
        // Set tooltips max height
        $("[_has_tooltip=true]").each( function () 
        {
            setTooltipMaxHeight($(this));
        });
    }); 
    
    // Main menu
    initMainMenu();
    
    // Search articles
    initSearcher();
    
    // When the webpage is ready...
    $(document).ready(function()
    {

    });
});

function initMainMenu()
{    
    // Init tooltips menu for categories
    $("[_has_tooltip=true]").each( function () 
    {
        if ($(this).attr("_is_for_categories"))
        {
            initTooltipMenuForCategories($(this));
        }
    });
    
    // Init tooltip menu for shoppingcart
    initTooltipMenuForShoppingcart();
    
    // Init tooltip menu for searcher
    initTooltipMenuForSearcher();

    // Init breadcrums tooltip
    initTooltipMenuForArticleTextOnBreadcrumbs();
}

function initTooltipMenuForCategories(menu)
{
    var raw_content = menu.attr("_content");
    if (!raw_content) return;
    var content = $(raw_content);
    //var left = menu.position().left;
    //var offsetX =  left * (-1);
    var offsetY = 0;
    
    var max_height = calculateTooltipMaxHeight(menu);
    content.css('max-height', max_height + 'px');
    
    menu.tooltipster({
        content: content,
        theme: 'menu-main-tooltip-theme',
        interactive: true,
        animationDuration: 0,
        arrow: false,
        functionReady: function(){
            menu.addClass('menu-main-firstlevel-hover');
        },
        functionAfter: function(){
            menu.removeClass('menu-main-firstlevel-hover');
        },
        
        // v3.2.6
        position: 'bottom-left',
        offsetY: (offsetY - 12),
        offsetX: - 1,
        interactiveTolerance: 5,
        speed: 0,
        trigger: (is_touch_device? 'click' : 'hover')//, 
        
        // 4.1.6
//        side: 'bottom',
//        distance: 0,   
//        delay: 1
        
    });     
}

function initTooltipMenuForShoppingcart()
{
    var menu = $('#shoppingcart-menu-option');
    var raw_content = menu.attr("_content");
    if (!raw_content) return;
    var content = $(raw_content);
    
    var max_height = calculateTooltipMaxHeight(menu);
    content.css('max-height', max_height + 'px');
    
    menu.tooltipster({
        content: content,
        theme: 'shoppingcart-menu-option-menu-option-tp-theme',
        interactive: true,
        arrow: false,
        
        // v3.2.6
        position: 'bottom-right',
        offsetY: -12,
        interactiveTolerance: 5,
        speed: 0,
        trigger: (is_touch_device? 'click' : 'hover')//, 
        
        // 4.1.6
//        side: ['bottom', 'right'],
//        distance: 2
    });       
}

function initTooltipMenuForSearcher()
{
    var menu = $('#searcher');
    var raw_content = menu.attr("_content");
    var content;
    if (raw_content)
    {
        content = $(raw_content);
        var max_height = calculateTooltipMaxHeight(menu);
        content.css('max-height', max_height + 'px');        
    }
    else
    {
        content = null;
    }
    
    menu.tooltipster({
        content: content,
        theme: 'shoppingcart-menu-option-menu-option-tp-theme',
        position: 'bottom',
        offsetY: -18,
        interactive: true,
        interactiveTolerance: 5,
        speed: 0,
        trigger: (is_touch_device? 'click' : 'hover'), 
//        delay: 0,
        arrow: false
        });
    }
           
function initTooltipMenuForArticleTextOnBreadcrumbs()
{
    var menu = $('#menu-breadcrumbs-breadcrumb-article-tooltip');
    if (!menu)
    {
        return;
    }
    var raw_content = menu.attr("_content");
    if (!raw_content) return;
    var content = $(raw_content);
    
    menu.tooltipster({
        content: content,
        theme: 'menu-breadcrumbs-breadcrumb-article-tooltip-theme',
        position: 'bottom',
        offsetY: 1,
        interactive: true,
        interactiveTolerance: 5,
        speed: 0,
        trigger: (is_touch_device? 'click' : 'hover'), 
//        delay: 0,
        arrow: true
    });         
}

function setTooltipContent(menu, content)
{
    menu.tooltipster('content', content);
}

function setTooltipMaxHeight(menu)
{
    var raw_content = menu.attr("_content");
    if (!raw_content)
    {
        return;
    }
//    var content = $(raw_content);
    var content = menu.tooltipster('content');
    if (!content)
    {
        return;
    }

    var max_height = calculateTooltipMaxHeight(menu);
    content.css('max-height', max_height + 'px');
    
    setTooltipContent(menu, content);
}

function calculateTooltipMaxHeight(menu)
{
    var scrollTop = $(window).scrollTop();
    var menuTop = menu.offset().top;
    var max_height = ($(window).height() - (menuTop - scrollTop)) - 100;    
    
    return max_height;
}

function initSearcher()
{
    $("#searcher-input").keyup(function(e)
    {
        if (e.keyCode == 13) 
        {
            searcher(true);
            return;
        }
        
        // Init timer to save the scroll position
        clearInterval(menu.search_articles_timer);
        menu.search_articles_timer = setTimeout('searcher()', 500);
    });    
}

function searcher(enter)
{
    var menu = $('#searcher');
    var content;
    var value = $("#searcher-input").val();
    if (value.length < 3)
    {
        setTooltipContent(menu, null);
        return;
    }
    
    if (enter)
    {
        window.location.href = base_url + '/showcase?search=' + value;
        return;
    }

    var postdata = "controller=modules\\ecommerce\\frontend\\controller\\menu\\searcher" +
                   "&method=search" + 
                   "&value=" + value;
    $.ajax({
//        type: "POST",
        type: "GET",
        url: "index.php",
        data: postdata,
        success: function(result)
        {
            //console.log(result);
            // Update tooltip content
            result = JSON.parse(result);
            var raw_content = result.searchResult;
            if (raw_content)
            {
                content = $(raw_content);
                var max_height = calculateTooltipMaxHeight(menu);
                content.css('max-height', max_height + 'px');
            }
            else
            {
                content = null;
            }
            setTooltipContent(menu, content);
            menu.tooltipster('show');
        }
    });     
}