jQuery(document).ready(function($){

    const itemsPerPage = 10;

    // Show the first 5 items
    function showPage(page) {
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        $('.auction-item').hide();
        $('.auction-item').slice(startIndex, endIndex).fadeIn();
    }

    $('.pagination-btn').on('click', function () {
        const page = $(this).data('page');
        showPage(page);

        $('.pagination-btn').removeClass('active');
        $(this).addClass('active');
    });

    showPage(1);
    $('.pagination-btn[data-page="1"]').addClass('active');

    $('.auction-tabcontent-box').each(function(index) {
        var $this = $(this);

        // Generate unique class names
        var sliderForClass = 'auction-slider-for-' + index;
        var sliderNavClass = 'auction-slider-nav-' + index;

        // Assign unique class names
        $this.find('.auction-slider-for').addClass(sliderForClass);
        $this.find('.auction-slider-nav').addClass(sliderNavClass);

        // Initialize sliders separately
        $('.' + sliderForClass).slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            fade: true,
            focusOnSelect: false,
            draggable: false,
            asNavFor: '.' + sliderNavClass
        });

        $('.' + sliderNavClass).slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.' + sliderForClass,
            dots: false,
            arrows: false,
            focusOnSelect: true,
        });

        // Handle next/prev button clicks separately for each slider
        $this.find('.slick-next').on('click', function() {
            $('.' + sliderForClass).slick('slickNext');
        });

        $this.find('.slick-prev').on('click', function() {
            $('.' + sliderForClass).slick('slickPrev');
        });
    });

	
	// Set the second <li> as active by default
  
// 	$('.auction-tab ul li').eq(1).addClass('active');
//     // Click event to go to a specific slide

//     jQuery('.auction-tab ul li').click(function() {
//         var clickedElement = $(this).index(); // Get the index of the clicked tab
        
//         // Change to the corresponding slide based on the tab click
//         jQuery('.auction-slider-for').slick('slickGoTo', clickedElement);
        
//         // Optionally: you can sync the nav slider if needed
//         jQuery('.auction-slider-nav').slick('slickGoTo', clickedElement);
        
//         // Optionally, toggle active class for tabs
//         jQuery('.auction-tab ul li').removeClass('active');
//         $(this).addClass('active');
//     });

    


//     jQuery('.auction-tab ul li').click(function(){
            
//     var clickedElement =  $(this).index() + 1;
//     jQuery('.auction-tabcontent > div').hide();
//     jQuery('.auction-tabcontent > div:nth-child('+clickedElement+')').show();

//     jQuery('.auction-tab ul li').removeClass('active');
//     $(this).addClass('active');
    
//     });
//     
//     updated code
   
    // Set the second <li> as active by default when the page loads
    $('.auction-tab ul li').eq(0).addClass('active');
    $('.auction-tab ul li').eq(1).removeClass('active');
    
    // Show the corresponding content for the second tab by default
    var defaultClickedElement = $('.auction-tab ul li.active').index() + 1;  // Get the index of the active tab (1-based)
    $('.auction-tabcontent > div').hide();  // Hide all tab content
    $('.auction-tabcontent > div:nth-child(' + defaultClickedElement + ')').show();  // Show the corresponding tab content

    // Click event to handle tab switching
    $('.auction-tab ul li').click(function() {
        var clickedElement = $(this).index();  // Get the index of the clicked tab
        
        // Change to the corresponding slide based on the tab click
        $('.auction-slider-for').slick('slickGoTo', clickedElement);
        
        // Sync the nav slider if needed
        $('.auction-slider-nav').slick('slickGoTo', clickedElement);
        
        // Toggle active class for tabs
        $('.auction-tab ul li').removeClass('active');
        $(this).addClass('active');
        
        // Show the corresponding tab content
        $('.auction-tabcontent > div').hide();
        $('.auction-tabcontent > div:nth-child(' + (clickedElement + 1) + ')').show();
    });


