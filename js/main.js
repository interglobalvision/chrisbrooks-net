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

  caption,
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
});

function singleLayout() {
  $('#single-slider').css({
    'padding-top': margin,
    'height': (windowHeight - captionHeight)
  });
}

// OBJECTS

  // HOME SPREADS

var Spreads = {
  containerHeight: 0,
  init: function() {
    var _this = this;
    _this.containerLayoutFix();
    _this.resizeImages();

    $('.spread-image').on('click', function() {
      _this.nextSpread();
    });

    $(window).resize(function() {
      _this.containerLayoutFix();
      _this.resizeImages();
    });
  },

  containerLayoutFix: function() { 
    var _this = this;
    _this.containerHeight = windowHeight - $('#header').outerHeight(true);
    $('#spread-container').height( _this.containerHeight );
  },

  resizeImages: function () {
    var _this = this;

    $('.home-spread.home-spread-active').children('.spread-image-wrapper').each(function() {

      var $this = $(this);
      var imageWrapHeight = $this.height();
      var position = $this.position();
      var top = position.top;

      if ((imageWrapHeight + top) > _this.containerHeight) {

        $this.css({
          'height' : (_this.containerHeight - top) + 'px'
        });
        console.log('imageHeight = '+ (_this.containerHeight - top) + 'px');

      } 

    });
  },

  nextSpread: function() {
    var activeSpread = $('.home-spread.home-spread-active');
    var nextSpread = activeSpread.next();

    activeSpread.removeClass('home-spread-active');

    if (nextSpread.length) {
      nextSpread.addClass('home-spread-active');
    } else {
      $('.home-spread').first().addClass('home-spread-active');
    }

    this.resizeImages();

  }
};

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
          $('#slide-nav').remove();
        } else {
          $('#slick-length').html(slidesLength);
        }

        // lazy load images for screen resolution
        lazyLoadImages('.slider-img');

        // fix images for window height
        _this.resizeImages();

        // fade in when ready
        $('#single-slider').css( 'opacity' , 1 );
        $('#single-slider-text').css( 'opacity' , 1 );
      },
      afterChange: function(event, slick, currentSlide){
        // set caption
        _this.replaceCaption(currentSlide);

        // set active index in human readable form
        activeIndex = $('[data-slick-index="'+currentSlide+'"]').attr('data-number');
        $('#slick-current-index').html(activeIndex);

        caption = $('[data-slick-index="'+currentSlide+'"]').attr('data-caption');

        console.log(caption+' '+activeIndex);
        //_this.pushSlideState(activeIndex,caption);
        history.pushState({state: activeIndex}, caption, "#"+activeIndex);
      },
    })
    .slick({
      fade: true,
      speed: 500,
      prevArrow: '#slick-prev',
      nextArrow: '#slick-next',
    });

    if (hashState > 1) {
      initSlide = $('[data-number="' + hashState + '"]').attr('data-slick-index');
      $('.js-slick-container').slick('slickGoTo',initSlide);
    }

    $('.js-slick-item').on('click', function() {
      $('.js-slick-container').slick('slickNext');
    });

    $(window).on('resize', function() {
      _this.resizeImages();
    });
  },

  pushSlideState: function(activeIndex, caption) {
    History.pushState({state:activeIndex}, null, "#"+activeIndex);
    console.log('push');
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

  // CONDITIONAL INITS

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

  if ($('body').hasClass('single')) {
    singleLayout();
  }

  if ($('body').hasClass('home')) {
    Spreads.init();
  }

  if ( $('.js-slick-item').length ) {
    Slick.init();
  }

});