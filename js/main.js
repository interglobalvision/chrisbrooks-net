/* jshint browser: true, devel: true, indent: 2, curly: true, eqeqeq: true, futurehostile: true, latedef: true, undef: true, unused: true */
/* global $, jQuery, document, Modernizr */

function l(data) {
  'use strict';
  console.log(data);
}

// VARS

var retina = Modernizr.highresdisplay,
  largeImageThreshold = 800,
  largestImageThreshold = 1400,

  margin = 35,

  windowHeight = $(window).innerHeight(),

  captionHeight = $('#single-slider-text').outerHeight(),

  slidesMargin = 70;

var caption,
  activeIndex,
  initSlide,
  State = History.getState(),
  hashState = State.data.state;




// FUNCTIONS

  // LAZY IMAGES

function lazyLoadImages(selector) {
  $(selector).each(function() {
    var $this = $(this);
    var data = $this.data();
    var windowWidth = $(window).width();

    if (retina) {
      if (windowWidth > (largeImageThreshold*1.5)) {
        $this.attr('src', data.largest);
      } else {
        $this.attr('src', data.large);
      }
    } else if (windowWidth > largestImageThreshold) {
      $this.attr('src', data.largest);
    } else if (windowWidth > largeImageThreshold) {
      $this.attr('src', data.large);
    } else {
      $this.attr('src', data.basic);
    }
  });
}

  // LAYOUT

$(window).resize(function() {
  windowHeight = $(window).innerHeight();

  if ($('body').hasClass('home')) {
    slidesContainerLayoutFix();
    resizeSlideImages();
  }
});

function slidesContainerLayoutFix() {
  $('#slide-container').height( windowHeight - $('#header').outerHeight() );
}

function resizeSlideImages() {
  $('.slide-image').each(function() {

    var $this = $(this);
    var imageHeight = $this.height();
    var offset = $this.offset();
    var top = offset.top;

    if ((imageHeight + top + slidesMargin) > windowHeight) {

      if (top < 20) {
        top = 20;
      }

      $this.css({
        'max-height' : (windowHeight - top - slidesMargin) + 'px'
      });

    } else {

      $this.css({
        'max-height' : (windowHeight - slidesMargin) + 'px'
      });

    }

  });
}

function singleLayout() {
  $('#single-slider').css({
    'padding-top': margin,
    'height': (windowHeight - captionHeight)
  });
}

if ($('body').hasClass('home')) {
  slidesContainerLayoutFix();
  resizeSlideImages();
}

if ($('body').hasClass('single')) {
  singleLayout();
}

  // RESIZE

function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this, args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) {
        func.apply(context, args);
      }
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) {
      func.apply(context, args);
    }
  };
}

  // SLICK

var Slick = {
  init: function() {
    var _this = this;
    $('.js-slick-container').on({
      init: function(event, slick){
        var currentSlideIndex = $('.slick-active').attr('data-slick-index');
        // set caption
        _this.replaceCaption(currentSlideIndex);

        // set length for n of * in captions
        var slidesLength = $('.js-slick-item').length;
        if (slidesLength === 1) {
          $('#slick-length').html(1);
        } else {
          $('#slick-length').html(slidesLength-2);
        }

        // lazy load images for screen resolution
        lazyLoadImages('.slider-img');

        // fix images for window height
        _this.resizeImages();

        // init slide
        
        //$('.js-slick-container').slickGoTo(initSlide);

        // fade in when ready
        $('#single-slider').css( 'opacity' , 1 );
        $('#single-slider-text').css( 'opacity' , 1 );
      },
      afterChange: function(event, slick, currentSlide, nextSlide){
        // set caption
        _this.replaceCaption(currentSlide);

        // set active index in human readable form
        activeIndex = $('[data-slick-index="'+currentSlide+'"]').attr('data-number');
        $('#slick-current-index').html(activeIndex);

        caption = $('[data-slick-index="'+currentSlide+'"]').attr('data-caption');

        _this.pushSlideState(activeIndex,caption);
      },
    })
    .slick({
      fade: true,
      speed: 500,
      prevArrow: '#slick-prev',
      nextArrow: '#slick-next',
    });

    // go to hash initial slide
    if (hashState > 1) {
      initSlide = $('[data-number="' + hashState + '"]').attr('data-slick-index');
      $('.js-slick-container').slick('slickGoTo',initSlide);
    } 

    $('.js-slick-item').on('click', function() {
      $('.js-slick-container').slick('slickNext');
    });
  },

  pushSlideState: function(activeIndex, caption) {
    History.pushState({state: activeIndex}, caption, "#"+activeIndex);
  },

  replaceCaption: function(currentSlide) {
    var caption = $('[data-slick-index="' + currentSlide + '"]').data('caption');
    if (! caption || caption === undefined || caption === null) {
      $('#slick-caption').html(' ');
    } else {
      $('#slick-caption').html(caption);
    }
  },

  resizeImages: function() {
    $('.js-slick-item img').css( 'max-height' , ( windowHeight - captionHeight - margin ) );
  }
};


jQuery(document).ready(function () {
  'use strict';
  l('Hola Globie');

// PACKERY
  if ( $('.js-packery-container').length ) {
    $('.js-packery-container').imagesLoaded( function() {
      $('.js-packery-container').packery({
        itemSelector: '.js-packery-item',
        transitionDuration: '0s',
        percentPosition: true
      }).css({
        'opacity': 1
      });
    });
  }

// SLICK
/*
  var resizeFunction = debounce(function() {
    resizeImages();
  }, 30);
*/

  if ( $('.js-slick-item').length ) {
    Slick.init();
  }

  $(window).on('resize', function() {
    Slick.resizeImages();
  });

});