
var home = {
    
    mySlider: null,
    
    init: function()
    {
        // Init slider
        home.initSlider();
        
        // Change slider images regarding the new orientation
        home.changeSliderContent();

        // Change height of slider (page) regarding the new orientation
        home.changeSliderPageHeight();         

    },
    
    initSlider: function()
    {
        home.mySlider = new Swiper('.swiper-container', {
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
            },
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            loop: true,
            autoplay: {
                delay: 3000
            }                
        }); 
    
    },

    changeSliderContent: function() {

        var slides;
//        if (isPortrait())
//        {
//            slides = $('.swiper-slides-portrait').html();     
//        }
//        else
//        {
            slides = $('.swiper-slides-landscape').html();         
//        }
        //console.log(slides);
        //$('.swiper-wrapper').empty().append(slides);
        home.mySlider.removeAllSlides();
        home.mySlider.appendSlide(slides);
    },

    changeSliderPageHeight: function() {

       if ($('#page-slider-wrapper').length <= 0) 
       {
           return;
       }

       var perc = 55; // % regarding width
       var width = window.innerWidth;
       //console.log(width);
       var height = (width * perc) / 100;
       //console.log(height);

       $("#page-slider-wrapper").height(height); 
   }
    
};

$(function()
{
    // When the webpage is ready...
    $(document).ready(function()
    {
//        $(window).on('orientationchange', function(event) {
//            alert("This device is in " + event.orientation + " mode!");
//        }); 
        
        $(window).on('resize', function(event) {
            
            home.init();
        }); 
         
        home.init();
        
    });
});