//     
    /*accordian js */

    jQuery('.question').click(function(){
    jQuery(".answer").slideUp();
    jQuery(".accordian-box").removeClass("active");
    if(jQuery(this).parent(".accordian-box").find(".answer").is(":visible")){
        jQuery(this).parent(".accordian-box").find(".answer").slideUp(); 
        jQuery(this).parent(".accordian-box").removeClass("active");
    }
    else{
        jQuery(this).parent(".accordian-box").find(".answer").slideDown(); 
        jQuery(".accordian-box").removeClass(".active");
        jQuery(this).parent(".accordian-box").addClass("active");
    }

    });

    /*tooltips  js */
    jQuery(document).ready(function(){
        // When a tooltip button is clicked
        jQuery('.auctions-tooltips .tooltip').click(function(event) {
            // Close all other tooltips
            jQuery('.tooltip').removeClass('active');
            
            // Toggle 'active' class on the clicked tooltip
            jQuery(this).toggleClass('active');
            
            // Prevent the body click handler from firing
            event.stopPropagation();
        });
    
        // When clicking anywhere on the body, close all tooltips
        jQuery('body').click(function() {
            // Remove 'active' class from all tooltips
            jQuery('.tooltip').removeClass('active');
        });
    });


    var $modal = $('.modal');
    var $trigger = $('.trigger');
    var $closeButton = $('.close-button');
    
    function toggleModal() {
        /* alert("Hello");
        
        var moreContent = jQuery(this).siblings('.show_more_content').html();
        
        // Append the content to the modal body
        jQuery('.modal-body').html(moreContent); */
        $modal.toggleClass('show-modal');
    }
    
    $trigger.on('click', toggleModal);
    $closeButton.on('click', toggleModal);
    
    // Close modal if you click outside the modal
    $(window).on('click', function(event) {
        if ($(event.target).is($modal[0])) {
            toggleModal();
        }
    });



    $('.show-more').click(function(event){
        event.stopPropagation();
    
        var moreContent = jQuery(this).siblings('.show_more_content').html();
        var auc_title = $(this).closest('.auction-content-box').find('.auction-title h2 a').html();
        
        $('.modal-body').html(moreContent);
        $('.modal-header h2').html(auc_title);
        $('body').toggleClass('modal-open');
        
    });
        
    // If you click anywhere outside of .show-more, remove the modal-open class
    $('body').click(function() {
        $('body').removeClass('modal-open');
    });
    
    // Prevent clicks inside .show-more from closing the modal
    $('.show-more').click(function(event) {
        event.stopPropagation();
    });
        
 



    jQuery(document).ready(function($) {
      var $slider = $('.featured-slider');
      var slideCount = $slider.children().length; // Count number of slides
  
      if (slideCount > 4) {
          $slider.slick({
              dots: true,
              infinite: false,
              speed: 300,
              slidesToShow: 4,
              slidesToScroll: 1,
              arrows: true,
              responsive: [
                  {
                      breakpoint: 1200,
                      settings: {
                          slidesToShow: 3,
                          slidesToScroll: 1
                      }
                  },
                  {
                      breakpoint: 992,
                      settings: {
                          slidesToShow: 2,
                          slidesToScroll: 1
                      }
                  },
                  {
                      breakpoint: 768,
                      settings: {
                          slidesToShow: 1,
                          slidesToScroll: 1
                      }
                  }
              ]
          });
  
          // Progress Bar for Slick Slider
          var $progressBar = $('.progress');
          var $progressBarLabel = $('.slider__label');
  
          $slider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {   
              var calc = ((nextSlide) / (slick.slideCount - 1)) * 100;
              
              $progressBar
                  .css('background-size', calc + '% 100%')
                  .attr('aria-valuenow', calc);
              
              $progressBarLabel.text(calc.toFixed(0) + '% completed'); // Round percentage
          });
      }
  });
  

  $(".countdown-timer").each(function () {
        let timerElement = $(this);
        let timeStr = timerElement.data("time"); // Get time from data attribute
        
        if (!timeStr) return;
        
        // Parse the time string (e.g., "2D, 5H, 4M")
        let days = parseInt(timeStr.match(/(\d+)D/)?.[1] || 0);
        let hours = parseInt(timeStr.match(/(\d+)H/)?.[1] || 0);
        let minutes = parseInt(timeStr.match(/(\d+)M/)?.[1] || 0);
        
        // Convert total time to seconds
        let totalSeconds = (days * 86400) + (hours * 3600) + (minutes * 60);

        function updateTimer() {
            if (totalSeconds <= 0) {
                timerElement.text("Expired");
                clearInterval(timerInterval);
                return;
            }

            totalSeconds--;

            let d = Math.floor(totalSeconds / 86400);
            let h = Math.floor((totalSeconds % 86400) / 3600);
            let m = Math.floor((totalSeconds % 3600) / 60);
            let s = totalSeconds % 60;

            let formattedTime = `${d}D, ${h}H, ${m}M, ${s}S`;
            timerElement.text(formattedTime);
        }

        updateTimer();
        let timerInterval = setInterval(updateTimer, 1000);
    });

});