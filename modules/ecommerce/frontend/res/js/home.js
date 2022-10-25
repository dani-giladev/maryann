
var home = {
    
    pheight: 0,
    
    setStyles: function()
    {
        // Set width articles content
        var page_center_width = $('.page-center').width();
        var sidebar_width = 200;
        var content_width = page_center_width- sidebar_width - 50;
        $('#home-novelty-articles-content').css({'width': content_width + 'px'});
        $('#home-outstanding-articles-content').css({'width': content_width + 'px'});
        
        // Set size of article img
        var article_img_width = $('.showcase-content-article').width();
        $('.showcase-content-article-img-wrapper').css({'width': article_img_width + 'px', 'height': article_img_width + 'px'});
        
        // Show articles with fade effect
        if ( $(".showcase-content-article-wrapper").is(".fadeout") )
        {
            $(".showcase-content-article-wrapper").removeClass("fadeout").addClass("fadein");
        }
        if ( !$("#home-slider-mask").is(".fadeout") )
        {
            setTimeout(function(){ 
                $("#home-slider-mask").addClass("fadeout");
//                $('#home-slider-mask[class=fadeout]').css('-webkit-animation','fadeout 5s linear');            
            }, 1000);
        }
    },
    
    initSlider: function()
    {
        $('.pgwSlider').pgwSlider({
            displayList: false,
            displayControls: true,
            intervalDuration: 4500,
            transitionDuration: 1500,
            maxHeight: home.pheight - 100,
            verticalCentering: true,
            adaptiveHeight: true
        });
    }
    
};

$(function()
{
    
    $(window).resize(function() {
        home.setStyles();
    }); 
    home.setStyles();
    
    home.pheight = $(window).height();

    $('#home-slider-mask').css({'height': (home.pheight - 100) + 'px'});
    home.initSlider();

    // When the webpage is ready...
    $(document).ready(function()
    {
        
        
    });
});