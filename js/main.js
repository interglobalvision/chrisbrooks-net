/* jshint browser: true, devel: true, indent: 2, curly: true, eqeqeq: true, futurehostile: true, latedef: true, undef: true, unused: true */
/* global $, jQuery, document, Modernizr */

function l(data) {
  'use strict';
  console.log(data);
}

function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}

// VARS

var retina = Modernizr.highresdisplay,
  largeImageThreshold = 800,
  largestImageThreshold = 1400,

  noSpreadLayoutThreshold = 600,

  margin = 35,

  windowHeight = $(window).height(),
  windowWidth = $(window).width(),

  captionHeight = $('#single-slider-text').outerHeight(),

  caption,
  activeIndex,
  activeId,
  initSlide,
  State = History.getState(),
  hashState = State.hash.slice(-1);


// FUNCTIONS

$(window).on('resize', function() {
  windowHeight = $(window).height();
  windowWidth = $(window).width();
});

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


// OBJECTS

  // HOME SPREADS

var Spreads = {
  containerHeight: 0,
  containerMargin: 0,
  init: function() {
    var _this = this;
    _this.firstSpread();
    _this.containerLayoutFix();
    _this.resizeImages();

    $('.spread-image').on('click', function() {
      _this.nextSpread();
    });

    $(window).resize( $.debounce( 250, function() {
        _this.containerLayoutFix();
        _this.resizeImages();
      })
    );
  },

  containerLayoutFix: function() {
    var _this = this;
    _this.containerMargin = parseInt($('#header').css('margin-top'), 10);
    _this.containerHeight = windowHeight - $('#header').outerHeight(true) - _this.containerMargin;

    $('#spread-container').height( _this.containerHeight );
  },

  resizeImages: function () {
    var _this = this;

    if (noSpreadLayoutThreshold > windowWidth) {
      return false;
    }

    $('.home-spread.home-spread-active').children('.spread-image-wrapper').each(function() {

      $(this).css('height','');

      var $this = $(this),
      imageWrapHeight = $this.height(),
      position = $this.position(),
      top = position.top,
      scale = ($this.attr('data-scale')*0.01),
      imageCaptionHeight = $this.find('.spread-image-caption').outerHeight();

      if ((imageWrapHeight + top) > _this.containerHeight) {

        $this.css({
          'height' : (_this.containerHeight - top) + 'px'
        });

      }

      imageWrapHeight = $this.height();

      if (scale > 0) {
        if (scale > 1) {
          scale = 1;
        }
        var imageMaxHeight = (imageWrapHeight*scale) - imageCaptionHeight;
      } else {
        var imageMaxHeight = imageWrapHeight - imageCaptionHeight;
      }

      if (imageMaxHeight < 60) {
        imageMaxHeight = 60;
      }

      $this.find('img.spread-image').css( 'max-height', imageMaxHeight );

    });
  },

  firstSpread: function() {

    var length = $('.home-spread').length;
    var random = getRandomInt(0, length);

    $('.home-spread').eq(random).addClass('home-spread-active');

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

        // set length for n of * in captions
        var slidesLength = $('.js-slick-item').length;
        if (slidesLength === 1) {
          $('.slide-nav').remove();
        }

        // lazy load images for screen resolution
        lazyLoadImages('.slider-img');

        // fix images for window height
        _this.resizeImages();

        // fade in when ready
        $('#single-slider').css( 'opacity' , 1 );
      },
    })
    .slick({
      fade: true,
      speed: 500,
      prevArrow: '.slick-prev',
      nextArrow: '.slick-next',
    });

    if (hashState > 1) {
      initSlide = $('[data-index="' + hashState + '"]').attr('data-slick-index');
      $('.js-slick-container').slick('slickGoTo',initSlide);
    }

    $('.js-slick-item').on('click', function() {
      $('.js-slick-container').slick('slickNext');
    });

    $(window).resize( $.debounce( 250, function() {
        _this.resizeImages();
      })
    );
  },

  resizeImages: function() {
    $('#single-slider').css( 'height', windowHeight );
    $('.js-slick-item img').css({
      'max-height' : ( windowHeight - captionHeight - margin ),
      'margin-top' : margin
    });
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

  if ($('#single-slider').length) {
    $('#single-slider').css( 'height', windowHeight );
  }

  if ($('body').hasClass('home')) {
    Spreads.init();
  }

  if ( $('.js-slick-item').length ) {
    Slick.init();
  }

});